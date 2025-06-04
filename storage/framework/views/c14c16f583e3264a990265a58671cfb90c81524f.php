

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
        .content-header{
            display: flex;
            justify-content: space-between;
        }
        .box-body {
            margin-bottom: 0px;
        }
        .box {
            margin-bottom: 0px;
        }
        .box-header{
            background-color: #3c8dbc;
            color: white;
        }
        .list-group {
            margin-bottom: 5px !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
            <h1>
                <?php echo app('translator')->get($module_name); ?>
            </h1>
            <a class="pull-right" href="<?php echo e(route( 'mealmenu.daily.report')); ?>">
                <button type="button" class="btn btn-sm btn-success"><?php echo app('translator')->get('Danh sách thống kê'); ?></button>
            </a>
    </section>

    <!-- Main content -->
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

        <?php if(!empty($groupedIngredients)): ?>
            <div style="margin-bottom: 30px;" class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Tổng hợp tất cả nguyên liệu cho ngày <?php echo e(\Carbon\Carbon::parse($date)->format('d/m/Y')); ?></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?php $__currentLoopData = $groupedIngredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label><strong>Loại: <?php echo e(ucfirst(__($type))); ?></strong></label>
                        <table class="table table-bordered " style="margin-bottom: 20px">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên nguyên liệu</th>
                                    <th>Định lượng tổng</th>
                                    <th>KG</th>
                                    <th>Đơn vị chính</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $ingredient = $item['ingredient'];
                                        $count = max($item['count_student'], 1);
                                        $valuePerOne = $item['total'] / $count;
                                        $valueInKg = $item['total'] / 1000;
                                        $defaultUnit = $ingredient->unitDefault->name ?? '';
                                        $convertedValue = null;
                                        if ($ingredient->convert_to_gram) {
                                            $ratio = $ingredient->convert_to_gram;
                                            $convertedValue = $ratio ? $item['total'] / $ratio : null;
                                        }
                                    ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($ingredient->name); ?></td>
                                        <td><?php echo e(rtrim(rtrim(number_format($item['total'], 2, '.', ''), '0'), '.')); ?> g</td>
                                        <td><?php echo e(rtrim(rtrim(number_format($valueInKg, 2, '.', ''), '0'), '.')); ?> kg</td>
                                        <td>
                                            <?php if($convertedValue): ?>
                                                <?php echo e(rtrim(rtrim(number_format($convertedValue, 2, '.', ''), '0'), '.')); ?> <?php echo e($defaultUnit); ?>

                                            <?php else: ?>
                                                <?php echo e(rtrim(rtrim(number_format($valueInKg, 2, '.', ''), '0'), '.')); ?> kg
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>


        <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="margin-bottom: 30px;" class="box collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo app('translator')->get('Nguyên liệu cho nhóm tuổi'); ?>: <?php echo e($menu->mealAge->name ?? '-'); ?>

                        <span>(<?php echo e($menu->count_student ?? 0); ?> suất)</span>
                    </h3>
                    
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body" >
                    <?php if($menu->menuIngredients->count()): ?>
                        <table class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('STT'); ?></th>
                                    <th><?php echo app('translator')->get('Tên nguyên liệu'); ?></th>
                                    <th><?php echo app('translator')->get('Định lượng cho 1 suất'); ?></th>
                                    <th>Định lượng tổng (x<?php echo e($menu->count_student); ?> suất) g</th>
                                    <th><?php echo app('translator')->get('Tính theo KG'); ?></th>
                                    <th><?php echo app('translator')->get('Tính theo đơn vị chính'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $menu->menuIngredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $valuePerOne = $item->value / max($menu->count_student, 1);
                                        $ingredient = $item->ingredients;
                                        $defaultUnit = $ingredient->unitDefault->name ?? '';
                                        // Tính theo KG
                                        $valueInKg = $item->value / 1000;
                                        // Tính theo đơn vị chính
                                        $convertedValue = null;
                                        if ($ingredient->convert_to_gram) {
                                            $ratio = $ingredient->convert_to_gram ;
                                            $convertedValue = $ratio ? $item->value / $ratio : null;
                                        }
                                    ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($item->ingredients->name ?? ''); ?></td>
                                        <td>
                                            <?php echo e(rtrim(rtrim(number_format($valuePerOne, 2, '.', ''), '0'), '.')); ?> g
                                        </td>
                                        <td>
                                            <?php echo e(rtrim(rtrim(number_format($item->value, 2), '0'), '.')); ?> g
                                        </td>
                                        <td>
                                            <?php echo e(rtrim(rtrim(number_format($valueInKg, 2, '.', ''), '0'), '.')); ?> kg
                                        </td>

                                        <td>
                                            <?php if($convertedValue): ?>
                                            <?php echo e(rtrim(rtrim(number_format($convertedValue, 2, '.', ''), '0'), '.')); ?> <?php echo e($defaultUnit); ?>

                                            <?php else: ?>
                                            <?php echo e(rtrim(rtrim(number_format($valueInKg, 2, '.', ''), '0'), '.')); ?> kg
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p><?php echo app('translator')->get('Không có nguyên liệu nào được tính toán cho thực đơn này.'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/meal/menu_dailys/show_by_date.blade.php ENDPATH**/ ?>