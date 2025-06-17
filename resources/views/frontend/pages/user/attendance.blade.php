@extends('frontend.layouts.default')

@section('content')
    <section class="student">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 information box-day">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h4 class="box-title d-flex justify-content-between flex-wrap">
                                <span class="mb-3"><i class="fa fa-calendar-check-o"></i> @lang('Thông tin điểm danh trong ngày')
                                </span>
                                <button class="btn btn-warning text-white"
                                    onclick="showTab(this,'box-month')">@lang('Xem theo tháng')</button>
                                </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group attendance_arrival">
                                        <p class="text-center w-100 font-weight-bold mb-15">@lang('Điểm danh đến')</p>
                                        <div class="d-flex align-items-center">
                                            <div class="box_image text-center">
                                                <img class="photo"
                                                    src="{{ isset($detail->json_params->img) && $detail->json_params->img != '' ? asset($detail->json_params->img) : url('themes/admin/img/no_image.jpg') }}">
                                            </div>
                                            <div class="box_content information">
                                                <div class="form-group">
                                                    <p><strong>@lang('Người đưa'):

                                                        </strong>{{ $detail->checkinParent->first_name ?? '' }}{{ $detail->checkinParent->last_name ?? '' }}
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <p><strong>@lang('Người đón'):
                                                        </strong>{{ $detail->checkinTeacher->name ?? '' }}
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <p><strong>@lang('Ghi chú'):
                                                        </strong>{{ isset($detail->json_params->note) ? $detail->json_params->note : '' }}
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <p><strong>@lang('Thời gian'):
                                                        </strong>{{ isset($detail->checkin_at) && $detail->checkin_at != '' ? Carbon\Carbon::parse($detail->checkin_at)->format('H:i') : '' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group attendance_return">
                                        <p class="text-center w-100 font-weight-bold mb-15">@lang('Điểm danh về')</p>
                                        <div class="d-flex align-items-center">
                                            <div class="box_image text-center">
                                                <img class="photo"
                                                    src="{{ isset($detail->json_params->img_return) && $detail->json_params->img_return != '' ? asset($detail->json_params->img_return) : url('themes/admin/img/no_image.jpg') }}">
                                            </div>
                                            <div class="box_content information">
                                                <div class="form-group">
                                                    <p><strong>@lang('Người đưa'):

                                                        </strong>{{ $detail->checkoutParent->first_name ?? '' }}{{ $detail->checkoutParent->last_name ?? '' }}
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <p><strong>@lang('Người đón'):
                                                        </strong>{{ $detail->checkoutTeacher->name ?? '' }}
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <p><strong>@lang('Ghi chú'):
                                                        </strong>{{ isset($detail->json_params->note_return) ? $detail->json_params->note_return : '' }}
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <p><strong>@lang('Thời gian'):
                                                        </strong>{{ isset($detail->checkout_at) && $detail->checkout_at != '' ? Carbon\Carbon::parse($detail->checkout_at)->format('H:i') : '' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 information box-month" style="display: none;">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h4 class="box-title d-flex justify-content-between flex-wrap">
                                <span class="mb-3"> <i class="fa fa-calendar-check-o"></i> @lang('Thông tin điểm danh theo tháng')
                                </span>
                                <button class="btn btn-warning text-white"
                                    onclick="showTab(this,'box-day')">@lang('Xem hôm nay')</button>
                                </h3>
                        </div>
                        <div class="box-body">
                            <div id="calendar">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-12 information">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h3 class="box-title d-flex justify-content-between">
                                <span> <i class="fa fa-calendar-check-o"></i> @lang('Thống kê điểm danh theo tháng')
                                </span>
                            </h3>
                        </div>
                        <div class="box-body">
                            <div id="calendar">
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
    <script>
        function showTab(th, _class) {
            $(th).parents('.information').hide();
            $('.' + _class).show();

            var calendarEl = document.getElementById('calendar');
            if (!calendarEl) {
                console.error('Phần tử #calendar không tồn tại.');
                return;
            }

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'vi',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    right: 'prev,next',
                    left: 'title',
                },
                events: @json($events),
                eventClick: function(info) {
                    var details = info.event.extendedProps.details || [];
                    var detailsList = details.length > 0 ?
                        details.map(item =>
                            `<li class="list-group-item">${item.student_name} - ${item.status}</li>`).join('') :
                        '<li class="list-group-item">Không có thông tin chi tiết</li>';

                    $('#attendanceDetails').html(detailsList);
                    $('#attendanceModal').modal('show');
                }
            });

            calendar.render();
        }
    </script>
@endsection
