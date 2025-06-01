<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\WareHouseProduct;
use App\Models\WareHouseEntry;
use App\Models\WareHouseEntryDetail;
use App\Models\WarehouseAsset;
use App\Models\WareHouse;
use App\Models\Department;
use App\Models\WareHousePosition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Consts;
use Carbon\Carbon;
use Exception;
use App\Http\Services\WarehouseService;


class WareHouseEntryImportAsset implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $hasDuplicateError = false;
    protected $params = [];
    protected $entry_id;
    protected $idsStudent = [];
    protected $isArea = [];
    private $rowCount = 0;
    private $rowUpdate = 0;
    private $rowInsert = 0;
    private $rowError = 0;
    public $hasError = false;
    public $errorMessage;
    public $arrErrorMessage = [];

    public function __construct($params = [], $entry_id)
    {
        set_time_limit(0);
        $this->params = $params;
        $this->entry_id = $entry_id;
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

                // Check mã sp
                if ($row[3] == null || $row[3] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập mã sản phẩm!');
                    continue;
                }
                // Check số lượng
                if ($row[6] == null || $row[6] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập số lượng sản phẩm!');
                    continue;
                }
                // Tách 4 số cuối thành mã
                $code = Str::substr($row[3], -4);

                // Lấy thông tin sản phẩm và phiếu xuất
                $product = WareHouseProduct::where('code', $code)->first();
                $entry = WareHouseEntry::find($this->entry_id);

                // Lấy id phòng ban theo mã $row[15]
                $depanent = Department::where('code', 'like', '%' . trim($row[15]) . '%')->first();
                $depanent_id = $depanent->id ?? null;

                // Lấy vị trí cấp cha nếu có
                if ($row[20] != null && $row[20] != '') {
                    $position_parent = WareHousePosition::where('warehouse_id', $entry->warehouse_id)->where('name', 'like', ['%' . trim($row[20]) . '%'])->first();
                    if (empty($position_parent)) {
                        // Chưa có thì tạo mới
                        $params_position_parent['name'] = trim($row[20]);
                        $params_position_parent['warehouse_id'] = $entry->warehouse_id;
                        $params_position_parent['status'] = Consts::STATUS['active'];
                        $params_position_parent['admin_created_id'] = Auth::guard('admin')->user()->id;
                        $position_parent = WareHousePosition::create($params_position_parent);
                    }
                }
                // Lấy vị trí theo tên $row[16] và kho tương ứng theo phiếu nhập
                $position = WareHousePosition::where('warehouse_id', $entry->warehouse_id)->where('name', 'like', ['%' . trim($row[16]) . '%'])->first();
                if (empty($position)) {
                    // Chưa có thì tạo mới
                    $params_position['parent_id'] = $position_parent->id ?? null;
                    $params_position['name'] = trim($row[16]);
                    $params_position['warehouse_id'] = $entry->warehouse_id;
                    $params_position['status'] = Consts::STATUS['active'];
                    $params_position['admin_created_id'] = Auth::guard('admin')->user()->id;
                    $position = WareHousePosition::create($params_position);
                }
                $position_id = $position->id ?? null;

                // Thêm vào bảng order_detail
                $order_detail_params['entry_id'] = $entry->id;
                $order_detail_params['period'] = $entry->period;
                $order_detail_params['product_id'] = $product->id;
                $order_detail_params['type'] =  Consts::WAREHOUSE_TYPE_ENTRY['nhap_kho'];
                $order_detail_params['quantity'] = isset($row[20]) && $row[20]  != '' ? $row[6] : 0;
                $order_detail_params['price'] = 0;
                $order_detail_params['subtotal_money'] =  0;
                $order_detail_params['warehouse_id'] = $entry->warehouse_id ?? null;
                $order_detail_params['admin_created_id'] = Auth::guard('admin')->user()->id;
                $order_detail_params['created_at'] = Carbon::now();
                $wareHouseEntryDetail = WareHouseEntryDetail::create($order_detail_params);

                if ($product) {
                    if ($product->warehouse_type == Consts::WAREHOUSE_PRODUCT_TYPE['taisan'] || $product->warehouse_type == Consts::WAREHOUSE_PRODUCT_TYPE['congcudungcu']) {
                        $quantity = $row[6] != '' ? (int) $row[6] : 1;
                        for ($i = 1; $i <= $quantity; $i++) {
                            // Tạo mã tài sản
                            $currentYear = Carbon::now()->year;
                            // lần mua
                            $year_entry_order = WareHouseEntry::where('type', Consts::WAREHOUSE_TYPE_ENTRY['nhap_kho']) // Chỉ lấy phiếu nhập kho
                                ->where('period', 'like', "$currentYear-%")->get()->count();
                            $year_entry_order = str_pad(($year_entry_order + 1), 2, '0', STR_PAD_LEFT);
                            //Đếm số lượng sản phẩm
                            $latestAsset = WarehouseAsset::where('product_id', $product->id)
                                ->where('warehouse_id', $entry->warehouse_id ?? null)
                                ->get()->count();
                            $nextNumber = $latestAsset ? $latestAsset + 1 : 1; // Số thứ tự tiếp theo
                            $area = WareHouse::find($entry->warehouse_id);
                            $name_area = isset($area) ? $area->area->code : "";
                            $assetCode = Carbon::now()->year . $year_entry_order . $name_area . $product->code_auto . $product->category_product->code_auto . $product->code . '_' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

                            // Chuẩn bị dữ liệu để lưu vào bảng `tb_warehouses_asset`
                            $params_asset['code'] = $assetCode;
                            $params_asset['warehouse_id'] = $entry->warehouse_id ?? null;
                            $params_asset['entry_id'] = $this->entry_id;
                            $params_asset['product_id'] = $product->id;
                            $params_asset['price'] = 0;
                            $params_asset['quantity'] = 1;
                            $params_asset['name'] = $product->name;
                            $params_asset['product_type'] = $product->warehouse_type ?? "";
                            $params_asset['status'] = ($row[13] == Consts::STATE_WAREHOUSES_ASSET['using'] && $depanent_id != null) ? 'deliver' : Consts::WAREHOUSE_ASSET_STATUS['new'];
                            $params_asset['admin_created_id'] = Auth::guard('admin')->user()->id;
                            $params_asset['state'] = Consts::STATE_WAREHOUSES_ASSET[$row[13]] ?? null;
                            $params_asset['department_id'] = $depanent_id;
                            $params_asset['position_id'] = $position_id;
                            $params_asset['json_params']['note'] = 'Chủ sở hữu: ' . $row[17];
                            $WarehouseAsset =  WarehouseAsset::create($params_asset);

                            // Tạo lịch sử tài sản trong bảng asset history
                            $params_asset_history['type'] = Consts::WAREHOUSE_TYPE_ASSET_HISTORY['nhapkho'];
                            $params_asset_history['asset_id'] = $WarehouseAsset->id;
                            $params_asset_history['quantity'] = $WarehouseAsset->quantity;
                            $params_asset_history['position_id'] = $WarehouseAsset->position_id;
                            $params_asset_history['department_id'] = $WarehouseAsset->department_id;
                            $params_asset_history['state'] = $WarehouseAsset->state;
                            $params_asset_history['product_id'] = $WarehouseAsset->product_id;
                            $params_asset_history['warehouse_id'] = $WarehouseAsset->warehouse_id;
                            $params_asset_history['json_params']['note'] =  $row[19]. '. Chủ sở hữu: ' . $row[17];
                            WarehouseService::createdWarehouseAssetHistory($params_asset_history);
                        }
                    }

                    if ($product->warehouse_type == Consts::WAREHOUSE_PRODUCT_TYPE['vattutieuhao']) {
                        $quantity = $row[6] ?? 1;
                        // Kiểm tra nếu mã sản phẩm đã tồn tại
                        $existingAsset = WarehouseAsset::where('product_id', $product->id)
                            ->where('warehouse_id', $entry->warehouse_id ?? null)->first();
                        if ($existingAsset) {
                            // Cộng dồn số lượng
                            $existingAsset->quantity += $quantity;
                            $existingAsset->updated_at = Carbon::now();
                            $existingAsset->save();
                        } else {
                            $params_asset = [
                                'entry_id' => $this->entry_id,
                                'product_id' => $product->id,
                                'product_type' => $product->warehouse_type ?? "",
                                'quantity' => $row[6],
                                'price' => 0,
                                'warehouse_id' => $entry->warehouse_id ?? null,
                                'code' => $product->code,
                                'name' => $product->name,
                                'status' => Consts::WAREHOUSE_ASSET_STATUS['new'],
                                'admin_created_id' => Auth::guard('admin')->user()->id,
                            ];
                            WarehouseAsset::create($params_asset);
                        }
                    }
                }

                $this->rowInsert++;
                continue;
            }
            DB::commit();
            $this->hasError = false;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->hasError = true;
            $this->errorMessage = "Lỗi tại vị trí " . $this->rowCount . ": " . $e->getMessage();
        }
    }

    public function getRowCount()
    {
        $data_count = [
            'total_row' => $this->rowCount,
            'update_row' => $this->rowUpdate,
            'insert_row' => $this->rowInsert,
            'error_row' => $this->rowError,
            'error_mess' => $this->arrErrorMessage,
        ];
        return $data_count;
    }
}
