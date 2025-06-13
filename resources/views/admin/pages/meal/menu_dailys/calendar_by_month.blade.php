@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .modal-header, .fc-header-toolbar {
            background-color: #3c8dbc;
            color: white;
        }
        .table-wrapper {
            max-height: 450px;
            overflow-y: auto;
            display: block;
        }

        .table-wrapper thead {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 2;
        }

        .table-wrapper table {
            border-collapse: separate;
            width: 100%;
        }
        .fc-button {
            background-color: #fff !important;
            color: #3c8dbc !important;
            border: #3c8dbc 1px solid !important;
        }
        .fc-daygrid-day-frame {
            min-height: 120px;
        }

        /* Hiển thị event lớn rõ ràng */
        .fc-daygrid-event {
            cursor: pointer;
            margin: 5px 15px !important;
            font-size: 14px;
            padding: 3px 5px;
            border-radius: 5px;
            line-height: 1.4;
            color: #fff; /* chữ trắng cho rõ */
        }

        /* Xóa dot mặc định nếu có */
        .fc-daygrid-event-dot {
            display: none;
        }
    </style>
@endsection

@section('content-header')
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box">
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

                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="month">@lang('Chọn khu vực')</label>
                                <select class="form-control select2" name="area_id">
                                    <option value="">@lang('Chọn')</option>
                                    @foreach($list_area as $area)
                                        <option value="{{ $area->id }}" {{ $selected_area_id == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Chức năng')</label>
                                <div style="display:flex;jsutify-content:space-between;">
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @if ($selected_area_id)
                <div id="calendar"></div>
                @else
                    <div class="alert alert-warning">
                        @lang('Vui lòng chọn khu vực để xem lịch ăn uống.')
                    </div>
                @endif
            </div>
        </div>
    </section>
    <div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Chi tiết suất ăn theo lớp')</h4>
                </div>
                <div class="modal-body">
                    
                    <!-- Nội dung sẽ load từ AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'vi',
                initialDate: '{{ $month }}-01', // ví dụ: 2025-06
                firstDay: 1, // bắt đầu từ Thứ 2
                hiddenDays: [0, 6], 
                dayHeaderFormat: { weekday: 'long' }, // Hiện đầy đủ: "Thứ Hai", "Thứ Ba"
                events: @json($calendarEvents),
                eventDisplay: 'block',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'today'
                },
                eventClick: function(info) {
                    const mealAgeId = info.event.extendedProps.meal_age_id;
                    const date = info.event.extendedProps.date;
                    const menu_daily_id = info.event.extendedProps.menu_daily_id;

                    if (mealAgeId && date) {
                        $.ajax({
                            url: '{{ route("admin.calendar.getAttendanceDetail") }}',
                            method: 'GET',
                            data: {
                                meal_age_id: mealAgeId,
                                date: date,
                                menu_daily_id: menu_daily_id,
                                area_id: $('select[name="area_id"]').val(),
                            },
                            success: function(response) {
                                $('#attendanceModal .modal-body').html(response.html);
                                $('#attendanceModal').modal('show');
                            },
                            error: function() {
                                alert('Không thể tải dữ liệu.');
                            }
                        });
                    }
                }
            });

            calendar.render();
        });
    </script>
@endsection