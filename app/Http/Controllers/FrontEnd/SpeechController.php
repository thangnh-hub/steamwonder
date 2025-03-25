<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Services\TranscribeService;
use Exception;
use Illuminate\Http\Request;

class SpeechController extends Controller
{

    public function transcribe(Request $request)
    {
        if ($request->hasFile('audio')) {
            $file = $request->file('audio');
            $transcribe = new TranscribeService;
            $data = $transcribe->transcribeSpeechToText($file);
            return $data;
        }


        return response()->json(['error' => 'No audio file uploaded'], 400);
    }

    public function transcribeView()
    {
        return $this->responseView('frontend.pages.transcribe.index');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('audio')) {
            $file = $request->file('audio');
            if ($file->isValid()) {
                $transcribe = new TranscribeService;
                $data = $transcribe->transcribeSpeechToText($file);
                $arr_text = explode(" ", $data);
                return $this->sendResponse($arr_text, 'Chuyển đổi thành công');
            }
        }
        return response()->json(['error' => 'No valid file uploaded'], 400);
    }

    public function textToSpeech(Request $request)
    {
        $request->validate([
            'text' => 'required'
        ]);
        try {
            $text = $request->text; // Text to Speech
            $speakingRate = $request->speakingRate ?? 1.0; // Speed audio convert from text to speech
            $filePath = 'data/vocabulary/' . $text . '-' . $speakingRate . '.mp3'; // Đường dẫn tương đối của file
            // Check file exist in folder
            if (file_exists($filePath)) {
                return asset($filePath);
            }
            // Call API to speech
            $transcribe = new TranscribeService;
            $data = $transcribe->synthesizeSpeech($text, public_path($filePath), $speakingRate);
            return asset($filePath);
        } catch (Exception $ex) {
            abort(422, __($ex->getMessage()));
        }
    }
}
