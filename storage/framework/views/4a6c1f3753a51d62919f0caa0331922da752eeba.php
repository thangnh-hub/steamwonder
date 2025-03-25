

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .background-warning-yellow {
            background: #f9e7a2;
        }

        .font-weight-bold {
            font-weight: bold;
            font-size: 16px
        }

        th {
            text-align: center;
            vertical-align: middle !important;
        }

        #alert-config {
            width: auto !important;
        }
        .ml-3{
            margin-left: 3rem !important;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content">
        
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="<?php echo e(route('report.attendance.bymonth')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Tháng'); ?> </label>
                                <input type="month" class="form-control" name="month"
                                    value="<?php echo e(isset($params['month']) ? $params['month'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Học viên'); ?></label>
                                <input type="text" name="keyword" class="form-control"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Số buổi vắng'); ?></label>
                                <input type="number" name="absent" class="form-control"
                                    value="<?php echo e(isset($params['absent']) ? $params['absent'] : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Số buổi muộn'); ?></label>
                                <input type="number" name="late" class="form-control"
                                    value="<?php echo e(isset($params['late']) ? $params['late'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Lớp'); ?></label>
                                <select name="class_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['class_id']) && $params['class_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Khu vực'); ?></label>
                                <select name="area_id[]" id="" class="form-control select2" multiple
                                    style="width: 100%;" aria-placeholder="Chọn khu vực">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['area_id']) && in_array($item->id, $params['area_id']) ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route('report.attendance.bymonth')); ?>">
                                        <?php echo app('translator')->get('Reset'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        
        <?php
        $count_row = 0;
            $collection = collect();
            $collection = $rows->filter(function ($item) use ($params) {
                $late = $absent = $both = $all = false;
                // Lấy ra muộn
                $late =
                    isset($params['late']) &&
                    $params['late'] > 0 &&
                    $item->late_count == $params['late'];
                // Lấy ra vắng
                $absent =
                    isset($params['absent']) &&
                    $params['absent'] > 0 &&
                    $item->absent_count == $params['absent'];
                // Lấy ra khi vừa vắng + muộn
                $both =
                    isset($params['late']) &&
                    $params['late'] > 0 &&
                    isset($params['absent']) &&
                    $params['absent'] > 0;
                // Lấy ra khi không lọc
                $all = !isset($params['late']) && !isset($params['absent']);

                if ($both) {
                    return $late && $absent;
                }

                return $late || $absent || $all;
            });
        ?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo app('translator')->get('List'); ?></h3>
                <h3 class="pull-right box-title ml-3">Tổng số đi muộn: <?php echo e($collection->sum('late_count')); ?></h3>
                <h3 class="pull-right box-title ">Tổng số vắng mặt: <?php echo e($collection->sum('absent_count')); ?></h3>
            </div>
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
                <?php if(count($rows) == 0): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo app('translator')->get('not_found'); ?>
                    </div>
                <?php else: ?>
                    <table class="table  table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('Stt'); ?></th>
                                <th><?php echo app('translator')->get('Mã học viên'); ?></th>
                                <th><?php echo app('translator')->get('Tên học viên'); ?></th>
                                <th><?php echo app('translator')->get('Mã CBTS'); ?></th>
                                <th><?php echo app('translator')->get('Khu vực'); ?></th>
                                <th><?php echo app('translator')->get('Tháng'); ?></th>
                                <th><?php echo app('translator')->get('Có mặt'); ?></th>
                                <th><?php echo app('translator')->get('Vắng mặt'); ?></th>
                                <th><?php echo app('translator')->get('Đi muộn'); ?></th>
                            </tr>

                        </thead>
                        <tbody>
                           
                            <?php $__currentLoopData = $collection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="">
                                    <td><?php echo e($loop->index + 1); ?></td>
                                    <td><?php echo e($item->student->admin_code ?? ''); ?></td>
                                    <td><?php echo e($item->student->name ?? ''); ?></td>
                                    <td><?php echo e($item->student->admission->admin_code ?? ''); ?></td>
                                    <td><?php echo e($item->schedule->area->name ?? ''); ?></td>
                                    <td>
                                        Tháng <?php echo e($item->month ?? ''); ?> - <?php echo e($item->year ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($item->attendant_count ?? 0); ?>

                                    </td>
                                    <td
                                        class="<?php echo e($item->absent_count >= 3 ? 'background-warning-yellow font-weight-bold' : ''); ?>">
                                        <?php echo e($item->absent_count ?? 0); ?>


                                        <button name-student="<?php echo e($item->student->admin_code ?? ''); ?> - <?php echo e($item->student->name ?? ''); ?>"
                                            data-student="<?php echo e($item->user_id); ?>" data-month="<?php echo e($item->month ?? ''); ?>"
                                            data-year="<?php echo e($item->year ?? ''); ?>" data-status="absent"
                                            data-toggle="modal"data-target=".bd-example-modal-lg" style="margin-left: 20px"
                                            type="button" class="btn btn-primary view-user btn-sm"><i
                                                class="fa fa-eye"></i> Xem
                                            chi tiết vắng mặt
                                        </button>
                                        <?php if($item->absent_count > 0): ?>
                                            <?php
                                                $params['user_id'] = $item->user_id;
                                                $params['month'] = $item->month ?? '';
                                                $params['year'] = $item->year ?? '';
                                                $params['status'] = 'absent';
                                                $list_absent = App\Models\Attendance::getSqlAttendance($params)
                                                    ->groupBy('class_id')
                                                    ->orderBy('date', 'asc')
                                                    ->get();
                                                $string_absent = '';
                                                foreach ($list_absent as $value) {
                                                    $string_absent .= ', ' . date('d/m', strtotime($value->date));
                                                }
                                                $string_absent = trim($string_absent, ', ');
                                            ?>
                                            <span style="font-size: 14px">( <?php echo e($string_absent); ?>)</span>
                                        <?php endif; ?>
                                    </td>

                                    <td
                                        class="<?php echo e($item->late_count >= 3 ? 'background-warning-yellow font-weight-bold' : ''); ?>">
                                        <?php echo e($item->late_count ?? 0); ?>


                                        <button name-student="<?php echo e($item->student->admin_code ?? ''); ?> - <?php echo e($item->student->name ?? ''); ?>"
                                            data-student="<?php echo e($item->user_id); ?>" data-month="<?php echo e($item->month ?? ''); ?>"
                                            data-year="<?php echo e($item->year ?? ''); ?>" data-status="late"
                                            data-toggle="modal"data-target=".bd-example-modal-lg"
                                            style="margin-left: 20px" type="button"
                                            class="btn btn-primary view-user btn-sm"><i class="fa fa-eye"></i> Xem
                                            chi tiết đi muộn
                                        </button>
                                        <?php if($item->late_count > 0): ?>
                                            <?php
                                                $params['user_id'] = $item->user_id;
                                                $params['month'] = $item->month ?? '';
                                                $params['year'] = $item->year ?? '';
                                                $params['status'] = 'late';
                                                $list_absent = App\Models\Attendance::getSqlAttendance($params)
                                                    ->groupBy('class_id')
                                                    ->orderBy('date', 'asc')
                                                    ->get();
                                                $string_absent = '';
                                                foreach ($list_absent as $value) {
                                                    $string_absent .= ', ' . date('d/m', strtotime($value->date));
                                                }
                                                $string_absent = trim($string_absent, ', ');
                                            ?>
                                            <span style="font-size: 14px">(<?php echo e($string_absent); ?>)</span>
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div class="box-footer clearfix">
                <div class="row">
                    
                    
                </div>
            </div>

        </div>
    </section>
    <div class="modal fade bd-example-modal-lg "data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full">
            <div id="alert-config">
            </div>
            <div class="modal-content">

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
                                            <th>Ngày</th>
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

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        function updateCheckboxValue(checkbox) {
            if (checkbox.checked) {
                checkbox.value = 1;
            } else {
                checkbox.value = 0;
            }
        }

        function updateAjax(th) {
            let _id = $(th).attr('data-id');
            var _note = $(th).parents('tr').find('.note').val();
            var _is_contact_to_parents = $(th).parents('tr').find('.is_contact_to_parents').val();
            var _parents_method = $(th).parents('tr').find('.parents_method').val();
            let url = "<?php echo e(route('ajax.update.note')); ?>/";
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
                    $("#alert-config").append(
                        '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Đã lưu cập nhật</div>'
                    );
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
            var _name_student = $(this).attr('name-student');
            var _student_id = $(this).attr('data-student');
            var _status = $(this).attr('data-status');
            var _month = $(this).attr('data-month');
            var _year = $(this).attr('data-year');
            let _url = "<?php echo e(route('ajax.report.attendance.byday')); ?>";
            var _html = $('.show-user');
            var _content = "";
            $.ajax({
                type: "GET",
                url: _url,
                data: {
                    "user_id": _student_id,
                    "month": _month,
                    "year": _year,
                    "status": _status,
                },
                dataType: 'JSON',
                success: function(response) {
                    $('#myModalLabel').text(_name_student);
                    _list = response.data;
                    if (_list.length > 0) {
                        var i = 1;
                        _list.forEach(it => {
                            _content += `<tr class="valign-middle">
                                            <td>${i}</td>
                                            <td><a target="_blank" href="${it.link_student}"><strong style="font-size: 14px;">${it.date}</strong></a></td>
                                            <td><a target="_blank" href="${it.link_class}">${it.class.name}</a></td>
                                            <td>${it.status}</td>
                                            <td>${it.resson} ${(it.status=="đi muộn")?"phút":""}</td>
                                            <td>${it.note_teacher!=null ? it.note_teacher : ""}</td>

                                            <td><a target="_blank" href="${it.link_attendance}">Link điểm danh</a></td>
                                            <td><input type="checkbox" ${(it.is_contact_to_parents=="1")?"checked":""} class="is_contact_to_parents" value="${it.is_contact_to_parents}"  onchange="updateCheckboxValue(this) "></td>
                                            <td>
                                                <select class="form-control parents_method" >
                                                    <?php $__currentLoopData = App\Consts::CONTACT_PARENTS_METHOD; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key); ?>" ${(it.parents_method=="<?php echo e($key); ?>")?"selected":""}>
                                                            <?php echo e(__($item)); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                        <td colspan='10'>Không có bản ghi phù hợp</td>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\dwn\resources\views/admin/pages/reports/attendacebymonth.blade.php ENDPATH**/ ?>