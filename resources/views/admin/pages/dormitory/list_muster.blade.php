@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
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
            <form action="{{ route('dormitory.listmuster') }}" method="GET" id="form_filter">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" id="area_id" class="form-control area_id select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($area as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['area_id']) && $value->id == $params['area_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}
                                            (Mã: {{ $value->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Phòng')</label>
                                <select name="id" id="dormitory" class="form-control dormitory select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($dormitory as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['id']) && $value->id == $params['id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Chọn ngày') <span class="text-red">*</span></label>
                                <input type="date" class="form-control time_muster" required name="time_muster"
                                max="{{ date('Y-m-d', time()) }}"
                                value="{{ isset($params['time_muster']) ? $params['time_muster'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Lấy thông tin')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-body table-responsive box_alert">
                @if (session('errorMessage'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! session('errorMessage') !!}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('successMessage') }}
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

                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Phòng')</th>
                                <th>@lang('Area')</th>
                                <th>@lang('Đơn nguyên')</th>
                                <th>@lang('Học viên / Sức chứa')</th>
                                <th>@lang('Địa chỉ')</th>
                                <th>@lang('Có mặt')</th>
                                <th>@lang('Vắng mặt')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td> <strong
                                            style="font-size: 14px;">{{ $row->json_params->name->{$lang} ?? $row->name }}</strong>
                                    </td>
                                    <td>
                                        {{ $row->area->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->don_nguyen ?? '' }}
                                    </td>
                                    <td>
                                        {{-- {{ $row->quantity }}/{{ $row->slot ?? '' }} --}}
                                        {{ $row->dormitoryUsers()->where('status', 'already')->count() }}/{{ $row->slot ?? '' }}

                                    </td>
                                    <td>
                                        {{ $row->json_params->address ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->total_present ?? 0 }}
                                    </td>
                                    <td>
                                        {{ $row->total_absent ?? 0 }}
                                    </td>
                                    <td>
                                        <button class="btn btn-warning add_muster" data-id="{{ $row->id }}"
                                            style="margin-right: 5px"><i class="fa fa-plus"></i>
                                            @lang('Lấy điểm danh')</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy {{ $rows->count() }} kết quả
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('script')
    <script>
        $(document).ready(function() {

            $('.area_id').on('change', function() {
                var areaId = $(this).val();
                loadDormitory(areaId);
            })

            $('.add_muster').click(function() {
                var time_muster = $('.time_muster').val();
                var id = $(this).data('id');
                if (time_muster == '' || time_muster == null) {
                    alert('Vui lòng chọn ngày để lấy điểm danh !')
                    $('.time_muster').focus();
                    return;
                }
                if ($("#form_filter")[0].checkValidity()) {
                    window.open("{{ route('dormitory.getmuster') }}" + "?id=" + id + "&time=" +
                        time_muster, '_blank');


                } else {
                    $("#form_filter")[0].reportValidity();
                }

            })
        });

        function loadDormitory(areaId) {
            var dormitory = @json($dormitory ?? []);
            var _html = `<option value="">@lang('Please select')</option>`;
            dormitory.forEach(function(val) {
                if (val.area_id == areaId) {
                    _html += `<option value="` + val.id + `">` + val.name + `</option>`;
                }
            })
            $('.dormitory').html(_html).select2({
                width: '100%'
            });
        }
    </script>
@endsection
