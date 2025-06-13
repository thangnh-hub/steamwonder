<h5><?php echo e($mealAge->name); ?> - Ngày <?php echo e(\Carbon\Carbon::parse($date)->format('d/m/Y')); ?> - Khu vực <?php echo e($area_name); ?></h5>
<br>

<div class="table-wrapper">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Lớp</th>
                <th>Số suất ăn</th>
                <th>Thực đơn</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($class->name); ?></td>
                    <td><?php echo e($class->attendance_count); ?></td>
                    <td>
                        <a target="_blank" href="<?php echo e(route('menu_dailys.edit', $menu_daily_id)); ?>">
                            <?php echo app('translator')->get('Link thực đơn'); ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="3">Không có lớp nào.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>                    
</div>

<?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/meal/menu_dailys/view_detal_calendar.blade.php ENDPATH**/ ?>