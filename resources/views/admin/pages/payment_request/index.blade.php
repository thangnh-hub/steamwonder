@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content-header')
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section>
@endsection

@section('content')
    <section class="content">
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
                                <label>@lang('Trạng thái')</label>
                                <select name="status" class="form-control select2"
                                    style="width: 100%;">
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
                                <label>@lang('Phòng ban')</label>
                                <select name="dep_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($department as $key => $item)
                                        <option value="{{ $item->id }}"
                                        {{ isset($params['dep_id']) && $params['dep_id'] == $item->id ? 'selected' : '' }}>
                                        {{ __($item->name) }}</option>
                                    @endforeach
                                </select>
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
                                <th>@lang('STT')</th>
                                <th style="width:20%">@lang('Title')</th>
                                <th>@lang('Bộ phận')</th>
                                <th>@lang('Khoản thanh toán')</th>
                                <th>@lang('Tổng tiền (VNĐ)')</th>
                                <th>@lang('Tổng tiền (EURO)')</th>
                                <th>@lang('Số tài khoản')</th>
                                <th>@lang('Trạng thái')</th>
                                <th>@lang('Người tạo')</th>
                                <th>@lang('Ngày đề xuất')</th>
                                <th>@lang('Người duyệt')</th>
                                <th style="width:200px">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                    onsubmit="return confirm('@lang('confirm_action')')">
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <a href="{{ route(Request::segment(2) . '.show', $row->id) }}"><strong>{{ $row->content ?? "" }} <i class="fa fa-eye"></i> </strong></a>
                                        </td>
                                        
                                        <td>
                                            {{ $row->department->name ?? "" }}
                                        </td>
                                        <td>
                                            @if($row->is_entry == 0)
                                                {{ $row->paymentDetails()->count() }}
                                            @else
                                                <a href="{{ route('entry_warehouse.show', $row->entry_id) }}"><strong>{{ $row->entry->name ?? "" }} <i class="fa fa-eye"></i> </strong></a> 
                                            @endif
                                        </td>
                                        

                                        <td>
                                            @if($row->is_entry == 0)
                                                {{ isset($row->total_money_vnd_finally) && is_numeric($row->total_money_vnd_finally) ? number_format($row->total_money_vnd_finally, 0, ',', '.') : '' }} 
                                            @else
                                                {{ isset($row->json_params->total_money_vnd_without_vat) && is_numeric($row->json_params->total_money_vnd_without_vat) ? number_format($row->json_params->total_money_vnd_without_vat, 0, ',', '.') : '' }}
                                            @endif
                                        </td>

                                        <td>
                                            {{ isset($row->total_money_euro_finally) && is_numeric($row->total_money_euro_finally) ? number_format($row->total_money_euro_finally, 0, ',', '.') : '' }} 

                                        </td>
                                        <td>
                                            {{ $row->qr_number ?? "" }}
                                        </td>
                                        
                                        <td >
                                            <p class="{{ $row->status  == 'new' ?"text-danger":"text-success"}}">
                                                {{ __($row->status ?? "") }}
                                            </p>
                                            @if ($row->status == 'new')
                                                <button data-id="{{ $row->id }}" type="button" data-toggle="tooltip"
                                                    title="@lang('Nhấn để duyệt đề xuất')"
                                                    class="btn btn-sm btn-success approve_payment">@lang('Duyệt')</button>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $row->user->name ?? "" }}
                                        </td>
                                        <td>
                                            {{ date("d-m-Y",strtotime($row->created_at)) }}
                                        </td>
                                        <td> 
                                            @if ($row->status == 'paid')
                                                {{ $row->approved_admin->name??"" }}
                                            @endif 
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                title="@lang('Update')" data-original-title="@lang('Update')"
                                                href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit"
                                                data-toggle="tooltip" title="@lang('Delete')"
                                                data-original-title="@lang('Delete')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <a class="btn btn-sm btn-info" data-toggle="tooltip"
                                                title="@lang('Update')" data-original-title="@lang('Update')"
                                                href="{{ route(Request::segment(2) . '.show', $row->id) }}">
                                                <i class="fa fa-eye">Xem chi tiết</i>
                                            </a>
                                        </td>
                                    </tr>
                                </form>
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
@endsection
@section('script')
    <script>
        $('.approve_payment').click(function(e) {
            
            if (confirm('Bạn có chắc chắn muốn duyệt đề nghị thanh toán này ?')) {
                let _id = $(this).attr('data-id');
                let url = "{{ route('payment.approve') }}/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        id: _id,
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        });
    </script>
@endsection
