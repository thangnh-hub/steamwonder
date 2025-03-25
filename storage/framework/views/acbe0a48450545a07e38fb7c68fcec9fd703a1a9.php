<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
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
            <form action="<?php echo e(route(Request::segment(2) . '.index')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Lọc theo mã học viên, họ tên hoặc email'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Lớp'); ?></label>
                                <select name="id_class" class="form-control select2 w-100">
                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                    <?php $__currentLoopData = $class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['id_class']) && $params['id_class'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Trình độ'); ?> </label>
                                <select name="id_level" class="form-control select2 w-100">
                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                    <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id ?? ''); ?>"
                                            <?php echo e(isset($params['id_level']) && $params['id_level'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Ngày thi'); ?></label>
                                <input type="date" name="day_exam" class="form-control"
                                    value="<?php echo e(isset($params['day_exam']) ? $params['day_exam'] : ''); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Kỹ năng'); ?></small></label>
                                <select name="skill_test" class="form-control select2 w-100">
                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                    <?php $__currentLoopData = $skill; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>"
                                            <?php echo e(isset($params['skill_test']) && $params['skill_test'] == $val ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                        <?php echo app('translator')->get('Reset'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        

        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo app('translator')->get('List'); ?></h3>


            </div>
            <div class="box-body table-responsive box-alert">
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
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('STT'); ?></th>
                                <th><?php echo app('translator')->get('Mã Hv'); ?></th>
                                <th><?php echo app('translator')->get('Họ tên'); ?></th>
                                <th><?php echo app('translator')->get('Lớp'); ?></th>
                                <th><?php echo app('translator')->get('Trình độ'); ?></th>
                                <th><?php echo app('translator')->get('Ngày thi'); ?></th>
                                <th><?php echo app('translator')->get('Kỹ năng thi'); ?></th>
                                <th><?php echo app('translator')->get('trạng thái'); ?></th>
                                <th><?php echo app('translator')->get('Người chấm'); ?></th>
                                <th><?php echo app('translator')->get('Điểm'); ?></th>
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="valign-middle">
                                    <td>
                                        <?php echo e($loop->index + 1); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->student->admin_code ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->student->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->classs->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->level->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e(date('d-m-Y', strTotime($row->exam_session->day_exam))); ?>

                                    </td>
                                    <td>
                                        <?php echo e(__($row->skill_test) ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e(__($row->status) ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->exam_session->grader_exam->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->score ?? ''); ?>

                                    </td>
                                    <td>
                                        <div class="d-flex-wap ">
                                            <a class="btn btn-sm btn-success mr-10 btn_show" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Chi tiết'); ?>" data-original-title="<?php echo app('translator')->get('Chi tiết'); ?>"
                                                href="<?php echo e(route(Request::segment(2) . '.show', $row->id)); ?>">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-warning mr-10 btn_reset" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Reset'); ?>" data-original-title="<?php echo app('translator')->get('Reset'); ?>"
                                                data-url="<?php echo e(route(Request::segment(2) . '.reset')); ?>"
                                                data-id = "<?php echo e($row->id); ?>">
                                                <i class="fa fa-refresh"></i>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy <?php echo e($rows->total()); ?> kết quả
                    </div>
                    <div class="col-sm-7">
                        <?php echo e($rows->withQueryString()->links('admin.pagination.default')); ?>

                    </div>
                </div>
            </div>

        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $('.btn_reset').click(function() {
            var _this = $(this);
            var url = _this.data('url');
            var id = _this.data('id');
            if (confirm('Bạn có chắc chắn muốn reset kết quả này không?')) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        id: id,
                    },
                    success: function(response) {
                        if (response.data != null) {
                            if (response.data == 'warning') {
                                var _html = `<div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    ` + response.message + `
                                    </div>`;
                                $('.box-alert').prepend(_html);
                                $('html, body').animate({
                                    scrollTop: $(".alert-warning").offset().top
                                }, 1000);
                                setTimeout(function() {
                                    $(".alert").fadeOut(3000, function() {});
                                }, 800);
                                console.log(2);
                            } else {
                                console.log(1);
                                location.reload();
                            }
                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                Bạn không có quyền thao tác chức năng này!
                                </div>`;
                            $('.box-alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert-warning").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert").fadeOut(3000, function() {});
                            }, 800);
                        }
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });

            }

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\dwn\resources\views/admin/pages/hv_exam_result/index.blade.php ENDPATH**/ ?>