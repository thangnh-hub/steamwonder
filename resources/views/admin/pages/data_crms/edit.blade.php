@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section>

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

        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
                        </div>
                        @csrf
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Area')<small class="text-red">*</small></label>
                                                    <select required name="area_id" class="form-control select2">
                                                        <option value="">@lang('Chọn khu vực')</option>
                                                        @foreach ($list_area as $val)
                                                            <option value="{{ $val->id }}" {{ ($detail->area_id ?? old('area_id')) == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('First Name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="first_name"
                                                        placeholder="@lang('First Name')" value="{{ $detail->first_name ?? old('first_name') }}" required>
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Last Name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="last_name"
                                                        placeholder="@lang('Last Name')" value="{{ $detail->last_name ?? old('last_name') }}" required>
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('SĐT') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="phone"
                                                        placeholder="@lang('Phone')" value="{{ $detail->phone ?? old('phone') }}" required>
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Email') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="email"
                                                        placeholder="@lang('Email')" value="{{ $detail->email ?? old('email') }}" required>
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Địa chỉ') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="address"
                                                        placeholder="@lang('Địa chỉ')" value="{{ $detail->address ?? old('address') }}">
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('CBTS')<small class="text-red">*</small></label>
                                                    <select required name="admission_id" class="form-control select2">
                                                        <option value="">@lang('Chọn CBTS')</option>
                                                        @foreach ($admission as $val)
                                                            <option value="{{ $val->id }}" {{ ($detail->admission_id ?? old('admission_id')) == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? "" }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="{{ route(Request::segment(2) . '.index') }}">
                                <button type="button" class="btn btn-sm btn-success">Danh sách</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </section>

@endsection

@section('script')
    <script>
        
    </script>
@endsection
