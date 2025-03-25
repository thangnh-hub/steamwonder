

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        th {
            text-align: center;
            vertical-align: middle !important;
        }

        .table>tbody>tr>td {
            text-align: center;
            vertical-align: inherit;
        }

        .table>tbody>tr>td.text_left {
            text-align: left;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .btn_active,
        .btn_warning,
        .btn_deactive {
            background-color: #eeeeee;
            border-color: #878787;
            color: #000;
        }

        .btn_deactive.active {
            background-color: #dd4b39;
            border-color: #d73925;
            color: #fff;
        }

        .btn_active.active {
            background-color: #00a65a;
            border-color: #008d4c;
            color: #fff;
        }

        .btn_warning.active {
            background-color: #f39c12;
            border-color: #e08e0b;
            color: #fff;
        }

        .mb-2 {
            margin-bottom: 1.5rem;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }
?>
<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>

    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div id="loading-notification" class="loading-notification">
        <p><?php echo app('translator')->get('Please wait'); ?>...</p>
    </div>
    <!-- Main content -->
    <section class="content">
        
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="<?php echo e(route('book_distribution.eligible_students')); ?>" id="form_filter" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Lọc theo mã học viên, họ tên hoặc email'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Trình độ đang học'); ?></label>
                                <select name="level_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['level_id']) && $params['level_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Class'); ?></label>
                                <select name="class_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $classs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value->id); ?>"
                                            <?php echo e(isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : ''); ?>>
                                            <?php echo e(__($value->name)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Area'); ?></label>
                                <select name="area_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : ''); ?>>
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
                                    <a class="btn btn-default btn-sm mr-10"
                                        href="<?php echo e(route('book_distribution.eligible_students')); ?>">
                                        <?php echo app('translator')->get('Reset'); ?>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-success btn_export"
                                        data-url="<?php echo e(route('book_distribution.export_eligible_students')); ?>"><i
                                            class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        <?php echo app('translator')->get('Export'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo app('translator')->get('Danh sách học viên đã được thêm vào lớp học và chưa nhận sách'); ?></h3>
            </div>
            <div class="box-body box_alert">
                <?php if(session('errorMessage')): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="false">&times;</button>
                        <?php echo session('errorMessage'); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('successMessage')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="false">&times;</button>
                        <?php echo session('successMessage'); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="false">&times;</button>

                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><?php echo e($error); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                <?php endif; ?>
                <?php if(count($students) == 0): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="false">&times;</button>
                        <?php echo app('translator')->get('not_found'); ?>
                    </div>
                <?php else: ?>
                    <table class="table table-hover table-bordered sticky">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('STT'); ?></th>
                                <th><?php echo app('translator')->get('Mã học viên'); ?></th>
                                <th><?php echo app('translator')->get('Họ và tên'); ?></th>
                                <th><?php echo app('translator')->get('CB tuyển sinh'); ?></th>
                                <th><?php echo app('translator')->get('Loại hợp đồng'); ?></th>
                                <th><?php echo app('translator')->get('Khu vực'); ?></th>
                                <th><?php echo app('translator')->get('Trình độ'); ?></th>
                                <th><?php echo app('translator')->get('Lớp đã học'); ?></th>
                                <th><?php echo app('translator')->get('Ngày vào lớp'); ?></th>
                                <th><?php echo app('translator')->get('Sách đã lấy'); ?></th>
                                <th><?php echo app('translator')->get('Sách chưa lấy'); ?></th>
                                <th><?php echo app('translator')->get('Các GD nộp tiền'); ?></th>
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->index + 1); ?></td>
                                    <td><?php echo e($row->student->admin_code); ?></td>
                                    <td><?php echo e($row->student->name ?? ''); ?> </td>
                                    <td><?php echo e($row->student->admission->admin_code ?? ''); ?> </td>
                                    <td><?php echo e($row->student->json_params->contract_type ?? ''); ?></td>
                                    <td><?php echo e($row->student->area->code ?? ''); ?></td>
                                    <td><?php echo e($row->level->name ?? ''); ?></td>
                                    <td class="text_left">
                                        <?php if(isset($row->student->classs)): ?>
                                            <?php
                                                $day_in_class = '';
                                            ?>
                                            <ul>
                                                <?php $__currentLoopData = $row->student->classs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="<?php echo e($row->class_id == $val->id ? 'font-weight-bold' : ''); ?>">
                                                        <?php echo e($val->name); ?>

                                                        (<?php echo e(__($val->pivot->status)); ?>)
                                                    </li>
                                                    <?php
                                                        if (
                                                            $row->class_id == $val->id &&
                                                            isset($val->pivot->json_params)
                                                        ) {
                                                            $day_in_class =
                                                                json_decode($val->pivot->json_params)->day_in_class ??
                                                                '';
                                                        }
                                                    ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo e($day_in_class != '' ? \Carbon\Carbon::parse($day_in_class)->format('d/m/Y') : ''); ?>

                                    </td>
                                    <td class="text_left">
                                        <?php if(isset($row->student->history_book_active)): ?>
                                            <ul>
                                                <?php $__currentLoopData = $row->student->history_book_active; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $his): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($his->product->name ?? ''); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php endif; ?>
                                        
                                        <?php if(isset($row->student->json_params->note_book_history)): ?>
                                            <span class="text-bold text-danger"><?php echo e($row->student->json_params->note_book_history); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo e($row->product->name ?? ''); ?>


                                    </td>

                                    <td class="text_left">
                                        <?php if(isset($row->student->AccountingDebt)): ?>
                                            <ul>
                                                <?php $__currentLoopData = $row->student->AccountingDebt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e(__($item->type_revenue)); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button
                                            class=" mb-2 btn btn_change btn_active <?php echo e($row->status == $status_book_distribution_student['dudieukien'] ? 'active' : ''); ?>"
                                            data-id="<?php echo e($row->id); ?>"
                                            data-origin=<?php echo e($status_book_distribution_student['dudieukien']); ?>

                                            data-status="<?php echo e($row->status == $status_book_distribution_student['dudieukien'] ? null : $status_book_distribution_student['dudieukien']); ?>">
                                            <input type="checkbox"
                                                <?php echo e($row->status == $status_book_distribution_student['dudieukien'] ? 'checked' : ''); ?>

                                                class="input_checkbox" style="pointer-events: none;">
                                            <span class="txt_btn"><?php echo app('translator')->get('ĐỦ điều kiện phát sách'); ?></span>
                                        </button>
                                        </br>
                                        <button
                                            class=" mb-2 btn btn_change btn_deactive <?php echo e($row->status == $status_book_distribution_student['khongdudieukien'] ? 'active' : ''); ?>"
                                            data-id="<?php echo e($row->id); ?>"
                                            data-origin=<?php echo e($status_book_distribution_student['khongdudieukien']); ?>

                                            data-status="<?php echo e($row->status == $status_book_distribution_student['khongdudieukien'] ? null : $status_book_distribution_student['khongdudieukien']); ?>">
                                            <input type="checkbox"
                                                <?php echo e($row->status == $status_book_distribution_student['khongdudieukien'] ? 'checked' : ''); ?>

                                                class="input_checkbox" style="pointer-events: none;">
                                            <span class="txt_btn"><?php echo app('translator')->get('KHÔNG đủ điều kiện phát sách'); ?></span>
                                        </button>
                                        </br>
                                        <button
                                            class=" mb-2 btn btn_change btn_warning <?php echo e($row->status == $status_book_distribution_student['danhansach'] ? 'active' : ''); ?>"
                                            data-id="<?php echo e($row->id); ?>"
                                            data-origin=<?php echo e($status_book_distribution_student['danhansach']); ?>

                                            data-status="<?php echo e($row->status == $status_book_distribution_student['danhansach'] ? null : $status_book_distribution_student['danhansach']); ?>">
                                            <input type="checkbox"
                                                <?php echo e($row->status == $status_book_distribution_student['danhansach'] ? 'checked' : ''); ?>

                                                class="input_checkbox" style="pointer-events: none;">
                                            <span class="txt_btn"><?php echo app('translator')->get('Đã nhận sách'); ?></span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        <div class="row">
                            <div class="col-sm-5">
                                Tìm thấy <?php echo e($students->total()); ?> kết quả
                            </div>
                            <div class="col-sm-7">
                                <?php echo e($students->withQueryString()->links('admin.pagination.default')); ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {
            $('.btn_export').click(function() {
                var formData = $('#form_filter').serialize();
                var url = $(this).data('url');
                show_loading_notification()
                $.ajax({
                    url: url,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    data: formData,
                    success: function(response) {

                        if (response) {
                            var a = document.createElement('a');
                            var url = window.URL.createObjectURL(response);
                            a.href = url;
                            a.download = 'Danh sách học viên đủ điều kiện.xlsx';
                            document.body.append(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);
                            hide_loading_notification()
                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                            $('.box_alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $('.alert').remove();
                            }, 3000);
                            hide_loading_notification()
                        }

                    },
                    error: function(response) {
                        hide_loading_notification()
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            })
        });

        $(document).on('click', '.btn_active, .btn_deactive, .btn_warning', function() {
            var _this = $(this);
            var id = _this.data('id');
            var status = _this.attr('data-status');

            // Reset trạng thái của các nút khác trong cùng ô
            _this.parents('td').find('.btn_active, .btn_deactive, .btn_warning')
                .not(_this) // Loại trừ nút hiện tại
                .removeClass('active')
                .each(function() {
                    var button = $(this);
                    if (button.hasClass('btn_active')) {
                        button.attr('data-status', 'dudieukien');
                    } else if (button.hasClass('btn_deactive')) {
                        button.attr('data-status', 'khongdudieukien');
                    } else if (button.hasClass('btn_warning')) {
                        button.attr('data-status', 'daphatsach');
                    }
                });

            // Bỏ chọn checkbox trong cùng ô
            _this.parents('td').find('.input_checkbox').prop('checked', false);
            // Gọi hàm xử lý trạng thái
            change_active_student(_this, id, status);
        });


        function change_active_student(element, id, status) {

            $.ajax({
                type: "POST",
                url: "<?php echo e(route('book_distribution.change_status')); ?>",
                data: {
                    "_token": '<?php echo e(csrf_token()); ?>',
                    'id': id,
                    'status': status,
                },
                success: function(response) {
                    if (response.data != null) {
                        if (element.hasClass('active')) {
                            element.removeClass('active');
                            element.attr('data-status', element.attr('data-origin'));
                            element.find('.input_checkbox').prop('checked', false);
                        } else {
                            element.addClass('active');
                            element.attr('data-status', '');
                            element.find('.input_checkbox').prop('checked', true);
                        }

                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="false">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.box_alert').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert-warning").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $(".alert-warning").fadeOut(3000, function() {});
                        }, 800);
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\dwn\resources\views/admin/pages/book_distribution/eligible_students.blade.php ENDPATH**/ ?>