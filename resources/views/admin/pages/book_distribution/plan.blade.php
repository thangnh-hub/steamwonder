@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        th {
            text-align: center;
            vertical-align: middle !important;
        }

        .table>tbody>tr>td {
            text-align: center;
            vertical-align: inherit;
        }
    </style>
@endsection

@php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }
@endphp
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>

    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('book_distribution.plan') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Tên lớp') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Nhập tên lớp')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Level')</label>
                                <select name="level_id" id="" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($levels as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['level_id']) && $params['level_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($areas as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('book_distribution.plan') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('Thống kê giáo trình')</h3>
                @if (count($areas) > 0)
                <button class="btn btn-warning btn-sm pull-right print_book">
                    <i class="fa fa-save"></i> @lang('Đề xuất in sách')
                </button>
                @endif
                <a href="{{ route('book_distribution.class_has_published') }}" class="btn btn-success btn-sm pull-right"
                    style="margin-right: 15px">
                    <i class="fa fa-list-alt"></i> @lang('Lớp đang in sách')
                </a>
            </div>
            <div class="box-body">

                <table class="table table-hover table-bordered">
                    <thead>
                        @if (isset($levels) && count($levels) > 0)
                            <tr>
                                <th rowspan="2">@lang('Khu vực')</th>
                                @foreach ($levels as $val)
                                    <th colspan="3">@lang('Cấp giáo trình') {{ $val->next_level->name }} </th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($levels as $val)
                                    <th>Số học viên</th>
                                    <th>Tồn kho</th>
                                    <th>Cần in</th>
                                @endforeach
                            </tr>
                        @endif
                    </thead>
                    <tbody>
                        @if (isset($areas) && count($areas) > 0)
                            @php
                                $arr_total = [];
                            @endphp
                            @foreach ($areas as $area)
                                <tr>
                                    <td>{{ $area->name }}</td>
                                    @foreach ($levels as $val)
                                        @php
                                            if (!empty($area->data) && is_iterable($area->data)) {
                                                $area_level = collect($area->data)->first(
                                                    fn($item) => $item->id == $val->id,
                                                );
                                                $total = $area_level->count_student - $area_level->product_quantity > 0 ? $area_level->count_student - $area_level->product_quantity : 0;
                                                $arr_total[$area->id][$val->id] =$total;
                                            }
                                        @endphp
                                        <td>{{ $area_level->count_student ?? '' }}</td>
                                        <td>{{ $area_level->product_quantity ?? '' }}</td>
                                        <td class="level_area" data-level= "{{ $val->next_level->id }}"
                                            data-area="{{ $area->id }}" data-val="{{ $total }}">
                                            <strong>{{ $total }} </strong>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            @php
                                $totalByCurriculum = [];
                                // Duyệt qua từng khu vực
                                foreach ($arr_total as $curriculums) {
                                    foreach ($curriculums as $curriculumId => $quantity) {
                                        $totalByCurriculum[$curriculumId] =
                                            ($totalByCurriculum[$curriculumId] ?? 0) + $quantity;
                                    }
                                }
                            @endphp
                            <tr>
                                <td><strong>Tổng</strong></td>
                                @foreach ($totalByCurriculum as $total)
                                    <td colspan="2"></td>
                                    <td><strong>{{$total}}</strong></td>
                                @endforeach

                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('Danh sách chi tiết')</h3>
            </div>
            <div class="box-body box-alert">
                @if (session('errorMessage'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! session('errorMessage') !!}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! session('successMessage') !!}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach

                    </div>
                @endif
                @if (count($areas) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered sticky">
                        <thead>
                            <tr>
                                <th rowspan="2">@lang('STT')</th>
                                <th rowspan="2">@lang('Khu vực')</th>
                                <th rowspan="2">@lang('Tên lớp')</th>
                                <th rowspan="2">@lang('Trình độ')</th>
                                <th colspan="3">@lang('Số buổi')</th>
                                <th colspan="3">@lang('Thời gian')</th>
                                <th rowspan="2">@lang('Sĩ số')</th>
                            </tr>
                            <tr>
                                <th style="width:120px">@lang('Tổng số')</th>
                                <th style="width:120px">@lang('Đã học')</th>
                                <th style="width:120px">@lang('Còn lại')</th>
                                <th style="width:120px">@lang('Bắt đầu')</th>
                                <th style="width:120px">@lang('Dự kiến')</th>
                                <th style="width:120px">@lang('Thực tế')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($areas)
                                @foreach ($areas as $area)
                                    @php
                                        $stt = $loop->index + 1;
                                        $i = 1;
                                    @endphp
                                    @foreach ($area->class as $items)
                                        @if ($i == 1)
                                            <tr>
                                                <td rowspan="{{ count($area->class) }}">{{ $stt }}</td>
                                                <td rowspan="{{ count($area->class) }}">{{ $area->name ?? '' }}</td>
                                                <td>
                                                    <strong
                                                        style="font-size: 14px">{{ $items->json_params->name->{$lang} ?? $items->name }}</strong>
                                                </td>
                                                <td>
                                                    {{ $items->level->name ?? '' }}
                                                </td>

                                                <td>
                                                    {{ $items->total_schedules ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->total_attendance ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->total_schedules - $items->total_attendance }}
                                                </td>
                                                <td>
                                                    {{ date('d-m-Y', strtotime($items->day_start)) }}
                                                </td>
                                                <td>

                                                    {{ date('d-m-Y', strtotime($items->day_end_expected)) }}
                                                </td>
                                                <td>

                                                    {{ date('d-m-Y', strtotime($items->day_end)) }}
                                                </td>
                                                <td>
                                                    <strong>{{ count($items->students ?? []) }}</strong>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>
                                                    <strong
                                                        style="font-size: 14px">{{ $items->json_params->name->{$lang} ?? $items->name }}</strong>
                                                </td>
                                                <td>
                                                    {{ $items->level->name ?? '' }}
                                                </td>

                                                <td>
                                                    {{ $items->total_schedules ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->total_attendance ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->total_schedules - $items->total_attendance }}
                                                </td>
                                                <td>
                                                    {{ date('d-m-Y', strtotime($items->day_start)) }}
                                                </td>
                                                <td>
                                                    {{ date('d-m-Y', strtotime($items->day_end_expected)) }}
                                                </td>
                                                <td>

                                                    {{ date('d-m-Y', strtotime($items->day_end)) }}
                                                </td>
                                                <td>
                                                    <strong>{{ count($items->students ?? []) }}</strong>
                                                </td>
                                            </tr>
                                        @endif
                                        @php $i++; @endphp
                                    @endforeach
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal_level_book" tabindex="-1" role="dialog" aria-hidden="false"
        data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Đề xuất in sách')</h5>
                </div>
                <div class="modal-body ">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Kho') </label>
                                <input type="text" class="form-control" value="Kho Mỹ đình" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Phòng') </label>
                                <input type="text" class="form-control" value="Phòng đào tạo" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Tên phiếu đề xuất') </label>
                                <input type="text" class="form-control" value="Đề xuất cấp phát sách" readonly>
                            </div>
                        </div>
                    </div>


                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                @foreach ($levels as $val)
                                    <th>@lang('Giáo trình') {{ $val->next_level->name }} </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="box_tableBody">
                            <tr class="tr_tableBody"></tr>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success active_print_book">@lang('Xác nhận')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Đóng')</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        var params = @json($params ?? []);
        $('.print_book').click(function() {
            $('#modal_level_book').modal('show');
            let levelData = [];
            let levelSum = [];
            $('.level_area').each(function() {
                let level = $(this).data('level');
                let area = $(this).data('area');
                let val = parseFloat($(this).data('val'));

                if (!levelSum[level]) {
                    levelSum[level] = 0;
                }
                levelSum[level] += val;
            });
            let row = '';
            $.each(levelSum, function(level, total) {
                if (total !== undefined) {
                    row += `<td>${total}</td>`;
                }
            });
            $('.tr_tableBody').html(row);
        })

        $('.active_print_book').click(function() {
            if (confirm("Bạn có chắc chắn muốn thực hiện thao tác?")) {
                let levelData = [];
                $('#modal_level_book').modal('hide');
                $('.level_area').each(function() {
                    let level = $(this).data('level');
                    let area = $(this).data('area');
                    let val = $(this).data('val');

                    if (!levelData[level]) {
                        levelData[level] = [];
                    }
                    levelData[level].push({
                        area: area,
                        val: val
                    });
                });
                let url = "{{ route('book_distribution.create.order.product.buy') }}";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        levelData: levelData,
                        params: params,
                    },
                    success: function(response) {
                        if (response.data != null) {
                            var _html = `<div class="alert alert-` + response.data + ` alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        ` + response.message + `
                        </div>`;
                            $('.box-alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert-warning").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert").fadeOut(3000, function() {});
                            }, 800);
                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Bạn không có quyền thao tác chức năng này!
                        </div>`;
                            $('.box-alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert-warning").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert").fadeOut(3000, function() {});
                            }, 800);
                        }
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        })
    </script>
@endsection
