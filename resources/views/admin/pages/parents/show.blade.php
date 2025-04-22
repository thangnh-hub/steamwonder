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
                        <div class="col-lg-12">
                            <div class="box box-primary">
                                <div class="box-body">
                                    <!-- Custom Tabs -->
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="active">
                                                <a href="#tab_1" data-toggle="tab">
                                                    <h5>Thông tin chính </h5>
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="#tab_2" data-toggle="tab">
                                                    <h5>Mối quan hệ với bé</h5>
                                                </a>
                                            </li>
                                        </ul>
        
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_1">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="job"><strong>@lang('Họ và tên'):</strong>
                                                            {{ $detail->last_name ?? '' }} {{ $detail->first_name ?? '' }}
                                                        </p>
                                                    </div>
                                                
                                                    <div class="col-md-6">
                                                        <p class="job"><strong>@lang('Số CMND/CCCD'):</strong>
                                                            {{ $detail->identity_card ?? '' }}
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
                                                        <p class="job"><strong>@lang('Giới tính'):</strong>
                                                            {{ $list_sex[$detail->sex] ?? '' }}
                                                        </p>
                                                    </div>
                                                
                                                    <div class="col-md-6">
                                                        <p class="job"><strong>@lang('Ngày sinh'):</strong>
                                                            {{ $detail->birthday ? \Carbon\Carbon::parse($detail->birthday)->format('d/m/Y') : '' }}
                                                        </p>
                                                    </div>
                                                
                                                    <div class="col-md-6">
                                                        <p class="job"><strong>@lang('Ảnh đại diện'):</strong><br>
                                                            <a target="_blank" href="{{ $detail->avatar ?? url('themes/admin/img/no_image.jpg') }}">
                                                                <img src="{{ $detail->avatar ?? url('themes/admin/img/no_image.jpg') }}" alt="avatar" style="max-height: 120px;">
                                                            </a>   
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                            </div>
        
                                            <div class="tab-pane " id="tab_2">
                                                
                                            </div>
                                        </div>
                                    </div><!-- /.tab-content -->
                                </div><!-- nav-tabs-custom -->
                            </div>
                        </div>
                    </div>
                </div>
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
