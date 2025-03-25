@foreach ($data['warehouse_asset'] as $items)
    <tr class="text-center">
        <td>
            {{ $loop->index + 1 }}
        </td>
        <td>
            {{ $items->code ?? '' }}
        </td>
        <td>
            {{ $items->name ?? '' }}
        </td>
        <td>
            {{ __($items->name_product_type ?? '') }}
        </td>
        <td>
            {{ $items->product->category_product->name ?? '' }}
        </td>
        <td>
            {{ $items->product->json_params->specification ?? '' }}
        </td>
        <td>
            {{ $items->product->json_params->origin ?? '' }}
        </td>
        <td>
            {{ $items->product->json_params->manufacturer ?? '' }}
        </td>
        <td>
            {{ $items->product->json_params->warranty ?? '' }}
        </td>
        <td>
            <select class="form-control select2" name="asset[{{ $items->id }}][state]" style="width: 100%"
                {{ $items->product_type == 'vattutieuhao' ? 'disabled' : '' }}>
                <option value="">@lang('Trình trạng')</option>
                @foreach ($data['state'] as $key => $val)
                    <option value="{{ $key }}"
                        {{ isset($items->state) && $items->state == $key ? 'selected' : '' }}>
                        @lang($val) </option>
                @endforeach
            </select>
        </td>
        <td>
            <select class="form-control select2" name="asset[{{ $items->id }}][department_id]" style="width: 100%">
                <option value="">@lang('Phòng ban')</option>
                @foreach ($data['department'] as $val)
                    <option value="{{ $val->id }}"
                        {{ isset($items->department_id) && $items->department_id == $val->id ? 'selected' : '' }}>
                        {{ $val->name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <select class="form-control select2" name="asset[{{ $items->id }}][position_id]" style="width: 100%">
                <option value="">@lang('Vị trí')</option>
                @foreach ($data['positions'] as $val)
                    @if (empty($val->parent_id))
                        <option value="{{ $val->id }}"
                            {{ isset($items->position_id) && $items->position_id == $val->id ? 'selected' : '' }}>
                            @lang($val->name)</option>
                        @foreach ($data['positions'] as $val1)
                            @if ($val1->parent_id == $val->id)
                                <option value="{{ $val1->id }}"
                                    {{ isset($items->position_id) && $items->position_id == $val1->id ? 'selected' : '' }}>
                                    - - @lang($val1->name)</option>
                                @foreach ($data['positions'] as $val2)
                                    @if ($val2->parent_id == $val1->id)
                                        <option value="{{ $val2->id }}"
                                            {{ isset($items->position_id) && $items->position_id == $val2->id ? 'selected' : '' }}>
                                            - - - - @lang($val2->name)</option>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </select>
        </td>
        <td>
            <input {{ $items->product_type == 'vattutieuhao' ? '' : 'readonly' }} type="number"
                name="asset[{{ $items->id }}][quantity]" class="form-control" value="{{ $items->quantity ?? 0 }}"
                min="0">
        </td>
        <td>
            <textarea cols="3" name="asset[{{ $items->id }}][note]" class="form-control">{{ $items->json_params->note ?? '' }}</textarea>
        </td>
        <td>
            <button class="btn btn-sm btn-danger" onclick="$(this).parents('tr').remove();" data-toggle="tooltip"
                title="@lang('Delete')" data-original-title="@lang('Delete')">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    </tr>
@endforeach
