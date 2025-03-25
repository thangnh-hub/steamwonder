<tr class="toggle_{{ $detail->id }}">
    <td colspan="9" class="table-responsive">
        <table style="border: 1px solid #dddddd;" class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">@lang('Order')</th>
                    <th rowspan="2">@lang('Lớp')</th>
                    <th rowspan="2">@lang('Ngày thi')</th>
                    <th rowspan="2">@lang('Hình thức')</th>
                    <th colspan="7">@lang('Điểm ')</th>
                    <th colspan="3">@lang('Điểm danh')</th>
                    <th colspan="3">@lang('Bài tập về nhà')</th>
                </tr>
                <tr>
                    <th style="width: 50px">@lang('Nghe')</th>
                    <th style="width: 50px">@lang('Nói')</th>
                    <th style="width: 50px">@lang('Đọc')</th>
                    <th style="width: 50px">@lang('Viết')</th>
                    <th style="width: 50px">@lang('TB')</th>
                    <th style="width: 170px">@lang('Nhận xét')</th>
                    <th style="width: 100px">@lang('Xếp loại')</th>
                    <th style="width: 70px">@lang('Có')</th>
                    <th style="width: 70px">@lang('Vắng')</th>
                    <th style="width: 70px">@lang('Muộn')</th>
                    <th style="width: 90px">@lang('Có làm')</th>
                    <th style="width: 90px">@lang('Không làm')</th>
                    <th style="width: 90px">@lang('Làm không đủ')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_class as $key => $item)
                    <tr class="valign-middle">
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                            {{ $item->class->name ?? '' }}
                        </td>
                        <td>
                            {{ $item->class->day_exam ?? '' }}
                        </td>

                        <td>
                            {{ $item->status!=""?App\Consts::USER_CLASS_STATUS[$item->status]:"" }}
                        </td>

                        <td>
                            {{ $item->score_listen }}
                        </td>
                        <td>
                            {{ $item->score_speak }}
                        </td>
                        <td>
                            {{ $item->score_read }}
                        </td>
                        <td>
                            {{ $item->score_write }}
                        </td>
                        <td>
                            {{ $item->score_average }}
                        </td>
                        <td>
                            {{ $item->note_score }}
                        </td>
                        <td>
                            {{ $item->status_rank != '' ? App\Consts::ranked_academic[$item->status_rank] ?? $item->status_rank : 'Chưa xác định' }}
                        </td>
                        <td>
                            {{ $item->attendant }}
                        </td>
                        <td>
                            {{ $item->absent }}
                            @if ($item->absent > 0)
                                ({{ $item->absent_has_reason !=''? "Có phép: ".$item->absent_has_reason :""}}
                                {{ $item->absent_has_reason !='' && $item->absent_no_reason!=''? ", ":""}}
                                {{ $item->absent_no_reason!=''?"Không phép: ".$item->absent_no_reason:"" }})
                            @endif
                        </td>
                        <td>
                            {{ $item->late }} lần
                            @if ($item->late > 0)
                                (Tổng: {{ $item->count_late }} phút)
                            @endif
                        </td>
                        <td>
                            @if ($item->is_homework_have > 0)
                                {{ $item->is_homework_have }} lần
                            @endif
                        </td>
                        <td>
                            @if ($item->is_homework_not_have > 0)
                                {{ $item->is_homework_not_have }} lần
                            @endif
                        </td>
                        <td>
                            @if ($item->is_homework_did_not_complete > 0)
                                {{ $item->is_homework_did_not_complete }} lần
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </td>
</tr>
@if (count($list_evolution) > 0)
    <tr class="toggle_{{ $detail->id }}">
        <td colspan="9">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th style="width:10%">@lang('Từ ngày')</th>
                            <th style="width:10%">@lang('Đến ngày')</th>
                            <th style="width:20%">@lang('Học lực')</th>
                            <th style="width:20%">@lang('Ý thức')</th>
                            <th style="width:20%">@lang('Kiến thức')</th>
                            <th style="width:20%">@lang('Kỹ năng')</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        @foreach ($list_class as $item)
                            @php
                                $list_by_class = $list_evolution->filter(function ($val, $key) use ($item) {
                                    return $val->class_id == $item->class_id;
                                });
                            @endphp
                            @if (isset($list_by_class) && count($list_by_class) > 0)
                                @foreach ($list_by_class as $value)
                                    @if (
                                        $value->from_date &&
                                            $value->to_date &&
                                            ((isset($value->json_params->ability) && $value->json_params->ability != '') ||
                                                (isset($value->json_params->consciousness) && $value->json_params->consciousness != '') ||
                                                (isset($value->json_params->knowledge) && $value->json_params->knowledge != '') ||
                                                (isset($value->json_params->skill) && $value->json_params->skill != '')))
                                        <tr>
                                            <td>
                                                {{ $value->from_date ? date('d-m-Y', strtotime($value->from_date)) : 'Chưa cập nhật' }}
                                            </td>
                                            <td>
                                                {{ $value->to_date ? date('d-m-Y', strtotime($value->to_date)) : 'Chưa cập nhật' }}
                                            </td>
                                            <td>
                                                {!! isset($value->json_params->ability) ? nl2br($value->json_params->ability) : '' !!}
                                            </td>
                                            <td>
                                                {!! isset($value->json_params->consciousness) ? nl2br($value->json_params->consciousness) : '' !!}
                                            </td>
                                            <td>
                                                {!! isset($value->json_params->knowledge) ? nl2br($value->json_params->knowledge) : '' !!}
                                            </td>
                                            <td>
                                                {!! isset($value->json_params->skill) ? nl2br($value->json_params->skill) : '' !!}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </td>
    </tr>
@endif
