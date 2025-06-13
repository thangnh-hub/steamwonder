

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box">
            <div class="box-body table-responsive">
                <?php if(session('errorMessage')): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo e(session('errorMessage')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('successMessage')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo e(session('successMessage')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><?php echo e($error); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                <?php endif; ?>

                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="month"><?php echo app('translator')->get('Chọn khu vực'); ?></label>
                                <select class="form-control select2" name="area_id">
                                    <option value=""><?php echo app('translator')->get('Chọn'); ?></option>
                                    <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($area->id); ?>" <?php echo e($selected_area_id == $area->id ? 'selected' : ''); ?>>
                                            <?php echo e($area->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Chức năng'); ?></label>
                                <div style="display:flex;jsutify-content:space-between;">
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <?php if($selected_area_id): ?>
                <div id="calendar"></div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <?php echo app('translator')->get('Vui lòng chọn khu vực để xem lịch ăn uống.'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo app('translator')->get('Chi tiết suất ăn theo lớp'); ?></h4>
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

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'vi',
                initialDate: '<?php echo e($month); ?>-01', // ví dụ: 2025-06
                firstDay: 1, // bắt đầu từ Thứ 2
                // hiddenDays: [0, 6], 
                dayHeaderFormat: { weekday: 'long' }, // Hiện đầy đủ: "Thứ Hai", "Thứ Ba"
                events: <?php echo json_encode($calendarEvents, 15, 512) ?>,
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
                            url: '<?php echo e(route("admin.calendar.getAttendanceDetail")); ?>',
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/meal/menu_dailys/calendar_by_month.blade.php ENDPATH**/ ?>