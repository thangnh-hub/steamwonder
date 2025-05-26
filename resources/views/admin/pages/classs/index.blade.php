@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>

            {{-- Tạm thời chưa dùng đến --}}
            {{-- <div class="pull-right" style="display: flex; margin-left:15px ">
                <input class="form-control" type="file" name="files" id="fileImport" placeholder="@lang('Select File')">
                <button type="button" class="btn btn-sm btn-success" onclick="importFile()">
                    <i class="fa fa-file-excel-o"></i>
                    @lang('Import dữ liệu')</button>
            </div> --}}
        </h1>

    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p>@lang('Please wait')...</p>
        </div>
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET" id="form_filter">
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
                                <label>@lang('Room')</label>
                                <select name="room_id" id="room_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($rooms as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['room_id']) && $params['room_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Trạng thái')</label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $params['status'] == $key ? 'selected' : '' }}>
                                            {{ __($item) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Độ tuổi')</label>
                                <select name="education_age_id" id="education_age" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($ages as $key => $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['education_age_id']) && $params['education_age_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Chương trình')</label>
                                <select name="education_program_id" id="education_programs" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($programs as $key => $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['education_program_id']) && $params['education_program_id'] == $item->id ? 'selected' : '' }}>
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
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
                                    </a>
                                    <a href="javascript:void(0)" data-url="{{ route('class.export_class') }}"
                                        class="btn btn-sm btn-success btn_export"><i class="fa fa-file-excel-o"></i>
                                        @lang('Export dữ liệu')</a>
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
                <h3 class="box-title">@lang('List')</h3>
            </div>
            <div class="box-body box_alert">
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
                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Mã lớp')</th>
                                <th>@lang('Title')</th>
                                <th>@lang('Area')</th>
                                <th>@lang('Room')</th>
                                <th>@lang('Sĩ số')</th>
                                <th>@lang('Hệ đào tạo')</th>
                                <th>@lang('Nhóm tuổi')</th>
                                <th>@lang('Năm cuối')</th>
                                <th>@lang('Giáo viên')</th>
                                <th>@lang('Trạng thái')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td>
                                        <strong style="font-size: 14px">{{ $row->code ?? '' }}</strong>
                                    </td>

                                    <td>
                                        {{ $row->name ?? '' }}
                                    </td>

                                    <td>
                                        {{ $row->area->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->room->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ count($row->students) }} / {{ $row->slot }}
                                    </td>
                                    <td>
                                        {{ $row->education_programs->name ?? '' }}
                                    </td>
                                    <td>{{ $row->education_ages->name ?? '' }}</td>
                                    <td>
                                        <div class="sw_featured d-flex-al-center">
                                            <label class="switch">
                                                <input class="" type="checkbox" value="1" disabled
                                                    {{ isset($row->is_lastyear) && $row->is_lastyear == '1' ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        @if (!empty($row->teacher))
                                            <ul>
                                                @foreach ($row->teacher as $item)
                                                    @if ($item->pivot->status != 'delete')
                                                        <li
                                                            class="{{ optional($item->pivot)->is_teacher_main === 1 ? 'text-success text-bold' : '' }}">
                                                            {{ $item->admin_code ?? '' }} -
                                                            {{ $item->name ?? '' }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>
                                        {{ __($row->status) }}
                                    </td>
                                    <td style="width:150px">
                                        <a class="btn btn-sm btn-primary mb-5" data-toggle="tooltip" target="_blank"
                                            title="@lang('Điểm danh đến')" data-original-title="@lang('Điểm danh đến')"
                                            href="{{ route('attendance.index', ['class_id' => $row->id, 'tracked_at' => date('Y-m-d')]) }}"
                                            onclick="return openCenteredPopup(this.href)">
                                            <i class="fa fa-calendar-check-o"></i>
                                        </a>
                                        <a class="btn btn-sm btn-danger mb-5" data-toggle="tooltip" target="_blank"
                                            title="@lang('Điểm danh về')" data-original-title="@lang('Điểm danh về')"
                                            href="{{ route('attendance.checkout', ['class_id' => $row->id, 'tracked_at' => date('Y-m-d')]) }}"
                                            onclick="return openCenteredPopup(this.href)">
                                            <i class="fa fa-calendar-check-o"></i>
                                        </a>
                                        <br>
                                        <button class="btn btn-sm btn-success btn_show_detail" data-toggle="tooltip"
                                            data-id="{{ $row->id }}"
                                            data-url="{{ route(Request::segment(2) . '.show', $row->id) }}"
                                            title="@lang('Chi tiết')" data-original-title="@lang('Chi tiết')">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                            title="@lang('Chỉnh sửa')" data-original-title="@lang('Chỉnh sửa')"
                                            href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
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
                        Tìm thấy {{ $rows->total() }} kết quả
                    </div>
                    <div class="col-sm-7">
                        {{ $rows->withQueryString()->links('admin.pagination.default') }}
                    </div>
                </div>
            </div>

        </div>
    </section>
    <div class="modal fade" id="modal_show_class" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header ">
                    <h3 class="modal-title text-center col-md-12">@lang('Thông tin lớp học')</h3>
                    </h3>
                </div>
                <div class="modal-body show_detail_class">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-remove"></i> @lang('Close')
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $('.btn_show_detail').click(function() {
            var url = $(this).data('url');
            var id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    if (response) {
                        $('.show_detail_class').html(response.data.view);
                        $('#modal_show_class').modal('show');
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

                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        })
        $('.btn_export').click(function() {
            show_loading_notification()
            var formData = $('#form_filter').serialize();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                data: formData,
                success: function(response) {
                    if (response) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(response);
                        a.href = url;
                        a.download = 'Class.xlsx';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                        hide_loading_notification()
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
                        hide_loading_notification()
                    }
                },
                error: function(response) {
                    hide_loading_notification()
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        })

        function importFile() {
            show_loading_notification();
            var formData = new FormData();
            var file = $('#fileImport')[0].files[0];
            if (file == null) {
                alert('Cần chọn file để Import!');
                return;
            }
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: '{{ route('class.import_class') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    hide_loading_notification();
                    if (response.data != null) {
                        location.reload();
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.table-responsive').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert-warning").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert-warning').remove();
                        }, 3000);
                    }
                },
                error: function(response) {
                    // Get errors
                    hide_loading_notification();
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        }
    </script>
@endsection
