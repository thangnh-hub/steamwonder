@extends('admin.layouts.app')

@section('title')
  @lang($module_name)
@endsection
@section('style')
  
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
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

        <div class="box box-default">
            <div class="box-header">
                <h3 class="text-title">@lang($module_name)</h3>
            </div>
            <div class="box-body table-responsive">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="job"><strong>@lang('Họ và tên'):</strong>
                                {{ $detail->last_name ?? '' }} {{ $detail->first_name ?? '' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="job"><strong>@lang('Số điện thoại'):</strong>
                                {{ $detail->phone ?? '' }} 
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="job"><strong>@lang('Email'):</strong>
                                {{ $detail->email ?? '' }} 
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="job"><strong>@lang('Địa chỉ'):</strong>
                                {{ $detail->address ?? '' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="job"><strong>@lang('Khu vực'):</strong>
                                {{ $detail->area->name ?? '' }} 
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="job"><strong>@lang('CBTS'):</strong>
                                {{ $detail->admission->name ?? '' }} 
                            </p>
                        </div>
                    </div>
                    <hr style="border-top: dashed 2px #a94442; ">
                </div>
                

                <h3 class="box-title">@lang('Danh sách lịch sử tư vấn')</h3>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th style="width:20%">@lang('Ngày tư vấn')</th>
                            <th style="width:30%">@lang('Nội dung')</th>
                            <th style="width:10%">@lang('Trạng thái')</th>
                            <th style="width:10%">@lang('Kết quả')</th>
                            <th style="width:20%">@lang('Ghi chú')</th>
                            <th style="width:20%">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        @isset($dataCrmLogs)
                            @foreach ($dataCrmLogs as $dataCrmLog)
                            <tr class="valign-middle">
                                <td>
                                    {{ $dataCrmLog->consulted_at ? \Carbon\Carbon::parse($dataCrmLog->consulted_at)->format('d/m/Y') : '' }}
                                </td>
                                <td >
                                    <p style="white-space: pre-line">{{ $dataCrmLog->content ?? '' }}</p>
                                </td>

                                <td>
                                    @lang($dataCrmLog->status ?? '')
                                </td>

                                <td>
                                    @lang($dataCrmLog->result ?? '')
                                </td>
                            
                                <td>
                                    @if ($dataCrmLog->json_params && isset($dataCrmLog->json_params->note))
                                        {{ $dataCrmLog->json_params->note }}
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route(Request::segment(2) . '.destroy', $detail->id) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        @endisset
                    </tbody>
                </table>

                <h3 class="box-title">@lang('Thêm mới lịch sử tư vấn')</h3>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th style="width:20%">@lang('Ngày tư vấn')</th>
                            <th style="width:30%">@lang('Nội dung')</th>
                            <th style="width:10%">@lang('Trạng thái')</th>
                            <th style="width:10%">@lang('Kết quả')</th>
                            <th style="width:20%">@lang('Ghi chú')</th>
                            <th style="width:20%">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form action="{{ route('data_crms_log_store') }}" method="post" >
                            <input type="hidden" name="data_crm_id" value="{{ $detail->id }}">
                            @csrf
                            <tr class="valign-middle">
                                <td>
                                    <input required type="date" class="form-control" name="consulted_at" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" placeholder="@lang('Ngày tư vấn')">
                                </td>
                                <td>
                                    <textarea required class="form-control" name="content" placeholder="@lang('Nội dung')"></textarea>
                                </td>
                                <td>
                                    <select class="form-control select2" name="status">
                                        <option value="">@lang('Trạng thái')</option>
                                        @foreach ($status_crmlog as $key => $item)
                                            <option value="{{ $key }}">{{ __($item) }}</option>
                                        @endforeach
                                    </select>
                                    
                                </td>
                                <td>
                                    <select class="form-control select2" name="result">
                                        <option value="">@lang('Kết quả')</option>
                                        @foreach ($result_crmlog as $key => $item)
                                            <option value="{{ $key }}">{{ __($item) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="json_params[note]" class="form-control" placeholder="@lang('Ghi chú')">
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-warning btn_submit">@lang('Thêm lịch sử')</button>
                                </td>
                            </tr>
                        </form>
                    </tbody>
                </table>


            </div>
            <div class="box-footer clearfix">
                <a href="{{ route(Request::segment(2) . '.index') }}">
                    <button type="button" class="btn btn-sm btn-success">Danh sách</button>
                </a>
            </div>
        </div>
    </section>
@endsection
@section('script')
  
@endsection
