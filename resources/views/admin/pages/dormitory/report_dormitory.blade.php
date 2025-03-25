@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .box {
            border-top: 3px solid;
            border-bottom: 1px solid #CDCDCD;
            border-right: 1px solid #CDCDCD;
            border-left: 1px solid #CDCDCD;
            box-shadow: none;
        }

        .box-header .box-title {
            line-height: 20px
        }

        .table-bordered>thead>tr>th,
        .table-bordered>thead>tr>td {
            border-bottom-width: 1px;
        }

        .table>thead>tr>th {
            font: bold 14px/28px "Source Sans Pro";
            text-align: center;
            align-content: center;
        }

        .table>tbody>tr>td {
            text-align: center
        }

        .table>thead>tr {
            background-color: #3c8dbc;
            color: #FFFFFF;

        }

        ul.nav-stacked {
            max-height: 500px;
            overflow: auto;
        }

        .detail {
            display: flex;
            gap: 5px;
            justify-items: center;
        }

        .box.box-solid>.box-header .btn:hover {
            background-color: #00acd6;
        }




        .table_scroll {
            height: 560px;
            overflow-y: auto;
        }

        .table_scroll table {
            width: 100%;
            border-collapse: collapse;
        }

        .table_scroll thead {
            background-color: #f1f1f1;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table_scroll tbody {
            display: block;
            max-height: 500px;
            overflow-y: auto;
            width: 100%;
        }

        .table_scroll tbody::-webkit-scrollbar {
            width: 4px;
        }

        .table_scroll tbody::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .table_scroll tbody::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;
        }

        .table_scroll tbody::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .table_scroll thead,
        .table_scroll tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .table_scroll td,
        .table_scroll th {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .loading-notification {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 1.5rem;
            z-index: 9999;
        }
    </style>
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" target="_blank" href="{{ route('dormitory.report_month') }}">
                @lang('Xem thống kê theo tháng')</a>
        </h1>
    </section>
@endsection
@section('content')
    <div id="loading-notification" class="loading-notification">
        <p>@lang('Please wait')...</p>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            @if (session('errorMessage'))
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('errorMessage') }}
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
            <div class="col-md-5">
                @foreach ($list_area as $area)
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-building"></i>
                                {{ $area->area->name }}
                            </h3>
                            <div class="box-tools">
                                <button class=" btn btn-sm btn-info btn-flat detail" data-id="{{ $area->area_id }}">
                                    Xem chi tiết
                                </button>
                            </div>
                        </div>
                        <div class="no-padding">
                            <div class="mailbox-messages">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">@lang('Tổng số phòng cho thuê')</th>
                                            <th colspan="3">@lang('Số chỗ đang ở')</th>
                                            <th colspan="4">@lang('Trống')</th>
                                        </tr>
                                        <tr>
                                            <th>@lang('Nam')</th>
                                            <th>@lang('Nữ')</th>
                                            {{-- <th>@lang('Khác')</th> --}}
                                            <th>@lang('Tổng')</th>
                                            <th>@lang('Nam')</th>
                                            <th>@lang('Nữ')</th>
                                            <th>@lang('Khác')</th>
                                            <th>@lang('Tổng')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="valign-middle">
                                            <td>
                                                {{ $area->total_rooms }} ({{ $area->total_slot }} chỗ)
                                            </td>
                                            <td>
                                                {{ $area->already->already_male ?? '' }}
                                            </td>
                                            <td>
                                                {{ $area->already->already_female ?? '' }}
                                            </td>
                                            {{-- <td>
                                            {{ $student_already_other ?? '' }}
                                        </td> --}}
                                            <td>
                                                {{ $area->already->already_total }}
                                            </td>
                                            <td>
                                                {{ $area->empty->empty_male }}
                                            </td>
                                            <td>
                                                {{ $area->empty->empty_female }}
                                            </td>
                                            <td>
                                                {{ $area->empty->empty_other }}
                                            </td>
                                            <td>
                                                {{ $area->empty->empty_total }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-7">
                <div class="box box-primary box_detail">
                    <div class="box-header with-border">
                        <h3 class="box-title" id="title-list">@lang('Thống kê chi tiết theo tháng') <span class="area_name"></span></h3>
                        <div class="pull-right">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="month" class="form-control ip_month" placeholder="@lang('Chọn tháng')"
                                        max="{{ date('Y-m', time()) }}" value="{{ date('Y-m', time()) }}">
                                    <input type="hidden" name="area_id" id="area_id" value="">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="no-padding">
                        <div class="box_alert"></div>
                        <div class="table-responsive mailbox-messages ">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="3">@lang('Vào')</th>
                                        <th colspan="3">@lang('Rời đi')</th>
                                    </tr>
                                    <tr>
                                        <th>@lang('Nam')</th>
                                        <th>@lang('Nữ')</th>
                                        <th>@lang('Khác')</th>
                                        <th>@lang('Nam')</th>
                                        <th>@lang('Nữ')</th>
                                        <th>@lang('Khác')</th>
                                    </tr>
                                </thead>
                                <tbody class="table_total_detail">

                                </tbody>
                            </table>


                        </div>
                        <div class="box-header with-border">
                            <h3 class="box-title" id="title-list">@lang('Danh sách học viên trong tháng')</h3>
                            <button class="pull-right btn btn-sm btn-success btn_export" style="display: none"
                                data-url="{{ route('dormitory.export_report_student') }}"><i class="fa fa-file-excel-o"
                                    aria-hidden="false"></i>
                                @lang('Export Danh sách')</button>
                        </div>
                        <div class="table-responsive mailbox-messages table_scroll">
                            <table class="table table-bordered table_scroll">
                                <thead>
                                    <tr>
                                        <th>@lang('STT')</th>
                                        <th>@lang('Mã HV')</th>
                                        <th>@lang('Họ tên')</th>
                                        <th>@lang('CBTS')</th>
                                        <th>@lang('Giới tính')</th>
                                        <th>@lang('Phòng')</th>
                                        <th>@lang('Trạng thái')</th>
                                        <th>@lang('Ngày vào')</th>
                                        <th>@lang('Ngày ra')</th>
                                    </tr>
                                </thead>
                                <tbody class="table_detail">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).on('click', '.detail', function() {
            let area = $(this).data('id');
            $('#area_id').val(area);
            let month = $('.ip_month').val();
            getDataDormitory(area, month);
        });
        $(document).on('click', '.btn_export', function() {

            let area = $('#area_id').val();
            let month = $('.ip_month').val();
            if (!area || area == '') {
                var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Vui lòng chọn khu vực cần xem trước!
                            </div>`;
                $('.box_alert').prepend(_html);
                $('html, body').animate({
                    scrollTop: $(".alert-warning").offset().top
                }, 1000);
                setTimeout(function() {
                    $('.alert-warning').remove();
                }, 5000);
                hide_loading_notification()
                return;
            }
            var url = $(this).data('url');
            show_loading_notification()
            $.ajax({
                url: url,
                type: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                data: {
                    area_id: area,
                    months_come_leave: month,
                },
                success: function(response) {
                    if (response) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(response);
                        a.href = url;
                        a.download = 'Danh_sach_hoc_vien_ktx.xlsx';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.box_alert').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert').remove();
                        }, 3000);
                    }
                    hide_loading_notification()
                },
                error: function(response) {
                    hide_loading_notification()
                    let errors = response.responseJSON.message;
                    alert(errors);
                    eventInProgress = false;
                }
            });

        });

        $(document).on('change', '.ip_month', function() {
            let month = $(this).val();
            let area = $('#area_id').val();
            if (!area || area == '') {
                var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Vui lòng chọn khu vực cần xem trước!
                            </div>`;
                $('.box_alert').prepend(_html);
                $('html, body').animate({
                    scrollTop: $(".alert-warning").offset().top
                }, 1000);
                setTimeout(function() {
                    $('.alert-warning').remove();
                }, 5000);
            } else {
                getDataDormitory(area, month);
            }
        })

        function getDataDormitory(area, month) {
            show_loading_notification()
            $.ajax({
                url: '{{ route('dormitory.area.get') }}',
                type: 'GET',
                data: {
                    area_id: area,
                    months_come_leave: month,
                },
                success: function(response) {
                    $('.area_name').html(' khu vực: ' + response.data.area_name);
                    let list = response.data.list || null;
                    let _html = '';
                    if (list.length > 0) {
                        list.forEach((item, key) => {
                            _html += '<tr class="valign-middle">';
                            _html += '<td>' + (key + 1) + '</td>';
                            _html += '<td><strong style="font-size: 14px;">' + item.admin_code +
                                '</strong></td>';
                            _html += '<td>' + item.user_name + '</td>';
                            _html += '<td>' + item.staff_name + '</td>';
                            _html += '<td>' + item.user_gender + '</td>';
                            _html += '<td>' + item.dormitory_name + '</td>';
                            _html += '<td>' + item.status + '</td>';
                            _html += '<td>' + item.time_in + '</td>';
                            _html += '<td>' + item.time_out + '</td>';
                            _html += '</tr>';
                        });

                    }
                    $('.table_detail').html(_html)
                    let _html_total = '<tr class="valign-middle">';
                    _html_total += '<td>' + response.data.student_come_male + '</td>';
                    _html_total += '<td>' + response.data.student_come_female + '</td>';
                    _html_total += '<td>' + response.data.student_come_other + '</td>';
                    _html_total += '<td>' + response.data.student_out_male + '</td>';
                    _html_total += '<td>' + response.data.student_out_female + '</td>';
                    _html_total += '<td>' + response.data.student_out_other + '</td>';
                    _html_total += '</tr>';
                    $('.table_total_detail').html(_html_total)
                    $('html, body').animate({
                        scrollTop: $(".box_detail").offset().top
                    }, 1000);
                    hide_loading_notification()
                    $('.btn_export').show();
                },
                error: function(response) {
                    hide_loading_notification()
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });

        }
    </script>
@endsection
