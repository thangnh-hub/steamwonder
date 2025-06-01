<?php if(isset($department_assets) && count($department_assets) > 0): ?>
    <td colspan="<?php echo e(6 + (int) $params['colspan']); ?>">
        <p><strong> Thống kê tài sản: <?php echo e($product->name); ?> theo phòng ban tại: <?php echo e($warehause->name); ?> </strong></p>
        <table style="border: 1px solid #dddddd;" class="table table-bordered">
            <thead>
                <tr>
                    <?php $__currentLoopData = $department_assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="text-center"><?php echo e($val['department_name']); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php $__currentLoopData = $department_assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="text-center cursor td_detail" title="<?php echo app('translator')->get('Chi tiết'); ?>">
                            <a href="<?php echo e(route('warehouse_asset.index', ['product_id' => $product->id, 'warehouse_id' => $warehause->id, 'department_id' => $val['department_id']])); ?>"
                                target="_blank" class="block_full_width"> <?php echo e($val['total_quantity']); ?></a>
                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </tbody>
        </table>
    </td>
<?php endif; ?>
<?php if(isset($position_hierarchy) && count($position_hierarchy) > 0): ?>
    <td colspan="<?php echo e(6 + (int) $params['colspan']); ?>">
        <p><strong>Thống kê tài sản: <?php echo e($product->name); ?> theo vị trí tại: <?php echo e($warehause->name); ?></strong></p>
        <table style="border: 1px solid #dddddd;" class="table table-bordered">
            
            <thead>
                <tr>
                    <?php $arr_sub = [] ?>
                    <?php $__currentLoopData = $position_hierarchy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th colspan="<?php echo e($val['colspan']); ?>"class="text-center"><?php echo e($val['position_name']); ?></th>
                        <?php
                            $arr_sub[] = $val['children'];
                        ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php $__currentLoopData = $position_hierarchy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td colspan="<?php echo e($val['colspan']); ?>" class="text-center cursor td_detail"
                            title="<?php echo app('translator')->get('Chi tiết'); ?>">
                            <a href="<?php echo e(route('warehouse_asset.index', ['product_id' => $product->id, 'warehouse_id' => $warehause->id, 'position_id' => $val['position_id']])); ?>"
                                target="_blank" class="block_full_width"> <?php echo e($val['total_quantity']); ?></a>
                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </tbody>


            
            <?php if(isset($arr_sub)): ?>
                <?php
                    // đếm xem có thằng con mới hiển thị
                    $count_sub = collect($arr_sub)
                        ->filter(function ($item) {
                            return $item->count() > 0;
                        })
                        ->count();
                ?>
                <?php if($count_sub > 0): ?>

                    <thead>
                        <tr>
                            <?php $arr_sub_child = [] ?>
                            <?php $__currentLoopData = $arr_sub; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val_sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $val_sub; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $arr_sub_child[] = $sub['children'] ?? [];
                                    ?>
                                    <th colspan="<?php echo e($sub['colspan']); ?>"class="text-center"><?php echo e($sub['position_name']); ?>

                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php $__currentLoopData = $arr_sub; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val_sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(count($val_sub) > 0): ?>
                                    <?php $__currentLoopData = $val_sub; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td colspan="<?php echo e($sub['colspan']); ?>" class="text-center cursor td_detail"
                                            title="<?php echo app('translator')->get('Chi tiết'); ?>">
                                            <a href="<?php echo e(route('warehouse_asset.index', ['product_id' => $product->id, 'warehouse_id' => $warehause->id, 'position_id' => $val['position_id']])); ?>"
                                                target="_blank" class="block_full_width">
                                                <?php echo e($sub['total_quantity']); ?></a>
                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <td></td>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </tbody>
                <?php endif; ?>
            <?php endif; ?>

            
            <?php if(isset($arr_sub_child)): ?>
                <?php
                    // đếm xem có thằng con mới hiển thị
                    $count_sub_child = collect($arr_sub_child)
                        ->filter(function ($item) {
                            return $item->count() > 0;
                        })
                        ->count();
                ?>
                <?php if($count_sub_child > 0): ?>
                    <thead>
                        <tr>
                            <?php $__currentLoopData = $arr_sub_child; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val_sub_child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(count($val_sub_child) > 0): ?>
                                    <?php $__currentLoopData = $val_sub_child; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub_child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="text-center"><?php echo e($sub_child['position_name']); ?></th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <th></th>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php $__currentLoopData = $arr_sub_child; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val_sub_child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(count($val_sub_child) > 0): ?>
                                    <?php $__currentLoopData = $val_sub_child; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub_child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-center cursor th_detail" title="<?php echo app('translator')->get('Chi tiết'); ?>">
                                            <a href="<?php echo e(route('warehouse_asset.index', ['product_id' => $product->id, 'warehouse_id' => $warehause->id, 'position_id' => $val['position_id']])); ?>"
                                                target="_blank" class="block_full_width">
                                                <?php echo e($sub_child['total_quantity']); ?></a>
                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <td></td>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </tbody>
                <?php endif; ?>

            <?php endif; ?>
        </table>
    </td>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_asset/view_statistical.blade.php ENDPATH**/ ?>