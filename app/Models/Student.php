<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tb_students';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlStudent($params = [])
    {
        $query = Student::select('tb_students.*', 'a.name as area_name')
            ->leftJoin('tb_areas as a', 'tb_students.area_id', '=', 'a.id');

        if (isset($params['list_id']) && !empty($params['list_id'])) {
            $query->whereIn('tb_students.id', $params['list_id']);
        }

        if (isset($params['name']) && !empty($params['name'])) {
            $query->where('tb_students.name', 'like', '%' . $params['name'] . '%');
        }

        if (!empty($params['status'])) {
            $query->where('tb_students.status', $params['status']);
        }
        return $query;
    }

    public function adminCreated()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }

    public function adminUpdated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }

    public function currentClass()
    {
        return $this->belongsTo(tbClass::class, 'current_class_id', 'id');
    }

    // Sử dụng cho phần lấy các lớp mà sinh viên đó đã theo học
    public function userClasses()
    {
        return $this->belongsToMany(tbClass::class, StudentClass::class, 'student_id', 'class_id');
    }

    public function studentParents()
    {
        return $this->hasMany(StudentParent::class, 'student_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function admission()
    {
        return $this->belongsTo(Admin::class, 'admission_id', 'id');
    }
}
