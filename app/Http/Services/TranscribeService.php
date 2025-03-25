<?php

namespace App\Http\Services;

use Exception;
use Illuminate\Support\Facades\File;
use GuzzleHttp\Client;
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionAudio;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Throw_;

class TranscribeService
{
    protected $googleService;
    protected $mimeTypeAllowConvert = ['video/3gpp'];
    protected $sample_rate_hertz = 16000;
    protected $language_code = 'de-DE';
    protected $inputPath, $outputPath;

    // Return path to json file get from google API services
    public function __construct()
    {
        $this->googleService = new GoogleAPIService;
    }

    protected function handleAudioFile($file)
    {
        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('audio', $fileName, 'uploads');
        $this->inputPath = $this->outputPath = public_path('data/' . $filePath);

        // Kiểm tra mime type
        if (in_array($file->getMimeType(), $this->mimeTypeAllowConvert)) {
            // Xử lý convert to Mp3
            $this->outputPath = public_path('data/audio/' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.mp3');

            $ffmpeg = FFMpeg::create();
            $audio = $ffmpeg->open($this->inputPath);

            $format = new Mp3();
            $audio->save($format, $this->outputPath);
        }
    }

    protected function removeFile()
    {
        File::delete($this->inputPath);
        File::delete($this->outputPath);
    }

    public function transcribeSpeechToText($file)
    {
        try {

            $this->handleAudioFile($file);
            // Đọc nội dung tệp âm thanh
            $audioContent = File::get($this->outputPath);

            // Cấu hình Speech Client
            $config = new RecognitionConfig([
                'encoding' => RecognitionConfig\AudioEncoding::MP3,
                'sample_rate_hertz' => $this->sample_rate_hertz,
                'language_code' => $this->language_code
            ]);
            $audio = (new RecognitionAudio())->setContent($audioContent);
            // Tạo Speech Client
            $client = new SpeechClient([
                'credentials' => json_decode(file_get_contents($this->googleService->getCredentialsPath()), true),
            ]);
            // Gửi yêu cầu đến API
            $response = $client->recognize($config, $audio);
            $transcription = '';
            // Xử lý kết quả trả về
            foreach ($response->getResults() as $result) {
                $transcription .= $result->getAlternatives()[0]->getTranscript();
            }
            $client->close();
            // Remove all file
            $this->removeFile();
            // Trả về kết quả
            return  $transcription;
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 400);
        }
    }

    public function transcribeWithToken($file)
    {
        try {
            $this->handleAudioFile($file);
            // Đọc nội dung file âm thanh
            $audioContent = File::get($this->outputPath);

            // Mã hóa file âm thanh thành base64
            $base64Audio = base64_encode($audioContent);

            // Tạo yêu cầu đến Google Speech-to-Text API
            $client = new Client();
            $response = $client->post('https://speech.googleapis.com/v1/speech:recognize', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->googleService->getAccessToken(),
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'config' => [
                        'encoding' => 'MP3',
                        'sampleRateHertz' => $this->sample_rate_hertz,
                        'languageCode' => $this->language_code
                    ],
                    'audio' => [
                        'content' => $base64Audio
                    ]
                ]
            ]);

            // Xử lý kết quả trả về từ API
            $result = json_decode($response->getBody(), true);
            // Remove all file
            $this->removeFile();
            return response()->json($result);
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 400);
        }
    }


    /**
     * Convert text to file audio
     * @return String filePath
     */
    public function synthesizeSpeech($text, $outputPath, $speakingRate = 1.0)
    {
        // Khởi tạo client cho Google Cloud Text-to-Speech
        $client = new TextToSpeechClient([
            'credentials' => json_decode(file_get_contents($this->googleService->getCredentialsPath()), true),
        ]);

        // Cấu hình văn bản cần chuyển đổi
        $input = new SynthesisInput();
        $input->setText($text);

        // Cấu hình giọng nói
        $voice = new VoiceSelectionParams();
        $voice->setLanguageCode($this->language_code); // Chọn ngôn ngữ
        $voice->setSsmlGender(SsmlVoiceGender::NEUTRAL); // Chọn giọng nam, nữ hoặc trung tính

        // Cấu hình định dạng file đầu ra
        $audioConfig = new AudioConfig();
        $audioConfig->setAudioEncoding(AudioEncoding::MP3); // Định dạng file MP3
        $audioConfig->setSpeakingRate($speakingRate); // Tốc độ đọc Giá trị mặc định là 1.0: Tốc độ bình thường. Giá trị thấp hơn 1.0: Tốc độ chậm hơn. Giá trị lớn hơn 1.0: Tốc độ nhanh hơn.

        // Thực hiện gọi API để chuyển đổi văn bản thành giọng nói
        $response = $client->synthesizeSpeech($input, $voice, $audioConfig);

        // Lưu file âm thanh ra file .mp3
        $audioContent = $response->getAudioContent();
        file_put_contents($outputPath, $audioContent);

        // Đóng kết nối client
        $client->close();

        return $outputPath;
    }
}
