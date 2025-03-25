<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Consts;


class ExamSessionUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_exam_session_users';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlExamSessionUser($params = [])
    {

        $query = ExamSessionUser::select('tb_exam_session_users.*')
            ->leftJoin('tb_exam_session', 'tb_exam_session.id', '=', 'tb_exam_session_users.exam_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_exam_session_users.user_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_exam_session.title', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.json_params->cccd', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['course_id']), function ($query) use ($params) {
                return $query->where('tb_exam_session.course_id', $params['course_id']);
            })
            ->when(!empty($params['day_exam']), function ($query) use ($params) {
                return $query->where('tb_exam_session.day_exam', $params['day_exam']);
            })
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                return $query->where('tb_exam_session_users.user_id', $params['user_id']);
            })
            ->when(!empty($params['exam_id']), function ($query) use ($params) {
                return $query->where('tb_exam_session_users.exam_id', $params['exam_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_exam_session_users.status', $params['status']);
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_exam_session.type', $params['type']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_exam_session_users.id', $params['id']);
            });
        if (!empty($params['class_id'])) {
            $query->selectRaw('GROUP_CONCAT("", tb_user_class.class_id) as class_id');
            $query->leftJoin('tb_user_class', 'tb_exam_session_users.user_id', '=', 'tb_user_class.user_id');
            $query->where('tb_user_class.class_id', $params['class_id']);
        }
        $query->groupBy('tb_exam_session_users.id');
        return $query;
    }

    public static function getSqlExamResult($params = [])
    {
        $query = ExamSessionUser::select('tb_exam_session_users.user_id')
            ->leftJoin('tb_exam_session', 'tb_exam_session.id', '=', 'tb_exam_session_users.exam_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_exam_session_users.user_id')
            ->selectRaw("MAX(CASE WHEN tb_exam_session.type = '" . Consts::TYPE_EXAM_SESSION['test_iq'] . "' THEN tb_exam_session_users.score ELSE NULL END) AS diem_iq")
            ->selectRaw("MAX(CASE WHEN tb_exam_session.type = '" . Consts::TYPE_EXAM_SESSION['test_acceptance'] . "' THEN tb_exam_session_users.score ELSE NULL END) AS diem_acceptance")
            ->selectRaw("tb_exam_session.course_id")
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_exam_session.title', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.json_params->cccd', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['course_id']), function ($query) use ($params) {

                return $query->where('tb_exam_session.course_id', $params['course_id']);
            })
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                return $query->where('tb_exam_session_users.user_id', $params['user_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_exam_session_users.status', $params['status']);
            });

        if (!empty($params['class_id'])) {
            $query->selectRaw('GROUP_CONCAT("", tb_user_class.class_id) as class_id');
            $query->leftJoin('tb_user_class', 'tb_exam_session_users.user_id', '=', 'tb_user_class.user_id');
            $query->where('tb_user_class.class_id', $params['class_id']);
        }
        if (!empty($params['order_by'])) {
            // Thêm tính tổng điểm và sắp xếp theo tổng điểm cao nhất
            $query->selectRaw("MAX(CASE WHEN tb_exam_session.type = '" . Consts::TYPE_EXAM_SESSION['test_iq'] . "' THEN tb_exam_session_users.score ELSE 0 END) + MAX(CASE WHEN tb_exam_session.type = '" . Consts::TYPE_EXAM_SESSION['test_acceptance'] . "' THEN tb_exam_session_users.score ELSE 0 END) AS total_score");
            $query->orderBy('total_score', $params['order_by']);
        } else {
            $query->orderBy('tb_exam_session_users.id', "DESC");
        }

        $query->groupBy('tb_exam_session_users.user_id');
        return $query;
    }

    public function student()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }
    public function exam()
    {
        return $this->belongsTo(ExamSession::class, 'exam_id', 'id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}
