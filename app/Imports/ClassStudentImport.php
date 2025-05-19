<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\tbClass;
use App\Models\StudentClass;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Consts;
use Carbon\Carbon;
use Exception;

class ClassStudentImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $hasDuplicateError = false;
    protected $params = [];
    protected $idsStudent = [];
    protected $isArea = [];
    private $rowCount = 0;
    private $rowUpdate = 0;
    private $rowInsert = 0;
    private $rowError = 0;
    public $hasError = false;
    public $errorMessage;
    public $arrErrorMessage = [];

    public function __construct($params = [])
    {
        set_time_limit(0);
        $this->params = $params;
    }
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $key => $row) {
                $this->rowCount++;
                if (empty(array_filter($row->toArray()))) {
                    continue;
                }
                if ($this->rowCount == 1) {
                    continue;
                }

                if ($row[7] == null || $row[7] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập lớp học!');
                    continue;
                }
                // Xử lý nếu k nhập ngày sinh hoặc ngày sinh k hợp lệ
                $start_at = Carbon::now()->format('Y-m-d');
                if ($row[8] !== null && $row[8] !== '') {
                    $excelDateCount = $row[8];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $start_at = Carbon::createFromFormat('m/d/Y', $formattedDate);
                    } else {
                        try {
                            $start_atString = trim($row[8]);
                            $start_at = Carbon::createFromFormat('d/m/Y', $start_atString)->format('Y-m-d');
                        } catch (Exception $e) {
                            $this->rowError++;
                            array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Sai định dạng ngày nhập học!');
                            continue;
                            // return null;
                        }
                    }
                }

                // Kiểm tra phòng học và tạo mới phòng học
                $room = Room::getSqlRoom(['keyword' => $row[7]])->first();
                if (empty($room)) {
                    $room = Room::create([
                        'name' => trim($row[7]),
                        'area_id' => '1',
                        'slot' => '40',
                        'type' => Consts::ROOM_TYPE['classroom'],
                        'status' => Consts::STATUS['active'],
                    ]);
                }
                // Kiểm tra lớp học và tạo mới nếu chưa có
                $class = tbClass::where('name', 'like', '%' . $row[7] . '%')->where('area_id',13)->first();
                if (empty($class)) {
                    $class = tbClass::create([
                        'area_id' => 13,
                        'code' => trim($row[7]),
                        'name' => trim($row[7]),
                        'slot' => '40',
                        'room_id' => $room->id,
                        'education_program_id' => trim($row[6]),
                        'education_age_id' => 2,
                        'status' => Consts::STATUS['active'],
                    ]);
                }
                // Kiểm tra học sinh, và xem đã có trong lớp hay chưa
                $student = Student::where('student_code', trim($row[1]))->first();
                if (!empty($student)) {
                    $student_class = StudentClass::where('class_id', $class->id)->where('student_id', $student->id)->first();
                    //Chưa trong lớp thì thêm vào lớp
                    if (empty($student_class)) {
                        $student_class = StudentClass::create([
                            'class_id' => $class->id,
                            'student_id' => $student->id,
                            'start_at' => $start_at,
                            'type' => null,
                            'status' => Consts::STATUS['active'],
                            'admin_created_id' => Auth::guard('admin')->user()->id,
                        ]);
                    }
                    $student->update([
                        'current_class_id' => $class->id,
                    ]);
                }
                $this->rowInsert++;
                continue;
            }
            DB::commit();
            $this->hasError = false;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->hasError = true;
            $this->errorMessage = "Lỗi tại vị trí " . $this->rowCount . ": " . $e->getMessage();
        }
    }

    public function getRowCount()
    {
        $data_count = [
            'total_row' => $this->rowCount,
            'update_row' => $this->rowUpdate,
            'insert_row' => $this->rowInsert,
            'error_row' => $this->rowError,
            'error_mess' => $this->arrErrorMessage,
        ];
        return $data_count;
    }
}
