<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\WareHouseProduct;
use App\Models\WareHouseOrder;
use App\Models\WareHouseOrderDetail;
use App\Models\WarehouseAsset;
use App\Models\WarehouseAssetHistory;
use App\Models\WareHouseEntry;
use App\Models\WareHouseEntryDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseService
{
    public static function getDataProduct($params)
    {
        $rows = WareHouseProduct::getSqlWareHouseProduct($params)->get();

        // Lấy tất cả WarehouseAsset liên quan
        $warehouseAssets = WarehouseAsset::whereIn('product_id', $rows->pluck('id'))
            ->where('status', Consts::WAREHOUSE_ASSET_STATUS['new'])
            ->where('warehouse_id', $params['warehouse_id'] ?? "")
            ->get()
            ->groupBy('product_id');
        // Gộp và tính tổng số lượng tồn kho
        $rows->map(function ($product) use ($warehouseAssets) {
            $product->ton_kho = $warehouseAssets->get($product->id, collect())->sum('quantity');
            return $product;
        });
        return $rows;
    }

    public static function getTonkho($product_id, $warehouse_id)
    {
        $params_asset['product_id'] = $product_id;
        $params_asset['warehouse_id'] = $warehouse_id ?? "";
        $params_asset['status'] = Consts::WAREHOUSE_ASSET_STATUS['new'];
        $info_product = WareHouseProduct::find($product_id);
        $ton_kho = NULL;
        if ($info_product) {
            $product = WarehouseAsset::getSqlWareHouseAsset($params_asset)->get();
            $ton_kho = $product->sum('quantity');
        }
        return $ton_kho;
    }
    public static function getTonkho_truoc_ky($product_id, $warehouse_id, $time)
    {
        $ton_kho = 0;
        $rows = WareHouseEntryDetail::select(
            DB::raw("SUM(CASE WHEN tb_warehouse_entry_detail.type = 'nhap_kho' THEN quantity ELSE 0 END) AS nhap_kho_quantity"),
            DB::raw("SUM(CASE WHEN tb_warehouse_entry_detail.type = 'xuat_kho' THEN quantity ELSE 0 END) AS xuat_kho_quantity"),
            DB::raw("SUM(CASE WHEN tb_warehouse_entry_detail.type = 'thu_hoi' THEN quantity ELSE 0 END) AS thu_hoi_quantity"),
            DB::raw("
                SUM(CASE
                    WHEN tb_warehouse_entry_detail.type = 'dieu_chuyen'
                    AND tb_warehouse_entry.status = 'approved'
                    AND tb_warehouse_entry_detail.warehouse_id_deliver = '{$warehouse_id}'
                    THEN quantity
                    ELSE 0
                END) AS dieu_chuyen_giao_quantity
            "),
            DB::raw("
                SUM(CASE
                    WHEN tb_warehouse_entry_detail.type = 'dieu_chuyen'
                    AND tb_warehouse_entry.status = 'approved'
                    AND tb_warehouse_entry_detail.warehouse_id = '{$warehouse_id}'
                    THEN quantity_entry
                    ELSE 0
                END) AS dieu_chuyen_nhan_quantity
            ")
        )
            ->leftJoin('tb_warehouse_product', 'tb_warehouse_entry_detail.product_id', '=', 'tb_warehouse_product.id')
            ->leftJoin('tb_warehouse_entry', 'tb_warehouse_entry.id', '=', 'tb_warehouse_entry_detail.entry_id')
            ->where('tb_warehouse_entry_detail.product_id', $product_id)
            ->whereDate('tb_warehouse_entry.created_at', '<', $time)
            ->where(function ($query) use ($warehouse_id) {
                $query->where('tb_warehouse_entry_detail.warehouse_id', $warehouse_id)
                    ->orWhere('tb_warehouse_entry_detail.warehouse_id_deliver', $warehouse_id);
            })
            ->groupBy('tb_warehouse_entry_detail.product_id')
            ->orderBy('tb_warehouse_entry_detail.product_id', 'asc')
            ->first();
        if ($rows) {
            $ton_kho = ($rows->nhap_kho_quantity + $rows->dieu_chuyen_nhan_quantity) - ($rows->xuat_kho_quantity + $rows->dieu_chuyen_giao_quantity) + $rows->thu_hoi_quantity;
        }
        return $ton_kho;
    }


    public static function getReportOrderProduct($params)
    {
        $reportOrderbyProduct = WareHouseOrderDetail::reportWareHouseOrderDetail($params)->get();
        return $reportOrderbyProduct;
    }

    /** Trừ số lượng trong bảng WarehouseAsset */
    public static function minusQuantityAsset($id, $quantity, $warehouse_id)
    {
        $warehouses_asset = WarehouseAsset::where('product_id', $id)->where('warehouse_id', $warehouse_id)->first();
        if (empty($warehouses_asset) || $warehouses_asset->quantity < $quantity) {
            return false;
        } else {
            $warehouses_asset->quantity -= $quantity;
            $warehouses_asset->save();
            return true;
        }
    }

    /** Tạo mới WareHouseEntryDetail*/
    public static function createdWareHouseEntryDetail($entry_id, $product_id, $quantity, $warehouse_id_deliver, $period, $staff_entry, $type)
    {
        // Tìm sản phẩm
        $product = WareHouseProduct::find($product_id);
        if (!$product) {
            throw new \Exception("Sản phẩm ID: $product_id không tồn tại.");
        }

        // Kiểm tra admin có đăng nhập không
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            throw new \Exception("Admin chưa đăng nhập.");
        }

        // Tạo dữ liệu nhập kho
        $params = [
            'entry_id' => $entry_id,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $product->price,
            'subtotal_money' => $product->price * $quantity,
            'warehouse_id_deliver' => $warehouse_id_deliver,
            'staff_entry' => $staff_entry,
            'type' => $type,
            'period' => $period,
            'admin_created_id' => $admin->id,
        ];

        // Thử tạo bản ghi và bắt lỗi
        try {
            return WareHouseEntryDetail::create($params);
        } catch (\Exception $e) {
            throw new \Exception("Lỗi khi tạo phiếu nhập kho: " . $e->getMessage());
        }
    }

    // Tạo mã theo ngày tháng kết hợp vs id của bảng nhập xuất kho và phiếu đề xuất
    public static function autoUpdateCode($id, $type)
    {
        // demo NK-152451
        $date = date('my');
        $code = $type . '-' . $date . $id;

        $warehouseEntryTypes = ['TH', 'ĐC', 'NK', 'XK', 'PHATSACH', 'QUATANG'];
        $warehouseOrderTypes = ['ĐX', 'MS'];

        if (in_array($type, $warehouseEntryTypes)) {
            $warehouse_entry = WareHouseEntry::find($id);
            if ($warehouse_entry) {
                $warehouse_entry->code = $code;
                return $warehouse_entry->save();
            }
        } elseif (in_array($type, $warehouseOrderTypes)) {
            $warehouse_order = WareHouseOrder::find($id);
            if ($warehouse_order) {
                $warehouse_order->code = $code;
                return $warehouse_order->save();
            }
        }

        return false; // Trả về false nếu không cập nhật được
    }

    /** Lấy thuộc tính từ collection*/
    public static function getUniqueObjectToData($property, $collection)
    {
        $list_property = $collection->map(function ($data) use ($property) {
            return $data->{$property};
        })->unique();
        return $list_property;
    }

    /** Tạo mới WarehouseAssetHistory*/
    public static function createdWarehouseAssetHistory($params = [])
    {
        // Định nghĩa giá trị mặc định
        $defaultParams = [
            'type' => null,
            'inventory_id' => null,
            'quantity' => null,
            'position_id' => null,
            'department_id' => null,
            'state' => null,
            'asset_id' => null,
            'product_id' => null,
            'staff_entry' => null,
            'staff_deliver' => null,
            'day_entry' => null,
            'day_deliver' => null,
            'status' => null,
            'warehouse_id' => null,
            'warehouse_id_deliver' => null,
            'json_params' => null,
            'admin_created_id' => Auth::guard('admin')->user()->id ?? null,
        ];

        // Hợp nhất params với giá trị mặc định
        $params_create = array_merge($defaultParams, $params);

        // Tạo bản ghi và kiểm tra kết quả
        try {
            $asset_history = WarehouseAssetHistory::create($params_create);
            return $asset_history ?: false;
        } catch (\Exception $e) {
            Log::error('Lỗi tạo WarehouseAssetHistory: ' . $e->getMessage());
            return false;
        }
    }

    public static function getAllChildrenIds($warehouse_category)
    {
        $ids = [];
        foreach ($warehouse_category->children as $child) {
            $ids[] = $child->id; // Lấy ID của con trực tiếp
            $ids = array_merge($ids, self::getAllChildrenIds($child)); // Đệ quy lấy ID của con cấp dưới
        }
        return $ids;
    }
}
