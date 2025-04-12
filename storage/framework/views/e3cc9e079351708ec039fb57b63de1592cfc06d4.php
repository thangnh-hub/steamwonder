

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

        <form role="form" action="<?php echo e(route(Request::segment(2) . '.store')); ?>" method="POST" id="form_product">
            <?php echo csrf_field(); ?>
            <?php if(Request::get('lang') != '' && Request::get('lang') != $item->lang_locale): ?>
                <input type="hidden" name="lang" value="<?php echo e(Request::get('lang')); ?>">
            <?php endif; ?>
            <input type="hidden" name="admission_id"
                value="<?php echo e(isset($admin_auth->id) && $admin_auth->id ? $admin_auth->id : ''); ?>">
            <?php if($lang != ''): ?>
                <input type="hidden" name="lang" value="<?php echo e($lang); ?>">
            <?php endif; ?>
            <div class="row">
                <div class="col-lg-9">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Create form'); ?></h3>
                            <?php if(isset($languages)): ?>
                                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($item->is_default == 1 && $item->lang_locale != Request::get('lang')): ?>
                                        <?php if(Request::get('lang') != ''): ?>
                                            <a class="text-primary pull-right"
                                                href="<?php echo e(route(Request::segment(2) . '.create')); ?>" style="padding-left: 15px">
                                                <i class="fa fa-language"></i> <?php echo e(__($item->lang_name)); ?>

                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if(Request::get('lang') != $item->lang_locale): ?>
                                            <a class="text-primary pull-right"
                                                href="<?php echo e(route(Request::segment(2) . '.create')); ?>?lang=<?php echo e($item->lang_locale); ?>"
                                                style="padding-left: 15px">
                                                <i class="fa fa-language"></i> <?php echo e(__($item->lang_name)); ?>

                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->

                        <?php echo csrf_field(); ?>
                        <div class="box-body hidden">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1_1" data-toggle="tab">
                                            <h5>Thông tin đăng nhập</h5>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1_1">
                                        <div class="d-flex-wap">

                                            
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->

                        </div>
                        <!-- /.box-body -->
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_2_1" data-toggle="tab">
                                            <h5>Thông tin học viên<span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_2_1">
                                        <div class="d-flex-wap">

                                            <div class="col-xs-12 col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Full name'); ?> <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="<?php echo app('translator')->get('Full name'); ?>" value="<?php echo e(old('name')); ?>"
                                                        required>
                                                </div>
                                            </div>
                                            

                                            <div class="col-md-4 hidden">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Phone'); ?></label>
                                                    <input type="text" class="form-control" name="phone"
                                                        placeholder="<?php echo app('translator')->get('Phone'); ?>" value="<?php echo e(old('phone')); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4 hidden">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Birthday'); ?></label>
                                                    <input type="date" class="form-control" name="birthday"
                                                        placeholder="<?php echo app('translator')->get('Birthday'); ?>" value="<?php echo e(old('birthday')); ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12 hidden">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Address'); ?></label>
                                                    <textarea name="json_params[address]" class="form-control" rows="5"><?php echo e(old('json_params[address]')); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                        </div>
                        <div class="box-body hidden">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_3_1" data-toggle="tab">
                                            <h5>Thông tin tuyển sinh</h5>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_3_1">
                                        <div class="d-flex-wap">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Entry Level'); ?></label>
                                                    <select name="json_params[entry_level_id]"
                                                        class=" form-control select2">
                                                        <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                        <?php $__currentLoopData = $entry_level; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($val->id); ?>"
                                                                <?php echo e(isset($detail->json_params->entry_level_id) && $detail->json_params->entry_level_id == $val->id ? 'selected' : ''); ?>>
                                                                <?php echo e($val->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Major'); ?></label>
                                                    <select name="json_params[major_id]" class=" form-control select2">
                                                        <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                        <?php $__currentLoopData = $major; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($val->id); ?>"
                                                                <?php echo e(isset($detail->json_params->major_id) && $detail->json_params->major_id == $val->id ? 'selected' : ''); ?>>
                                                                <?php echo e($val->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Forms of training'); ?></label>
                                                    <select name="json_params[forms_training]"
                                                        class=" form-control select2">
                                                        <?php $__currentLoopData = $forms_training; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                            <option value="<?php echo e($key); ?>"
                                                                <?php echo e(isset($detail->json_params->forms_training) && $detail->json_params->forms_training == $val ? 'selected' : ''); ?>>
                                                                <?php echo app('translator')->get($val); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Admissions'); ?></label>
                                                    <select name="admission_id" class=" form-control select2">
                                                        <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                        <?php $__currentLoopData = $admission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($val->id); ?>"
                                                                <?php echo e(isset($admin_auth->id) && $admin_auth->id == $val->id ? 'selected' : ''); ?>>
                                                                <?php echo e($val->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Field'); ?></label>
                                                    <select name="field_id[]" multiple="multiple"
                                                        class="form-control select2">
                                                        <?php $__currentLoopData = $field; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($val->id); ?>">
                                                                <?php echo e($val->json_params->name->$lang ?? $val->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="box-body hidden">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_4_1" data-toggle="tab">
                                            <h5>Thông tin gia đình</h5>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_5_1">
                                        <div class="d-flex-wap">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Father full name'); ?></label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[dad_name]" placeholder="<?php echo app('translator')->get('Father full name'); ?>"
                                                        value="<?php echo e(old('json_params[dad_name]')); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Father phone'); ?></label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[dad_phone]" placeholder="<?php echo app('translator')->get('Father phone'); ?>"
                                                        value="<?php echo e(old('json_params[dad_phone]')); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Mother full name'); ?></label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[mami_name]" placeholder="<?php echo app('translator')->get('Mother full name'); ?>"
                                                        value="<?php echo e(old('json_params[mami_name]')); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Mother phone'); ?></label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[mami_phone]" placeholder="<?php echo app('translator')->get('Mother phone'); ?>"
                                                        value="<?php echo e(old('json_params[mami_phone]')); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_5_1" data-toggle="tab">
                                            <h5>Thông tin giấy tờ tùy thân</h5>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_5_1">
                                        <div class="d-flex-wap">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('CCCD / CMT'); ?> <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="json_params[cccd]"
                                                        placeholder="<?php echo app('translator')->get('CCCD / CMT'); ?>"
                                                        value="<?php echo e(old('json_params[cccd]')); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Date range'); ?></label>
                                                    <input type="date" class="form-control"
                                                        name="json_params[date_range]" placeholder="<?php echo app('translator')->get('Date range'); ?>"
                                                        value="<?php echo e(old('json_params[date_range]')); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Issued by'); ?></label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[issued_by]" placeholder="<?php echo app('translator')->get('Issued by'); ?>"
                                                        value="<?php echo e(old('json_params[issued_by]')); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">

                    <div class="box box-primary hidden">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Role'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Role'); ?></label>
                                <select name="role" id="role" class="form-control select2">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php if(isset($status)): ?>
                        <div class="box box-primary hidden">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo app('translator')->get('Status'); ?></h3>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <select name="status_study" class=" form-control select2">
                                        <option value=""><?php echo app('translator')->get('Chọn trạng thái'); ?></option>
                                        <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($val->id); ?>"
                                                <?php echo e(isset($detail->status_study) && $detail->status_study == $val->id ? 'selected' : ''); ?>>
                                                <?php echo app('translator')->get($val->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Gender'); ?> <small class="text-red">*</small></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="gender" class=" form-control select2" required>
                                    <option value="" selected disabled><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $gender; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($detail->gender) && $detail->gender == $val ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Thuộc khu vực'); ?> <span class="text-danger">*</span></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="area_id" required class=" form-control select2">
                                    <option value="" selected disabled><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($items->id); ?>"
                                            <?php echo e(isset($detail->area_id) && $detail->area_id == $items->id ? 'selected' : ''); ?>>
                                            <?php echo e(__($items->code)); ?>

                                            - <?php echo e(__($items->name)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Khóa học'); ?> <small class="text-red">*</small></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="course_id" class=" form-control select2" required>
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($items->id); ?>"
                                            <?php echo e(isset($detail->course_id) && $detail->course_id == $items->id ? 'selected' : ''); ?>>
                                            <?php echo e(__($items->name)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Chỗ ở'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="json_params[dormitory]" class="form-control select2">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $dormitory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($detail->json_params->dormitory) && $detail->json_params->dormitory == $key ? 'selected' : ''); ?>>
                                            <?php echo e(__($items)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary hidden">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Contract'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Contract type'); ?></label>
                                <select name="json_params[contract_type]" class=" form-control select2">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $contract_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($detail->json_params->contract_type) && $detail->json_params->contract_type == $val ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Contract status'); ?></label>
                                <select name="json_params[contract_status]" class=" form-control select2">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $contract_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($detail->json_params->contract_status) && $detail->json_params->contract_status == $val ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Contract performance status'); ?></label>
                                <select name="json_params[contract_performance_status]" class=" form-control select2">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $contract_performance_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($detail->json_params->contract_performance_status) && $detail->json_params->contract_performance_status == $val ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary hidden">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Avatar'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group box_img_right <?php echo e(isset($detail->avatar) ? 'active' : ''); ?>">
                                <div id="avatar-holder">
                                    <img src="<?php echo e(url('themes/admin/img/no_image.jpg')); ?>">
                                </div>
                                <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-trash"></i></span>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a data-input="avatar" data-preview="avatar-holder" class="btn btn-primary lfm"
                                            data-type="cms-avatar">
                                            <i class="fa fa-picture-o"></i> <?php echo app('translator')->get('Choose'); ?>
                                        </a>
                                    </span>
                                    <input id="avatar" class="form-control inp_hidden" type="hidden" name="avatar"
                                        placeholder="<?php echo app('translator')->get('Image source'); ?>" value="<?php echo e($detail->avatar ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Publish'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="btn-set">
                                <button type="submit" class="btn btn-info">
                                    <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                                </button>
                                &nbsp;&nbsp;
                                <a class="btn btn-success " href="<?php echo e($admin_auth->role == 11 ? route('student.cskh') : route(Request::segment(2) . '.index')); ?>">
                                    <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        CKEDITOR.replace('content_vi', ck_options);

        $(document).ready(function() {
            // Fill Available Blocks by template
            $(document).on('click', '.btn_search', function() {
                let keyword = $('#search_title_post').val();
                let taxonomy_id = $('#search_taxonomy_id').val();
                let _targetHTML = $('#post_available');
                _targetHTML.html('');
                let checked_post = [];
                $("input:checkbox:checked").each(function() {
                    checked_post.push($(this).val());
                });

                let url = "<?php echo e(route('cms_product.search')); ?>";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        keyword: keyword,
                        taxonomy_id: taxonomy_id,
                        other_list: checked_post,
                        is_type: "<?php echo e(App\Consts::TAXONOMY['product']); ?>"
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data || null;
                            let _item = '';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<tr>';
                                    _item += '<td>' + item.id + '</td>';
                                    _item += '<td>' + item.name + '</td>';
                                    _item += '<td>' + item.is_type + '</td>';
                                    _item += '<td>' + formatDate(item.created_at) +
                                        '</td> ';
                                    _item +=
                                        '<td><input name="json_params[related_post][]" type="checkbox" value="' +
                                        item.id +
                                        '" class="mr-15 related_post_item cursor" autocomplete="off"></td>';
                                    _item += '</tr>';
                                });
                                _targetHTML.html(_item);
                            }
                        } else {
                            _targetHTML.html('<tr><td colspan="5">' + response.message +
                                '</td></tr>');
                        }
                    },
                    error: function(response) {
                        // Get errors
                        let errors = response.responseJSON.message;
                        _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                    }
                });
            });

            // Checked and unchecked item event
            $(document).on('click', '.related_post_item', function() {
                let ischecked = $(this).is(':checked');
                let _root = $(this).closest('tr');
                let _targetHTML;

                if (ischecked) {
                    _targetHTML = $("#post_related");
                } else {
                    _targetHTML = $("#post_available");
                }
                _targetHTML.append(_root);
            });



            var no_image_link = '<?php echo e(url('themes/admin/img/no_image.jpg')); ?>';

            $('.add-gallery-image').click(function(event) {
                let keyRandom = new Date().getTime();
                let elementParent = $('.list-gallery-image');
                let elementAppend =
                    '<div class="col-lg-3 col-md-3 col-sm-4 mb-1 gallery-image my-15">';
                elementAppend += '<img class="img-width"';
                elementAppend += 'src="' + no_image_link + '">';
                elementAppend += '<input type="text" name="json_params[gallery_image][' + keyRandom +
                    ']" class="hidden" id="gallery_image_' + keyRandom +
                    '">';
                elementAppend += '<div class="btn-action">';
                elementAppend +=
                    '<span class="btn btn-sm btn-success btn-upload lfm mr-5" data-input="gallery_image_' +
                    keyRandom +
                    '" data-type="cms-image">';
                elementAppend += '<i class="fa fa-upload"></i>';
                elementAppend += '</span>';
                elementAppend += '<span class="btn btn-sm btn-danger btn-remove">';
                elementAppend += '<i class="fa fa-trash"></i>';
                elementAppend += '</span>';
                elementAppend += '</div>';
                elementParent.append(elementAppend);

                $('.lfm').filemanager('image', {
                    prefix: route_prefix
                });
            });


            // Change image for img tag gallery-image
            $('.list-gallery-image').on('change', 'input', function() {
                let _root = $(this).closest('.gallery-image');
                var img_path = $(this).val();
                _root.find('img').attr('src', img_path);
            });

            // Delete image
            $('.list-gallery-image').on('click', '.btn-remove', function() {
                // if (confirm("<?php echo app('translator')->get('confirm_action'); ?>")) {
                let _root = $(this).closest('.gallery-image');
                _root.remove();
                // }
            });

            $('.list-gallery-image').on('mouseover', '.gallery-image', function(e) {
                $(this).find('.btn-action').show();
            });
            $('.list-gallery-image').on('mouseout', '.gallery-image', function(e) {
                $(this).find('.btn-action').hide();
            });


            $('.inp_hidden').on('change', function() {
                $(this).parents('.box_img_right').addClass('active');
            });

            $('.box_img_right').on('click', '.btn-remove', function() {
                let par = $(this).parents('.box_img_right');
                par.removeClass('active');
                par.find('img').attr('src', no_image_link);
                par.find('.input[type=hidden]').val("");
            });


            // Routes get all
            var routes = <?php echo json_encode(App\Consts::ROUTE_NAME ?? [], 15, 512) ?>;
            $(document).on('change', '#route_name', function() {
                let _value = $(this).val();
                let _targetHTML = $('#template');
                let _list = filterArray(routes, 'name', _value);
                let _optionList = '<option value=""><?php echo app('translator')->get('Please select'); ?></option>';
                if (_list) {
                    _list.forEach(element => {
                        element.template.forEach(item => {
                            _optionList += '<option value="' + item.name + '"> ' + item
                                .title + ' </option>';
                        });
                    });
                    _targetHTML.html(_optionList);
                }
                $(".select2").select2();
            });

            //add space
            $('.add_space').on('click', function() {
                var _item =
                    "<input type='text' class='form-control form-group' name='json_product[space][]' placeholder='Nhập không gian' value=''>";
                $('.defautu_space').append(_item);
            });

            $('.add_convenient').on('click', function() {
                var _item = "";
                _item += "<div class='col-md-3 form-group'>";
                _item +=
                    "<input type='text' class='form-control' name='json_product[convenient][icon][]' placeholder='Icon' value=''>";
                _item += "</div>";
                _item += "<div class='col-md-9 form-group'>";
                _item +=
                    "<input type='text' class='form-control' name='json_product[convenient][name][]' placeholder='Nhập tiện nghi' value=''>";
                _item += "</div>";

                $('.defaunt_convenient').append(_item);
            });
            $('.ck_ty').on('change', function() {
                if ($("#form_product input[name='type']:checked").val() == 2) {
                    $('#type_price').attr("disabled", "true");
                } else {
                    $('#type_price').removeAttr('disabled');

                }

            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/students/create.blade.php ENDPATH**/ ?>