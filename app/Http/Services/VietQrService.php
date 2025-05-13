<?php

namespace App\Http\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Response;

class VietQrService
{
    // Xử lý mã hóa QR VietQR
    public function generateQrImage(string $bankBin, string $accountNumber, float $amount = 0, string $note = '')
    {
        $note = strtoupper($this->removeVietnamese($note));

        $payload = $this->buildPayload($bankBin, $accountNumber, $amount, $note);

        $qrCode = QrCode::create($payload)
            ->setSize(300)
            ->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return 'data:image/png;base64,' . base64_encode($result->getString());
    }

    // Tạo mã payload trước khi mã hóa QR
    protected function buildPayload(string $bankBin, string $accountNumber, float $amount, string $note): string
    {
        $tag_bankBin = $this->formatTag('00', $bankBin);
        $tag_accountNumber = $this->formatTag('01', $accountNumber);
        $merchantAccount = $this->formatTag('00', 'A000000727')
            . $this->formatTag('01', $tag_bankBin . $tag_accountNumber)
            . $this->formatTag('02', 'QRIBFTTA');

        $payload = '';
        $payload .= $this->formatTag('00', '01'); // EMV version
        $payload .= $this->formatTag('01', '12'); // dynamic
        $payload .= $this->formatTag('38', $merchantAccount);
        $payload .= $this->formatTag('53', '704'); // currency code (704 = VND)

        if ($amount > 0) {
            $payload .= $this->formatTag('54', $amount);
        }

        $payload .= $this->formatTag('58', 'VN');;

        if (!empty($note)) {
            $payload .= $this->formatTag('62', $this->formatTag('08', $note));
        }

        // Calculate CRC
        $payloadToCRC = $payload . '6304';
        $crc = strtoupper(dechex($this->crc16_ccitt($payloadToCRC)));
        $payload .= $this->formatTag('63', str_pad($crc, 4, '0', STR_PAD_LEFT));

        return $payload;
    }

    protected function formatTag(string $id, string $value): string
    {
        return $id . str_pad(strlen($value), 2, '0', STR_PAD_LEFT) . $value;
    }

    function removeVietnamese(string $str): string
    {
        $accents = [
            'a' => ['à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ'],
            'A' => ['À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ'],
            'e' => ['è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ'],
            'E' => ['È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ'],
            'i' => ['ì', 'í', 'ị', 'ỉ', 'ĩ'],
            'I' => ['Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ'],
            'o' => ['ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ'],
            'O' => ['Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ'],
            'u' => ['ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ'],
            'U' => ['Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ'],
            'y' => ['ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ'],
            'Y' => ['Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ'],
            'd' => ['đ'],
            'D' => ['Đ']
        ];

        foreach ($accents as $nonAccent => $accentChars) {
            $str = str_replace($accentChars, $nonAccent, $str);
        }

        return $str;
    }


    protected function crc16_ccitt(string $data, int $poly = 0x1021, int $init = 0xFFFF): int
    {
        $crc = $init;
        foreach (str_split($data) as $char) {
            $crc ^= ord($char) << 8;
            for ($i = 0; $i < 8; $i++) {
                $crc = ($crc & 0x8000) ? ($crc << 1) ^ $poly : $crc << 1;
            }
        }
        return $crc & 0xFFFF;
    }
}
