<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        th {
            text-align: center;
            vertical-align: middle !important;
        }

        td {
            vertical-align: middle !important;
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div id="loading-notification" class="loading-notification">
        <p><?php echo app('translator')->get('Please wait'); ?>...</p>
    </div>
    <!-- Main content -->
    <section class="content">
        
        <div class="box box-default hide-print">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="<?php echo e(route('book_distribution.list_book_distribution_student')); ?>" id="form_filter" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Kỳ'); ?></label>
                                <input type="month" class="form-control" name="period"
                                    value="<?php echo e($params['period'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm mr-10"
                                        href="<?php echo e(route('book_distribution.list_book_distribution_student')); ?>">
                                        <?php echo app('translator')->get('Reset'); ?>
                                    </a>
                                    <button class="btn btn-sm btn-warning mr-10" onclick="window.print()"><i
                                            class="fa fa-print"></i>
                                        <?php echo app('translator')->get('In danh sách'); ?></button>
                                    <button type="button" class="btn btn-sm btn-success btn_export"
                                        data-url="<?php echo e(route('book_distribution.export_list_book_distribution_student')); ?>"><i
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
                <h3 class="box-title"><?php echo app('translator')->get($module_name); ?></h3>
            </div>
            <div class="box-body box-alert">
                <?php if(session('errorMessage')): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo session('errorMessage'); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('successMessage')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo session('successMessage'); ?>

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
                    <table class="table table-hover table-bordered sticky">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('STT'); ?></th>
                                <th><?php echo app('translator')->get('Mã HV'); ?></th>
                                <th><?php echo app('translator')->get('Họ tên'); ?></th>
                                <th><?php echo app('translator')->get('khóa học'); ?></th>
                                <th><?php echo app('translator')->get('Lớp'); ?></th>
                                <th><?php echo app('translator')->get('Trình độ'); ?></th>
                                <th><?php echo app('translator')->get('Sách đã phát'); ?></th>
                                <th><?php echo app('translator')->get('Kỳ nhận sách'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                    <td class="text-center"><?php echo e($val->student->admin_code ?? ''); ?></td>
                                    <td class="text-center"><?php echo e($val->student->name ?? ''); ?></td>
                                    <td class="text-center"><?php echo e($val->student->course->name ?? ''); ?></td>
                                    <td class="text-center"><?php echo e($val->class->name); ?></td>
                                    <td class="text-center"><?php echo e($val->level->name ?? ''); ?></td>
                                    <td class="text-center"><?php echo e($val->product->name); ?></td>
                                    <td class="text-center">
                                        <?php echo e(\Carbon\Carbon::parse($params['period'])->format('m/Y') ?? ''); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <div class="box-footer clearfix hide-print">

            </div>
        </div>
    </section>

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
                            a.download = 'Danh sách học viên đã nhận sách.xlsx';
                            document.body.append(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);
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
                        }
                        hide_loading_notification()
                    },
                    error: function(response) {
                        hide_loading_notification()
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            })
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\dwn\resources\views/admin/pages/book_distribution/list_book_distribution_student.blade.php ENDPATH**/ ?>