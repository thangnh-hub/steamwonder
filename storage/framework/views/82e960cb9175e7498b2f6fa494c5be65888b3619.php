

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
      
        .card {
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
            background-color: #fff;
            position: relative;
            max-height: 600px; /* hoặc theo nhu cầu */
            overflow-y: auto;
        }

        .card-header {
            position: sticky;
            top: 0;
            z-index: 10;
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .card-body {
            padding: 15px;
        }

        .card-footer {
            padding: 10px 15px;
            background-color: #f5f5f5;
            border-top: 1px solid #ddd;
        }
       .area-block {
            border: 1px solid #ccc;
            padding: 8px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }

        .area-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 6px;
        }

        .age-block {
            margin-left: 10px;
            padding-left: 8px;
            border-left: 3px solid #ccc;
            margin-bottom: 6px;
        }

        .age-title {
            font-weight: bold;
            color: #006699;
            margin-bottom: 4px;
        }

        .meal-block {
            margin-left: 10px;
            padding-left: 10px;
            border-left: 2px dashed #aaa;
            margin-bottom: 4px;
        }

        .meal-title {
            font-weight: bold;
            color: #555;
            margin-bottom: 2px;
        }

        .dish-list {
            list-style: disc;
            padding-left: 20px;
            margin-bottom: 0;
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
    <section class="content-header">
        
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        
        

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?php if(!$show_report): ?>
                        <?php echo app('translator')->get('Thống kê thực đơn theo tuần'); ?>
                    <?php else: ?>
                        <?php echo app('translator')->get($module_name); ?>
                    <?php endif; ?>
                </h3>
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
               
                <?php if(!$show_report): ?>
                    <form method="GET" action="<?php echo e(route('mealmenu.week.report')); ?>" class="form-inline mb-3">
                        <div class=" mr-2 box-center">
                            <input type="month" name="month" class="form-control" style="width: 300px; display: inline-block;"
                                value="<?php echo e($selected_month ?? now()->format('Y-m')); ?>"
                                onchange="this.form.submit()">

                        </div>
                    </form>
                    <br>
                    <div class="row">
                        <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <strong><?php echo e($area->name); ?></strong>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group">
                                            <?php $__currentLoopData = $currentYearWeeks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $week): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="<?php echo e(route('mealmenu.week.report', ['area_id' => $area->id, 'week' => $week['value'], 'month' => $selected_month])); ?>">
                                                    <?php echo e($week['label']); ?>

                                                    <i class="fa fa-arrow-right pull-right"></i>
                                                </li>
                                                </a>
                                                
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <a href="<?php echo e(route('mealmenu.week.report')); ?>" class="btn btn-primary">
                            ← Quay lại chọn tuần
                        </a>
                        <div class="pull-right" style="margin-bottom: 15px;">
                            <button id="btnViewByAge" class="btn btn-primary"><i class="fa fa-eye"></i> Hiển thị theo nhóm tuổi</button>
                            <button id="btnViewByDay" class="btn btn-default"><i class="fa fa-eye"></i> Hiển thị theo ngày</button>
                        </div>
                    </div>
                    <br>
                    <!-- View 1: Theo ngày -->
                    <div id="viewByDay" style="display:none;">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <?php $__currentLoopData = $daysInWeek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="text-center"><?php echo e(Str::ucfirst(\Carbon\Carbon::parse($day)->translatedFormat('l d/m'))); ?></th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php $__currentLoopData = $daysInWeek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $dayStr = $day->format('Y-m-d'); ?>
                                        <td style="vertical-align: top;">
                                            <?php if(isset($menusGrouped[$dayStr])): ?>
                                                <?php $__currentLoopData = $menusGrouped[$dayStr]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ageName => $meals): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="age-block">
                                                        <div class="age-title"><strong>Nhóm tuổi:</strong> <?php echo e($ageName); ?></div>
                                                        <?php $__currentLoopData = $meals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $dishes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="meal-block">
                                                                <div class="meal-title"><strong><?php echo e(ucfirst(__($type))); ?></strong></div>
                                                                <ul class="dish-list">
                                                                    <?php $__currentLoopData = $dishes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dish): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <li><?php echo e($dish->name ?? '-'); ?></li>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </ul>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Không có thực đơn</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- View 2: Theo nhóm tuổi -->
                    <div id="viewByAge">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="align-middle text-center">Nhóm tuổi</th>
                                    <th rowspan="2" class="align-middle text-center">Bữa ăn</th>
                                    <?php $__currentLoopData = $daysInWeek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="text-center"><?php echo e(Str::ucfirst(\Carbon\Carbon::parse($day)->translatedFormat('l d/m'))); ?></th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($menusGroupedByAge) == 0): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">Không có dữ liệu</td>
                                    </tr>
                                <?php else: ?>    
                                <?php $__currentLoopData = $menusGroupedByAge; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ageName => $mealsByType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $firstRow = true; ?>
                                    <?php $__currentLoopData = $dishesTime; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <?php if($firstRow): ?>
                                                <td rowspan="<?php echo e(count($dishesTime)); ?>" class="align-middle"><strong><?php echo e($ageName); ?></strong></td>
                                                <?php $firstRow = false; ?>
                                            <?php endif; ?>
                                            <td><strong><?php echo e(__($label)); ?></strong></td>
                                            <?php $__currentLoopData = $daysInWeek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $dayStr = $day->format('Y-m-d');
                                                    $dishes = $mealsByType[$type][$dayStr] ?? [];
                                                ?>
                                                <td>
                                                    <?php if(count($dishes)): ?>
                                                        <ul class="mb-0 pl-3">
                                                            <?php $__currentLoopData = $dishes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dish): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li><?php echo e($dish->name ?? '-'); ?></li>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </ul>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function(){
            $('#btnViewByDay').click(function(){
                $('#viewByDay').show();
                $('#viewByAge').hide();
                $(this).addClass('btn-primary').removeClass('btn-default');
                $('#btnViewByAge').removeClass('btn-primary').addClass('btn-default');
            });

            $('#btnViewByAge').click(function(){
                $('#viewByAge').show();
                $('#viewByDay').hide();
                $(this).addClass('btn-primary').removeClass('btn-default');
                $('#btnViewByDay').removeClass('btn-primary').addClass('btn-default');
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/meal/menu_dailys/report_by_week.blade.php ENDPATH**/ ?>