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

class WareHouseProductImportAsset implements ToCollection
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
                // Check danh mục tài sản
                if ($row[1] == null || $row[1] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập mã danh mục!');
                    continue;
                }
                // Check mã sp
                if ($row[3] == null || $row[3] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập mã sản phẩm!');
                    continue;
                }
                // Check loại tài sản
                if ($row[2] == null || $row[2] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập loại tài sản!');
                    continue;
                }
                $type = '';
                switch (strtolower(trim($row[2]))) {
                    case 'cc':
                        $type = 'congcudungcu';
                        break;
                    case 'ts':
                        $type = 'taisan';
                        break;
                    default:
                        $type = 'vattutieuhao';
                        break;
                }

                // Tách 4 số cuối thành mã
                $code = Str::substr($row[3], -4);
                // Lấy thông tin sản phẩm
                $product = WareHouseProduct::where('code', $code)->first();
                if ($product) {
                    if (trim($product->name) != trim($row[4])) {
                        $this->rowError++;
                        array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': '.$row[3].' -- '.$row[4] .' -- trùng với: '.$product->name);
                        continue;
                    }
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Mã sản phẩm đã tồn tại!');
                    continue;
                }

                // Chưa có thì thêm mới
                $params['warehouse_category_id'] = $row[1];
                $params['warehouse_type'] = $type;
                $params['code'] = $code;
                $params['name'] = trim($row[4]) ?? '';
                $params['price'] = 0;
                $params['status'] = Consts::STATUS['active'];
                $params['admin_created_id'] = Auth::guard('admin')->user()->id;
                $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
                $params['json_params']['manufacturer'] = $row[11] ?? '';
                $params['json_params']['warranty'] = $row[12] ?? '';
                $params['json_params']['origin'] = $row[10] ?? '';
                $params['code_auto'] = $row[2] ?? '';
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
