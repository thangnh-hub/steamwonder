<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use App\Consts;

class StudentDept implements ToCollection, WithHeadingRow
{
    protected $params = [];
    public $arrErrorMessage = [];

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    public function collection(Collection $rows)
    {
        // Process each row in the collection
        foreach ($rows as $key => $row){
            if (empty($row['ma_hv']) && empty($row['trang_thai']) && empty($row['version_hd']) && empty($row['ngay_nhap_hoc_chinh_thuc'])) {
                continue;
            }
            $student = Student::where('admin_code', $row['ma_hv'])->first();
            $slug = isset($row['trang_thai']) ? Str::slug($row['trang_thai']) : "";

            // Determine status based on 'trang_thai' slug
            if ($slug == "da-thanh-toan") {
                $status = Consts::KETOAN_XACNHAN['collected_money'];
            } elseif ($slug == "tam-dung-hoc") {
                $status = Consts::KETOAN_XACNHAN['liquidated'];
            } else {
                $status = Consts::KETOAN_XACNHAN['unpaid'];
            }

            if (isset($student)) {
                // Update student record
                $student->update([
                    'ketoan_xacnhan' => $status,
                    'version' => $row['version_hd'] != "" ? strtolower(str_replace(' ', '', $row['version_hd'])) : "null",
                ]);

                if ($row['ngay_nhap_hoc_chinh_thuc'] != "") {
                    try {
                        $unixTimestamp = ($row['ngay_nhap_hoc_chinh_thuc'] - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $day_official = Carbon::createFromFormat('m/d/Y', $formattedDate)->startOfDay()->format('Y-m-d');
                        $student->update([
                            'day_official' => $day_official,
                        ]);
                    } catch (\Exception $e) {
                        array_push($this->arrErrorMessage, 'Vị trí ' . ($key + 2) . ': Ngày nhập học không hợp lệ cho mã học viên ' . $row['ma_hv']);
                    }
                }
            } else {
                // Add error message if student not found
                array_push($this->arrErrorMessage, 'Vị trí ' . ($key + 2) . ': Mã học viên ' . $row['ma_hv'] . ' không tìm thấy');
                continue;
            }

        };
    }

    public function getErrorMessages()
    {
        return $this->arrErrorMessage;
    }
}
