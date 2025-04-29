


<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }
?>
<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2) . '.create')); ?>"><i
                    class="fa fa-plus"></i> <?php echo app('translator')->get('Thêm mới học viên'); ?></a>
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
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin học sinh <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_2" data-toggle="tab">
                                            <h5>Người thân của bé</h5>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="box-body">
                                            <div class="d-flex-wap">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Khu vực'); ?><small class="text-red">*</small></label>
                                                        <select name="area_id" class="form-control select2" required>
                                                            <option value=""><?php echo app('translator')->get('Chọn khu vực'); ?></option>
                                                            <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($val->id); ?>" <?php echo e(old('area_id', $detail->area_id) == $val->id ? 'selected' : ''); ?>>
                                                                    <?php echo e($val->name); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Last Name'); ?><small class="text-red">*</small></label>
                                                        <input type="text" class="form-control" name="last_name" value="<?php echo e(old('last_name', $detail->last_name)); ?>" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('First Name'); ?><small class="text-red">*</small></label>
                                                        <input type="text" class="form-control" name="first_name" value="<?php echo e(old('first_name', $detail->first_name)); ?>" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Tên thường gọi'); ?></label>
                                                        <input type="text" class="form-control" name="nickname" value="<?php echo e(old('nickname', $detail->nickname)); ?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Giới tính'); ?></label>
                                                        <select name="sex" class="form-control select2">
                                                            <?php $__currentLoopData = $list_sex; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($key); ?>" <?php echo e(old('sex', $detail->sex) == $key ? 'selected' : ''); ?>>
                                                                    <?php echo e(__($value)); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Ngày sinh'); ?></label>
                                                        <input type="date" class="form-control" name="birthday" value="<?php echo e(old('birthday', $detail->birthday)); ?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Ngày nhập học'); ?></label>
                                                        <input type="date" class="form-control" name="enrolled_at" value="<?php echo e(old('enrolled_at', $detail->enrolled_at)); ?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Trạng thái'); ?></label>
                                                        <select name="status" class="form-control select2">
                                                            <?php $__currentLoopData = $list_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($key); ?>" <?php echo e(old('status', $detail->status) == $key ? 'selected' : ''); ?>>
                                                                    <?php echo e(__($value)); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group box_img_right">
                                                        <label><?php echo app('translator')->get('Ảnh đại diện'); ?></label>
                                                        <div id="image-holder">
                                                            <img src="<?php echo e(!empty($detail->avatar) ? asset($detail->avatar) : url('themes/admin/img/no_image.jpg')); ?>" style="max-height: 120px;">
                                                        </div>
                                                        <div class="input-group">
                                                            <span class="input-group-btn">
                                                                <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm" data-type="cms-image">
                                                                    <i class="fa fa-picture-o"></i> <?php echo app('translator')->get('Choose'); ?>
                                                                </a>
                                                            </span>
                                                            <input id="image" class="form-control inp_hidden" type="hidden" name="avatar"
                                                                placeholder="<?php echo app('translator')->get('Image source'); ?>" value="<?php echo e(old('avatar', $detail->avatar)); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane " id="tab_2">
                                        <div class="box-body ">
                                            <div>
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addParentModal">
                                                    <i class="fa fa-plus"></i> <?php echo app('translator')->get('Cập nhật người thân'); ?>
                                                </button>     
                                            </div>
                                            
                                            <br>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo app('translator')->get('STT'); ?></th>
                                                        <th><?php echo app('translator')->get('Avatar'); ?></th>
                                                        <th><?php echo app('translator')->get('Họ và tên'); ?></th>
                                                        <th><?php echo app('translator')->get('Giới tính'); ?></th>
                                                        <th><?php echo app('translator')->get('Ngày sinh'); ?></th>
                                                        <th><?php echo app('translator')->get('Số CMND/CCCD'); ?></th>
                                                        <th><?php echo app('translator')->get('Số điện thoại'); ?></th>
                                                        <th><?php echo app('translator')->get('Email'); ?></th>  
                                                        <th><?php echo app('translator')->get('Địa chỉ'); ?></th>
                                                        <th><?php echo app('translator')->get('Khu vực'); ?></th>
                                                        <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                                        <th><?php echo app('translator')->get('Quan hệ'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if($detail->studentParents->count()): ?>
                                                    <?php $__currentLoopData = $detail->studentParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr class="valign-middle">
                                                            <td>
                                                                <?php echo e($loop->iteration); ?>

                                                            </td>
                                                            <td>
                                                                <?php if(!empty($row->parent->avatar)): ?>
                                                                    <img src="<?php echo e(asset($row->parent->avatar)); ?>" alt="Avatar" width="100" height="100" style="object-fit: cover;">
                                                                <?php else: ?>
                                                                    <span class="text-muted">No image</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <a target="_blank" href="<?php echo e(route('parents.show', $row->parent->id)); ?>">
                                                                    <?php echo e($row->parent->first_name ?? ''); ?> <?php echo e($row->parent->last_name ?? ''); ?>  
                                                                </a>
                                                            </td>
                                                            <td><?php echo app('translator')->get($row->parent->sex ?? ''); ?></td>
                                                            <td><?php echo e($row->parent->birthday ? \Carbon\Carbon::parse($row->parent->birthday)->format('d/m/Y') : ''); ?></td>
                                                            <td><?php echo e($row->parent->identity_card ?? ''); ?></td>
                                                            <td><?php echo e($row->parent->phone ?? ''); ?></td>
                                                            <td><?php echo e($row->parent->email ?? ''); ?></td>
                                                            <td><?php echo e($row->parent->address ?? ''); ?></td>
                                                            <td><?php echo e($row->parent->area->name ?? ''); ?></td>
                                                            <td><?php echo app('translator')->get($row->parent->status ?? ''); ?></td>
                                                            <td><?php echo e($row->relationship->title ?? ''); ?></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="14" class="text-center">Không có dữ liệu</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                                
                                            </table>
                                            
                                        </div>                      
                                    </div>
                                </div> <!-- tab-content -->
                            </div>
                        </div>
                    
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                            </button>
                            <a href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                <button type="button" class="btn btn-sm btn-success"><?php echo app('translator')->get('Danh sách'); ?></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>    
        </form>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="addParentModal" tabindex="-1" role="dialog" aria-labelledby="addParentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
        <form action="<?php echo e(route('student.addParent', $detail->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addParentModalLabel"><?php echo app('translator')->get('Chọn người thân'); ?></h5>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="search-parent" placeholder="<?php echo app('translator')->get('Tìm theo tên phụ huynh...'); ?>">
                        </div>
                        <table class="table table-hover table-bordered" id="parent-table">
                            <thead>
                                <tr>
                                    <th>Chọn</th>
                                    <th><?php echo app('translator')->get('Họ và tên'); ?></th>
                                    <th><?php echo app('translator')->get('Giới tính'); ?></th>
                                    <th><?php echo app('translator')->get('Số điện thoại'); ?></th>
                                    <th><?php echo app('translator')->get('Email'); ?></th>
                                    <th><?php echo app('translator')->get('Chọn mối quan hệ'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $allParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $isChecked = in_array($parent->id, $studentParentIds);
                                    $existingRelation = $detail->studentParents->firstWhere('parent_id', $parent->id);
                                ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="parents[<?php echo e($parent->id); ?>][id]" value="<?php echo e($parent->id); ?>" <?php echo e($isChecked ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="parent-name"><?php echo e($parent->first_name); ?> <?php echo e($parent->last_name); ?></td>
                                        <td><?php echo app('translator')->get($parent->sex); ?></td>
                                        <td><?php echo e($parent->phone); ?></td>
                                        <td><?php echo e($parent->email); ?></td>
                                        <td>
                                            <select style="width:100%" name="parents[<?php echo e($parent->id); ?>][relationship_id]" class="form-control select2">
                                                <?php $__currentLoopData = $list_relationship; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option <?php echo e($existingRelation && $existingRelation->relationship_id == $relation->id ? 'selected' : ''); ?> value="<?php echo e($relation->id); ?>"><?php echo e($relation->title); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('Lưu người thân đã chọn'); ?></button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo app('translator')->get('Đóng'); ?></button>
                </div>
            </div>
        </form>
        </div>
    </div>
  
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $('#search-parent').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $('#parent-table tbody tr').filter(function() {
                $(this).toggle($(this).find('.parent-name').text().toLowerCase().indexOf(value) > -1);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/students/edit.blade.php ENDPATH**/ ?>