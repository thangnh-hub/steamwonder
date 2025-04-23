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
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Thêm mới học viên')</a>
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
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin học sinh <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_2" data-toggle="tab">
                                            <h5>Người thân của bé</h5>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="box-body">
                                            <div class="d-flex-wap">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('Khu vực')<small class="text-red">*</small></label>
                                                        <select name="area_id" class="form-control select2" required>
                                                            <option value="">@lang('Chọn khu vực')</option>
                                                            @foreach ($list_area as $val)
                                                                <option value="{{ $val->id }}" {{ old('area_id', $detail->area_id) == $val->id ? 'selected' : '' }}>
                                                                    {{ $val->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('Họ')<small class="text-red">*</small></label>
                                                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $detail->last_name) }}" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('Tên')<small class="text-red">*</small></label>
                                                        <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $detail->first_name) }}" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('Tên thường gọi')</label>
                                                        <input type="text" class="form-control" name="nickname" value="{{ old('nickname', $detail->nickname) }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('Giới tính')</label>
                                                        <select name="sex" class="form-control select2">
                                                            @foreach ($list_sex as $key => $value)
                                                                <option value="{{ $key }}" {{ old('sex', $detail->sex) == $key ? 'selected' : '' }}>
                                                                    {{ __($value) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày sinh')</label>
                                                        <input type="date" class="form-control" name="birthday" value="{{ old('birthday', $detail->birthday) }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày nhập học')</label>
                                                        <input type="date" class="form-control" name="enrolled_at" value="{{ old('enrolled_at', $detail->enrolled_at) }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('Trạng thái')</label>
                                                        <select name="status" class="form-control select2">
                                                            @foreach ($list_status as $key => $value)
                                                                <option value="{{ $key }}" {{ old('status', $detail->status) == $key ? 'selected' : '' }}>
                                                                    {{ __($value) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group box_img_right">
                                                        <label>@lang('Ảnh đại diện')</label>
                                                        <div id="image-holder">
                                                            <img src="{{ !empty($detail->avatar) ? asset($detail->avatar) : url('themes/admin/img/no_image.jpg') }}" style="max-height: 120px;">
                                                        </div>
                                                        <div class="input-group">
                                                            <span class="input-group-btn">
                                                                <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm" data-type="cms-image">
                                                                    <i class="fa fa-picture-o"></i> @lang('Choose')
                                                                </a>
                                                            </span>
                                                            <input id="image" class="form-control inp_hidden" type="hidden" name="avatar"
                                                                placeholder="@lang('Image source')" value="{{ old('avatar', $detail->avatar) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane " id="tab_2">
                                        <div class="box-body table-responsive">
                                            <div>
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addParentModal">
                                                    <i class="fa fa-plus"></i> @lang('Cập nhật người thân')
                                                </button>     
                                            </div>
                                            
                                            <br>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('STT')</th>
                                                        <th>@lang('Avatar')</th>
                                                        <th>@lang('Họ và tên')</th>
                                                        <th>@lang('Giới tính')</th>
                                                        <th>@lang('Ngày sinh')</th>
                                                        <th>@lang('Số CMND/CCCD')</th>
                                                        <th>@lang('Số điện thoại')</th>
                                                        <th>@lang('Email')</th>  
                                                        <th>@lang('Địa chỉ')</th>
                                                        <th>@lang('Khu vực')</th>
                                                        <th>@lang('Trạng thái')</th>
                                                        <th>@lang('Quan hệ')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($detail->studentParents->count())
                                                    @foreach ($detail->studentParents as $row)
                                                        <tr class="valign-middle">
                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td>
                                                                @if (!empty($row->parent->avatar))
                                                                    <img src="{{ asset($row->parent->avatar) }}" alt="Avatar" width="100" height="100" style="object-fit: cover;">
                                                                @else
                                                                    <span class="text-muted">No image</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a target="_blank" href="{{ route('parents.show', $row->parent->id) }}">
                                                                    {{ $row->parent->first_name ?? '' }} {{ $row->parent->last_name ?? '' }}  
                                                                </a>
                                                            </td>
                                                            <td>@lang($row->parent->sex ?? '')</td>
                                                            <td>{{ $row->parent->birthday ? \Carbon\Carbon::parse($row->parent->birthday)->format('d/m/Y') : '' }}</td>
                                                            <td>{{ $row->parent->identity_card ?? '' }}</td>
                                                            <td>{{ $row->parent->phone ?? '' }}</td>
                                                            <td>{{ $row->parent->email ?? '' }}</td>
                                                            <td>{{ $row->parent->address ?? '' }}</td>
                                                            <td>{{ $row->parent->area->name ?? '' }}</td>
                                                            <td>@lang($row->parent->status ?? '')</td>
                                                            <td>{{ $row->relationship->title ?? '' }}</td>
                                                        </tr>
                                                    @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="14" class="text-center">Không có dữ liệu</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                                
                                            </table>
                                            
                                        </div>                      
                                    </div>
                                </div> <!-- tab-content -->
                            </div>
                        </div>
                    
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                            <a href="{{ route(Request::segment(2) . '.index') }}">
                                <button type="button" class="btn btn-sm btn-success">@lang('Danh sách')</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>    
        </form>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="addParentModal" tabindex="-1" role="dialog" aria-labelledby="addParentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('student.addParent', $detail->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addParentModalLabel">@lang('Chọn người thân')</h5>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="search-parent" placeholder="@lang('Tìm theo tên phụ huynh...')">
                        </div>
                        <table class="table table-hover table-bordered" id="parent-table">
                            <thead>
                                <tr>
                                    <th>Chọn</th>
                                    <th>@lang('Họ và tên')</th>
                                    <th>@lang('Giới tính')</th>
                                    <th>@lang('Số điện thoại')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Chọn mối quan hệ')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allParents as $parent)
                                @php
                                    $isChecked = in_array($parent->id, $studentParentIds);
                                    $existingRelation = $detail->studentParents->firstWhere('parent_id', $parent->id);
                                @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="parents[{{ $parent->id }}][id]" value="{{ $parent->id }}" {{ $isChecked ? 'checked' : '' }}>
                                        </td>
                                        <td class="parent-name">{{ $parent->first_name }} {{ $parent->last_name }}</td>
                                        <td>@lang($parent->sex)</td>
                                        <td>{{ $parent->phone }}</td>
                                        <td>{{ $parent->email }}</td>
                                        <td>
                                            <select style="width:100%" name="parents[{{ $parent->id }}][relationship_id]" class="form-control select2">
                                                @foreach($list_relationship as $relation)
                                                    <option {{ $existingRelation && $existingRelation->relationship_id == $relation->id ? 'selected' : '' }} value="{{ $relation->id }}">{{ $relation->title }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">@lang('Lưu người thân đã chọn')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Đóng')</button>
                </div>
            </div>
        </form>
        </div>
    </div>
  
@endsection

@section('script')
    <script>
        $('#search-parent').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $('#parent-table tbody tr').filter(function() {
                $(this).toggle($(this).find('.parent-name').text().toLowerCase().indexOf(value) > -1);
            });
        });
    </script>
@endsection
