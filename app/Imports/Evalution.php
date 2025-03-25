<?php

namespace App\Imports;

use App\Models\UserClass;
use App\Models\Evaluation;
use App\Models\Student;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Consts;

class Evalution implements ToModel,WithHeadingRow
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
        $list_student_in_class=UserClass::where('class_id', $this->params['class_id'])->get()->pluck('user_id')->toArray();
        $student=Student::where('admin_code',$row['ma_hoc_vien'])->first();
        $student_id=isset($student)?$student->id:"";
        
        if(isset($student_id) && in_array($student_id,$list_student_in_class)){
            $json = [
                "ability" =>  $row['hoc_luc'],
                "consciousness" =>  $row['y_thuc'],
                "knowledge" =>  $row['kien_thuc'],
                "skill" =>  $row['ky_nang'],
            ];
            $evalution = Evaluation::create([
                'student_id' => $student_id,
                'teacher_id' => $this->params['teacher_id'],
                'class_id' => $this->params['class_id'],
                'from_date' => $this->params['from_date'],
                'to_date' => $this->params['to_date'],
                'status' => Consts::STATUS['active'],
                'json_params' => $json,
            ]);
        }
    }
}
