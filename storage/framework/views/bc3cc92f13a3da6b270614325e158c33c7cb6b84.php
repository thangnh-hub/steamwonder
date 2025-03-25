

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="/">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li class="active"><?php echo app('translator')->get($module_name); ?></li>
        </ol>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
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
        
        <div class="row">
            <div class="col-lg-2 col-xs-6 hidden">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3 data-count="<?php echo e($admission); ?>" class="counter"></h3>
                        <p>Cán bộ tuyển sinh</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user-plus"></i>
                    </div>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-2 col-xs-6 hidden">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3 data-count="<?php echo e($teacher); ?>" class="counter"></h3>
                        <p>Giáo viên</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-cube"></i>
                    </div>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3 data-count="<?php echo e($trial_student ?? ''); ?>" class="counter"></h3>
                        <p>Học viên học thử</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3 data-count="<?php echo e($student ?? ''); ?>" class="counter"></h3>
                        <p>Học viên chính thức</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3 data-count="<?php echo e($student_liquidation_of_admission ?? ''); ?>" class="counter"></h3>
                        <p>Học viên đã thanh lý</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div><!-- ./col -->
        </div><!-- /.row -->


        <div class="row ">
            <div class="col-lg-6 col-xs-12">
                <div class="box box-info collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Khóa học mới khai giảng</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Ngày khai giảng</th>
                                        <th>Tổng số học viên đăng ký</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $list_course; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($course->name ?? ''); ?></td>
                                            <td><?php echo e(isset($course->day_opening) && $course->day_opening!=""? date('d-m-Y', strtotime($course->day_opening)) : ''); ?></td>
                                            <td><?php echo e($course->count_student ?? ''); ?> </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div><!-- /.table-responsive -->
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div><!-- ./col -->
            <div class="col-lg-6 col-xs-12">
                <div class="box box-info collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Lớp mới khai giảng</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Giáo viên</th>
                                        <th>Sỹ số</th>
                                        <th>Khu vực</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $list_class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $teacher = \App\Models\Teacher::where(
                                                'id',
                                                $row->json_params->teacher ?? 0,
                                            )->first();

                                            $quantity_student = \App\Models\UserClass::where('class_id', $row->id)
                                                ->get()
                                                ->count();
                                        ?>
                                        <tr>
                                            <td><?php echo e($row->name ?? ''); ?></td>
                                            <td><?php echo e($teacher->name ?? ''); ?></td>
                                            <td> <?php echo e($quantity_student); ?> </td>
                                            <td><?php echo e($row->area->name ?? ''); ?> </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div><!-- /.table-responsive -->
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div><!-- ./col -->
        </div><!-- /.row -->
        
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {
            $('.counter').each(function() {
                var $this = $(this);
                var countTo = $this.attr('data-count');
                var duration = 1500;

                $({
                    countNum: $this.text()
                }).animate({
                    countNum: countTo
                }, {
                    duration: parseInt(duration),
                    easing: 'linear',
                    step: function() {
                        $this.text(Math.floor(this.countNum));
                    },
                    complete: function() {
                        $this.text(this.countNum);
                    }
                });
            });
            $('.col-lg-6.col-xs-12 .box.box-info .fa-minus').click();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\dwn\resources\views/admin/pages/home/index.blade.php ENDPATH**/ ?>