

<?php $__env->startSection('title'); ?>
  <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo app('translator')->get($module_name); ?>
      <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2) . '.create')); ?>"><i
          class="fa fa-plus"></i> <?php echo app('translator')->get('Add'); ?></a>
    </h1>
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

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo app('translator')->get('Update form'); ?></h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="<?php echo e(route(Request::segment(2) . '.update', $detail->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="box-body">

          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_1" data-toggle="tab">
                  <h5><?php echo app('translator')->get('General Information'); ?> <span class="text-danger">*</span></h5>
                </a>
              </li>
              <li>
                <a href="#tab_2" data-toggle="tab">
                  <h5><?php echo app('translator')->get('Access menu'); ?></h5>
                </a>
              </li>
              <li>
                <a href="#tab_3" data-toggle="tab">
                  <h5><?php echo app('translator')->get('Access action'); ?></h5>
                </a>
              </li>
              <button type="submit" class="btn btn-primary btn-sm pull-right">
                <i class="fa fa-floppy-o"></i>
                <?php echo app('translator')->get('Save'); ?>
              </button>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="row">
                  <div class="col-md-6">

                    <div class="form-group">
                      <label><?php echo app('translator')->get('Name'); ?> <small class="text-red">*</small></label>
                      <input type="text" class="form-control" name="name" placeholder="<?php echo app('translator')->get('Name'); ?>"
                        value="<?php echo e($detail->name); ?>" required>
                    </div>

                    <div class="form-group">
                      <label><?php echo app('translator')->get('Order'); ?></label>
                      <input type="number" class="form-control" name="iorder" placeholder="<?php echo app('translator')->get('Order'); ?>"
                        value="<?php echo e($detail->iorder); ?>">
                    </div>

                    <div class="form-group">
                      <label><?php echo app('translator')->get('Status'); ?></label>
                      <div class="form-control">
                        <label>
                          <input type="radio" name="status" value="active"
                            <?php echo e($detail->status == 'active' ? 'checked' : ''); ?>>
                          <small><?php echo app('translator')->get('Active'); ?></small>
                        </label>
                        <label>
                          <input type="radio" name="status" value="deactive"
                            <?php echo e($detail->status == 'deactive' ? 'checked' : ''); ?> class="ml-15">
                          <small><?php echo app('translator')->get('Deactive'); ?></small>
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">

                    <div class="form-group">
                      <label><?php echo app('translator')->get('Description'); ?></label>
                      <textarea name="description" id="description" class="form-control" rows="5"><?php echo e($detail->description); ?></textarea>
                    </div>

                  </div>
                </div>

              </div><!-- /.tab-pane -->

              <div class="tab-pane" id="tab_2">
                <div class="masonry-container">
                  <?php if(count($activeMenus) == 0): ?>
                    <div class="col-12">
                      <?php echo app('translator')->get('No record found on the system!'); ?>
                    </div>
                  <?php else: ?>
                    <?php $__currentLoopData = $activeMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php if($row->parent_id == 0 || $row->parent_id == null): ?>
                        <div class="masonry-box-item">
                          <ul class="checkbox_list">
                            <?php
                              $checked = '';
                              if (
                                  isset($detail->json_access->menu_id) &&
                                  in_array($row->id, $detail->json_access->menu_id)
                              ) {
                                  $checked = 'checked';
                              }
                            ?>

                            <li>
                              <input name="json_access[menu_id][]" type="checkbox" value="<?php echo e($row->id); ?>"
                                id="json_access_menu_id_<?php echo e($row->id); ?>" class="mr-15" <?php echo e($checked); ?>>
                              <label
                                for="json_access_menu_id_<?php echo e($row->id); ?>"><strong><?php echo e(__($row->name)); ?></strong></label>
                            </li>

                            <?php $__currentLoopData = $activeMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <?php if($sub->parent_id == $row->id): ?>
                                <?php
                                  $checked = '';
                                  if (
                                      isset($detail->json_access->menu_id) &&
                                      in_array($sub->id, $detail->json_access->menu_id)
                                  ) {
                                      $checked = 'checked';
                                  }
                                ?>

                                <li>
                                  <input name="json_access[menu_id][]" type="checkbox" value="<?php echo e($sub->id); ?>"
                                    id="json_access_menu_id_<?php echo e($sub->id); ?>" class="mr-15" <?php echo e($checked); ?>>
                                  <label for="json_access_menu_id_<?php echo e($sub->id); ?>">-
                                    - <?php echo e(__($sub->name)); ?></label>
                                </li>

                                <?php if($sub->submenu > 0): ?>
                                  <?php $__currentLoopData = $activeMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub_child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($sub_child->parent_id == $sub->id): ?>
                                      <?php
                                        $checked = '';
                                        if (
                                            isset($detail->json_access->menu_id) &&
                                            in_array($sub_child->id, $detail->json_access->menu_id)
                                        ) {
                                            $checked = 'checked';
                                        }
                                      ?>

                                      <li>
                                        <input name="json_access[menu_id][]" type="checkbox" value="<?php echo e($sub_child->id); ?>"
                                          id="json_access_menu_id_<?php echo e($sub_child->id); ?>" class="mr-15"
                                          <?php echo e($checked); ?>>
                                        <label for="json_access_menu_id_<?php echo e($sub_child->id); ?>">
                                          - - - - <?php echo e(__($sub_child->name)); ?>

                                        </label>
                                      </li>
                                    <?php endif; ?>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                              <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                          </ul>
                        </div>
                      <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php endif; ?>

                </div>

              </div><!-- /.tab-pane -->

              <div class="tab-pane" id="tab_3">

                <div class="masonry-container">
                  <?php if(count($activeModules) == 0): ?>
                    <div class="col-12">
                      <?php echo app('translator')->get('No record found on the system!'); ?>
                    </div>
                  <?php else: ?>
                    <?php $__currentLoopData = $activeModules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <div class="masonry-box-item">
                        <ul class="checkbox_list">
                          <?php
                            $checked = '';
                            if (
                                isset($detail->json_access->module_code) &&
                                in_array($row->module_code, $detail->json_access->module_code)
                            ) {
                                $checked = 'checked';
                            }
                          ?>
                          <li>
                            <label
                              for="json_access_module_code_<?php echo e($row->id); ?>"><strong><?php echo e(__($row->name)); ?></strong></label>
                          </li>

                          <?php $__currentLoopData = $activeFunctions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($sub->module_id == $row->id): ?>
                              <?php
                                $checked = '';
                                if (
                                    isset($detail->json_access->function_code) &&
                                    in_array($sub->function_code, $detail->json_access->function_code)
                                ) {
                                    $checked = 'checked';
                                }
                              ?>
                              <li>
                                <input name="json_access[function_code][]" type="checkbox"
                                  value="<?php echo e($sub->function_code); ?>" id="json_access_function_code_<?php echo e($sub->id); ?>"
                                  class="mr-15" <?php echo e($checked); ?>>
                                <label for="json_access_function_code_<?php echo e($sub->id); ?>"><?php echo e(__($sub->name)); ?>

                                  (<?php echo e($sub->function_code ?? ''); ?>)
                                </label>
                              </li>
                            <?php endif; ?>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </ul>
                      </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php endif; ?>

                </div>

              </div><!-- /.tab-pane -->

            </div><!-- /.tab-content -->
          </div><!-- nav-tabs-custom -->

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <a class="btn btn-success btn-sm" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
            <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
          </a>
          <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
            <?php echo app('translator')->get('Save'); ?></button>
        </div>
      </form>
    </div>
  </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/roles/edit.blade.php ENDPATH**/ ?>