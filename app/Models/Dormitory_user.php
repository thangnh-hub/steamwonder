<?php

namespace App\Models;

use Carbon\Carbon;
use App\Consts;
use Illuminate\Database\Eloquent\Model;


class Dormitory_user extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_dormitory_user';

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

    public static function getSqlDormitoryUser($params = [])
    {
        $query = Dormitory_user::select('tb_dormitory_user.*')
            ->selectRaw('admins.admin_code,admins.name as user_name,admins.gender as user_gender,admins.admission_id,admins.course_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_dormitory_user.id_user')
            ->leftJoin('tb_dormitory', 'tb_dormitory.id', '=', 'tb_dormitory_user.id_dormitory')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.json_params->cccd', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['gender_user']), function ($query) use ($params) {
                return $query->where('admins.gender', $params['gender_user']);
            })
            ->when(!empty($params['status_study']), function ($query) use ($params) {
                return $query->where('admins.status_study', $params['status_study']);
            })
            ->when(!empty($params['dormitory']), function ($query) use ($params) {
                return $query->where('tb_dormitory_user.id_dormitory', $params['dormitory']);
            })
            ->when(!empty($params['list_user']), function ($query) use ($params) {
                return $query->whereIn('tb_dormitory_user.id_user', $params['list_user']);
            })
            ->when(!empty($params['id_user']), function ($query) use ($params) {
                return $query->where('tb_dormitory_user.id_user', $params['id_user']);
            })
            ->when(!empty($params['time_in']), function ($query) use ($params) {
                return $query->where('tb_dormitory_user.time_in', $params['time_in']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_dormitory_user.id', $params['id']);
            })

            ->when(!empty($params['months']), function ($query) use ($params) {
                return $query->where('tb_dormitory_user.time_in', '<=', Carbon::parse($params['months'])->lastOfMonth()->toDateString());
            })

            ->when(!empty($params['months_come']), function ($query) use ($params) {
                return $query->whereMonth('tb_dormitory_user.time_in', Carbon::parse($params['months_come'])->month)
                    ->whereYear('tb_dormitory_user.time_in', Carbon::parse($params['months_come'])->year);
            })

            ->when(!empty($params['months_leave']), function ($query) use ($params) {
                return $query->whereMonth('tb_dormitory_user.time_out', Carbon::parse($params['months_leave'])->month)
                    ->whereYear('tb_dormitory_user.time_out', Carbon::parse($params['months_leave'])->year);
            })

            ->when(!empty($params['day_expired']), function ($query) use ($params) {
                return $query->where('tb_dormitory_user.time_expires', '<=', Carbon::parse($params['day_expired']));
            })

            ->when(!empty($params['months_come_leave']), function ($query) use ($params) {
                // Ngày vào nhỏ hơn ngày cuối cùng trong tháng
                $query->where('tb_dormitory_user.time_in', '<=', Carbon::parse($params['months_come_leave'])->lastOfMonth()->toDateString());
                // Chưa ra hoặc ngày ra lớn hơn ngày đầu tiên của tháng
                $query->where(function ($query) use ($params) {
                    $query->whereNull('tb_dormitory_user.time_out')
                        ->orWhere('tb_dormitory_user.time_out', '>=', Carbon::parse($params['months_come_leave'])->firstOfMonth()->toDateString());
                });
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_dormitory.area_id', $params['area_id']);
            });
        if (!empty($params['status_other_deactive'])) {
            $query->where('tb_dormitory.status', '!=', Consts::STATUS_DORMITORY['deactive']);
        }

        // Có cả từ tháng và đến tháng
        if (!empty($params['from_month']) && !empty($params['to_month'])) {
            // Nếu chọn loại
            if (!empty($params['type'])) {
                switch ($params['type']) {
                    case 'come':
                        $query->where('tb_dormitory_user.time_in', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString())
                            ->where('tb_dormitory_user.time_in', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString());
                        break;
                    case 'leave':
                        $query->where('tb_dormitory_user.time_out', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString())
                            ->where('tb_dormitory_user.time_out', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString());
                        break;
                    case 'expire':
                        $query->where('tb_dormitory_user.time_expires', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString())
                            ->where('tb_dormitory_user.time_expires', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString());
                        break;
                    default:

                        break;
                }
            } else {
                $query->where(function ($query) use ($params) {
                    $query->where('tb_dormitory_user.time_in', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString())
                        ->orWhere(function ($query) use ($params) {
                            $query->where('tb_dormitory_user.time_out', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString())
                                ->where('tb_dormitory_user.time_out', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString());
                        })
                        ->orWhere(function ($query) use ($params) {
                            $query->where('tb_dormitory_user.time_expires', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString())
                                ->where('tb_dormitory_user.time_expires', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString());
                        });
                });
            }
        }
        // chỉ có từ tháng
        elseif (!empty($params['from_month'])) {
            if (!empty($params['type'])) {
                switch ($params['type']) {
                    case 'come':
                        $query->where('tb_dormitory_user.time_in', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString());
                        break;
                    case 'leave':
                        $query->where('tb_dormitory_user.time_out', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString());
                        break;
                    case 'expire':
                        $query->where('tb_dormitory_user.time_expires', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString());
                        break;
                    default:
                        break;
                }
            } else {
                $query->where('tb_dormitory_user.time_in', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString())
                    ->orWhere('tb_dormitory_user.time_out', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString())
                    ->orWhere('tb_dormitory_user.time_expires', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString());
            }
        }
        // Chỉ có đến tháng
        elseif (!empty($params['to_month'])) {
            if (!empty($params['type'])) {
                switch ($params['type']) {
                    case 'come':
                        $query->where('tb_dormitory_user.time_in', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString());
                        break;
                    case 'leave':
                        $query->where('tb_dormitory_user.time_out', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString());
                        break;
                    case 'expire':
                        $query->where('tb_dormitory_user.time_expires', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString());
                        break;
                    default:
                        break;
                }
            } else {
                $query->where('tb_dormitory_user.time_in', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString())
                    ->orWhere('tb_dormitory_user.time_out', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString())
                    ->orWhere('tb_dormitory_user.time_expires', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString());
            }
        };

        if (!empty($params['status'])) {
            $query->where('tb_dormitory_user.status', $params['status']);
        };
        $query->orderByRaw("FIELD(tb_dormitory_user.status, 'already', 'leave')");

        return $query;
    }


    public function dormitory()
    {
        return $this->belongsTo(Dormitory::class, 'id_dormitory', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'id_user', 'id');
    }
}
