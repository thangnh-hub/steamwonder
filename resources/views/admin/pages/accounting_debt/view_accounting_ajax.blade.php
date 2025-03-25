@isset($list_accounting)
    @foreach ($list_accounting as $item)
        <tr>
            <td>
                <div class="box_show"> @lang($item->type_revenue)</div>
                <div class="box_hide" style="display: none">
                    <select style="width: 100%" class="form-control select2 select_type">
                        @foreach ($type_revenue as $key => $val)
                            <option value="{{ $key }}"
                                {{ isset($item->type_revenue) && $item->type_revenue == $key ? 'selected' : '' }}>
                                @lang($val)
                            </option>
                        @endforeach
                    </select>
                </div>

            </td>
            <td>
                <div class="box_show">{{ $item->amount_paid != '' ? number_format($item->amount_paid, 0, ',', '.') : '---' }}
                    ₫</div>
                <div class="box_hide" style="display: none">
                    <input class="form-control money" type="number" value="{{ $item->amount_paid ?? '' }}">
                </div>

            </td>
            <td>
                <div class="box_show">
                    {{ $item->time_payment != '' ? date('d-m-Y', strtotime($item->time_payment)) : '---' }}
                </div>
                <div class="box_hide" style="display: none">
                    <input class="form-control time" type="date"
                        value="{{ $item->time_payment != '' ? date('Y-m-d', strtotime($item->time_payment)) : '' }}">
                </div>

            </td>
            <td>
                <div class="box_show">{{ $item->json_params->note ?? '' }}</div>
                <div class="box_hide" style="display: none">
                    <textarea class="form-control note" rows="3">{{ $item->json_params->note ?? '' }}</textarea>
                </div>

            </td>
            <td>
                <div class="box_show">
                    <button class="btn btn-sm btn-warning btn_show_edit" data-toggle="tooltip" title="@lang('Update')"
                        data-original-title="@lang('update')">
                        <i class="fa fa-pencil-square-o"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" data-id="{{ $item->id }}" data-toggle="tooltip"
                        title="@lang('Delete')" data-original-title="@lang('delete')"
                        onclick="delete_accounting_debt(this,{{ $item->id }})">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div class="box_hide" style="display: none">
                    <button class="btn btn-sm btn-success" data-toggle="tooltip" title="@lang('Save')"
                        data-original-title="@lang('Save')" onclick="update_accounting_debt(this,{{ $item->id }},{{$item->student_id}})">
                        <i class="fa fa-check"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn_cancel_edit" data-id="{{ $item->id }}"
                        data-toggle="tooltip" title="@lang('Hủy')" data-original-title="@lang('Hủy')">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </td>
        </tr>
    @endforeach
@endisset
