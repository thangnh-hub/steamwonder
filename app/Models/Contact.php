<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_contacts';

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

    public static function getContact($params)
    {
        $query = Contact::select('tb_contacts.*')
            // ->selectRaw('tb_cms_taxonomys.title AS department')
            // ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_contacts.json_params->department_id')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_contacts.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_contacts.email', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_contacts.phone', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['department_id']), function ($query) use ($params) {
                $query->where('tb_contacts.json_params->department_id', '=', $params['department_id']);
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_contacts.is_type', $params['is_type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_contacts.status', $params['status']);
            })
            ->when(!empty($params['created_at_from']), function ($query) use ($params) {
                $query->where('tb_contacts.created_at', '>=', $params['created_at_from']);
            })
            ->when(!empty($params['created_at_to']), function ($query) use ($params) {
                $query->where('tb_contacts.created_at', '<=', $params['created_at_to']);
            });

        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_contacts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_contacts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_contacts.is_type ASC, tb_contacts.id DESC');
        }

        return $query;
    }
}
