<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Area;
use App\Models\tbParent;
use App\Models\Relationship;
use App\Models\StudentParent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Consts;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StudentImport implements ToModel,WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected $params = [];
    public function __construct($params=[])
    {
        $this->params = $params;
    }
    public function model(array $row)
    {

        $statusSlug = Str::slug($row['trang_thai']);
        if ($statusSlug !== 'dang-hoc') {
            return null;
        }

        //mapping giới tính
        $genderMapping = [
            'nam' => Consts::GENDER['male'],
            'nữ' => Consts::GENDER['female'],
            'khác' => Consts::GENDER['other'],
        ];

        $genderValue = strtolower(trim($row['gioi_tinh'] ?? ''));
        $gender = $genderMapping[$genderValue] ?? Consts::GENDER['other'];

        //mapping trạng thái học sinh
        $statusSlug = Str::slug(trim($row['trang_thai'] ?? ''));
        $statusStudyMapping = array_flip(array_map(fn($item) => Str::slug($item), Consts::STATUS_STUDY));

        $status = $statusStudyMapping[$statusSlug] ?? 'khac';
        $status_study = Consts::STATUS_STUDY[$status] ?? Consts::STATUS_STUDY['khac'];

        //lấy họ , tên, tên đệm
        $fullName = trim($row['ho_ten_hoc_sinh'] ?? '');
        $nameParts = explode(' ', $fullName);
        $lastName = array_pop($nameParts); // tên riêng
        $firstName = implode(' ', $nameParts); // họ + đệm

        $area_id = $this->getAreaIdFromName($row['co_so']);

        $student = Student::firstOrCreate(
            ['student_code' => $row['ma_hoc_sinh']],
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'nickname' => $row['ten_yeu'],
                'birthday' => $this->excelDateToCarbon($row['ngay_sinh']),
                'sex' => $gender,
                'area_id' => $area_id,
                'status' => $status_study,
                'enrolled_at' =>$this->excelDateToCarbon($row['ngay_bat_dau_hoc_chinh_thuc']) ,
                'address' => $row['dia_chi_gia_dinh'] ?? '',
            ]
        );

        // Import Cha
        if (!empty($row['ho_ten_bo'])) {
            $this->importParentAndRelation($row['ho_ten_bo'], $student->id, 'bố',$area_id, $row['so_dien_thoai_bo'] , $row['email_bo']);
        }

        // Import Mẹ
        if (!empty($row['ho_ten_me'])) {
            $this->importParentAndRelation($row['ho_ten_me'], $student->id, 'mẹ',$area_id, $row['so_dien_thoai_me'] , $row['email_me']);
        }

        return null;
    }

    protected function importParentAndRelation($fullName, $student_id, $relationship_title, $area_id, $phone, $email)
    {
        $fullName = trim($fullName);
        $nameParts = explode(' ', $fullName);
        $lastName = array_pop($nameParts); // Tên riêng
        $firstName = implode(' ', $nameParts); // Họ + tên đệm

        // Tìm parent theo phone hoặc email
        $parent = tbParent::where('phone', $phone)
        ->orWhere('email', $email)
        ->first();

        if ($parent) {
            // Nếu đã có, cập nhật lại
            $parent->update([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'area_id' => $area_id,
            ]);
        } else {
            // Nếu chưa có thì tạo mới
            $parent = tbParent::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $phone,
                'email' => $email,
                'area_id' => $area_id,
            ]);
        }

        $relationship = Relationship::firstOrCreate([
            'title' => ucfirst($relationship_title),
        ]);

        StudentParent::updateOrCreate(
            [
                'student_id' => $student_id,
                'parent_id' => $parent->id,
                'relationship_id' => $relationship->id,
            ]
        );
    }

    protected function excelDateToCarbon($excelDate)
    {
        if (is_numeric($excelDate)) {
            $timestamp = ($excelDate - 25569) * 86400;
            return Carbon::createFromTimestamp($timestamp)->format('Y-m-d');
        }
        return null;
    }

    protected function getAreaIdFromName($areaName)
    {
        if (!$areaName) return null;

        $area = Area::firstOrCreate(['name' => trim($areaName)]);
        return $area->id;
    }
}
