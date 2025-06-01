

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

        <form role="form" action="<?php echo e(route(Request::segment(2) . '.update', $detail->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Create form'); ?></h3>
                        </div>
                        <?php echo csrf_field(); ?>
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Mã sản phẩm'); ?> <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="code"
                                                        placeholder="<?php echo app('translator')->get('Mã sản phẩm'); ?>" value="<?php echo e($detail->code ?? ''); ?>"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Tên sản phẩm'); ?> <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="<?php echo app('translator')->get('Tên sản phẩm'); ?>" value="<?php echo e($detail->name ?? ''); ?>"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Loại sản phẩm'); ?></label>
                                                    <select required name="warehouse_type" class=" form-control select2">
                                                        <option value="">Chọn</option>
                                                        <?php $__currentLoopData = $list_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($key); ?>"
                                                                <?php echo e(isset($detail->warehouse_type) && $detail->warehouse_type == $key ? 'selected' : ''); ?>>
                                                                <?php echo app('translator')->get($val); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Danh mục'); ?></label>
                                                    <select required name="warehouse_category_id"
                                                        class=" form-control select2">
                                                        <option value="">Chọn</option>
                                                        <?php $__currentLoopData = $list_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($val->category_parent == '' || $val->category_parent == null): ?>
                                                                <option value="<?php echo e($val->id); ?>"
                                                                    <?php echo e(isset($detail->warehouse_category_id) && $detail->warehouse_category_id == $val->id ? 'selected' : ''); ?>>
                                                                    <?php echo app('translator')->get($val->name ?? ''); ?></option>
                                                                <?php $__currentLoopData = $list_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row_child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php if($row_child->category_parent == $val->id): ?>
                                                                        <option value="<?php echo e($row_child->id); ?>"
                                                                            <?php echo e(isset($detail->warehouse_category_id) && $detail->warehouse_category_id == $row_child->id ? 'selected' : ''); ?>>
                                                                            --- <?php echo e($row_child->code ?? ''); ?>

                                                                        </option>
                                                                    <?php endif; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Đơn vị tính'); ?> </label>
                                                    <input type="text" class="form-control" name="unit"
                                                        placeholder="<?php echo app('translator')->get('Đơn vị tính'); ?>" value="<?php echo e($detail->unit ?? ''); ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Đơn giá'); ?> </label>
                                                    <input type="text" class="form-control" name="price"
                                                        placeholder="<?php echo app('translator')->get('price'); ?>"
                                                        value="<?php echo e($detail->price ?? ''); ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Quy cách'); ?> </label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[specification]" placeholder="<?php echo app('translator')->get('Quy cách'); ?>"
                                                        value="<?php echo e($detail->json_params->specification ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Xuất xứ'); ?> </label>
                                                    <input type="text" class="form-control" name="json_params[origin]"
                                                        placeholder="<?php echo app('translator')->get('Xuất xứ'); ?>"
                                                        value="<?php echo e($detail->json_params->origin ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Hãng sx'); ?> </label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[manufacturer]" placeholder="<?php echo app('translator')->get('Hãng sx'); ?>"
                                                        value="<?php echo e($detail->json_params->manufacturer ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Bảo hành'); ?> </label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[warranty]" placeholder="<?php echo app('translator')->get('Bảo hành'); ?>"
                                                        value="<?php echo e($detail->json_params->warranty ?? ''); ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Trạng thái'); ?></label>
                                                    <select name="status" class=" form-control select2">
                                                        <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($key); ?>"
                                                                <?php echo e(isset($detail->status) && $detail->status == $key ? 'selected' : ''); ?>>
                                                                <?php echo app('translator')->get($val); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Code Auto'); ?> </label>
                                                    <input type="text" class="form-control" name="code_auto"
                                                        placeholder="<?php echo app('translator')->get('Code Auto'); ?>"
                                                        value="<?php echo e(old('code_auto') ?? ($detail->code_auto ?? null)); ?>">
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                            <a class="btn btn-sm btn-success" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                            </a>
                        </div>
                        <!-- /.box-body -->


                    </div>
                </div>
            </div>
        </form>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_product/edit.blade.php ENDPATH**/ ?>