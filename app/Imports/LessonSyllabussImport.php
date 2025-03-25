<?php

namespace App\Imports;

use App\Models\LessonSylabu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Consts;
use Carbon\Carbon;
use Exception;

class LessonSyllabussImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $hasDuplicateError = false;
    protected $params = [];
    public $hasError = false;
    public $errorMessage;
    public $arrErrorMessage = [];
    private $rowCount = 0;

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
                $params['syllabus_id'] = $this->params['syllabuss_id'];
                $params['title'] = $row[0];
                $params['content'] = $row[1];
                $params['target'] = $row[2];
                $params['teacher_mission'] = $row[3];
                $params['student_mission'] = $row[4];
                $file1 = array_filter(explode('http',$row[5]));
                $file2 = array_filter(explode('http',$row[6]));
                $file = array_merge($file1, $file2);
                $data_file['file']=[];
                foreach($file as $key => $val){
                    $data_file['file'][$key]['title']='';
                    $data_file['file'][$key]['link']='http'.trim($val);
                }

                $params['json_params'] = $data_file;
                $syllabus_lesson = LessonSylabu::create($params);
            }
            DB::commit();
            $this->hasError = false;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->hasError = true;
        }
    }
}
