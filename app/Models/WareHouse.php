<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Consts;


class WareHouse extends Model
{
  protected $table = 'tb_warehouses';

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

  public static function getSqlWareHouse($params = [])
  {
    $query = WareHouse::select('tb_warehouses.*')
      ->when(!empty($params['keyword']), function ($query) use ($params) {
        $keyword = $params['keyword'];
        return $query->where(function ($where) use ($keyword) {
          return $where->where('tb_warehouses.name', 'like', '%' . $keyword . '%');
        });
      })
      ->when(!empty($params['status']), function ($query) use ($params) {
        return $query->where('tb_warehouses.status', $params['status']);
      })
      ->when(!empty($params['area_id']), function ($query) use ($params) {
        return $query->where('tb_warehouses.area_id', $params['area_id']);
      })
      ->when(!empty($params['warehouse_permission']), function ($query) use ($params) {
        return $query->whereIn('tb_warehouses.id', $params['warehouse_permission']);
      });
    $query->groupBy('tb_warehouses.id');
    return $query;
  }

  public static function getReportSqlWareHouseEntryDeliver($params = [])
  {
    $query = WareHouseEntryDetail::select(
      '*',
      DB::raw("SUM(CASE WHEN tb_warehouse_entry_detail.type = 'nhap_kho' THEN quantity ELSE 0 END) AS nhap_kho_quantity"),
      DB::raw("SUM(CASE WHEN tb_warehouse_entry_detail.type = 'nhap_kho' THEN subtotal_money ELSE 0 END) AS nhap_kho_subtotal_money"),
      DB::raw("SUM(CASE WHEN tb_warehouse_entry_detail.type = 'xuat_kho' THEN quantity ELSE 0 END) AS xuat_kho_quantity"),
      DB::raw("SUM(CASE WHEN tb_warehouse_entry_detail.type = 'xuat_kho' THEN subtotal_money ELSE 0 END) AS xuat_kho_subtotal_money"),
      DB::raw("SUM(CASE WHEN tb_warehouse_entry_detail.type = 'thu_hoi' THEN quantity ELSE 0 END) AS thu_hoi_quantity"),
      DB::raw("SUM(CASE WHEN tb_warehouse_entry_detail.type = 'dieu_chuyen' AND tb_warehouse_entry.status = 'approved' THEN quantity ELSE 0 END) AS dieu_chuyen_giao_quantity"),
      DB::raw("SUM(CASE WHEN tb_warehouse_entry_detail.type = 'dieu_chuyen' AND tb_warehouse_entry.status = 'approved' THEN quantity_entry ELSE 0 END) AS dieu_chuyen_nhan_quantity")
    )
      ->groupBy('product_id')->orderBy('product_id')
      ->leftJoin('tb_warehouse_product', 'tb_warehouse_entry_detail.product_id', '=', 'tb_warehouse_product.id')
      ->leftJoin('tb_warehouse_entry', 'tb_warehouse_entry.id', '=', 'tb_warehouse_entry_detail.entry_id')
      ->when(!empty($params['keyword']), function ($query) use ($params) {
        $keyword = $params['keyword'];
        return $query->where(function ($where) use ($keyword) {
          return $where->where('tb_warehouse_product.name', 'like', '%' . $keyword . '%');
        });
      })

      ->when(!empty($params['warehouse_type']), function ($query) use ($params) {
        return $query->where('tb_warehouse_product.warehouse_type', $params['warehouse_type']);
      })
      ->when(!empty($params['warehouse_category_id']), function ($query) use ($params) {
        if (is_array($params['warehouse_category_id'])) {
          return $query->whereIn('tb_warehouse_product.warehouse_category_id', $params['warehouse_category_id']);
        } else {
          return $query->where('tb_warehouse_product.warehouse_category_id', $params['warehouse_category_id']);
        }
      })

      ->when(!empty($params['period_before']), function ($query) use ($params) {
        return $query->whereDate('tb_warehouse_entry.created_at', "<", $params['period_before']);
      })
      ->when(!empty($params['from_date']), function ($query) use ($params) {
        return $query->whereDate('tb_warehouse_entry.created_at', ">=", $params['from_date']);
      })
      ->when(!empty($params['to_date']), function ($query) use ($params) {
        return $query->whereDate('tb_warehouse_entry.created_at', "<=", $params['to_date']);
      })
      ->when(!empty($params['product_id']), function ($query) use ($params) {
        if (is_array($params['product_id'])) {
          return $query->whereIn('tb_warehouse_entry_detail.product_id', $params['product_id']);
        } else {
          return $query->where('tb_warehouse_entry_detail.product_id', $params['product_id']);
        }
      })
      ->when(!empty($params['warehouse_id']), function ($query) use ($params) {
        return $query->where(function ($where) use ($params) {
          return $where->where('tb_warehouse_entry_detail.warehouse_id', $params['warehouse_id'])
            ->orWhere('tb_warehouse_entry_detail.warehouse_id_deliver', $params['warehouse_id']);
        });
      })
      ->when(!empty($params['entry_permission']), function ($query) use ($params) {
        return $query->whereIn('tb_warehouse_entry_detail.entry_id', $params['entry_permission']);
      })


      ->when(!empty($params['warehouse_permission']), function ($query) use ($params) {
        return $query->where(function ($where) use ($params) {
          return $where->whereIn('tb_warehouse_entry_detail.warehouse_id', $params['warehouse_permission'])
            ->orWhereIn('tb_warehouse_entry_detail.warehouse_id_deliver', $params['warehouse_permission']);
        });
      });
    if (!empty($params['warehouse_id'])) {
      $query->addSelect(DB::raw("
                    SUM(CASE
                        WHEN tb_warehouse_entry_detail.type = 'dieu_chuyen'
                        AND tb_warehouse_entry.status = 'approved'
                        AND tb_warehouse_entry_detail.warehouse_id_deliver = '{$params['warehouse_id']}'
                        THEN quantity
                        ELSE 0
                    END) AS dieu_chuyen_giao_quantity
                "));
      $query->addSelect(DB::raw("
                    SUM(CASE
                        WHEN tb_warehouse_entry_detail.type = 'dieu_chuyen'
                        AND tb_warehouse_entry.status = 'approved'
                        AND tb_warehouse_entry_detail.warehouse_id = '{$params['warehouse_id']}'
                        THEN quantity_entry
                        ELSE 0
                    END) AS dieu_chuyen_nhan_quantity
                "));
    }
    return $query;
  }
  public function admin_created()
  {
    return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
  }
  public function admin_updated()
  {
    return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
  }
  public function area()
  {
    return $this->belongsTo(Area::class, 'area_id', 'id');
  }
}
