<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Team extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tb_teams';
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['is_super_admin'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'json_params' => 'object',
    ];

    /**
     * Add a mutator to ensure hashed passwords
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
    public static function getSqlTeam($params = [])
    {   
        $query = Team::select('tb_teams.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_teams.email', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_teams.name', 'like', '%' . $keyword . '%');
                });
            })->when(!empty($params['other_list']), function ($query) use ($params) {
                return $query->whereNotIn('tb_teams.id', $params['other_list']);
            });
            
        $query->groupBy('tb_teams.id');

        return $query;
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
}