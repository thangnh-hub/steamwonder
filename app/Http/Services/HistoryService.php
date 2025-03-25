<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\Student;
use App\Models\StatusStudent;
use App\Models\History;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class HistoryService
{

    public static function addHistoryStatusStudy($student_id, $status_old = '', $status_new, $admin_id_update = NULL)
    {
        if ($status_old != $status_new) {
            $params_history['student_id'] = $student_id;
            if ($status_old != '') {
                $params_history['status_study_old'] = $status_old;
            }
            $params_history['status_study_new'] = $status_new;
            $params_history['type'] = Consts::HISTORY_TYPE['change_status_student'];
            if ($admin_id_update == 'auto') {
                $params_history['admin_id_update'] = NULL;
            } else {
                $params_history['admin_id_update'] = Auth::guard('admin')->user()->id;
            }

            $history = History::create($params_history);
            return $history;
        }
        return false;
    }
}
