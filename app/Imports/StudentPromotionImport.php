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
use App\Models\StudentPromotion;
use Carbon\Carbon;
use Exception;

class StudentPromotionImport implements ToCollection
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

                if ($row[0] == null || $row[0] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập mã học sinh!');
                    continue;
                }
                // Xử lý nếu k nhập kỳ hết hạn hoặc kỳ hết hạn k hợp lệ
                if ($row[5] !== null && $row[5] !== '') {
                    $excelDateCount = $row[5];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $time_end = Carbon::createFromFormat('m/d/Y', $formattedDate);
                    } else {
                        try {
                            $time_endString = trim($row[5]);
                            $time_end = Carbon::createFromFormat('d/m/Y', $time_endString)->format('Y-m-d');
                        } catch (Exception $e) {
                            $this->rowError++;
                            array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Sai định dạng kỳ hết hạn!');
                            continue;
                        }
                    }
                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập thời gian hết hạn!');
                    continue;
                }
                // Kiểm tra học sinh, và xem đã có trong lớp hay chưa
                $student = Student::where('student_code', trim($row[0]))->first();
                if (empty($student)) {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Không tìm thấy học sinh!');
                    continue;
                }

                $student_promotion = StudentPromotion::where('student_id', $student->id)->where('promotion_id', trim($row[4]))->first();
                if (!empty($student_promotion)) {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Học sinh đã được áp dụng khuyến mãi!');
                    continue;
                }
                //học viên chưa được áp dụng km thì thêm

                $student_promotion = StudentPromotion::create([
                    'student_id' => $student->id,
                    'promotion_id' => trim($row[4]),
                    'time_start' => date('Y-m-d', strtoTime('01-04-2025')),
                    'time_end' => $time_end,
                    'status' => Consts::STATUS['active'],
                    'admin_created_id' => Auth::guard('admin')->user()->id,
                ]);

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
