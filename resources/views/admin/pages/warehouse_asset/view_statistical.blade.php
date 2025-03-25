@if (isset($department_assets) && count($department_assets) > 0)
    <td colspan="{{ 6 + (int) $params['colspan'] }}">
        <p><strong> Thống kê tài sản: {{ $product->name }} theo phòng ban tại: {{ $warehause->name }} </strong></p>
        <table style="border: 1px solid #dddddd;" class="table table-bordered">
            <thead>
                <tr>
                    @foreach ($department_assets as $val)
                        <th class="text-center">{{ $val['department_name'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach ($department_assets as $val)
                        <td class="text-center cursor td_detail" title="@lang('Chi tiết')">
                            <a href="{{ route('warehouse_asset.index', ['product_id' => $product->id, 'warehouse_id' => $warehause->id, 'department_id' => $val['department_id']]) }}"
                                target="_blank" class="block_full_width"> {{ $val['total_quantity'] }}</a>
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </td>
@endif
@if (isset($position_hierarchy) && count($position_hierarchy) > 0)
    <td colspan="{{ 6 + (int) $params['colspan'] }}">
        <p><strong>Thống kê tài sản: {{ $product->name }} theo vị trí tại: {{ $warehause->name }}</strong></p>
        <table style="border: 1px solid #dddddd;" class="table table-bordered">
            {{-- Cấp 1 --}}
            <thead>
                <tr>
                    @php $arr_sub = [] @endphp
                    @foreach ($position_hierarchy as $val)
                        <th colspan="{{ $val['colspan'] }}"class="text-center">{{ $val['position_name'] }}</th>
                        @php
                            $arr_sub[] = $val['children'];
                        @endphp
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach ($position_hierarchy as $val)
                        <td colspan="{{ $val['colspan'] }}" class="text-center cursor td_detail"
                            title="@lang('Chi tiết')">
                            <a href="{{ route('warehouse_asset.index', ['product_id' => $product->id, 'warehouse_id' => $warehause->id, 'position_id' => $val['position_id']]) }}"
                                target="_blank" class="block_full_width"> {{ $val['total_quantity'] }}</a>
                        </td>
                    @endforeach
                </tr>
            </tbody>


            {{-- Cấp 2 --}}
            @if (isset($arr_sub))
                @php
                    // đếm xem có thằng con mới hiển thị
                    $count_sub = collect($arr_sub)
                        ->filter(function ($item) {
                            return $item->count() > 0;
                        })
                        ->count();
                @endphp
                @if ($count_sub > 0)

                    <thead>
                        <tr>
                            @php $arr_sub_child = [] @endphp
                            @foreach ($arr_sub as $val_sub)
                                @foreach ($val_sub as $sub)
                                    @php
                                        $arr_sub_child[] = $sub['children'] ?? [];
                                    @endphp
                                    <th colspan="{{ $sub['colspan'] }}"class="text-center">{{ $sub['position_name'] }}
                                    </th>
                                @endforeach
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($arr_sub as $val_sub)
                                @if (count($val_sub) > 0)
                                    @foreach ($val_sub as $sub)
                                        <td colspan="{{ $sub['colspan'] }}" class="text-center cursor td_detail"
                                            title="@lang('Chi tiết')">
                                            <a href="{{ route('warehouse_asset.index', ['product_id' => $product->id, 'warehouse_id' => $warehause->id, 'position_id' => $val['position_id']]) }}"
                                                target="_blank" class="block_full_width">
                                                {{ $sub['total_quantity'] }}</a>
                                        </td>
                                    @endforeach
                                @else
                                    <td></td>
                                @endif
                            @endforeach
                        </tr>
                    </tbody>
                @endif
            @endif

            {{-- Cấp 3 --}}
            @if (isset($arr_sub_child))
                @php
                    // đếm xem có thằng con mới hiển thị
                    $count_sub_child = collect($arr_sub_child)
                        ->filter(function ($item) {
                            return $item->count() > 0;
                        })
                        ->count();
                @endphp
                @if ($count_sub_child > 0)
                    <thead>
                        <tr>
                            @foreach ($arr_sub_child as $val_sub_child)
                                @if (count($val_sub_child) > 0)
                                    @foreach ($val_sub_child as $sub_child)
                                        <th class="text-center">{{ $sub_child['position_name'] }}</th>
                                    @endforeach
                                @else
                                    <th></th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($arr_sub_child as $val_sub_child)
                                @if (count($val_sub_child) > 0)
                                    @foreach ($val_sub_child as $sub_child)
                                        <td class="text-center cursor th_detail" title="@lang('Chi tiết')">
                                            <a href="{{ route('warehouse_asset.index', ['product_id' => $product->id, 'warehouse_id' => $warehause->id, 'position_id' => $val['position_id']]) }}"
                                                target="_blank" class="block_full_width">
                                                {{ $sub_child['total_quantity'] }}</a>
                                        </td>
                                    @endforeach
                                @else
                                    <td></td>
                                @endif
                            @endforeach
                        </tr>
                    </tbody>
                @endif

            @endif
        </table>
    </td>
@endif
