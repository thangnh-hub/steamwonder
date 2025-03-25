<?php

namespace App\Exports;

use App\Models\WarehouseAsset;
use App\Models\WareHouse;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Http\Services\DataPermissionService;
use App\Http\Services\WarehouseService;
use App\Consts;
use Illuminate\Support\Facades\Auth;


class WarehouseAssetStatisticalExport implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    protected $area;
    protected $all_asset;
    protected $list_warehouse;
    private $stt = 0;
    public function __construct($params)
    {
        $this->params = $params;
        $params['asset_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
        $this->all_asset = WarehouseAsset::getSqlWareHouseAsset($params)->get();
        $this->list_warehouse = Warehouse::whereIN('id', WarehouseService::getUniqueObjectToData('warehouse_id', $this->all_asset))->get();
        // Lấy khu vực theo kho
        $list_area =  WarehouseService::getUniqueObjectToData('area', $this->list_warehouse);
        foreach ($list_area as $area) {
            $area->warehouse = $this->list_warehouse->filter(function ($item, $key) use ($area) {
                return $item->area_id == $area->id;
            });
        }
        $this->area = $list_area;
    }
    public function collection()
    {

        // tổng hợp tài sản groupBy theo product_id
        $rows = $this->all_asset
            ->groupBy('product_id')
            ->map(function ($items) {
                $product = new \stdClass();
                $product->product_id = $items->first()['product_id'];
                $product->product_code = $items->first()['product']['code'];
                $product->name = $items->first()['name'];
                $product->product_type = $items->first()['product_type'];
                return $product;
            })
            ->values();
        $groupedAssets = $this->all_asset->groupBy(function ($item) {
            return $item->warehouse_id . '_' . $item->product_id;
        });

        foreach ($rows as $val) {
            $val->warehouse = $this->list_warehouse->mapWithKeys(function ($warehouse) use ($groupedAssets, $val) {
                $key = $warehouse->id . '_' . $val->product_id;
                // Lấy tổng số lượng từ groupedAssets nếu tồn tại
                $totalQuantity = $groupedAssets->get($key, collect())->sum('quantity');

                // Tính tổng số lượng trong kho (status = new)
                $total_quantity_new = $groupedAssets->get($key, collect())
                    ->filter(fn($item) => $item['status'] == 'new')
                    ->sum('quantity');
                // Tính tổng số lượng đang sử dụng (status != new)
                $total_quantity_using = $groupedAssets->get($key, collect())
                    ->filter(fn($item) => $item['status'] != 'new')
                    ->sum('quantity');

                return [$warehouse->id => [
                    'total' => $totalQuantity,
                    'new' => $total_quantity_new,
                    'using' => $total_quantity_using,
                ]];
            })->toArray();
            // Tổng số lượng tất cả khu vực
            $val->total_warehouse = array_sum(array_column($val->warehouse, 'total'));
            $val->total_warehouse_new = array_sum(array_column($val->warehouse, 'new'));
            $val->total_warehouse_using = array_sum(array_column($val->warehouse, 'using'));
        }

        return $rows;
    }
    public function headings(): array
    {
        $heading = [
            'STT',
            'Mã tài sản',
            'Tên tài sản',
            'Loại tài sản',
        ];
        foreach ($this->area as $area) {
            foreach ($area->warehouse as $warehouse) {
                array_push($heading, '(' . $area->name . ') - ' . $warehouse->name);
            }
        }
        array_push($heading, 'Tổng trong kho');
        array_push($heading, 'Tổng Đ.Sử dụng');
        return $heading;
    }
    public function map($user): array
    {
        $this->stt++;
        $result = [
            $this->stt,
            $user->product_code ?? '',
            $user->name ?? '',
            __($user->product_type ?? ''),
        ];
        foreach ($this->area as $area) {
            foreach ($area->warehouse as $warehouse) {
                array_push($result, $user->warehouse[$warehouse->id]['total'] ?? 0);
            }
        }
        array_push($result, $user->total_warehouse_new);
        array_push($result, $user->total_warehouse_using);
        return $result;
    }
}
