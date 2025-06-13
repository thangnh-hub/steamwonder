@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .table>thead>tr>th,
        .table>tbody>tr>td {
            vertical-align: middle;
        }

        .h-25 {
            height: 25px;
        }

        input[type="radio"] {
            transform: scale(1.5);
        }

        .box_radio {
            margin-bottom: 0px
        }

        .radiobox {
            margin-top: 0px !important
        }


        .bg-danger {
            background-color: #dc3545 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }

        .text-secondary {
            color: #6c757d !important;
        }

        .text-success {
            color: #28a745 !important;
        }

        .item_day {
            font-size: 20px;
        }

        .item_day:hover.text-secondary {
            opacity: 1;
        }

        .item_day.text-secondary {
            opacity: 0.4;
        }

        .attendance_arrival {
            border-right: 1px solid #6c757d
        }

        .box_image {
            position: relative;
        }

        .box_capture {
            font-size: 60px;
            position: absolute;
            top: calc(50% - 42px);
            left: calc(50% - 32px);
            opacity: 0;
            cursor: pointer;
            z-index: 1;
        }

        .box_capture:hover {
            opacity: 0.5;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-around {
            justify-content: space-around;
        }

        .align-items-center {
            align-items: center
        }

        .box_content {
            width: calc(100% - 210px);
        }

        .select2-container {
            width: 100% !important;
        }

        .photo {
            width: 180px;
            height: 180px;
            padding: 8px;
        }

        #modal_attendance .modal-dialog {
            width: 70%;
        }

        .camera-container {
            position: relative;
            width: 100%;
            height: auto;
            overflow: hidden;
        }

        #video {
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .controls {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            display: flex;
            gap: 10px;
        }

        .div_h {
            height: 25px;
            margin-bottom: 10px
        }



        @media (max-width: 768px) {

            #modal_attendance .modal-dialog {
                width: calc(100% - 20px);
                max-height: calc(100vh - 20px);
                overflow-y: auto;
            }

            .box_checked {
                width: 100%;
                margin-bottom: 15px
            }

            .box_image,
            .box_content {
                width: 100%;
            }

            .attendance_arrival {
                border-right: none;
            }

            .div_h {
                display: none;
            }

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
        <div id="loading-notification" class="loading-notification">
            <p>@lang('Please wait')...</p>
        </div>
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route(Request::segment(2) . '.studentMeal') }}" method="GET">
                <div class="box-body">
                    <div class="d-flex-wap">
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" class="area_id form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($areas as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <label>@lang('Lớp') <small class="text-red">*</small></label>
                                <select required name="class_id" class="class_id form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($classs as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['class_id']) && $params['class_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->code ?? '' }} - {{ $item->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <label>@lang('Tháng') <small class="text-red">*</small></label>
                                <input type="month" name="month" class="form-control month" required
                                    value="{{ isset($params['month']) && $params['month'] != '' ? $params['month'] : date('Y-m', time()) }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Lọc theo mã học viên, họ tên hoặc email')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <label>@lang('Lấy điểm danh')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm"
                                        href="{{ route(Request::segment(2) . '.studentMeal') }}">
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
                {{-- <h3 class="box-title">@lang('List')</h3> --}}
            </div>
            <div class="box-body box_alert">
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
                    <div class="table-responsive">
                        <div class="mb-15">
                            <b>Lưu ý:</b> - Báo lịch ăn cho học sinh ngày hôm sau phải trước 15h ngày hôm nay
                            <br>
                            - <span class="text-success"><i class='fa fa-check-circle-o' aria-hidden='true'></i></span> là
                            có ăn, <span class="text-danger"><i class='fa fa-ban' aria-hidden='true'></i></span> là không ăn
                            <br>
                            - Trường hợp không ăn sẽ được tính là nghỉ có phép
                        </div>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center">@lang('STT')</th>
                                    <th rowspan="2" class="text-center">@lang('Thông tin học sinh')</th>
                                    @for ($i = 1; $i <= $daysInMonth; $i++)
                                        <th
                                            class="text-center {{ $carbonDate->copy()->day($i)->dayOfWeek == 0 ? 'bg-danger' : ($carbonDate->copy()->day($i)->dayOfWeek == 6 ? 'bg-warning' : '') }}">
                                            {{ $day_week[$carbonDate->copy()->day($i)->dayOfWeek] ?? 'CN' }}
                                        </th>
                                    @endfor
                                </tr>
                                <tr>
                                    @for ($i = 1; $i <= $daysInMonth; $i++)
                                        <th
                                            class="text-center {{ $carbonDate->copy()->day($i)->dayOfWeek == 0 ? 'bg-danger' : ($carbonDate->copy()->day($i)->dayOfWeek == 6 ? 'bg-warning' : '') }}">
                                            {{ $i }}
                                        </th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $row)
                                    <tr>
                                        <td class="text-center">{{ $loop->index + 1 }}</td>
                                        <td class="name_student">{{ $row->student->first_name }}
                                            {{ $row->student->last_name }}
                                            {{ $row->student->nickname != '' ? '(' . $row->student->nickname . ')' : '' }}
                                        </td>
                                        @for ($i = 1; $i <= $daysInMonth; $i++)
                                            <td class="text-center box-item">
                                                @if ($carbonDate->copy()->day($i)->dayOfWeek != 0)
                                                    <div
                                                        class="item_day {{ isset($row->student_meal[$i]) ? ($row->student_meal[$i]->status == 'active' ? 'text-success' : 'text-danger') : 'text-secondary' }}">
                                                        <i class="{{ isset($row->student_meal[$i]) ? ($row->student_meal[$i]->status == 'active' ? 'fa fa-check-circle-o' : 'fa fa-ban') : 'fa fa-window-minimize' }} "
                                                            aria-hidden="true"></i>
                                                    </div>
                                                    @if ($carbonDate->copy()->day($i) == $tomorrow && $carbonDate->isSameMonth(now()))
                                                        <div class="box-actions" data-class="{{ $row->class_id }}"
                                                            data-student="{{ $row->student_id }}"
                                                            data-date="{{ $carbonDate->copy()->day($i)->format('Y-m-d') }}">
                                                            <button class="btn btn-success btn-sm btn_change_meal"
                                                                data-status = "active" data-toggle="tooltip"
                                                                data-original-title="@lang('Có ăn')">
                                                                <i class="fa fa-check-circle-o"></i>
                                                            </button>
                                                            <button class="btn btn-danger btn-sm btn_change_meal"
                                                                data-status = "deactive" data-toggle="tooltip"
                                                                data-original-title="@lang('Không ăn')">
                                                                <i class="fa fa-ban"></i></button>
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        var areas = @json($areas ?? []);
        var classs = @json($classs ?? []);
        $(document).ready(function() {
            $('.area_id').change(function() {
                var area_id = $(this).val();
                var _html = `<option value="">{{ __('Please select') }}</option>`;
                if (area_id) {
                    _html += classs
                        .filter(item => item.area_id == area_id)
                        .map(item => `<option value="${item.id}">${item.code} - ${item.name}</option>`)
                        .join('');
                }
                $('.class_id').html(_html).trigger('change');
            })

            $('.btn_change_meal').click(function() {
                var status = $(this).data('status');
                var parents = $(this).closest('.box-actions');
                var student_id = parents.data('student');
                var class_id = parents.data('class');
                var meal_day = parents.data('date');
                var item_day = parents.closest('.box-item').find('.item_day');
                var statusClasses = {
                    active: {
                        add: 'text-success',
                        remove: 'text-danger text-secondary',
                        icon: "<i class='fa fa-check-circle-o' aria-hidden='true'></i>"
                    },
                    deactive: {
                        add: 'text-danger',
                        remove: 'text-success text-secondary',
                        icon: "<i class='fa fa-ban' aria-hidden='true'></i>"
                    }
                };
                $.ajax({
                    type: "POST",
                    url: "{{ route('attendance.save_studentMeal') }}",
                    data: {
                        'status': status,
                        'student_id': student_id,
                        'class_id': class_id,
                        'meal_day': meal_day,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.data != null) {
                            if (response.data == 'success') {
                                if (statusClasses[status]) {
                                    var update = statusClasses[status];
                                    item_day.removeClass(update.remove).addClass(update.add)
                                        .html(
                                            update.icon);
                                }
                            }

                            var _html = `<div class="alert alert-${response.data} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ${response.message}
                            </div>`;
                            $('.box_alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert").fadeOut(3000,
                                    function() {});
                            }, 800);

                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Bạn không có quyền thao tác chức năng này!
                        </div>`;
                            $('.box_alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert-warning").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert-warning").fadeOut(3000,
                                    function() {});
                            }, 800);
                        }
                    },
                    error: function(response) {
                        var errors = response.responseJSON.message;
                        console.log(errors);
                    }
                });
            })
        });
    </script>
@endsection
