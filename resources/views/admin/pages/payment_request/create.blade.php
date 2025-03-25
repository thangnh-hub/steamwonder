@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .d-flex{
            display: flex;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section>

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

        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST" >
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
                        </div>
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Người đề nghị thanh toán') </label>
                                                    <input type="text" class="form-control"
                                                    placeholder="@lang('Name')" disabled value="{{ $admin->name ??"" }}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Phòng ban') </label>
                                                    <select class="form-control select2" name="dep_id">
                                                        @foreach ($department as $dep)
                                                            <option 
                                                            {{ isset($admin->department_id) && $admin->department_id == $dep->id ? "selected" : "" }} 
                                                            value="{{ $dep->id }}">{{ $dep->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Số tài khoản') </label>
                                                    <input name="qr_number" type="text" class="form-control"
                                                    placeholder="@lang('Số tài khoản..')" value="{{ old('qr_number') }}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Số tiền VNĐ đã tạm ứng')</label>
                                                    <div class="d-flex">
                                                        <input value="{{ old('total_money_vnd_advance') ?? 0 }}" name="total_money_vnd_advance" type="number" class="form-control" placeholder="@lang('Số tiền vnđ đã tạm ứng..')">
                                                        <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Số tiền EURO đã tạm ứng')</label>
                                                    <div class="d-flex">
                                                        <input value="{{ old('total_money_euro_advance') ?? 0 }}" name="total_money_euro_advance" type="number" class="form-control" placeholder="@lang('Số tiền euro đã tạm ứng..')">
                                                        <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="EURO" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Nội dung') <small class="text-red">*</small></label>
                                                    <textarea class="form-control" name="content"
                                                    placeholder="@lang('Nội dung đề nghị')" required>{{ old('content') }}</textarea>
                                                </div>
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('script')
    
@endsection
