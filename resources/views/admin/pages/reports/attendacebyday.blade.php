@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        th {
            text-align: center;
            vertical-align: middle !important;
        }
        #alert-config{
            width: auto !important;
        }
    </style>
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>
@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('report.attendance.byday') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Ngày') </label>
                                <input type="date" class="form-control" name="date" placeholder="@lang('Nhập tên lớp')"
                                    value="{{ isset($params['date']) ? $params['date'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Lớp')</label>
                                <select name="class_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($class as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['class_id']) && $params['class_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Giáo viên')</label>
                                <select name="teacher_id" id="" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($teacher as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['teacher_id']) && $params['teacher_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Khu vực')</label>
                                <select name="area_id" id="" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($area as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Trạng thái điểm danh')</label>
                                <select name="status" class="form-control select2" style="width: 100%;">
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
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('report.attendance.byday') }}">
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
                <a target="_blank" href="{{ route('report.all.attendance.byday') }}">
                    <button type="button" class="btn btn-warning  pull-right"><i class="fa fa-eye"></i> Xem toàn bộ học viên vắng muộn</button>
                </a>
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
                                <th>@lang('Stt')</th>
                                <th>@lang('Ngày')</th>
                                <th>@lang('Ca học')</th>
                                <th>@lang('Lớp')</th>
                                <th>@lang('Giáo viên')</th>
                                <th>@lang('Khu vực')</th>
                                <th>@lang('Sĩ số')</th>
                                <th>@lang('Có mặt')</th>
                                <th>@lang('Vắng mặt')</th>
                                <th>@lang('Đi muộn')</th>
                            </tr>

                        </thead>
                        <tbody>

                            @foreach ($rows as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        {{-- {{ App\Consts::DAY_WEEK[date('N', strtotime($item->date))] }},  --}}
                                        {{ date('d-m-Y', strtotime($item->date)) }}</td>
                                    <td>Ca {{ $item->period->iorder ?? '' }} ( {{ $item->period->start_time ?? '' }} -
                                        {{ $item->period->end_time ?? '' }})</td>
                                    <td>Lớp {{ $item->class->name ?? '' }}</td>
                                    <td>{{ $item->teacher->name ?? '' }}</td>
                                    <td>{{ $item->area->name ?? '' }}</td>
                                    <td>
                                        {{ $item->quantity_student ?? '' }}
                                    </td>
                                    <td>
                                        @if ($item->status == 'chuahoc')
                                            Chưa điểm danh
                                        @else
                                            {{ $item->total_attendant }} Học viên
                                        @endif
                                    </td>
                                    <td>
                                        {{ $item->total_absent ?? '' }} Học viên
                                        <button data-schedule-id="{{ $item->id }}" data-status="absent"
                                            data-toggle="modal"data-target=".bd-example-modal-lg" style="margin-left: 20px"
                                            type="button" class="btn btn-primary view-user"><i class="fa fa-eye"></i> Xem
                                            DS vắng mặt
                                        </button>

                                    </td>
                                    <td>
                                        {{ $item->total_late ?? '' }} Học viên
                                        <button data-schedule-id="{{ $item->id }}" data-status="late"
                                            data-toggle="modal" data-target=".bd-example-modal-lg" style="margin-left: 20px"
                                            type="button" class="btn btn-primary view-user"><i class="fa fa-eye"></i> Xem
                                            DS đi muộn
                                        </button>
                                    </td>
                                </tr>
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
    <div class="modal fade bd-example-modal-lg "data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div id="alert-config"></div>
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            Danh sách học viên
                        </h4>
                    </div>

                    <form action="" method="POST" class="form-ajax-lesson">
                        <div class="modal-body modal-body-add-leson">
                            <div class="box-body table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Mã học viên</th>
                                            <th>Tên</th>
                                            <th>Lớp</th>
                                            <th>Trạng thái</th>
                                            <th>Ghi chú trạng thái</th>
                                            <th>Ghi chú GV</th>
                                            <th>Link điểm danh</th>
                                            <th>Đã báo phụ huynh</th>
                                            <th>Hình thức thông báo</th>
                                            <th>Ghi chú (Phòng đào tạo)</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody class="show-user">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Đóng
                            </button>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('script')
    <script>
       function updateCheckboxValue(checkbox) {
            if (checkbox.checked) {
                checkbox.value = 1;
            } else {
                checkbox.value = 0;
            }
        }
        function updateAjax(th){
            let _id = $(th).attr('data-id');
            var _note=$(th).parents('tr').find('.note').val();
            var _is_contact_to_parents=$(th).parents('tr').find('.is_contact_to_parents').val();
            var _parents_method=$(th).parents('tr').find('.parents_method').val();
            let url = "{{ route('ajax.update.note') }}/";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                    note: _note,
                    is_contact_to_parents: _is_contact_to_parents,
                    parents_method: _parents_method,
                },
                success: function(response) {
                    $("#alert-config").append('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Đã lưu cập nhật</div>');
                    setTimeout(function() {
                        $(".alert-success").fadeOut(2000, function() {});
                    }, 800);
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }
        $('.view-user').click(function(e) {
            e.preventDefault();
            var _schedule_id = $(this).attr('data-schedule-id');
            var _status = $(this).attr('data-status');
            let _url = "{{ route('ajax.report.attendance.byday') }}";
            var _html = $('.show-user');
            var _content = "";
            $.ajax({
                type: "GET",
                url: _url,
                data: {
                    "schedule_id": _schedule_id,
                    "status": _status,
                },
                dataType: 'JSON',
                success: function(response) {
                    _list = response.data;
                    if (_list.length > 0) {
                        var i = 1;
                        _list.forEach(it => {
                            _content += `<tr class="valign-middle">
                                            <td>${i}</td>
                                            <td><a target="_blank" href="${it.link_student}">${it.student.admin_code}</a></td>
                                            <td><a target="_blank" href="${it.link_student}"><strong style="font-size: 14px;">${it.student.name}</strong></a></td>
                                            <td><a target="_blank" href="${it.link_class}">${it.class.name}</a></td>
                                            <td>${it.status}</td>
                                            <td>${it.resson} ${(it.status=="đi muộn")?"phút":""}</td>
                                            <td>${it.note_teacher!=null ? it.note_teacher : ""}</td>
                                            <td><a target="_blank" href="${it.link_attendance}">Link điểm danh</a></td>
                                            <td><input type="checkbox" ${(it.is_contact_to_parents=="1")?"checked":""} class="is_contact_to_parents" value="${it.is_contact_to_parents}"  onchange="updateCheckboxValue(this) "></td>
                                            <td>
                                                <select class="form-control parents_method" >
                                                    @foreach (App\Consts::CONTACT_PARENTS_METHOD as $key => $item)
                                                        <option value="{{ $key }}" ${(it.parents_method=="{{ $key }}")?"selected":""}>
                                                            {{ __($item) }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            
                                            <td>
                                                <div class="input-group">
                                                    <input  type="text" class="form-control note" value="${it.note}" >
                                                    <span data-id="${it.id}" onclick="updateAjax(this)" class="input-group-btn ">
                                                        <a class="btn btn-primary">Lưu </a>
                                                    </span></td>
                                                </div>
                                            </td>
                                            
                                        </tr>`;
                            i++;
                        });
                        _html.html(_content);
                    } else {
                        _content = `<tr>
                                        <td colspan='11'>Không có bản ghi phù hợp</td>
                                    </tr>`;
                        _html.html(_content);
                    }
                },
                error: function(response) {
                    // Get errors
                    console.log(response);
                    var errors = response.responseJSON.errors;
                    // Foreach and show errors to htmluu
                    var elementErrors = '';
                    $.each(errors, function(index, item) {
                        if (item === 'CSRF token mismatch.') {
                            item = translations.csrf_mismatch;
                        }
                        elementErrors += '<p>' + item + '</p>';
                    });
                    $('.error-container').html(
                        elementErrors); // Assuming you have a container to display errors
                }
            });
        });
    </script>
@endsection
