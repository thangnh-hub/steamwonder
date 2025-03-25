<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\WareHouseProduct;
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

class WareHouseProductImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $hasDuplicateError = false;
    protected $params = [];
    protected $idsStudent = [];
    protected $isArea = [];
    private $rowCount = 0;
    private $rowUpdate = 0;
    private $rowInsert = 0;
    private $rowError = 0;
    public $hasError = false;
    public $errorMessage;
    public $arrErrorMessage = [];

    public function __construct($params = [])
    {
        set_time_limit(0);
        $this->params = $params;
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

                // Check danh mục
                if ($row[1] == null || $row[1] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập mã danh mục!');
                    continue;
                }
                // Check mã sp
                if ($row[2] == null || $row[2] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập mã sản phẩm!');
                    continue;
                }
                // Lấy thông tin sản phẩm
                $product = WareHouseProduct::where('code', trim($row[2]))->first();
                if ($product) {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Mã sản phẩm đã tồn tại!');
                    continue;
                }
                // Chưa có thì thêm mới
                $params['warehouse_category_id'] = $row[1];
                $params['warehouse_type'] = Consts::WAREHOUSE_PRODUCT_TYPE['taisan'];
                $params['code'] = $row[2];
                $params['name'] = $row[3] ?? '';
                $params['unit'] = $row[6] ?? '';
                $params['price'] = $row[7] ?? "";
                $params['status'] = Consts::STATUS['active'];
                $params['admin_created_id'] = Auth::guard('admin')->user()->id;
                $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
                $params['json_params']['specification'] = $row[4] ?? '';
                $params['json_params']['origin'] = $row[5] ?? '';
                WareHouseProduct::create($params);
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
