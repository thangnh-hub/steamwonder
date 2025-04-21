<?php

namespace App\Imports;

use App\Models\DataCrm;
use App\Models\Admin;
use App\Models\Area;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Consts;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataCrmImport implements ToModel,WithHeadingRow,WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected $params = [];
    public function __construct($params=[])
    {
        $this->params = $params;
    }
    public function model(array $row)
    {
        $area = Area::where('code', $row['khu_vuc'])->first();
        if (!$area) throw new \Exception("Khu vực không tồn tại: " . $row['khu_vuc']);
        if (!empty($row['ma_cbts'])) {
            $admission = Admin::where('admin_code', $row['ma_cbts'])->first();
            if (!$admission) {
                throw new \Exception("Mã CBTS không tồn tại: " . $row['ma_cbts']);
            }
            $admission_id = $admission->id;
        } else {
            $admission_id = Auth::guard('admin')->user()->id;
        }

        return DataCrm::create([
            'area_id' => $area->id,
            'admission_id' => $admission_id,
            'first_name' => $row['ten'] ?? '',
            'last_name'  => $row['ho'] ?? '',
            'phone'      => $row['so_dien_thoai'] ?? '',
            'email'      => $row['email'] ?? '',
            'address'    => $row['dia_chi'] ?? '',
            'status'     => Consts::STATUS_DATACRM['new'],
            'admin_created_id'     => Auth::guard('admin')->user()->id,
            'type_import'       => 'excel',
        ]);
    }

    public function rules(): array
    {
        return [
            '*.khu_vuc' => 'required',
            '*.ten' => 'required',
            '*.ho' => 'required',
            '*.so_dien_thoai' => 'required|unique:tb_data_crms,phone',
            '*.email' => 'required|email|unique:tb_data_crms,email',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.khu_vuc.required' => 'Vui lòng chọn khu vực.',
            '*.ten.required' => 'Vui lòng nhập tên.',
            '*.ho.required' => 'Vui lòng nhập họ.',
            '*.so_dien_thoai.required' => 'Vui lòng nhập số điện thoại.',
            '*.so_dien_thoai.unique' => 'Số điện thoại đã tồn tại.',
            '*.email.required' => 'Vui lòng nhập email.',
            '*.email.email' => 'Email không đúng định dạng.',
            '*.email.unique' => 'Email đã tồn tại.',
        ];
    }

}
