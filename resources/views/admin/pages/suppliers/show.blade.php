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
                                                            {{ $detail->first_name ?? '' }} {{ $detail->last_name ?? '' }} 
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
                                                <div class="tab-pane" id="tab_2">
                                                    <h4>@lang('Danh sách học sinh mà phụ huynh này là người thân')</h4>
                                                    <br>
                                                    @if($detail->parentStudents->count())
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>@lang('Avatar')</th>
                                                                    <th>@lang('Student code')</th>
                                                                    <th>@lang('Full name')</th>
                                                                    <th>@lang('Tên thường gọi')</th>
                                                                    <th>@lang('Gender')</th>
                                                                    <th>@lang('Area')</th>
                                                                    <th>@lang('Lớp đang học')</th>
                                                                    <th>@lang('Ngày nhập học chính thức')</th>
                                                                    <th>@lang('Quan hệ')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($detail->parentStudents as $relation)
                                                                    @php $student = $relation->student; @endphp
                                                                    @if($student)
                                                                        <tr>
                                                                            <td>
                                                                                <a target="_blank" href="{{ $student->avatar ?? url('themes/admin/img/no_image.jpg') }}">
                                                                                    <img src="{{ $student->avatar ?? url('themes/admin/img/no_image.jpg') }}" alt="avatar" style="max-height: 60px;">
                                                                                </a>
                                                                            </td>
                                                                            <td>{{ $student->student_code ?? '' }}</td>
                                                                            <td>{{ $student->first_name }} {{ $student->last_name }} </td>
                                                                            <td>{{ $student->nickname ?? '' }}</td>
                                                                            <td>{{ __($student->sex) }}</td>
                                                                            <td>{{ $student->area->name ?? '' }}</td>
                                                                            <td>{{ $student->currentClass->name ?? '' }}</td>
                                                                            <td>
                                                                                {{ isset($student->enrolled_at) &&  $student->enrolled_at !="" ?date("d-m-Y", strtotime($student->enrolled_at)): '' }}
                                                                            </td>
                                                                            <td>{{ $relation->relationship->title ?? '' }}</td>
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @else
                                                        <p class="text-muted">@lang('Phụ huynh này chưa được liên kết với học sinh nào.')</p>
                                                    @endif
                                                </div>
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
