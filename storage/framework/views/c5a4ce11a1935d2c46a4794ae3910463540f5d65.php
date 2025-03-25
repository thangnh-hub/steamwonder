<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
        .input_content {
            max-width: 100%;
        }
    </style>
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
        <div id="loading-notification" class="loading-notification">
            <p><?php echo app('translator')->get('Please wait'); ?>...</p>
        </div>
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

        <form role="form" action="<?php echo e(route(Request::segment(2) . '.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Tạo cấu hình phiên thi'); ?></h3>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                            </button>
                        </div>
                        <div class="box-body">
                            <div class="tab_offline">
                                <div class="tab-pane active">
                                    <div class="d-flex-wap">
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Trình độ'); ?> <small class="text-red">*</small></label>
                                                <select required name="id_level"
                                                    class="id_level form-control select2 w-100">
                                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                                    <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($val->id ?? ''); ?>">
                                                            <?php echo e($val->name ?? ''); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Tổ chức'); ?></label>
                                                <select name="organization" class="form-control select2 w-100">
                                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                                    <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($val); ?>">
                                                            <?php echo app('translator')->get($val); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Chọn kỹ năng'); ?> <small class="text-red">*</small></label>
                                                <select required name="skill_test" class="form-control select2 w-100">
                                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                                    <?php $__currentLoopData = $skill; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($val); ?>">
                                                            <?php echo app('translator')->get($val); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Thời gian thi'); ?> (Phút) <small class="text-red">*</small></label>
                                                <input required type="number" name="json_params[time]" class="form-control"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-10">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Nhóm phần thi, mô tả và số file'); ?> <small class="text-red">*</small></label>
                                                <div class="box_group mt-10">
                                                    <div class="d-flex-wap item_group mt-10 align-center">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Chọn nhóm'); ?> <small
                                                                        class="text-red">*</small></label>
                                                                <select required class="form-control select2 w-100"
                                                                    onchange="add_name_input(this)">
                                                                    <option value="" hidden><?php echo app('translator')->get('Please choose'); ?>
                                                                    </option>
                                                                    <?php $__currentLoopData = $arr_group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($val); ?>">
                                                                            <?php echo app('translator')->get($val); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Mô tả'); ?> <small
                                                                        class="text-red">*</small></label>
                                                                <textarea required type="text" name="" class="form-control input_content" value=""></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Số file tương ứng'); ?> <small
                                                                        class="text-red">*</small></label>
                                                                <input required type="number" name=""
                                                                    class="form-control input_number" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label><?php echo app('translator')->get('Mix'); ?></label>
                                                                <div class="sw_featured d-flex-al-center">
                                                                    <label class="switch ">
                                                                        <input name="" class="input_mix"
                                                                            value="1" type="checkbox">
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="btn btn-sm btn-danger"
                                                                onclick="delete_group(this)">
                                                                Xóa</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button"
                                                    class="btn btn-sm btn-primary add_group"><?php echo app('translator')->get('Thêm nhóm câu hỏi'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                            </a>
                            <button type="submit" class="btn btn-info pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        var arr_group = <?php echo json_encode($arr_group ?? [], 15, 512) ?>;
        $('.add_group').click(function() {
            let options = `<option value=""><?php echo app('translator')->get('Please choose'); ?></option>`;
            arr_group.forEach(val => {
                options += `<option value="${val}"><?php echo app('translator')->get('${val}'); ?></option>`;
            });
            let _html = `
                <div class="d-flex-wap item_group mt-10 align-center">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Chọn nhóm'); ?> <small class="text-red">*</small></label>
                            <select required class="form-control select2 w-100"  onchange="add_name_input(this)">
                                ${options}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Mô tả'); ?> <small
                                    class="text-red">*</small></label>
                           <textarea required type="text" name=""
                            class="form-control input_content" value=""></textarea>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Số file tương ứng'); ?> <small class="text-red">*</small></label>
                            <input required type="number" name="" class="form-control input_number" value="">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Mix'); ?></label>
                            <div class="sw_featured d-flex-al-center">
                                <label class="switch ">
                                    <input class="input_mix" name=""
                                        value="1" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="btn btn-sm btn-danger" onclick="delete_group(this)">Xóa</div>
                    </div>
                </div>
            `;

            // Thêm vào DOM và khởi tạo lại select2
            $('.box_group').append(_html);
            $('.select2').select2();
        });


        function delete_group(th) {
            $(th).parents('.item_group').remove();
        }

        function add_name_input(th) {
            let _type = $(th).val();
            let _name = `topic[${_type}]`;
            $(th).closest('.item_group')
                .find('.input_number')
                .attr('name', _name + '[file]').end()
                .find('.input_content')
                .attr('name', _name + '[content]').end()
                .find('.input_mix')
                .attr('name', _name + '[mix]');
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\dwn\resources\views/admin/pages/hv_exam_option/create.blade.php ENDPATH**/ ?>