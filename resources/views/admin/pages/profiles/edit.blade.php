@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .img-width{
            width: 100%;
            height: 100%;
        }
        .btn-action{
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .d-flex{
            display: flex;
        }
        .flex-wrap{
            flex-wrap: wrap
        }
        .m-1 {
            margin: .25rem !important;
        }
        .mt-2, .my-2 {
            margin-top: .5rem !important;
        }
        .p-3 {
            padding: 1rem !important;
        }

        .position-relative {
            position: relative !important;
        }
        .border {
            border: 1px solid #dee2e6 !important;
        }
        .pdf-box {
            width: 150px !important;
            height: 70px !important ;
            background: #f8f9fa;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
            text-align: center;
            overflow: hidden;
        }

        .pdf-name {
            width: 100px;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            border: 1px solid #ccc;
            padding: 2px;
            margin-top: 5px;
            background: white;
        }

        .add_hooby {
            cursor: pointer;
        }
        #cv-avatar, #cv-sign{
            height: 10cm;
        }
        .mx-auto {
            margin-right: auto !important;
            margin-left: auto !important;
        }
        .img-fluid {
            max-width: 250px;
        }
        label{
            font-weight: bold;
        }
        .d-none{
            display: none !important;
        }
    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
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
        
        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Update form')</h3>
                        </div>
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin cá nhân </h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_2" data-toggle="tab">
                                            <h5>Quá trình học tập</h5>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab_3" data-toggle="tab">
                                            <h5>Kinh nghiệm làm việc</h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_4" data-toggle="tab">
                                            <h5>Trình độ và kỹ năng</h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_5" data-toggle="tab">
                                            <h5>Sở thích, Phẩm chất, Điểm mạnh</h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_6" data-toggle="tab">
                                            <h5>Upload hồ sơ</h5>
                                        </a>
                                    </li>
                                    <button type="submit" class="btn btn-success btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>

                                    <a target="_blank" href="{{ route(Request::segment(2) . '.show', $detail->id) }}">
                                        <button style="margin-right: 4px" type="button" class="btn btn-info btn-sm pull-right ">
                                            <i class="fa fa-eye"></i> @lang('Xem trước CV')
                                        </button>
                                    </a>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Tiêu đề CV') <small class="text-danger">*</small></label>
                                                    <input type="text" class="form-control" name="cv_title"
                                                        placeholder="@lang('Tiêu đề CV')" value="{{ $detail->cv_title ?? '' }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Họ và tên') <small class="text-danger">*</small></label>
                                                    <input required type="text" class="form-control" name="json_params[profile][user_name]"
                                                        placeholder="@lang('Họ và tên')" value="{{ $detail->json_params->profile->user_name ?? ($info_user->name ??"") }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Ngày sinh') <small class="text-danger">*</small></label>
                                                    <input required type="date" class="form-control" name="json_params[profile][birthday]"
                                                        placeholder="@lang('Ngày sinh')"
                                                        value="{{ (isset($detail->json_params->profile->birthday) && $detail->json_params->profile->birthday != '') ? date('Y-m-d', strtotime($detail->json_params->profile->birthday)) : ($info_user->birthday??"") }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('SĐT') <small class="text-danger">*</small></label>
                                                    <input required type="text" class="form-control" name="json_params[profile][phone]"
                                                        placeholder="@lang('SĐT')" value="{{ $detail->json_params->profile->phone ?? ($info_user->phone ??"") }}"
                                                        autocomplete="off">
                                                </div>
                                            </div>
        
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Email') <small class="text-danger">*</small></label>
                                                    <input required type="email" class="form-control" name="json_params[profile][mail]"
                                                        placeholder="@lang('Email')" value="{{ $detail->json_params->profile->mail ?? ($info_user->email ??"") }}"
                                                        autocomplete="off">
                                                </div>
                                            </div>
        
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Mã học viên (Nếu các em không tham gia khóa học tiếng Đức tại DWN, hãy nhập "DV" nhé. Bạn nào không nhớ mã HV của mình có thể hỏi cán bộ chăm sóc, cán bộ tuyển sinh của em nhé.)') <small class="text-danger">*</small></label>
                                                    <input disabled type="text" class="form-control" 
                                                        placeholder="@lang('Mã học viên')" value="{{ $detail->json_params->profile->user_code ?? ($info_user->admin_code ??"") }}"
                                                        autocomplete="off">
                                                </div>
                                            </div>
        
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Tên ZALO') <small class="text-danger">*</small></label>
                                                    <input required type="text" class="form-control" name="json_params[profile][zalo]"
                                                        placeholder="@lang('Tên Zalo')" value="{{ $detail->json_params->profile->zalo ?? '' }}"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Nơi sinh (Viết liền không dấu)') <small class="text-danger">*</small></label>
                                                    <input required type="text" class="form-control" name="json_params[profile][born]"
                                                        placeholder="@lang('Nơi sinh')" value="{{ $detail->json_params->profile->born ?? '' }}"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Địa chỉ (VD: Xom 4, Xa Phuc Kien, Huyen Thanh Quan, Tinh Thai Binh --> Dorf 4, Gemeide Phuckien, Bezirk Thanhquan, Provinz Thaibinh)') <small class="text-danger">*</small></label>
                                                    <textarea required rows="3" class="form-control" name="json_params[profile][address]">{{ $detail->json_params->profile->address ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Quốc tịch') <small class="text-danger">*</small></label>
                                                    <input required type="text" class="form-control" name="json_params[profile][country]"
                                                        placeholder="@lang('Quốc tịch')" value="{{ $detail->json_params->profile->country ?? 'Việt Nam' }}"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Tình trạng hôn nhân') <small class="text-danger">*</small></label>
                                                    <select required name="json_params[profile][marital]" class="form-control">
                                                        @foreach ($marital as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ (isset($detail->json_params->profile->marital) && $detail->json_params->profile->marital==$key) ? 'selected' : '' }}>
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Giới thiệu bản thân') <small class="text-danger">*</small></label>
                                                    <textarea required rows="3" class="form-control" placeholder="Giới thiệu bản thân" name="json_params[profile][brief]">{{ $detail->json_params->profile->brief ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane " id="tab_2">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Trường cấp I. Hướng dẫn ghi đầy đủ: Grundschule + tên trường của mình viết cách không dấu. Ví dụ: Grundschule Hoang Hoa Tham') <small class="text-danger">*</small></label>
                                                    <input type="text" value="{{ isset($detail->json_params->learning_process->school_1st)?$detail->json_params->learning_process->school_1st:"" }}" class="form-control mr-2" name="json_params[learning_process][school_1st]" placeholder="Tên trường cấp 1.." required>
                                                </div>
                                            </div>
                                             
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Trường cấp II. Hướng dẫn ghi đầy đủ: Mittelschule  + tên trường của mình viết cách không dấu. Ví dụ: Mittelschule  Hoang Hoa Tham') <small class="text-danger">*</small></label>
                                                    <input type="text" value="{{ isset($detail->json_params->learning_process->school_2nd)?$detail->json_params->learning_process->school_2nd:"" }}" class="form-control mr-2" name="json_params[learning_process][school_2nd]" placeholder="Tên trường cấp 2.." required>
                                                </div>
                                            </div>
        
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Trường cấp III. Hướng dẫn ghi đầy đủ: Oberschule + tên trường của mình viết cách không dấu. Ví dụ: Oberschule Hoang Hoa Tham') <small class="text-danger">*</small></label>
                                                    <input type="text" value="{{ isset($detail->json_params->learning_process->school_3rd)?$detail->json_params->learning_process->school_3rd:"" }}" class="form-control mr-2" name="json_params[learning_process][school_3rd]" placeholder="Tên trường cấp 3.." required>
                                                </div>
                                            </div>
        
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Ngày tốt nghiệp cấp 3: Nằm ở góc dưới bên phải trên bằng tốt nghiệp cấp 3 của em') <small class="text-danger">*</small></label>
                                                    <input type="date" value="{{ (isset($detail->json_params->learning_process->school_3rd_time) && $detail->json_params->learning_process->school_3rd_time != '') ? date('Y-m-d', strtotime($detail->json_params->learning_process->school_3rd_time)) : "" }}" class="form-control mr-2" name="json_params[learning_process][school_3rd_time]" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Trường đại học, cao đẳng theo học. Sử dụng Google Dịch để dịch tên trường ra tiếng Đức nhé!')</label>
                                                    <input type="text" value="{{ isset($detail->json_params->learning_process->university)?$detail->json_params->learning_process->university:"" }}" class="form-control mr-2" name="json_params[learning_process][university]" placeholder="Tên trường đại học..">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Ngành đại học, cao đẳng theo học. Sử dụng Google Dịch để dịch tên trường ra tiếng Đức nhé!')</label>
                                                    <input type="text" value="{{ isset($detail->json_params->learning_process->field_university)?$detail->json_params->learning_process->field_university:"" }}" class="form-control mr-2" name="json_params[learning_process][field_university]" placeholder="Tên ngành học..">
                                                </div>
                                            </div>
        
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Ngày bắt đầu học ĐH-CĐ') </label>
                                                    <input type="date" value="{{ (isset($detail->json_params->learning_process->university_start) && $detail->json_params->learning_process->school_3rd_time != '') ? date('Y-m-d', strtotime($detail->json_params->learning_process->university_start)) : "" }}" class="form-control mr-2" name="json_params[learning_process][university_start]" >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Ngày kết thúc học ĐH-CĐ') </label>
                                                    <input type="date" value="{{ (isset($detail->json_params->learning_process->university_end) && $detail->json_params->learning_process->university_end != '') ? date('Y-m-d', strtotime($detail->json_params->learning_process->university_end)) : "" }}" class="form-control mr-2" name="json_params[learning_process][university_end]" >
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="width:100%; border-top: 2px dashed #000;">
                                        <div id="learning-process-list">
                                            @isset($detail->json_params->learning_process->other)
                                                @foreach($detail->json_params->learning_process->other as $key => $other)
                                                <div style="margin-right: 0px; margin-left: 0px; margin-top: 10px" class="row position-relative border p-3" >
                                                    <button type="button" class="btn btn-danger btn-del btn-sm position-absolute" style="top: 01px; right: 01px; z-index:9px" onclick="removeBlock(this)">
                                                        Xóa
                                                    </button>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Trường đại học, cao đẳng theo học.')</label>
                                                            <input type="text" value="{{ $other->university??"" }}" class="form-control mr-2" name="json_params[learning_process][other][{{ $key }}][university]" placeholder="Tên trường học.." required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Ngành học')</label>
                                                            <input type="text" value="{{ $other->field_university??"" }}" class="form-control mr-2" name="json_params[learning_process][other][{{ $key }}][field_university]" placeholder="Tên ngành học.." required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Ngày bắt đầu học ĐH-CĐ') </label>
                                                            <input type="date" value="{{ $other->university_start??"" }}" class="form-control mr-2" name="json_params[learning_process][other][{{ $key }}][university_start]" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Ngày kết thúc học ĐH-CĐ') </label>
                                                            <input type="date" value="{{ $other->university_end??"" }}" class="form-control mr-2" name="json_params[learning_process][other][{{ $key }}][university_end]" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endisset
                                        </div>
        
                                        <div class="row" style="margin: 10px 0px">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="pull-right add_learning_process text-primary" style="cursor: pointer;">
                                                        <i class="fa fa-plus"></i> @lang('Thêm trường ĐH-CĐ')
                                                    </label>
                                                </div>
                                            </div>        
                                        </div>
                                    </div>

                                    <div class="tab-pane " id="tab_3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Công ty, nơi làm việc 1 (Ví dụ: Café Quan Nho)') </label>
                                                    <input type="text" value="{{ isset($detail->json_params->experience->company)?$detail->json_params->experience->company:"" }}" class="form-control mr-2" name="json_params[experience][company]" placeholder="Nơi làm việc 1.." >
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Vị trí. Nếu vị trí các em làm không có trong danh mục dưới đây, các em có thể sử dụng Google dịch  để dịch chức vụ của mình ra tiếng Đức nhé!') </label>
                                                    <select name="json_params[experience][company_position]" class="form-control">
                                                        @foreach ($company_position as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ (isset($detail->json_params->experience->company_position) && $detail->json_params->experience->company_position == $key) ? 'selected' : '' }}>
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Ngày bắt đầu') </label>
                                                    <input type="date" value="{{ (isset($detail->json_params->experience->company_start) && $detail->json_params->experience->company_start != '') ? date('Y-m-d', strtotime($detail->json_params->experience->company_start)) : "" }}" class="form-control mr-2" name="json_params[experience][company_start]" >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Ngày kết thúc.') </label>
                                                    <input type="date" value="{{ (isset($detail->json_params->experience->company_end) && $detail->json_params->experience->company_end != '') ? date('Y-m-d', strtotime($detail->json_params->experience->company_end)) : "" }}" class="form-control mr-2" name="json_params[experience][company_end]" >
                                                </div>
                                            </div>
        
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Công việc trong thời gian làm? Liệt kê sơ qua công việc bằng tiếng việt, sau đó sử dụng Google Dịch dịch sang tiếng Đức')</label>
                                                    <textarea rows="3" class="form-control" placeholder="Công việc trong thời gian làm" name="json_params[experience][content_company]">{{ $detail->json_params->experience->content_company ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="width:100%; border-top: 2px dashed #000;">
                                        <div id="experience-list">
                                            @isset($detail->json_params->experience->other)
                                                @foreach($detail->json_params->experience->other as $key => $other)
                                                <div style="margin-right: 0px; margin-left: 0px; margin-top: 10px" class="row position-relative border p-3" >
                                                    <button type="button" class="btn btn-danger btn-del btn-sm position-absolute" style="top: 01px; right: 01px; z-index:9px" onclick="removeBlock(this)">
                                                        Xóa
                                                    </button>
                                                    
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>@lang('Công ty, nơi làm việc') </label>
                                                            <input required type="text" value="{{ $other->company??"" }}" class="form-control mr-2" name="json_params[experience][other][{{ $key }}][company]" placeholder="Công ty làm việc.." >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>@lang('Vị trí. Nếu vị trí các em làm không có trong danh mục dưới đây, các em có thể sử dụng Google dịch  để dịch chức vụ của mình ra tiếng Đức nhé!') </label>
                                                            <select required name="json_params[experience][other][{{ $key }}][company_position]" class="form-control">
                                                                @foreach ($company_position as $k => $val)
                                                                    <option value="{{ $k }}"
                                                                        {{ (isset($other->company_position) && $other->company_position==$k) ? 'selected' : '' }}>
                                                                        {{ __($val) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Ngày bắt đầu') </label>
                                                            <input required type="date" value="{{ (isset($other->company_start) && $other->company_start != '') ? date('Y-m-d', strtotime($other->company_start)) : "" }}" class="form-control mr-2" name="json_params[experience][other][{{ $key }}][company_start]" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Ngày kết thúc. (nếu chưa kết thúc chọn ngày hôm nay)') </label>
                                                            <input required type="date" value="{{ (isset($other->company_end) && $other->company_end != '') ? date('Y-m-d', strtotime($other->company_end)) : "" }}" class="form-control mr-2" name="json_params[experience][other][{{ $key }}][company_end]" >
                                                        </div>
                                                    </div>
                
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>@lang('Công việc trong thời gian làm? Liệt kê sơ qua công việc bằng tiếng việt, sau đó sử dụng Google Dịch dịch sang tiếng Đức')</label>
                                                            <textarea required rows="3" class="form-control" placeholder="Công việc trong thời gian làm" name="json_params[experience][other][{{ $key }}][content_company]">{{ $other->content_company ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endisset
                                        </div>
        
                                        <div class="row" style="margin: 10px 0px">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="pull-right add_experience text-primary" style="cursor: pointer;">
                                                        <i class="fa fa-plus"></i> @lang(' Thêm kinh nghiệm')
                                                    </label>
                                                </div>
                                            </div>        
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tab_4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Bẳt đầu học tiếng Đức từ bao giờ?') <small class="text-danger">*</small></label>
                                                    <input required type="date" value="{{ (isset($detail->json_params->qualification->germany_start) && $detail->json_params->qualification->germany_start != '') ? date('Y-m-d', strtotime($detail->json_params->qualification->germany_start)) : "" }}" class="form-control mr-2" name="json_params[qualification][germany_start]" >
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Thành phố nơi học') <small class="text-danger">*</small></label>
                                                    <input required type="text" value="{{ isset($detail->json_params->qualification->city_learn)?$detail->json_params->qualification->city_learn:"" }}" class="form-control mr-2" name="json_params[qualification][city_learn]" placeholder="Thành phố học tiếng Đức.." >
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Trình độ tiếng Đức') <small class="text-danger">*</small></label>
                                                    <select required name="json_params[qualification][germany_level]" class="form-control">
                                                        @foreach ($germany_level as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ (isset($detail->json_params->qualification->germany_level) && $detail->json_params->qualification->germany_level == $key) ? 'selected' : '' }}>
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Trình độ tiếng Anh') <small class="text-danger">*</small></label>
                                                    <select required name="json_params[qualification][english_level]" class="form-control">
                                                        @foreach ($germany_level as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ (isset($detail->json_params->qualification->english_level) && $detail->json_params->qualification->english_level == $key) ? 'selected' : '' }}>
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="width:100%; border-top: 2px dashed #000;">
                                        <div id="qualification-list">
                                            @isset($detail->json_params->qualification->other)
                                                @foreach($detail->json_params->qualification->other as $key => $other)
                                                <div style="margin-right: 0px; margin-left: 0px; margin-top: 10px" class="row position-relative border p-3" >
                                                    <button type="button" class="btn btn-danger btn-del btn-sm position-absolute" style="top: 01px; right: 01px; z-index:9px" onclick="removeBlock(this)">
                                                        Xóa
                                                    </button>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>@lang('Ngoại ngữ khác') <small class="text-danger">*</small></label>
                                                            <select required name="json_params[qualification][other][{{ $key }}][language]" class="form-control">
                                                                @foreach ($language as $k => $val)
                                                                    <option value="{{ $k }}"
                                                                        {{ (isset($other->language) && $other->language == $k) ? 'selected' : '' }}>
                                                                        {{ __($val) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>@lang('Trình độ ngoại ngữ') <small class="text-danger">*</small></label>
                                                            <input required type="text" value="{{ isset($other->level)?$other->level:"" }}" class="form-control mr-2" name="json_params[qualification][other][{{ $key }}][level]" placeholder="Trình độ .." >
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endisset
                                        </div>
        
                                        <div class="row" style="margin: 10px 0px">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="pull-right add_qualification text-primary" style="cursor: pointer;">
                                                        <i class="fa fa-plus"></i> @lang(' Thêm kỹ năng-trình độ')
                                                    </label>
                                                </div>
                                            </div>        
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tab_5">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Phẩm chất nổi bật 1. Tipp: nó nên liên quan, trợ giúp, hữu ích đến ngành nghề các em định học, đừng chọn bừa nhé') <small class="text-danger">*</small></label>
                                                    <select required name="json_params[hobby][quality1]" class="form-control">
                                                        <option value="">@lang('Chọn')</option>
                                                        @foreach ($quality as $k => $val)
                                                            <option value="{{ $k }}"
                                                                {{ (isset($detail->json_params->hobby->quality1) && $detail->json_params->hobby->quality1 == $k) ? 'selected' : '' }}>
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Phẩm chất nổi bật 2. Tipp: nó nên liên quan, trợ giúp, hữu ích đến ngành nghề các em định học, đừng chọn bừa nhé') <small class="text-danger">*</small></label>
                                                    <select required name="json_params[hobby][quality2]" class="form-control">
                                                        <option value="">@lang('Chọn')</option>
                                                        @foreach ($quality as $k => $val)
                                                            <option value="{{ $k }}"
                                                                {{ (isset($detail->json_params->hobby->quality2) && $detail->json_params->hobby->quality2 == $k) ? 'selected' : '' }}>
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Phẩm chất nổi bật 3. Tipp: nó nên liên quan, trợ giúp, hữu ích đến ngành nghề các em định học, đừng chọn bừa nhé') <small class="text-danger">*</small></label>
                                                    <select required name="json_params[hobby][quality3]" class="form-control">
                                                        <option value="">@lang('Chọn')</option>
                                                        @foreach ($quality as $k => $val)
                                                            <option value="{{ $k }}"
                                                                {{ (isset($detail->json_params->hobby->quality3) && $detail->json_params->hobby->quality3 == $k) ? 'selected' : '' }}>
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Phẩm chất nổi bật 4. Tipp: nó nên liên quan, trợ giúp, hữu ích đến ngành nghề các em định học, đừng chọn bừa nhé') <small class="text-danger">*</small></label>
                                                    <select required name="json_params[hobby][quality4]" class="form-control">
                                                        <option value="">@lang('Chọn')</option>
                                                        @foreach ($quality as $k => $val)
                                                            <option value="{{ $k }}"
                                                                {{ (isset($detail->json_params->hobby->quality4) && $detail->json_params->hobby->quality4 == $k) ? 'selected' : '' }}>
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
        
                                            {{-- Sở thích --}}
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Sở thích 1. nên chọn những hoạt động có lợi cho ngành nghề em định nộp') <small class="text-danger">*</small></label>
                                                    <select required name="json_params[hobby][hobby1]" class="form-control">
                                                        <option value="">@lang('Chọn')</option>
                                                        @foreach ($hobby as $k => $val)
                                                            <option value="{{ $k }}"
                                                                {{ (isset($detail->json_params->hobby->hobby1) && $detail->json_params->hobby->hobby1 == $k) ? 'selected' : '' }}>
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Sở thích 2. nên chọn những hoạt động có lợi cho ngành nghề em định nộp') <small class="text-danger">*</small></label>
                                                    <select required name="json_params[hobby][hobby2]" class="form-control">
                                                        <option value="">@lang('Chọn')</option>
                                                        @foreach ($hobby as $k => $val)
                                                            <option value="{{ $k }}"
                                                                {{ (isset($detail->json_params->hobby->hobby2) && $detail->json_params->hobby->hobby2 == $k) ? 'selected' : '' }}>
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Sở thích 3. nên chọn những hoạt động có lợi cho ngành nghề em định nộp') <small class="text-danger">*</small></label>
                                                    <select required name="json_params[hobby][hobby3]" class="form-control">
                                                        <option value="">@lang('Chọn')</option>
                                                        @foreach ($hobby as $k => $val)
                                                            <option value="{{ $k }}"
                                                                {{ (isset($detail->json_params->hobby->hobby3) && $detail->json_params->hobby->hobby3 == $k) ? 'selected' : '' }}>
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{!! nl2br('Thư động lực. Đối với các em chưa biết mình sẽ theo ngành gì cụ thể, thì việc điền này không bắt buộc. Nếu các em đã biết ngành nghề mình chọn, em trình bày động lực càng rõ ràng, cụ thể thì tỉ lệ đậu hồ sơ và đậu visa sẽ cao hơn. Động lực theo ngành của em là gì?
                                                        1 bản thư động lực nên có 4 đoạn văn:
                                                        Đoạn 1: giới thiệu bản thân, ngành muốn theo học
                                                        Đoạn 2: kể về động lực theo học của em, những tìm hiểu của em về nghề, nói lên được khát vọng, mục đích và sở thích của em về ngành nghề, dự định học bên Đức, kế hoạch sau khi học xong Ausbildung là gì
                                                        Đoạn 3: Điểm mạnh của em là gì, nó có thể giúp ích gì được cho em trong ngành nghề tương lai. 
                                                        Đoạn 4: hứa sẽ cố gắng và đóng góp. Mong đợi lịch phỏng vấn, hy vọng sớm nhận lại hồi âm. Cảm ơn') !!} </label>
                                                    <textarea placeholder="Nhập nội dung thư đông lực" class="form-control" rows="3" name="json_params[hobby][letter]">{{ $detail->json_params->hobby->letter ??"" }}</textarea>
                                                </div>
                                                <hr style="width:100%; border-top: 2px dashed #000;">
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group ">
                                                    <div id="hobby-list">
                                                        @if(isset($detail->json_params->hobby->other))
                                                            @foreach($detail->json_params->hobby->other as $hobby)
                                                            <div class="hobby-item d-flex align-items-center mt-2">
                                                                <input required type="text" class="form-control" name="json_params[hobby][other][]" placeholder="@lang('Sở thích cá nhân')" value="{{ $hobby ??"" }}"  required>
                                                                <button type="button" class="btn btn-danger btn-sm remove-hobby ml-2">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>
                                                            @endforeach
                                                        @endif
                                                    </div>    
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="row" style="margin: 10px 0px">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="pull-right add_hooby text-primary" style="cursor: pointer;">
                                                        <i class="fa fa-plus"></i> @lang(' Thêm sở thích, phẩm chất khác')
                                                    </label>
                                                </div>
                                            </div>        
                                        </div>    
                                    </div>

                                    <div class="tab-pane" id="tab_6">
                                        <div class="row">
                                            <input type="hidden" name="json_params[upload_image]" value="true"> 
                                            {{-- Ảnh CV --}}
                                            <div class="col-md-12">
                                                <div class="form-group ">
                                                    <label>@lang('1. Upload ảnh CV (ảnh mẫu ví dụ như dưới hình. Nền trắng, ghi, mặt tươi tắn. mặc áo sơ mi, tóc tai gọn gàng)') <small class="text-danger">*</small></label>
                                                    <a id="cv-avatar-link" href="{{ isset($detail->json_params->upload_image->avatar)?$detail->json_params->upload_image->avatar:asset('uploads/demo.jpg') }}" target="_blank">  
                                                        <div class="mx-3 pt-3 pb-3">
                                                            <img src="{{ isset($detail->json_params->upload_image->avatar)?$detail->json_params->upload_image->avatar:asset('uploads/demo.jpg') }}" style="" class="img-thumbnail mx-auto" alt="" id="cv-avatar">
                                                        </div>
                                                    </a>
                                                    <div class="mb-1">
                                                        <input type="file" id="avatarInput" class="d-none" name="avatar" accept="image/*">
                                                        <span class="btn btn-sm btn-info" onclick="document.getElementById('avatarInput').click()">
                                                        <i class="fa fa-camera"></i> Cập nhật ảnh</span>
                                                    </div>    
                                                </div>
                                                <hr style="width:100%; border-top: 2px dashed #000;">
                                            </div>

                                            {{-- Hộ chiếu --}}
                                            <div class="col-md-12">
                                                <div class="form-group ">
                                                    <label>@lang('2.Upload ảnh hộ chiếu. Ảnh đủ cả 4 góc, không scan, và CÓ CHỮ KÍ. Lưu ý: Đặt ra mặt phẳng, chụp thẳng từ trên xuống ko xô lệch, ko mất góc, ko rung lắc.') </label>
                                                    <br>    
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                          <div class="form-group">
                                                            <input class="btn btn-warning btn-sm add-gallery-image" data-toggle="tooltip"
                                                              title="Nhấn để chọn thêm ảnh" type="button" value="Thêm ảnh" />
                                                          </div>
                                                          <div class="row list-gallery-image">
                                                            @isset($detail->json_params->upload_image->passport_images)
                                                                @foreach ($detail->json_params->upload_image->passport_images as $index => $file)
                                                                @if ($file != null)
                                                                  <div class="col-lg-2 col-md-3 col-sm-4 mb-1 gallery-image">
                                                                    @if (Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                                                    <a href="{{ $file }}" target="_blank">
                                                                        <img class="img-width" src="{{ $file }}">
                                                                    </a>
                                                                    @elseif (Str::endsWith($file, ['.pdf']))
                                                                        <a href="{{ $file }}" target="_blank">
                                                                            <div class="file-item m-1 position-relative pdf-box" style="background: #f8f9fa; border: 1px solid #ddd;">
                                                                                <i class="fa fa-file-pdf text-danger" style="font-size: 40px; margin-top: 15px;"></i>
                                                                                <div class="pdf-name text-truncate" title="{{ basename($file) }}" style="max-width: 90px;">{{ basename($file) }}</div>
                                                                            </div>
                                                                        </a>
                                                                    @endif
                                                                    <input type="file" name="passport_images[]"
                                                                      class="hidden" id="gallery_image_{{ $index }}" accept="image/*,application/pdf">
                                                                    <div class="btn-action">
                                                                      <label class="btn btn-sm btn-success btn-upload  mr-5"
                                                                        for="gallery_image_{{ $index }}">
                                                                        <i class="fa fa-upload"></i>
                                                                      </label>
                                                                      <span class="btn btn-sm btn-danger btn-remove">
                                                                        <i class="fa fa-trash"></i>
                                                                      </span>
                                                                    </div>
                                                                  </div>
                                                                @endif
                                                              @endforeach
                                                            @endisset
                                                          </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr style="width:100%; border-top: 2px dashed #000;">
                                            </div>

                                            {{-- Chữ ký mẫu --}}
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('3.Upload chữ kí mẫu. Ảnh Kí bằng bút mực xanh, giấy trắng A4, không dòng kẻ, không bóng, không lóa. 
                                                    Đặt ra mặt phẳng, chụp thẳng từ trên xuống ko xô lệch, ko mất góc, ko rung lắc. Tải 1 tệp được hỗ trợ lên: PDF, drawing hoặc image.') <small class="text-danger">*</small></label>
                                                    <div class="mt-2">
                                                        <label for="signatureInput" class="btn btn-sm btn-info">
                                                            <i class="fa fa-upload"></i> Tải lên chữ ký (1 file: ảnh, PDF hoặc bản vẽ)
                                                        </label>
                                                        <input type="file" id="signatureInput" class="d-none" name="signature_image" accept="image/*,application/pdf" />
                                                        <div id="signaturePreview" class="d-flex mt-2 flex-wrap ">
                                                            @isset($detail->json_params->upload_image->signature_image)
                                                                @php $file = $detail->json_params->upload_image->signature_image; @endphp
                                                                @if (Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                                                <a href="{{ $file }}" target="_blank">
                                                                    <div class="file-item m-1 position-relative" style="width: 220px; height: 220px;">
                                                                        <img src="{{ asset($file) }}" class="img-thumbnail" style="width: 100%; height: 100%;" >
                                                                    </div>
                                                                </a>    
                                                                @elseif (Str::endsWith($file, ['.pdf']))
                                                                <a href="{{ $file }}" target="_blank">
                                                                    <div class="file-item m-1 position-relative pdf-box" style=" background: #f8f9fa; border: 1px solid #ddd;">
                                                                        <i class="fa fa-file-pdf text-danger" style="font-size: 40px; margin-top: 15px;"></i>
                                                                        <div class="pdf-name text-truncate" title="{{ basename($file) }}" style="max-width: 90px;">{{ basename($file) }}</div>
                                                                    </div>
                                                                </a>    
                                                                @endif
                                                            @endisset 
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr style="width:100%; border-top: 2px dashed #000;">
                                            </div>

                                            {{-- Bằng cấp 3 --}}
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('4.Upload bằng cấp 3 ảnh mẫu ví dụ như dưới hình. Đặt ra mặt phẳng, chụp thẳng từ trên xuống ko xô lệch, ko mất góc, ko rung lắc. Tải 1 tệp được hỗ trợ lên: PDF, drawing hoặc image.')</label>
                                                    <div class="mt-3">
                                                        <label for="diplomaInput" class="btn btn-sm btn-info">
                                                            <i class="fa fa-upload"></i> Tải lên bằng c3 (1 file: ảnh, PDF hoặc bản vẽ, document)
                                                        </label>
                                                        <input type="file" id="diplomaInput" class="d-none" name="diploma_image" accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain" />
                                                        <div id="diplomaPreview" class="d-flex mt-2 flex-wrap">
                                                            @isset($detail->json_params->upload_image->diploma_image)
                                                                @php $file = $detail->json_params->upload_image->diploma_image; @endphp
                                                                @if (Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                                                <a href="{{ asset($file) }}" target="_blank">
                                                                    <div class="file-item m-1 position-relative" style="width: 220px; height: auto;">
                                                                        <img src="{{ asset($file) }}" class="img-thumbnail" style="width: 100%; height: 100%;">
                                                                    </div>
                                                                </a>
                                                                @else
                                                                <a href="{{ asset($file) }}" target="_blank">
                                                                    <div class="file-item m-1 position-relative pdf-box" style="background: #f8f9fa; border: 1px solid #ddd;">
                                                                        <i class="fa fa-file-pdf text-danger" style="font-size: 40px; margin-top: 15px;"></i>
                                                                        <div class="pdf-name text-truncate" title="{{ basename($file) }}" style="max-width: 90px;">{{ basename($file) }}</div>
                                                                    </div>
                                                                </a>
                                                                @endif
                                                            @endisset 
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr style="width:100%; border-top: 2px dashed #000;">
                                            </div>
                                            
                                            {{-- Bằng tiếng Đức --}}
                                            <div class="col-md-12">
                                                <div class="form-group ">
                                                    <label>@lang('5.Upload bằng tiếng Đức. Đặt ra mặt phẳng, chụp thẳng từ trên xuống ko xô lệch, ko mất góc, ko rung lắc') </label>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                          <div class="form-group">
                                                            <input class="btn btn-warning btn-sm add-gallery-image-germany" data-toggle="tooltip"
                                                              title="Nhấn để chọn thêm ảnh" type="button" value="Thêm ảnh" />
                                                          </div>
                                                          <div class="row list-gallery-image-germany">
                                                            @isset($detail->json_params->upload_image->germany_images)
                                                                @foreach ($detail->json_params->upload_image->germany_images as $index => $file)
                                                                @if ($file != null)
                                                                  <div class="col-lg-2 col-md-3 col-sm-4 mb-1 gallery-image-germany">
                                                                    @if (Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                                                    <a href="{{ $file }}" target="_blank">
                                                                        <img class="img-width" src="{{ $file }}">
                                                                    </a>
                                                                    @elseif (Str::endsWith($file, ['.pdf']))
                                                                        <a href="{{ $file }}" target="_blank">
                                                                            <div class="file-item m-1 position-relative pdf-box" style="background: #f8f9fa; border: 1px solid #ddd;">
                                                                                <i class="fa fa-file-pdf text-danger" style="font-size: 40px; margin-top: 15px;"></i>
                                                                                <div class="pdf-name text-truncate" title="{{ basename($file) }}" style="max-width: 90px;">{{ basename($file) }}</div>
                                                                            </div>
                                                                        </a>
                                                                    @endif
                                                                    <input type="file" name="germany_images[]"
                                                                      class="hidden" id="gallery_image_{{ $index }}_germany" accept="image/*,application/pdf">
                                                                    <div class="btn-action">
                                                                      <label class="btn btn-sm btn-success btn-upload  mr-5"
                                                                        for="gallery_image_{{ $index }}_germany">
                                                                        <i class="fa fa-upload"></i>
                                                                      </label>
                                                                      <span class="btn btn-sm btn-danger btn-remove">
                                                                        <i class="fa fa-trash"></i>
                                                                      </span>
                                                                    </div>
                                                                  </div>
                                                                @endif
                                                              @endforeach
                                                            @endisset
                                                          </div>
                                                        </div>
                                                    </div>   
                                                </div>
                                                <hr style="width:100%; border-top: 2px dashed #000;">
                                            </div>
                                            {{-- Khác --}}
                                            <div class="col-md-12">
                                                <div class="form-group ">
                                                    <label>@lang('6.Upload các File khác.Upload tối đa 5 tệp. Không bắt buộc file upload nếu không có. (sổ tiêm, chứng chỉ hành nghề, Học bạ, video giới thiệu bản thân,...)') </label>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                          <div class="form-group">
                                                            <input class="btn btn-warning btn-sm add-gallery-image-other" data-toggle="tooltip"
                                                              title="Nhấn để chọn thêm ảnh" type="button" value="Thêm ảnh" />
                                                          </div>
                                                          <div class="row list-gallery-image-other">
                                                            @isset($detail->json_params->upload_image->other_file)
                                                                @foreach ($detail->json_params->upload_image->other_file as $index => $file)
                                                                @if ($file != null)
                                                                  <div class="col-lg-2 col-md-3 col-sm-4 mb-1 gallery-image-other">
                                                                    @if (Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                                                    <a href="{{ $file }}" target="_blank">
                                                                        <img class="img-width" src="{{ $file }}">
                                                                    </a>
                                                                    
                                                                    @elseif (Str::endsWith($file, ['.mp4', '.avi', '.mov']))
                                                                    <a href="{{ asset($file) }}" target="_blank">
                                                                        <div class="file-item m-1 position-relative" style="width: 220px; height: auto;">
                                                                            <video src="{{ asset($file) }}" class="img-thumbnail" style="width: 100%; height: 100%;" controls></video>
                                                                        </div>
                                                                    </a>
                                                                    @else
                                                                    <a href="{{ asset($file) }}" target="_blank">
                                                                        <div class="file-item m-1 position-relative pdf-box" style="background: #f8f9fa; border: 1px solid #ddd;">
                                                                            <i class="fa fa-file-pdf text-danger" style="font-size: 40px; margin-top: 15px;"></i>
                                                                            <div class="pdf-name text-truncate" title="{{ basename($file) }}" style="max-width: 90px;">{{ basename($file) }}</div>
                                                                        </div>  
                                                                    </a>    
                                                                    @endif
                                                                    <input type="file" name="other_file[]"
                                                                      class="hidden" id="gallery_image_{{ $index }}_other" accept="image/*,application/pdf">
                                                                    <div class="btn-action">
                                                                      <label class="btn btn-sm btn-success btn-upload  mr-5"
                                                                        for="gallery_image_{{ $index }}_other">
                                                                        <i class="fa fa-upload"></i>
                                                                      </label>
                                                                      <span class="btn btn-sm btn-danger btn-remove">
                                                                        <i class="fa fa-trash"></i>
                                                                      </span>
                                                                    </div>
                                                                  </div>
                                                                @endif
                                                              @endforeach
                                                            @endisset
                                                          </div>
                                                        </div>
                                                    </div>     
                                                </div>
                                                <hr style="width:100%; border-top: 2px dashed #000;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm pull-right">
                            <i class="fa fa-save"></i> @lang('Save')
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection


@section('script')
<script>
    $(document).on("click", ".btn-del", function () {
        $(this).closest(".row").remove();
    });
    
    $(".add_learning_process").click(function () {
        let index =  Date.now(); 
        var newLearningProcess = `<div style="margin-right: 0px; margin-left: 0px; margin-top: 10px" class="row position-relative border p-3" >
                                <button type="button" class="btn btn-danger btn-del btn-sm position-absolute" style="top: 01px; right: 01px; z-index:9px" onclick="removeBlock(this)">
                                    Xóa
                                </button>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Trường đại học, cao đẳng theo học.')</label>
                                        <input type="text" class="form-control mr-2" name="json_params[learning_process][other][${index}][university]" placeholder="Tên trường.." required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Ngành học')</label>
                                        <input type="text" class="form-control mr-2" name="json_params[learning_process][other][${index}][field_university]" placeholder="Tên ngành học.." required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Ngày bắt đầu học ĐH-CĐ') </label>
                                        <input type="date" class="form-control mr-2" name="json_params[learning_process][other][${index}][university_start]" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Ngày kết thúc học ĐH-CĐ') </label>
                                        <input type="date" class="form-control mr-2" name="json_params[learning_process][other][${index}][university_end]" required>
                                    </div>
                                </div>
                            </div>
        `;
        $("#learning-process-list").append(newLearningProcess);
    });
    
    $(".add_experience").click(function () {
            let index =  Date.now(); 
            var newLearningProcess = `<div style="margin-right: 0px; margin-left: 0px; margin-top: 10px" class="row position-relative border p-3" >
                                            <button type="button" class="btn btn-danger btn-del btn-sm position-absolute" style="top: 01px; right: 01px; z-index:9px" onclick="removeBlock(this)">
                                                Xóa
                                            </button>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Công ty, nơi làm việc') </label>
                                                    <input required type="text" class="form-control mr-2" name="json_params[experience][other][${index}][company]" placeholder="Công ty làm việc.." >
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Vị trí làm việc.') </label>
                                                    <select required name="json_params[experience][other][${index}][company_position]" class="form-control">
                                                        @foreach ($company_position as $k => $val)
                                                            <option value="{{ $k }}">
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Ngày bắt đầu') </label>
                                                    <input required type="date" class="form-control mr-2" name="json_params[experience][other][${index}][company_start]" >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Ngày kết thúc.') </label>
                                                    <input required type="date" class="form-control mr-2" name="json_params[experience][other][${index}][company_end]" >
                                                </div>
                                            </div>
        
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Công việc trong thời gian làm? Liệt kê sơ qua công việc bằng tiếng việt, sau đó sử dụng Google Dịch dịch sang tiếng Đức')</label>
                                                    <textarea required rows="3" class="form-control" placeholder="Công việc trong thời gian làm" name="json_params[experience][other][${index}][content_company]"></textarea>
                                                </div>
                                            </div>
                                        </div>
            `;
            $("#experience-list").append(newLearningProcess);
    });

    $(".add_qualification").click(function () {
        let index =  Date.now(); 
        var newLearningProcess = `<div style="margin-right: 0px; margin-left: 0px; margin-top: 10px" class="row position-relative border p-3" >
                                        <button type="button" class="btn btn-danger btn-del btn-sm position-absolute" style="top: 01px; right: 01px; z-index:9px" onclick="removeBlock(this)">
                                            Xóa
                                        </button>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>@lang('Ngoại ngữ khác') <small class="text-danger">*</small></label>
                                                <select required name="json_params[qualification][other][${index}][language]" class="form-control">
                                                    @foreach ($language as $k => $val)
                                                        <option value="{{ $k }}">
                                                            {{ __($val) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>@lang('Trình độ ngoại ngữ') <small class="text-danger">*</small></label>
                                                <input required type="text" class="form-control mr-2" name="json_params[qualification][other][${index}][level]" placeholder="Trình độ .." >
                                            </div>
                                        </div>
                                    </div>`;
        $("#qualification-list").append(newLearningProcess);
    });

    $(".add_hooby").click(function () {
        var newHobby = `
            <div class="hobby-item d-flex align-items-center mt-2">
                <input type="text" class="form-control" name="json_params[hobby][other][]" placeholder="@lang('Sở thích , phẩm chất khác')" required>
                <button type="button" class="btn btn-danger btn-sm remove-hobby ml-2">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        `;
        $("#hobby-list").append(newHobby);
    });

    // Xóa input khi nhấn vào nút xóa
    $(document).on("click", ".remove-hobby", function () {
            $(this).closest(".hobby-item").remove();
    });

    $('#avatarInput').change(function(event) {
        var img_path = URL.createObjectURL(event.target.files[0]);
        const file = this.files[0];
        if (file && !file.type.startsWith("image/")) {
            alert("Chỉ được chọn file ảnh!");
            this.value = ""; // Reset input
        }else{
            $("#cv-avatar").attr('src', img_path);
            $("#cv-avatar-link").attr('href', img_path);
        }
    });
    //chữ ký
    $('#signatureInput').change(function(event) {
        var file = event.target.files[0]; // Chỉ lấy 1 file duy nhất
        var preview = $("#signaturePreview");
        preview.html(""); // Xóa nội dung cũ

        if (!file) return; // Không có file thì thoát

        // Kiểm tra định dạng file
        if (!file.type.startsWith("image/") && file.type !== "application/pdf") {
            alert("Chỉ được chọn file ảnh, PDF hoặc bản vẽ!");
            this.value = ""; // Reset input
            return;
        }

        // Hiển thị ảnh
        if (file.type.startsWith("image/")) {
            let img_path = URL.createObjectURL(file);
            preview.append(`<a href="${img_path}" target="_blank"><img src="${img_path}" class="img-thumbnail m-1" style="width: 220px; height: auto;"></a>`);
        } 
        // Hiển thị icon PDF
        else if (file.type === "application/pdf") {
            let fileName = file.name; // Lấy tên file
            let fileUrl = URL.createObjectURL(file)
            preview.append(`<a href="${fileUrl}" target="_blank"><div class="pdf-box m-1">
                <i class="fa fa-file-pdf text-danger" style="font-size: 40px;"></i>
                <div class="pdf-name" title="${fileName}">${fileName}</div>
            </div></a>`);
        }
    });
     

    //bằng c3
    $('#diplomaInput').change(function(event) {
        var file = event.target.files[0]; // Chỉ lấy 1 file duy nhất
        var preview = $("#diplomaPreview");
        preview.html(""); // Xóa nội dung cũ

        if (!file) return; // Không có file thì thoát

        var validTypes = ["image/", "application/pdf", "application/msword", 
                        "application/vnd.openxmlformats-officedocument.wordprocessingml.document", 
                        "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", 
                        "text/plain"];

        // Kiểm tra định dạng file hợp lệ
        if (!validTypes.some(type => file.type.startsWith(type))) {
            alert("Chỉ được chọn file ảnh, PDF, Word, Excel hoặc TXT!");
            this.value = ""; // Reset input
            return;
        }

        // Xử lý hiển thị file theo từng loại
        if (file.type.startsWith("image/")) {
            let img_path = URL.createObjectURL(file);
            preview.append(`<a href="${img_path}" target="_blank"><div class="file-item m-1 position-relative" style="width: 220px; height: auto;">
                <img src="${img_path}" class="img-thumbnail" style="width: 100%; height: 100%;">
            </div></a>`);
        } 
        else {
            let fileName = file.name;
            let img_path = URL.createObjectURL(file);
            preview.append(`<a href="${img_path}" target="_blank"><div class="file-item m-1 position-relative pdf-box" style="background: #f8f9fa; border: 1px solid #ddd;">
                <i class="fa fa-file-pdf text-danger" style="font-size: 40px; margin-top: 15px;"></i>
                <div class="pdf-name text-truncate" title="${fileName}" style="max-width: 90px;">${fileName}</div>
            </div></a>`);
        }
    });

    //Họ chiếu
    var no_image_link = '{{ url('themes/admin/img/no_image.jpg') }}';
    $('.add-gallery-image').click(function(event) {
        let keyRandom = new Date().getTime();
        let elementParent = $('.list-gallery-image');
        let elementAppend =`<div class="col-lg-2 col-md-3 col-sm-4 mb-1 gallery-image">
                                <a href="${no_image_link}" target="_blank">
                                    <img class="img-width" src="${no_image_link}">
                                </a>
                                <input type="file" name="passport_images[]"
                                    class="hidden" id="gallery_image_${keyRandom}" accept="image/*,application/pdf">
                                <div class="btn-action">
                                    <label class="btn btn-sm btn-success btn-upload  mr-5"
                                    for="gallery_image_${keyRandom}">
                                    <i class="fa fa-upload"></i>
                                    </label>
                                    <span class="btn btn-sm btn-danger btn-remove">
                                    <i class="fa fa-trash"></i>
                                    </span>
                                </div>
                                </div>`;
        elementParent.append(elementAppend);
    });
    $('.list-gallery-image').on('change', 'input', function(event) {
        let _root = $(this).closest('.gallery-image');
        var file = event.target.files[0]; // Chỉ lấy 1 file duy nhất

        if (!file) return; // Nếu không có file, thoát

        if (!file.type.startsWith("image/") && file.type !== "application/pdf") {
            alert("Chỉ được chọn file ảnh hoặc PDF!");
            this.value = ""; // Reset input
            return;
        }

        let fileURL = URL.createObjectURL(file);
        let link = _root.find('a');
        
        if (file.type.startsWith("image/")) {
            // Xóa box PDF nếu có
            _root.find('.pdf-box').remove();

            // Nếu không có thẻ <img>, thêm mới
            if (_root.find('img').length === 0) {
                link.html(`<img class="img-width" src="${fileURL}">`);
            } else {
                _root.find('img').attr('src', fileURL).show(); // Cập nhật ảnh
            }

            link.attr('href', fileURL);
        } else if (file.type === "application/pdf") {
            _root.find('img').remove(); 

            let pdfBox = `
                <div class="file-item m-1 position-relative pdf-box" style="background: #f8f9fa; border: 1px solid #ddd;">
                    <i class="fa fa-file-pdf text-danger" style="font-size: 40px; margin-top: 15px;"></i>
                    <div class="pdf-name text-truncate" title="${file.name}" style="max-width: 90px;">${file.name}</div>
                </div>
            `;
            
            link.html(pdfBox); 
            link.attr('href', fileURL); 
        }
    });

    let deletedFiles = [];
      // Delete image
    $('.list-gallery-image').on('click', '.btn-remove', function() {
        let _root = $(this).closest('.gallery-image');
        let fileUrl = _root.find('a').attr('href');
        deletedFiles.push(fileUrl);
        _root.remove();
    });

    $('.list-gallery-image').on('mouseover', '.gallery-image', function(e) {
        $(this).find('.btn-action').show();
      });
    $('.list-gallery-image').on('mouseout', '.gallery-image', function(e) {
        $(this).find('.btn-action').hide();
    });

    //Bằng tiếng đức
    $('.add-gallery-image-germany').click(function(event) {
        let keyRandom = new Date().getTime();
        let elementParent = $('.list-gallery-image-germany');
        let elementAppend =`<div class="col-lg-2 col-md-3 col-sm-4 mb-1 gallery-image-germany">
                                <a href="${no_image_link}" target="_blank">
                                    <img class="img-width" src="${no_image_link}">
                                </a>
                                <input type="file" name="germany_images[]"
                                    class="hidden" id="gallery_image_${keyRandom}_germany" accept="image/*,application/pdf">
                                <div class="btn-action">
                                    <label class="btn btn-sm btn-success btn-upload  mr-5"
                                    for="gallery_image_${keyRandom}_germany">
                                    <i class="fa fa-upload"></i>
                                    </label>
                                    <span class="btn btn-sm btn-danger btn-remove">
                                    <i class="fa fa-trash"></i>
                                    </span>
                                </div>
                                </div>`;
        elementParent.append(elementAppend);
    });
    $('.list-gallery-image-germany').on('change', 'input', function(event) {
        let _root = $(this).closest('.gallery-image-germany');
        var file = event.target.files[0]; // Chỉ lấy 1 file duy nhất

        if (!file) return; // Nếu không có file, thoát

        if (!file.type.startsWith("image/") && file.type !== "application/pdf") {
            alert("Chỉ được chọn file ảnh hoặc PDF!");
            this.value = ""; // Reset input
            return;
        }

        let fileURL = URL.createObjectURL(file);
        let link = _root.find('a');
        
        if (file.type.startsWith("image/")) {
            // Xóa box PDF nếu có
            _root.find('.pdf-box').remove();

            // Nếu không có thẻ <img>, thêm mới
            if (_root.find('img').length === 0) {
                link.html(`<img class="img-width" src="${fileURL}">`);
            } else {
                _root.find('img').attr('src', fileURL).show(); // Cập nhật ảnh
            }

            link.attr('href', fileURL);
        } else if (file.type === "application/pdf") {
            _root.find('img').remove(); 

            let pdfBox = `
                <div class="file-item m-1 position-relative pdf-box" style="background: #f8f9fa; border: 1px solid #ddd;">
                    <i class="fa fa-file-pdf text-danger" style="font-size: 40px; margin-top: 15px;"></i>
                    <div class="pdf-name text-truncate" title="${file.name}" style="max-width: 90px;">${file.name}</div>
                </div>
            `;
            
            link.html(pdfBox); 
            link.attr('href', fileURL); 
        }
    });

    let deletedFilesGermany = [];
    $('.list-gallery-image-germany').on('click', '.btn-remove', function() {
        let _root = $(this).closest('.gallery-image-germany');
        let fileUrl = _root.find('a').attr('href');
        deletedFilesGermany.push(fileUrl);
        _root.remove();
    });

    $('.list-gallery-image-germany').on('mouseover', '.gallery-image-germany', function(e) {
        $(this).find('.btn-action').show();
      });
    $('.list-gallery-image-germany').on('mouseout', '.gallery-image-germany', function(e) {
        $(this).find('.btn-action').hide();
    });
    
    //Khác
    $('.add-gallery-image-other').click(function(event) {
        let keyRandom = new Date().getTime();
        let elementParent = $('.list-gallery-image-other');
        let elementAppend =`<div class="col-lg-2 col-md-3 col-sm-4 mb-1 gallery-image-other">
                                <a href="${no_image_link}" target="_blank">
                                    <img class="img-width" src="${no_image_link}">
                                </a>
                                <input type="file" name="other_file[]"
                                    class="hidden" id="gallery_image_${keyRandom}_other" accept="image/*,application/pdf">
                                <div class="btn-action">
                                    <label class="btn btn-sm btn-success btn-upload  mr-5"
                                    for="gallery_image_${keyRandom}_other">
                                    <i class="fa fa-upload"></i>
                                    </label>
                                    <span class="btn btn-sm btn-danger btn-remove">
                                    <i class="fa fa-trash"></i>
                                    </span>
                                </div>
                                </div>`;
        elementParent.append(elementAppend);
    });
    $('.list-gallery-image-other').on('change', 'input', function(event) {
        let _root = $(this).closest('.gallery-image-other');
        var file = event.target.files[0]; // Chỉ lấy 1 file duy nhất

        if (!file) return; // Nếu không có file, thoát

        if (!file.type.startsWith("image/") && file.type !== "application/pdf") {
            alert("Chỉ được chọn file ảnh hoặc PDF!");
            this.value = ""; // Reset input
            return;
        }

        let fileURL = URL.createObjectURL(file);
        let link = _root.find('a');
        
        if (file.type.startsWith("image/")) {
            // Xóa box PDF nếu có
            _root.find('.pdf-box').remove();

            // Nếu không có thẻ <img>, thêm mới
            if (_root.find('img').length === 0) {
                link.html(`<img class="img-width" src="${fileURL}">`);
            } else {
                _root.find('img').attr('src', fileURL).show(); // Cập nhật ảnh
            }

            link.attr('href', fileURL);
        } 
        else if (file.type.startsWith("video/")) {
            _root.find('img').remove(); 
            let pdfBox = `
                <div class="file-item m-1 position-relative" style="width: 220px; height: auto;">
                    <video src="${fileURL}" class="img-thumbnail" style="width: 100%; height: 100%;" controls></video>
                </div>
            `;
            
            link.html(pdfBox); 
            link.attr('href', fileURL); 
        }
        else  {
            _root.find('img').remove(); 

            let pdfBox = `
                <div class="file-item m-1 position-relative pdf-box" style="background: #f8f9fa; border: 1px solid #ddd;">
                    <i class="fa fa-file-pdf text-danger" style="font-size: 40px; margin-top: 15px;"></i>
                    <div class="pdf-name text-truncate" title="${file.name}" style="max-width: 90px;">${file.name}</div>
                </div>
            `;
            
            link.html(pdfBox); 
            link.attr('href', fileURL); 
        }
    });

    let deletedFilesOther = [];
    $('.list-gallery-image-other').on('click', '.btn-remove', function() {
        let _root = $(this).closest('.gallery-image-other');
        let fileUrl = _root.find('a').attr('href');
        deletedFilesOther.push(fileUrl);
        _root.remove();
    });

    $('.list-gallery-image-other').on('mouseover', '.gallery-image-other', function(e) {
        $(this).find('.btn-action').show();
      });
    $('.list-gallery-image-other').on('mouseout', '.gallery-image-other', function(e) {
        $(this).find('.btn-action').hide();
    });

    $(document).ready(function () {
        var hash = window.location.hash;
        if (hash) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
        }
        $('.nav-tabs a').on('click', function () {
            var newHash = $(this).attr('href');
            history.pushState(null, null, newHash);
        });
    });

    $('form').on('submit', function() {
        $('<input>').attr({
            type: 'hidden',
            name: 'deleted_files',
            value: JSON.stringify(deletedFiles)
        }).appendTo(this);
        $('<input>').attr({
            type: 'hidden',
            name: 'deleted_files_germany',
            value: JSON.stringify(deletedFilesGermany)
        }).appendTo(this);
        $('<input>').attr({
            type: 'hidden',
            name: 'deleted_files_other',
            value: JSON.stringify(deletedFilesOther)
        }).appendTo(this);
    });
</script>
@endsection

