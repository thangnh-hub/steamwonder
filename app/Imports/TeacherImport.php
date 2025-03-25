<?php

namespace App\Imports;

use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Consts;
use Carbon\Carbon;

class TeacherImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected $params = [];
    protected $idsTeacher;
    public $hasError, $errorMessage;

    public function __construct($params = [])
    {
        set_time_limit(0);
        $this->params = $params;
        $this->idsTeacher = Teacher::get()->pluck('admin_code');
    }
    public function collection(Collection $rows)
    {   
        
        try {
            $list_idsRow = $rows->pluck(0)->toArray();
            $filteredIdsRow = array_filter($list_idsRow, function ($value) {
                return trim($value) !== '';
            });
            $commonIds = collect($this->idsTeacher)->intersect($filteredIdsRow)->toArray();
            if (!empty($commonIds)) {
                $this->hasError = true;
                $this->errorMessage = 'Duplicate ID found: ' . implode(', ', $commonIds);
                return null;
            }
            foreach ($rows as $row) {
                
                if (empty(array_filter($row->toArray()))) {
                    continue;
                }
                
                // Bỏ qua hàng tiêu đề
                if ($this->isHeaderRow($row->toArray())) {
                    continue;
                }

                // Find the last admin code
                $lastAdmin = Teacher::orderBy('id', 'desc')->first();
                $lastAdminCode = $lastAdmin->id ?? 0;
                // Extract the numeric part and increment it
                $numericPart = (int)$lastAdminCode;
                // Calculate the number of digits required for the numeric part
                $numDigits = max(4, strlen((string)$numericPart));
                // Add one to the numeric part
                $newNumericPart = str_pad($numericPart + 1, $numDigits, '0', STR_PAD_LEFT);

                $admin_code_auto = 'GV' . $newNumericPart;
                $json = [
                    "last_name" => $row[3] ?? '',
                    "middle_name" => $row[4] ?? '',
                    "first_name" => $row[5] ?? '',
                    "address" => $row[6] ?? '',
                    "position" => $row[9] ?? '',
                ];
                $rowEmail = $row[1];
                if ($rowEmail !== null && $rowEmail !== '') {
                    $email = $rowEmail;
                } else {
                    $uniqueTimestamp = microtime(true);
                    $email = $uniqueTimestamp . '@tuhoctiengduc.vn';
                }

                // Xử lý nếu trùng Mã SV
                $rowCode = $row[0];
                if ($rowCode !== null && $rowCode !== '') {
                    $admin_code = $rowCode;
                } else {
                    $admin_code = $admin_code_auto;
                }
                if ($row[7] !== null && $row[7] !== '') {
                    $excelDateCount = $row[7];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $birthday = Carbon::createFromFormat('m/d/Y', $formattedDate);
                    }
                    else{
                        try {
                            $birthdayString = trim($row[7]);
                            $birthday = Carbon::createFromFormat('d/m/Y', $birthdayString)->format('Y-m-d');
                        } catch (\Exception $e) {
                            $this->hasError = true;
                            $this->errorMessage = 'Invalid date of birth at admin code: ' . $row[0];
                            return null; 
                        }
                    }
                } else {
                    $birthday = Carbon::now()->format('Y-m-d');
                }
                $teacher = Teacher::create([
                    'admin_code' => $admin_code,
                    'email' => $email,
                    'password' => $row[2],
                    'name' => $row[3] . ' ' . $row[4] . ' ' . $row[5],
                    'role' => 0,
                    'phone' => $row[2],
                    'birthday' => $birthday,
                    'gender' => $row[8],
                    'admin_type' => Consts::ADMIN_TYPE['teacher'],
                    'json_params' => $json,
                ]);
            }
            $this->hasError = false;
        }catch (\Exception $e) {
            $this->hasError = true;
            $this->errorMessage = $e->getMessage();
        }
    }

    private function isHeaderRow(array $row)
    {
        // Các giá trị tiêu đề mong đợi
        $expectedHeaders = ['Mã GV*', 'Email', 'Mật khẩu*', 'Họ', 'Tên đệm', 'Tên', 'Địa chỉ', 'Ngày sinh', 'Giới tính', 'Chức vụ'];

        $rowKeys = array_map('strtolower', array_map('trim', $row));
        $expectedHeaders = array_map('strtolower', array_map('trim', $expectedHeaders));

        // Kiểm tra xem các giá trị của hàng có khớp với các giá trị tiêu đề mong đợi hay không
        return count(array_diff($expectedHeaders, $rowKeys)) === 0;
    }

}
