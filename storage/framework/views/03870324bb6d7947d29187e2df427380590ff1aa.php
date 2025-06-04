<li>
  <label>
    <?php if(in_array($menu->id, $selectedMenus) &&
            (isset($admin->permission_access_by_role->menu_id) &&
                in_array($menu->id, $admin->permission_access_by_role->menu_id))): ?>
      <input type="checkbox" checked disabled>
      <span style="font-style: italic;" class="text-danger">
        <?php echo e($menu->name); ?>

      </span>
    <?php else: ?>
      <input type="checkbox" name="json_params[menu_id][]" value="<?php echo e($menu->id); ?>"
        <?php echo e(in_array($menu->id, $selectedMenus) ? 'checked' : ''); ?>>
      <span>
        <?php echo e($menu->name); ?>

      </span>
    <?php endif; ?>

  </label>

  <?php if($menu->children->isNotEmpty()): ?>
    <ul>
      <?php $__currentLoopData = $menu->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('admin.pages.admins.role-menu-item', [
            'menu' => $child,
            'selectedMenus' => $selectedMenus,
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  <?php endif; ?>
</li>
<?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/admins/role-menu-item.blade.php ENDPATH**/ ?>