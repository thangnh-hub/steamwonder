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
                                                    <h5>Người thân của bé</h5>
                                                </a>
                                            </li>
                                        </ul>
        
                                        <div class="tab-content">
                                            <!-- TAB 1: Thông tin học sinh -->
                                            <div class="tab-pane active" id="tab_1">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>@lang('Họ và tên'):</strong>
                                                            {{ $detail->last_name ?? '' }} {{ $detail->first_name ?? '' }}
                                                        </p>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <p><strong>@lang('Tên thường gọi'):</strong>
                                                            {{ $detail->nickname ?? '' }}
                                                        </p>
                                                    </div>
                                        
                                                    <div class="col-md-6">
                                                        <p><strong>@lang('Mã học sinh'):</strong>
                                                            {{ $detail->student_code  ?? '' }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>@lang('Ngày sinh'):</strong>
                                                            {{ $detail->birthday ? \Carbon\Carbon::parse($detail->birthday)->format('d/m/Y') : '' }}
                                                        </p>
                                                    </div>
                                                                                
                                                    <div class="col-md-6">
                                                        <p><strong>@lang('Giới tính'):</strong>
                                                            {{ __($detail->sex ?? '') }}
                                                        </p>
                                                    </div>
                                        
                                                    <div class="col-md-6">
                                                        <p><strong>@lang('Khu vực'):</strong>
                                                            {{ $detail->area->name ?? '' }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>@lang('Lớp đang học'):</strong>
                                                            {{ $detail->currentClass->name ?? '' }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>@lang('Ngày nhập học'):</strong>
                                                            {{ isset($detail->enrolled_at) &&  $detail->enrolled_at !="" ?date("d-m-Y", strtotime($detail->enrolled_at)): '' }}
                                                        </p>
                                                    </div>


                                        
                                                    <div class="col-md-6">
                                                        <p><strong>@lang('Ảnh đại diện'):</strong><br>
                                                            <a target="_blank" href="{{ $detail->avatar ?? url('themes/admin/img/no_image.jpg') }}">
                                                                <img src="{{ $detail->avatar ?? url('themes/admin/img/no_image.jpg') }}" alt="avatar" style="max-height: 120px;">
                                                            </a>   
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <!-- TAB 2: Người thân -->
                                            <div class="tab-pane" id="tab_2">
                                                @if ($detail->studentParents->isNotEmpty())
                                                    <table class="table table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>@lang('STT')</th>
                                                                <th>@lang('Avatar')</th>
                                                                <th>@lang('Họ và tên')</th>
                                                                <th>@lang('Mối quan hệ')</th>
                                                                <th>@lang('Giới tính')</th>
                                                                <th>@lang('Ngày sinh')</th>
                                                                <th>@lang('Số điện thoại')</th>
                                                                <th>@lang('Email')</th>
                                                                <th>@lang('Địa chỉ')</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($detail->studentParents as $index => $relation)
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>
                                                                        @if (!empty($relation->parent->avatar))
                                                                            <img src="{{ asset($relation->parent->avatar) }}" alt="Avatar" width="100" height="100" style="object-fit: cover;">
                                                                        @else
                                                                            <span class="text-muted">No image</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $relation->parent->last_name ?? '' }} {{ $relation->parent->first_name ?? '' }}</td>
                                                                    <td>{{ $relation->relationship->title ?? '' }}</td>
                                                                    <td>{{ __($relation->parent->sex ?? '') }}</td>
                                                                    
                                                                    <td>
                                                                        {{ $relation->parent->birthday ? \Carbon\Carbon::parse($relation->parent->birthday)->format('d/m/Y') : '' }}
                                                                    </td>
                                                                    
                                                                    <td>{{ $relation->parent->phone ?? '' }}</td>
                                                                    <td>{{ $relation->parent->email ?? '' }}</td>
                                                                    <td>{{ $relation->parent->address ?? '' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <p class="text-muted">@lang('Không có người thân nào được liên kết.')</p>
                                                @endif
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
