
<?php $__env->startSection('title'); ?>
  Bảng điểm theo lớp
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
  <?php if(isset($this_class)): ?>
    <div class="table-container">
      <h3 class="box-title">
        <?php echo app('translator')->get('Bảng điểm học viên'); ?>
        - Lớp <?php echo e($this_class['name']); ?>

        - Giáo viên <?php echo e($teacher['name']); ?>

      </h3>
      <?php if(count($rows) > 0): ?>
        <table class="content">
          <tbody>
            <tr>
              <th rowspan="2" style="text-align: center; width: 5%"><?php echo app('translator')->get('Mã'); ?></th>
              <th rowspan="2" style="text-align: center; width: 15%"><?php echo app('translator')->get('Student'); ?></th>
              <th rowspan="2" style="text-align: center; width: 15%"><?php echo app('translator')->get('Lớp'); ?></th>
              <th colspan="5" style="text-align: center; width: 40%"><?php echo app('translator')->get('Điểm'); ?> </th>
              <th rowspan="2" style="text-align: center; width: 15%"><?php echo app('translator')->get('Nhận xét'); ?></th>
              <th rowspan="2" style="text-align: center; width: 10%"><?php echo app('translator')->get('Xếp loại'); ?></th>
            </tr>
            <tr>
              <th style="text-align: center;width: 8%"><?php echo app('translator')->get('Nghe'); ?> </th>
              <th style="text-align: center;width: 8%"><?php echo app('translator')->get('Nói'); ?> </th>
              <th style="text-align: center;width: 8%"><?php echo app('translator')->get('Đọc'); ?> </th>
              <th style="text-align: center;width: 8%"><?php echo app('translator')->get('Viết'); ?> </th>
              <th style="text-align: center;width: 8%"><?php echo app('translator')->get('TB'); ?></th>
            </tr>

            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr class="row">
                <td rowspan="<?php echo e(isset($row['userClasses']) ? count($row['userClasses']) : 1); ?>">
                  <?php echo e($row['student']['admin_code'] ?? ''); ?>

                </td>
                <td rowspan="<?php echo e(isset($row['userClasses']) ? count($row['userClasses']) : 1); ?>">
                  <?php echo e($row['student']['name'] ?? ''); ?>


                </td>
                <?php if(isset($row['userClasses'])): ?>
                  <?php $__currentLoopData = $row['userClasses']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userClass): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $loop->index > 0 ? '<tr class="row">' : ''; ?>

                    <td>
                      <?php echo e($userClass['class']['name'] ?? ''); ?>

                      (<?php echo e(__($userClass['status'])); ?>)
                    </td>
                    <td><?php echo e($userClass['score']['score_listen'] ?? ''); ?></td>
                    <td><?php echo e($userClass['score']['score_speak'] ?? ''); ?></td>
                    <td><?php echo e($userClass['score']['score_read'] ?? ''); ?></td>
                    <td><?php echo e($userClass['score']['score_write'] ?? ''); ?></td>
                    <td><?php echo e($userClass['score']['json_params']['score_average'] ?? ''); ?></td>
                    <td><?php echo e($userClass['score']['json_params']['note'] ?? ''); ?></td>
                    <td>
                      <?php echo e(isset($userClass['score']['status']) ? App\Consts::ranked_academic_total[$userClass['score']['status']] ?? $userClass['score']['status'] : 'Chưa xác định'); ?>

                    </td>
                    <?php echo $loop->index > 0 ? '</tr>' : ''; ?>

                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                  <td>
                    <?php echo e($this_class['name'] ?? ''); ?>

                  </td>
                  <td>
                    <?php echo e($row['score_listen'] ?? ''); ?>

                  </td>
                  <td>
                    <?php echo e($row['score_speak'] ?? ''); ?>

                  </td>
                  <td>
                    <?php echo e($row['score_read'] ?? ''); ?>

                  </td>
                  <td>
                    <?php echo e($row['score_write'] ?? ''); ?>

                  </td>
                  <td>
                    <?php echo e($row['json_params']['score_average'] ?? '0'); ?>

                  </td>
                  <td>
                    <?php echo e($row['json_params']['note'] ?? ''); ?>

                  </td>
                  <td>
                    <?php echo e($row['status'] != '' ? App\Consts::ranked_academic_total[$row['status']] ?? $row['status'] : 'Chưa xác định'); ?>

                  </td>
                <?php endif; ?>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.pdf', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\dwn\resources\views/admin/pages/staffadmissions/pdf.blade.php ENDPATH**/ ?>