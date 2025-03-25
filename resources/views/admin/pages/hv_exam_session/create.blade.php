@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>

        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p>@lang('Please wait')...</p>
        </div>
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

        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Tạo phiên thi')</h3>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Trình độ') <small class="text-red">*</small></label>
                                        <select required name="id_level" class="id_level form-control select2 w-100">
                                            <option value="">@lang('Please choose')</option>
                                            @foreach ($levels as $val)
                                                <option value="{{ $val->id ?? '' }}">
                                                    {{ $val->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Kỹ năng thi') <small class="text-red">*</small></label>
                                        <select required name="skill_test" class="form-control select2 w-100">
                                            <option value="">@lang('Please choose')</option>
                                            @foreach ($skill as $val)
                                                <option value="{{ $val }}">
                                                    @lang($val)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Tổ chức')</label>
                                        <select name="organization" class="form-control select2 w-100">
                                            <option value="">@lang('Please choose')</option>
                                            @foreach ($type as $val)
                                                <option value="{{ $val }}">
                                                    @lang($val)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Phòng thi') </label>
                                        <input type="text" name="json_params[exam_room]" class="form-control"
                                            value="{{ old('json_params[exam_room]') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Ngày thi') <small class="text-red">*</small></label>
                                        <input type="date" class="form-control" name="day_exam"
                                            placeholder="@lang('Ngày thi')" value="{{ old('day_exam') }}" required>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Thời gian phiên thi (phút)') <small class="text-red">*</small></label>
                                        <input type="number" class="form-control" name="time_exam" min="0" step="5"
                                            placeholder="@lang('Thời gian thi (phút)')" value="{{ old('time_exam') }}" required>
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Thời gian bắt đầu') <small class="text-red">*</small></label>
                                        <input type="time" class="form-control" name="start_time"
                                            placeholder="@lang('Thời gian bắt đầu')" value="{{ old('time_exam') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Thời gian kết thúc') <small class="text-red">*</small></label>
                                        <input type="time" class="form-control" name="end_time"
                                            placeholder="@lang('Thời gian kết thúc')" value="{{ old('time_exam') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Giám thị coi thi') <small class="text-red">*</small></label>
                                        <select required name="id_invigilator" class="form-control select2 w-100">
                                            <option value="">@lang('Please choose')</option>
                                            @foreach ($list_admins as $val)
                                                <option value="{{ $val->id }}">
                                                    @lang($val->name ?? '')</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Người chấm thi') <small class="text-red">*</small></label>
                                        <select required name="id_grader_exam" class="form-control select2 w-100">
                                            <option value="">@lang('Please choose')</option>
                                            @foreach ($list_admins as $val)
                                                <option value="{{ $val->id }}">
                                                    @lang($val->name ?? '')</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <h4 style="padding-bottom:10px;">Tìm học viên</h4>
                                            <div style="padding-bottom: 5px">
                                                <div style="padding-left: 0px" class="col-md-6">
                                                    <select class="form-control select2 w-100" name=""
                                                        id="search_code_post">
                                                        <option value="">Danh sách lớp học...</option>
                                                        @foreach ($classs as $class)
                                                            <option value="{{ $class->id }}">
                                                                {{ $class->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="input-group col-md-6">
                                                    <input type="text" id="search_title_post"
                                                        class="form-control pull-right"
                                                        placeholder="Tên học viên, mã học viên..." autocomplete="off">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-default btn_search">
                                                            <i class="fa fa-search"></i> Lọc
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-config-overflow box-body table-responsive no-padding">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>STT</th>
                                                            <th>Mã HV</th>
                                                            <th>Tên HV</th>
                                                            <th>Trình độ</th>
                                                            <th>Chọn</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="post_available">

                                                    </tbody>
                                                </table>
                                            </div><!-- /.box-body -->
                                        </div>
                                        <div class="col-md-7">
                                            <h4 style="padding-bottom:10px;">Danh sách học viên được chọn</h4>
                                            <table id="myTable"
                                                class="table table-hover table-bordered table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('STT')</th>
                                                        <th>@lang('Mã HV')</th>
                                                        <th>@lang('Tên HV')</th>
                                                        <th>@lang('CCCD')</th>
                                                        <th>@lang('Khu vực')</th>
                                                        <th>@lang('Trình độ')</th>
                                                        <th>@lang('Khóa')</th>
                                                        <th>@lang('Lớp')</th>
                                                        <th>@lang('Chọn')</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tbody-order" id="post_related">

                                                </tbody>

                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                            <button type="submit" class="btn btn-info pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
@section('script')
    <script>
        var _data = '';
        $(document).on('click', '.btn_search', function() {
            let keyword = $('#search_title_post').val();
            let class_id = $('#search_code_post').val();
            let _targetHTML = $('#post_available');
            _targetHTML.html('');
            let checked_post = [];
            $('input.related_post_item2:checked').each(function() {
                checked_post.push($(this).val());
            });
            let url = "{{ route('hv_exam_session.search_student') }}/";
            show_loading_notification();
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    keyword: keyword,
                    class_id: class_id,
                    different_id: checked_post,
                },
                success: function(response) {
                    if (response.message == 'success') {
                        hide_loading_notification();
                        let list = response.data || null;
                        _data = response.data;
                        console.log(_data);

                        let _item = '';
                        if (list.length > 0) {
                            var _i = 0;
                            list.forEach(item => {
                                _i++;
                                _item += `
                                <tr>
                                    <td>${_i}</td>
                                    <td>${item.admin_code??""}</td>
                                    <td>${item.name??""}</td>
                                    <td>${item.level?.name ?? ""??""}</td>
                                    <td><input onchange="selected_students(this)" type="checkbox" value="${item.id}" class="mr-15 cursor" autocomplete="off"></td>
                                </tr>
                                `;
                            });
                            _targetHTML.html(_item);
                        }
                    } else {
                        hide_loading_notification();
                        _targetHTML.html('<tr><td colspan="5">' + response.message +
                            '</td></tr>');
                    }

                },
                error: function(response) {
                    // Get errors
                    hide_loading_notification();
                    let errors = response.responseJSON.message;
                    _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                }
            });
        });

        function selected_students(th) {
            let _id = $(th).val();
            let _item = _data.find(item => item.id == _id);
            $(th).closest('tr').remove();
            let _html = '';
            if (_item) {
                _html = `
                <tr class="valign-middle">
                    <td class="order-number"></td>
                    <td>${_item.admin_code??''}</td>
                    <td>${_item.name??''}</td>
                    <td>${_item.json_params.cccd??''}</td>
                    <td>${_item.area?.name??''}</td>
                    <td>${_item.level?.name??''}</td>
                    <td>${_item.course?.name??''}</td>
                    <td>${_item.class_detal?.name??''}</td>
                    <td><input name= "student[]" onclick="deleteStudent(this)" checked type="checkbox" value="${_item.id}" class="mr-15 related_post_item2 cursor" autocomplete="off"></td>
                    </tr>
                `;
            }
            $('#post_related').append(_html);
            change_stt('order-number');

        }

        function deleteStudent(th) {
            $(th).closest('tr').remove();
        }

        function change_stt(cl) {
            let _i = 0;
            $('.' + cl).each(function() {
                _i++;
                $(this).html(_i);
            });
        }
    </script>
@endsection
