

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
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
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="<?php echo e(route('mealmenu.week.report')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="week">Chọn tuần:</label>
                                <input type="week" name="week" id="week" class="form-control mx-2" value="<?php echo e($week); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Area'); ?></label>
                                <select class="form-control select2" name="area_id">
                                    <option value=""><?php echo app('translator')->get('Chọn'); ?></option>
                                    <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($area->id); ?>" <?php echo e(isset($params['area_id']) && $params['area_id'] == $area->id ? 'selected' : ''); ?>>
                                            <?php echo e($area->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div style="display:flex;jsutify-content:space-between;">
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm  mr-10" href="<?php echo e(route('mealmenu.week.report')); ?>">
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
                

                <?php if(request()->filled('area_id')): ?>
                    <!-- Nút chuyển đổi chế độ hiển thị -->
                    <div style="margin-bottom: 15px;">
                        <button id="btnViewByAge" class="btn btn-primary">Hiển thị theo nhóm tuổi</button>
                        <button id="btnViewByDay" class="btn btn-default">Hiển thị theo ngày</button>
                    </div>
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

                <?php else: ?>
                    <p class="text-muted">Vui lòng chọn khu vực để xem thực đơn.</p>
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