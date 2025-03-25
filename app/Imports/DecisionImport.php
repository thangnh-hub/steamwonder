<?php

namespace App\Imports;

use App\Models\Decision;
use Illuminate\Support\Str;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Consts;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DecisionImport implements ToModel,WithHeadingRow
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
        $student=Student::where('admin_code',$row['ma_nv'])->first();
        $student_id=isset($student)?$student->id:"";
       
        if(isset($student_id) && $student_id != ""){
            // Mapping trạng thái
            $decisionTypes = array_flip(array_map('Str::slug', Consts::DECISION_TYPE));
            $sluggedStatus = Str::slug($row['trang_thai']);
            $type = $decisionTypes[$sluggedStatus] ?? null;


            $unixTimestamp = ($row['ngay_bien_dong'] - 25569) * 86400;
            $formattedDate = date('m/d/Y', $unixTimestamp);
            $active_date = Carbon::createFromFormat('m/d/Y', $formattedDate)->startOfDay()->format('Y-m-d H:i:s');
            $json = [
                "student" => [
                    "admin_code" =>  $student->admin_code,
                    "name" => $student->name,  
                    "id" => $student_id, 
                ]
            ];
            return  Decision::create([
                'code' => $row['noi_dung'],
                'note' => $row['ghi_chu'],
                'active_date' => $active_date,
                'is_type' => $type,
                'json_params' => $json,
                'signer' => Auth::guard('admin')->user()->name,
                'is_sign' => $row['don']==1?1:0,
            ]);
           
        }
    }
}
