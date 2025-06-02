

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
        .modal-header {
            background-color: #3c8dbc;
            color: white;
        }
        .table-wrapper {
            max-height: 450px;
            overflow-y: auto;
            display: block;
        }

        .table-wrapper thead {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 2;
        }

        .table-wrapper table {
            border-collapse: separate;
            width: 100%;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
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
        <form action="<?php echo e(route(Request::segment(2) . '.update', $detail->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Edit form'); ?></h3>
                        </div>

                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin món ăn <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_2" data-toggle="tab">
                                            <h5>Nguyên liệu của món <?php echo e($detail->name ?? ""); ?> <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name"><?php echo app('translator')->get('Tên món ăn'); ?> <span class="text-danger">*</span></label>
                                                    <input placeholder="<?php echo app('translator')->get('Tên món ăn'); ?>" type="text" name="name" class="form-control" value="<?php echo e(old('name', $detail->name ?? '')); ?>" required>
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Loại món ăn'); ?></label>
                                                    <select name="dishes_type" class="form-control select2">
                                                        <option value=""><?php echo app('translator')->get('Chọn'); ?></option>
                                                        <?php $__currentLoopData = $list_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($key); ?>" <?php echo e(isset($detail->dishes_type) && $detail->dishes_type == $key ? 'selected' : ''); ?>><?php echo e(__($value)); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Bữa ăn áp dụng'); ?></label>
                                                    <select name="dishes_time" class="form-control select2">
                                                        <option value=""><?php echo app('translator')->get('Chọn'); ?></option>
                                                        <?php $__currentLoopData = $list_time; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($key); ?>" <?php echo e(isset($detail->dishes_time) && $detail->dishes_time == $key ? 'selected' : ''); ?>><?php echo e(__($value)); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="status"><?php echo app('translator')->get('Trạng thái'); ?></label>
                                                    <select name="status" class="form-control select2">
                                                        <?php $__currentLoopData = $list_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($key); ?>" <?php echo e(old('status', $detail->status ?? 1) == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for=""><?php echo app('translator')->get('Mô tả'); ?></label>
                                                    <textarea name="description" rows="5" class="form-control" placeholder="Mô tả"><?php echo e($detail->description ?? ""); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="tab-pane " id="tab_2">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="box" style="border-top: 3px solid #d2d6de;">
                                                    <div class="box-header">
                                                        <h3 class="box-title"><?php echo app('translator')->get('Danh sách nguyên liệu'); ?></h3>
                                                        <button type="button" class="btn btn-warning btn-sm pull-right " data-toggle="modal"
                                                            data-target="#addTPModal">
                                                            <?php echo app('translator')->get('Thêm nguyên liệu'); ?>
                                                        </button>
                                                    </div>
                                                    <div class="box-body no-padding">
                                                        <table class="table table-hover sticky">
                                                            <thead>
                                                                <tr>
                                                                    <th style="vertical-align: middle" class="text-center" rowspan="2"><?php echo app('translator')->get('STT'); ?></th>
                                                                    <th style="vertical-align: middle" class="text-center" rowspan="2"><?php echo app('translator')->get('Mã thực phẩm'); ?></th>
                                                                    <th style="vertical-align: middle" class="text-center" rowspan="2"><?php echo app('translator')->get('Tên thực phẩm'); ?></th>
                                                                    <th colspan="<?php echo e(($list_meal_age->count() ?? 0)); ?>" class="text-center"><?php echo app('translator')->get('Định lượng (g)'); ?></th>
                                                                    <th style="vertical-align: middle" class="text-center" rowspan="2"><?php echo app('translator')->get('Xóa'); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <?php $__empty_1 = true; $__currentLoopData = $list_meal_age; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $age): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                                        <th class="text-center"><?php echo e($age->name ?? ""); ?> </th>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                                        <th class="text-center text-muted" colspan="1"><?php echo app('translator')->get('Không có nhóm tuổi'); ?></th>
                                                                    <?php endif; ?>
                                                                </tr>

                                                            </thead>
                                                            <tbody id="ingredient-list">
                                                                <?php $__currentLoopData = $detail->quantitative; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredientId => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php
                                                                        $ingredient = $list_ingredient->firstWhere('id', $ingredientId);
                                                                        if (!$ingredient) continue;
                                                                        $ingredientCode = 'TP' . str_pad($ingredient->id, 5, '0', STR_PAD_LEFT);
                                                                    ?>
                                                                    <tr data-id="<?php echo e($ingredientId); ?>">
                                                                        <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                                                        <td class="text-center"><?php echo e($ingredientCode); ?></td>
                                                                        <td><?php echo e($ingredient->name); ?></td>
                                                                        <?php $__currentLoopData = $list_meal_age; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $age): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <td class="text-center">
                                                                                <input type="number" class="form-control"
                                                                                    name="json_params[quantitative][<?php echo e($ingredientId); ?>][<?php echo e($age->code); ?>]"
                                                                                    placeholder="Định lượng" step="any"
                                                                                    value="<?php echo e($data[$age->code]); ?>">
                                                                            </td>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        <td class="text-center">
                                                                            <button type="button" class="btn btn-sm btn-danger btn-remove-ingredient">X</button>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>

                        <div class="box-footer">
                            <a href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                <button type="button" class="btn btn-sm btn-success">Danh sách</button>
                            </a>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>


    <div class="modal fade" id="addTPModal" tabindex="-1" role="dialog" aria-labelledby="addTPModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTPModalLabel"><?php echo app('translator')->get('Chọn nguyên liệu'); ?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Tìm theo tên thực phẩm'); ?></label>
                                <input type="text" class="form-control" id="search-ingredient"
                                    placeholder="<?php echo app('translator')->get('Từ khóa'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="table-wrapper table-responsive">
                        <table class="table table-hover table-bordered" id="ingredient-table">
                            <thead>
                                <tr>
                                    <th>Chọn</th>
                                    <th><?php echo app('translator')->get('Tên thực phẩm'); ?></th>
                                    <th><?php echo app('translator')->get('Mã thực phẩm'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $list_ingredient; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="ingredient-row" style="cursor: pointer;">
                                    <td>
                                        <input type="checkbox" name="parents[<?php echo e($ingredient->id); ?>][id]"
                                            value="<?php echo e($ingredient->id); ?>" >
                                    </td>
                                    <td class="ingredient-name"><?php echo e($ingredient->name); ?> 
                                    <td class="ingredient-name"><?php echo e('TP' . str_pad($ingredient->id, 5, '0', STR_PAD_LEFT)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="confirm-add-ingredient"><?php echo app('translator')->get('Đồng ý'); ?></button>
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal"><?php echo app('translator')->get('Đóng'); ?></button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).on('click', '.ingredient-row', function (e) {
            if ($(e.target).is('input[type="checkbox"]')) return;
            const checkbox = $(this).find('input[type="checkbox"]');
            checkbox.prop('checked', !checkbox.prop('checked'));
        });
        $('#search-ingredient').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $('#ingredient-table tbody tr').filter(function() {
                $(this).toggle($(this).find('.ingredient-name').text().toLowerCase().indexOf(value) > -1);
            });
        });
        $(document).ready(function () {
            let stt = 1;

            $('#confirm-add-ingredient').on('click', function () {
                $('#ingredient-table input[type="checkbox"]:checked').each(function () {
                    const ingredientId = $(this).val();
                    const ingredientName = $(this).closest('tr').find('.ingredient-name').first().text().trim();
                    const ingredientCode = $(this).closest('tr').find('.ingredient-name').last().text().trim();

                    // chek đã tồn tại trong bảng list nguyên liệu chưa
                    if ($('#ingredient-list').find('tr[data-id="' + ingredientId + '"]').length === 0) {
                        const newRow = `
                            <tr data-id="${ingredientId}">
                                <td class="text-center">${stt++}</td>
                                <td class="text-center">${ingredientCode}</td>
                                <td>${ingredientName}</td>
                                
                                <?php $__currentLoopData = $list_meal_age; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $age): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td class="text-center">
                                        <input type="number" class="form-control"
                                            name="json_params[quantitative][${ingredientId}][<?php echo e($age->code); ?>]"
                                            placeholder="Định lượng" value="0" step="any">
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger btn-remove-ingredient">X</button>
                                </td>
                            </tr>
                        `;

                        $('#ingredient-list').append(newRow);
                    }
                  
                });
                $('#addTPModal').modal('hide');
            });

            // xóa nguyên liệu
            $(document).on('click', '.btn-remove-ingredient', function () {
                $(this).closest('tr').remove();
                $('#ingredient-list tr').each(function (index) {
                    $(this).find('td:first').text(index + 1);
                });
                stt = $('#ingredient-list tr').length + 1;
            });
        });
        </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/meal/dishes/edit.blade.php ENDPATH**/ ?>