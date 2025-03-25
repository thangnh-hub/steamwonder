@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
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
            <a class="btn btn-sm btn-warning pull-right" href="{{ route('leave.request.create') }}"><i class="fa fa-plus"></i>
                @lang('Add')</a>
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
            <form action="{{ route('leave.request.index') }}" method="GET">
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
                                <label>@lang('Trạng thái')</label>
                                <select name="status" class=" form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status as $key => $val)
                                        <option value="{{ $val }}"
                                            {{ isset($params['status']) && $params['status'] == $val ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('leave.request.index') }}">
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
                <div class="box_note">
                    <p><b>@lang('Ghi chú')</b></p>
                    <ul>
                        <li> Màn hình chỉ hiển thị danh sách các đơn của bạn và của các bộ cấp dưới do bạn quản lý trực tiếp</li>
                        <li> Khi tạo đơn cần báo cho người quản lý trực tiếp của bạn vào xác nhận, sau đó báo cho lãnh đạo để duyệt</li>
                        <li> Người xác nhận mặc định sẽ là người quản lý trực tiếp và người duyệt là Giám đốc</li>
                    </ul>
                </div>
            </div>
            <div class="box-body table-responsive box-alert">
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
                                <th>@lang('STT')</th>
                                <th>@lang('Người đề xuất')</th>
                                <th>@lang('Ngày nghỉ')</th>
                                <th>@lang('Từ ngày')</th>
                                <th>@lang('Đến ngày')</th>
                                <th>@lang('Loại')</th>
                                <th>@lang('Lý do')</th>
                                <th>@lang('Ghi chú')</th>

                                <th>@lang('Q.Lý trực tiếp')</th>
                                <th>@lang('Người duyệt')</th>
                                <th>@lang('Trạng thái')</th>
                                <th>@lang('Thao tác')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td> {{ $loop->index + 1 }}</td>
                                    <td> {{ $row->admins->name ?? '' }}</td>
                                    <td> {{ $row->total_days ?? '' }}</td>
                                    <td> {{ date('d-m-Y', strtotime($row->start_date)) ?? '' }}</td>
                                    <td> {{ date('d-m-Y', strtotime($row->end_date)) ?? '' }}</td>
                                    <td> {{ $row->is_type == 'paid' ? 'Có phép' : 'Không phép' }}</td>
                                    <td> {{ $row->reason ?? '' }}</td>
                                    <td> {{ $row->note ?? '' }}</td>

                                    <td> {{ $row->parent->name ?? '' }}</td>
                                    <td> {{ $row->approver->name ?? '' }}</td>
                                    <td>
                                        {{ __($row->status) }}
                                    </td>

                                    {{-- <td> {{ date('d-m-Y', strtotime($row->created_at)) }}</td> --}}
                                    <td>
                                        <div class="d-flex-wap">
                                            <a class="btn btn-sm btn-primary mr-10" data-toggle="tooltip"
                                                title="@lang('Xem chi tiết')" data-original-title="@lang('Xem chi tiết')"
                                                href="{{ route('leave.request.show', $row->id) }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @if ($row->status == 'pending_confirmation' && $row->user_id == $admin_auth->id)
                                                <a class="btn btn-sm btn-warning mr-10" data-toggle="tooltip"
                                                    title="@lang('Update')" data-original-title="@lang('Update')"
                                                    href="{{ route('leave.request.edit', $row->id) }}">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                                <form action="{{ route('leave.request.destroy', $row->id) }}"
                                                    method="POST" onsubmit="return confirm('@lang('confirm_action')')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" type="submit"
                                                        data-toggle="tooltip" title="@lang('Delete')"
                                                        data-original-title="@lang('Delete')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($row->status == 'pending_confirmation' && $row->parent_id == $admin_auth->id)
                                                <a data-id="{{ $row->id }}" href="javascript:void(0)"
                                                    data-type="parent"
                                                    class="btn btn-warning pull-right hide-print mr-10 approve_request">
                                                    @lang('Xác nhận')
                                                </a>
                                            @endif
                                            @if ($row->status == 'pending_approval' && $row->approver_id == $admin_auth->id)
                                                <a data-id="{{ $row->id }}" href="javascript:void(0)"
                                                    data-type="approve"
                                                    class="btn btn-success pull-right hide-print mr-10 approve_request">
                                                    @lang('Duyệt')
                                                </a>
                                            @endif

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

        </div>
    </section>

    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.approve_request').click(function() {
                if (confirm('Xác nhận duyệt đơn xin nghỉ ?')) {
                    var _id = $(this).data('id');
                    var _type = $(this).data('type');
                    let url = "{{ route('leave.request.approve') }}";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: _id,
                            type: _type,
                        },
                        success: function(response) {
                            if (response.data != null) {
                                if (response.data == 'success') {
                                    location.reload();
                                } else {
                                    var _html = `<div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        ` + response.message + `
                        </div>`;
                                    $('.box-alert').prepend(_html);
                                    $('html, body').animate({
                                        scrollTop: $(".alert-warning").offset().top
                                    }, 1000);
                                    setTimeout(function() {
                                        $(".alert-warning").fadeOut(3000,
                                            function() {});
                                    }, 800);

                                };
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
                                    $(".alert-warning").fadeOut(3000,
                                        function() {});
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
        });
    </script>
@endsection
