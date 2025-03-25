@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
<style>
    .modal-header {
        background: #3c8dbc;
        color: #fff;

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
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
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
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Type')</label>
                                <select name="is_type" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($type as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['is_type']) && $params['is_type'] == $key ? 'selected' : '' }}>
                                            @lang($item)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Từ ngày') </label>
                                <input type="date" class="form-control start_date" name="from_date"
                                    value="{{ isset($params['from_date']) ? $params['from_date'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Đến ngày') </label>
                                <input type="date" class="form-control end_date" name="to_date"
                                    value="{{ isset($params['to_date']) ? $params['to_date'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
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
                <h3 class="box-title">@lang('List')</h3>
            </div>
            <div class="box-body table-responsive">
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
                                <th>@lang('Title')</th>
                                <th>@lang('Type')</th>
                                <th>@lang('Ngày thực hiện')</th>
                                <th>@lang('Vào lúc')</th>
                                <th>@lang('Người thực hiện')</th>
                                <th>@lang('Số lượng (Slot)')</th>
                                <th>@lang('CV ứng tuyển')</th>
                                <th>@lang('Đạt/ Không đạt/ Vắng mặt/ Bị loại')</th>
                                <th>@lang('Updated at')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($rows as $row)
                                @php
                                    $history = $history_schedule_test->filter(function ($item, $key) use ($row) {
                                        return $item->id_schedule_test == $row->id;
                                    });
                                    $pass=$history_schedule_test->filter(function ($item, $key) use ($row) {
                                        return $item->id_schedule_test == $row->id && $item->result=='pass';
                                    });
                                    $nopass=$history_schedule_test->filter(function ($item, $key) use ($row) {
                                        return $item->id_schedule_test == $row->id && $item->result=='nopass';
                                    });
                                    $absent=$history_schedule_test->filter(function ($item, $key) use ($row) {
                                        return $item->id_schedule_test == $row->id && $item->result=='absent';
                                    });
                                    $cancel=$history_schedule_test->filter(function ($item, $key) use ($row) {
                                        return $item->id_schedule_test == $row->id && $item->result=='cancel';
                                    });
                                @endphp
                                <tr class="valign-middle">
                                    <td>
                                        <strong
                                            style="font-size: 14px;">{{ isset($row->json_params->title) ? $row->json_params->title : '' }}</strong>
                                    </td>
                                    <td>
                                        @lang($type[$row->is_type])
                                    </td>
                                    <td>
                                        {{ date('d-m-Y', strTotime($row->time)) }}
                                    </td>
                                    <td>
                                        {{ date('H:i', strTotime($row->time)) }}
                                    </td>
                                    <td>
                                        {{ $row->admin_name??"" }}
                                    </td>
                                    <td>
                                        {{ $row->slot??"" }}
                                    </td>
                                    <td>
                                        {{ isset($history) ? count($history) : '0' }}
                                    </td>
                                    <td>
                                        {{ count($pass) }}/ {{ count($nopass) }}/ {{ count($absent) }}/ {{ count($cancel) }}
                                    </td>
                                    <td>
                                        {{ $row->updated_at }}
                                    </td>

                                    <td>
                                        <div class="d-flex-wap">
                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                style="margin-right: 5px" title="@lang('Edit')"
                                                data-original-title="@lang('Edit')"
                                                href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                <i class="fa fa-pencil-square-o"></i> Sửa
                                            </a>
                                            <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}"
                                                method="POST" onsubmit="return confirm('@lang('confirm_action')')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                                    title="@lang('Delete')" data-original-title="@lang('Delete')">
                                                    <i class="fa fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                            <a style="margin-left: 5px" class="btn btn-sm btn-primary pull-right view-user-action" data-schedule-test="{{ $row->id }}"
                                                data-toggle="modal" style="margin-right: 5px" title="@lang('Xem danh sách ứng tuyển')"
                                                data-original-title="@lang('Xem danh sách ứng tuyển')" data-toggle="modal"
                                                data-target=".bd-example-modal-lg">
                                                <i class="fa fa-eye"></i> DS ứng tuyển
                                            </a>
                                        </div>
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
            <div class="modal fade bd-example-modal-lg " data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-full">
                    <div class="modal-content">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">
                                    Danh sách đăng ký
                                </h4>
                            </div>

                            <form action="" method="POST"  class="form-ajax-lesson">
                                @csrf
                                <div class="modal-body modal-body-add-leson">
                                    <div class="box-body table-responsive">
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Tên</th>
                                                    <th>Link CV</th>
                                                    <th>Kết quả</th>
                                                    <th>Nhận xét</th>
                                                    <th>Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody class="show-user-action">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        Đóng
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        $('.view-user-action').click(function (e) {
            e.preventDefault();
            var _chedule_id=$(this).attr('data-schedule-test');
            let _url = "{{ route('schedule_test.index') }}";
            var _html=$('.show-user-action');
            var _content="";
            $.ajax({
                type: "GET",
                url: _url,
                data: {
                    "schedule_id": _chedule_id
                },
                dataType: 'JSON',
                success: function(response) {
                    _list = response.data;
                    console.log(_list)
                    if (_list.length>0) {
                        var i=0;
                        _list.forEach(it => {
                            var paramAction = JSON.parse(it.param_action);

                            _content+=`<tr class="valign-middle">
                                        <input type="hidden" name="list[`+i+`][id]" value="`+it.id+`">
                                        <input type="hidden" name="list[`+i+`][id_user_action]" class="id_user_action_item" value="`+it.id_user_action+`">
                                        <input type="hidden" name="list[`+i+`][id_admin]" class="id_admin_item" value="`+it.id_admin+`">
                                        <td>
                                            <strong style="font-size: 14px;">`+(paramAction.name?paramAction.name:"")+`</strong>
                                        </td>

                                        <td>
                                            <a href="`+(paramAction.link_cv?paramAction.link_cv:"")+`"
                                                data-toggle="tooltip"
                                                data-original-title="`+(paramAction.link_cv?paramAction.link_cv:"")+`">`+(paramAction.link_cv?paramAction.link_cv:"")+`</a>
                                        </td>

                                        <td>
                                            <div class="">
                                            <select  style="width:100%" name="list[`+i+`][result]" class="form-control select2 result-item">
                                                <option value="">Chọn</option>
                                                @foreach(App\Consts::RESULT_INTERVIEW as $key => $item)
                                                    <option `+((it.result=="{{ $key }}")?"selected":"")+` value="{{ $key }}">@lang($item)</option>
                                                @endforeach
                                                <option value=""></option>
                                            </select>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="list[`+i+`][note]" class="form-control note-item">`+(it.json_params.note?it.json_params.note:"")+`</textarea>
                                        </td>
                                        <td>
                                            <button onclick="updateResultHistory(this)" data-history-type=`+it.type_schedule_test+` data-history-id=`+it.id+` type="button" class="btn btn-primary save-history-result">Lưu kết quả</button>
                                        </td>
                                    </tr>`;
                                    i++;
                        });
                        _html.html(_content);
                        $('.select2').select2()
                    }else{
                        _content=`<tr>
                                    <td colspan='4'>Không có bản ghi</td>
                                </tr>`;
                                _html.html(_content);
                    }
                },
                error: function(response) {
                    // Get errors
                    console.log(response);
                    var errors = response.responseJSON.errors;
                    // Foreach and show errors to html
                    var elementErrors = '';
                    $.each(errors, function(index, item) {
                        if (item === 'CSRF token mismatch.') {
                            item = "@lang('CSRF token mismatch.')";
                        }
                        elementErrors += '<p>' + item + '</p>';
                    });
                }
            });
        });
        function updateResultHistory(th){
            var _idHistory=$(th).attr('data-history-id');
            var _type=$(th).attr('data-history-type');
            var _note=$(th).parents('tr').find('.note-item').val();
            var _result=$(th).parents('tr').find('.result-item').val();
            var _id_user_action_item=$(th).parents('tr').find('.id_user_action_item').val();
            var _id_admin_item=$(th).parents('tr').find('.id_admin_item').val();
            let _url = "{{ route('history_schedule_test.ajax.update') }}";
            $.ajax({
                type: "GET",
                url: _url,
                data: {
                    "idHistory": _idHistory,
                    "typeHistory": _type,
                    "note": _note,
                    "result": _result,
                    "id_user_action": _id_user_action_item,
                    "id_admin": _id_admin_item,
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.data == 'success' && typeof response.data !== 'undefined') {
                        alert(response.message);
                    }

                },
                error: function(data) {
                    console.log(data);
                    var errors = data.responseJSON.message;
                    alert(data);
                }
            });
        }
    </script>
@endsection
