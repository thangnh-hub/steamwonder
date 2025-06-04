

<?php $__env->startSection('title'); ?>
    <?php echo e($module_name); ?>

<?php $__env->stopSection(); ?>
<?php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }

?>
<?php $__env->startSection('style'); ?>
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo e($module_name); ?>

            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2) . '.create')); ?>">
                <i class="fa fa-plus"></i> <?php echo app('translator')->get('Add'); ?>
            </a>
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
                <?php if(isset($languages)): ?>
                    <div class="collapse navbar-collapse pull-right">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-language"></i>
                                    <?php echo e(Request::get('lang') && Request::get('lang') != $languageDefault->lang_code
                                        ? $languages->first(function ($item, $key) use ($lang) {
                                            return $item->lang_code == $lang;
                                        })['lang_name']
                                        : $languageDefault->lang_name); ?>

                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($item->lang_code != $languageDefault->lang_code): ?>
                                            <li>
                                                <a href="<?php echo e(route(Request::segment(2) . '.edit', $detail->id)); ?>?lang=<?php echo e($item->lang_locale); ?>"
                                                    style="padding-top:10px; padding-bottom:10px;">
                                                    <i class="fa fa-language"></i>
                                                    <?php echo e($item->lang_name); ?>

                                                </a>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <a href="<?php echo e(route(Request::segment(2) . '.edit', $detail->id)); ?>"
                                                    style="padding-top:10px; padding-bottom:10px;">
                                                    <i class="fa fa-language"></i>
                                                    <?php echo e($item->lang_name); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <span class="pull-right" style="padding: 15px"><?php echo app('translator')->get('Language'); ?>: </span>
                <?php endif; ?>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab">
                                <h5>
                                    <?php echo app('translator')->get('General information'); ?>
                                    <span class="text-danger">*</span>
                                </h5>
                            </a>
                        </li>

                        <a class="btn btn-success btn-sm pull-right" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                            <i class="fa fa-bars"></i>
                            <?php echo app('translator')->get('List'); ?>
                        </a>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <!-- form start -->
                            <form role="form" action="<?php echo e(route(Request::segment(2) . '.update', $detail->id)); ?>"
                                method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <?php if(Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale): ?>
                                    <input type="hidden" name="lang" value="<?php echo e(Request::get('lang')); ?>">
                                <?php endif; ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Title'); ?>
                                                <small class="text-red">*</small>
                                            </label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="<?php echo app('translator')->get('Title'); ?>"
                                                value="<?php echo e($detail->json_params->name->$lang ?? $detail->name); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Menu type'); ?> <small class="text-red">*</small></label>
                                            <select name="menu_type" id="menu_type" class="form-control select2" required>
                                                <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                <?php $__currentLoopData = $menu_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($key); ?>" <?php echo e($detail->menu_type == $key?'selected':''); ?>>
                                                        <?php echo app('translator')->get($value); ?>
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Status'); ?></label>
                                            <div class="form-control">
                                                <?php $__currentLoopData = App\Consts::STATUS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <label>
                                                        <input type="radio" name="status" value="<?php echo e($value); ?>"
                                                            <?php echo e($detail->status == $value ? 'checked' : ''); ?>>
                                                        <small class="mr-15"><?php echo e(__($value)); ?></small>
                                                    </label>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Order'); ?></label>
                                            <input type="number" class="form-control" name="iorder"
                                                placeholder="<?php echo app('translator')->get('Order'); ?>" value="<?php echo e($detail->iorder); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Description'); ?></label>
                                            <textarea name="description" id="description" class="form-control" rows="3"><?php echo e($detail->json_params->description->$lang ?? $detail->description); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-floppy-o"></i>
                                            <?php echo app('translator')->get('Save'); ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12">
                                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                                </div>
                                <div class="col-md-6">
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">
                                                <?php echo app('translator')->get('Page list'); ?>
                                            </h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="panel-group" id="accordion">
                                                <?php if(isset($pages)): ?>
                                                    <div class="widget meta-boxes">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#taxonomy"
                                                            aria-expanded="false" class="collapsed">
                                                            <h4 class="widget-title">
                                                                <span><?php echo app('translator')->get('Pages'); ?></span>
                                                                <i class="fa fa-angle-down narrow-icon"></i>
                                                            </h4>
                                                        </a>
                                                        <div id="taxonomy" class="panel-collapse collapse">
                                                            <div class="widget-body">
                                                                <div class="box-links-for-menu">
                                                                    <div class="the-box">
                                                                        <form
                                                                            action="<?php echo e(route(Request::segment(2) . '.store')); ?>"
                                                                            method="POST" class="form-horizontal"
                                                                            id="form-main" enctype="multipart/form-data">
                                                                            <?php echo csrf_field(); ?>
                                                                            <?php echo method_field('POST'); ?>
                                                                            <?php if(Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale): ?>
                                                                                <input type="hidden" name="lang"
                                                                                    value="<?php echo e(Request::get('lang')); ?>">
                                                                            <?php endif; ?>
                                                                            <input type="hidden" name="name"
                                                                                value="name_menu">
                                                                            <input type="hidden" name="parent_id"
                                                                                value="<?php echo e($detail->id); ?>">
                                                                            <ul class="list-item mCustomScrollbar _mCS_1 mCS_no_scrollbar"
                                                                                style="padding: 0px;">
                                                                                <div id="mCSB_1"
                                                                                    class="mCustomScrollBox mCS-dark mCSB_vertical mCSB_inside"
                                                                                    style="max-height: none;" tabindex="0">
                                                                                    <div id="mCSB_1_container"
                                                                                        class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y"
                                                                                        style="position:relative; top:0; left:0;"
                                                                                        dir="ltr">
                                                                                        <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <li>
                                                                                                <label
                                                                                                    for="page-<?php echo e($item->id); ?>">
                                                                                                    <input
                                                                                                        id="page-<?php echo e($item->id); ?>"
                                                                                                        name="menu_page[]"
                                                                                                        type="checkbox"
                                                                                                        value="<?php echo e($item->id); ?>">
                                                                                                    <?php echo e($item->json_params->title->$lang ?? $item->title); ?>

                                                                                            </li>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    </div>
                                                                                </div>
                                                                            </ul>
                                                                            <div class="text-end">
                                                                                <div class="btn-group btn-group-devided">
                                                                                    <button type="submit"
                                                                                        class="btn-add-to-menu btn btn-primary">
                                                                                        <span class="text"><i
                                                                                                class="fa fa-plus"></i>
                                                                                            <?php echo app('translator')->get('Add to menu'); ?></span>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if(isset($taxonomy)): ?>
                                                    <div class="widget meta-boxes">
                                                        <a data-toggle="collapse" data-parent="#accordion"
                                                            href="#categories-1677117492" class="collapsed"
                                                            aria-expanded="false">
                                                            <h4 class="widget-title">
                                                                <span><?php echo app('translator')->get('Taxonomy'); ?></span>
                                                                <i class="fa fa-angle-down narrow-icon"></i>
                                                            </h4>
                                                        </a>
                                                        <div id="categories-1677117492" class="panel-collapse collapse">
                                                            <div class="widget-body">
                                                                <div class="box-links-for-menu">
                                                                    <div class="the-box">
                                                                        <form
                                                                            action="<?php echo e(route(Request::segment(2) . '.store')); ?>"
                                                                            method="POST" class="form-horizontal"
                                                                            id="form-main" enctype="multipart/form-data">
                                                                            <?php echo csrf_field(); ?>
                                                                            <?php echo method_field('POST'); ?>
                                                                            <?php if(Request::get('lang') != '' && Request::get('lang') != $lang): ?>
                                                                                <input type="hidden" name="lang"
                                                                                    value="<?php echo e(Request::get('lang')); ?>">
                                                                            <?php endif; ?>
                                                                            <input type="hidden" name="name"
                                                                                value="name_menu">
                                                                            <input type="hidden" name="parent_id"
                                                                                value="<?php echo e($detail->id); ?>">
                                                                            <ul class="list-item mCustomScrollbar _mCS_2 mCS_no_scrollbar"
                                                                                style="padding: 0px;">
                                                                                <div id="mCSB_2"
                                                                                    class="mCustomScrollBox mCS-dark mCSB_vertical mCSB_inside"
                                                                                    tabindex="0" style="max-height: none;">
                                                                                    <div id="mCSB_2_container"
                                                                                        class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y"
                                                                                        style="position:relative; top:0; left:0;"
                                                                                        dir="ltr">
                                                                                        <?php $__currentLoopData = $taxonomy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <?php if($row->parent_id == 0 || $row->parent_id == null): ?>
                                                                                                <li>
                                                                                                    <label
                                                                                                        for="menu_<?php echo e($row->id); ?>">
                                                                                                        <input
                                                                                                            id="menu_<?php echo e($row->id); ?>"
                                                                                                            name="menu_taxonomy[]"
                                                                                                            type="checkbox"
                                                                                                            value="<?php echo e($row->id); ?>">
                                                                                                        <?php echo e($row->json_params->name->$lang ?? $row->names); ?>

                                                                                                    </label>
                                                                                                    <ul>
                                                                                                        <?php $__currentLoopData = $taxonomy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                            <?php if($item->parent_id == $row->id): ?>
                                                                                                                <li>
                                                                                                                    <label
                                                                                                                        for="menu_chil_<?php echo e($item->id); ?>">
                                                                                                                        <input
                                                                                                                            id="menu_chil_<?php echo e($item->id); ?>"
                                                                                                                            name="menu_taxonomy[]"
                                                                                                                            type="checkbox"
                                                                                                                            value="<?php echo e($item->id); ?>">
                                                                                                                        <?php echo e($item->json_params->name->$lang ?? $item->name); ?>

                                                                                                                    </label>
                                                                                                                    <ul>
                                                                                                                        <?php $__currentLoopData = $taxonomy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                            <?php if($item_child->parent_id == $item->id): ?>
                                                                                                                                <li>
                                                                                                                                    <label
                                                                                                                                        for="menu_chil_<?php echo e($item_child->id); ?>">
                                                                                                                                        <input
                                                                                                                                            id="menu_chil_<?php echo e($item_child->id); ?>"
                                                                                                                                            name="menu_taxonomy[]"
                                                                                                                                            type="checkbox"
                                                                                                                                            value="<?php echo e($item_child->id); ?>">
                                                                                                                                        <?php echo e($item_child->json_params->name->$lang ?? $item_child->name); ?>

                                                                                                                                    </label>
                                                                                                                                    <ul>
                                                                                                                                    </ul>
                                                                                                                                </li>
                                                                                                                            <?php endif; ?>
                                                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                                    </ul>
                                                                                                                </li>
                                                                                                            <?php endif; ?>
                                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                    </ul>
                                                                                            <?php endif; ?>
                                                                                            </li>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    </div>
                                                                                </div>
                                                                            </ul>
                                                                            <div class="text-end">
                                                                                <div class="btn-group btn-group-devided">
                                                                                    <button type="submit"
                                                                                        class="btn-add-to-menu btn btn-primary">
                                                                                        <span class="text"><i
                                                                                                class="fa fa-plus"></i>
                                                                                            <?php echo app('translator')->get('Add to menu'); ?></span>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="widget meta-boxes">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#add-link"
                                                        class="collapsed" aria-expanded="false">
                                                        <h4 class="widget-title">
                                                            <span><?php echo app('translator')->get('Add link'); ?></span>
                                                            <i class="fa fa-angle-down narrow-icon"></i>
                                                        </h4>
                                                    </a>
                                                    <div id="add-link" class="panel-collapse collapse">
                                                        <div class="widget-body">
                                                            <div class="box-links-for-menu box box-primary">
                                                                <div class="the-box ">
                                                                    <form
                                                                        action="<?php echo e(route(Request::segment(2) . '.store')); ?>"
                                                                        method="POST" class="form-horizontal"
                                                                        id="form-main" enctype="multipart/form-data">
                                                                        <?php echo csrf_field(); ?>
                                                                        <?php echo method_field('POST'); ?>
                                                                        <?php if(Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale): ?>
                                                                            <input type="hidden" name="lang"
                                                                                value="<?php echo e(Request::get('lang')); ?>">
                                                                        <?php endif; ?>
                                                                        <div class="box-body">
                                                                            <input type="hidden" name="parent_id"
                                                                                value="<?php echo e($detail->id); ?>">
                                                                            <div class="form-group">
                                                                                <label for="link-name"
                                                                                    class="control-label">
                                                                                    <?php echo app('translator')->get('Title'); ?>
                                                                                    <small class="text-red">*</small>
                                                                                </label>

                                                                                <input type="text" class="form-control"
                                                                                    id="link-name"
                                                                                    placeholder="<?php echo app('translator')->get('Title'); ?>"
                                                                                    name="name" required
                                                                                    autocomplete="off">

                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="link-url_link"
                                                                                    class="control-label">
                                                                                    <?php echo app('translator')->get('Url'); ?>
                                                                                    <small class="text-red">*</small>
                                                                                </label>

                                                                                <input type="text" class="form-control"
                                                                                    id="link-url_link"
                                                                                    placeholder="<?php echo app('translator')->get('Url'); ?>"
                                                                                    value="/" name="url_link"
                                                                                    required autocomplete="off">

                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="link-target"
                                                                                    class="control-label">
                                                                                    <?php echo app('translator')->get('Select target'); ?>
                                                                                </label>

                                                                                <select name="json_params[target]"
                                                                                    id="link-target"
                                                                                    class="form-control select2"
                                                                                    autocomplete="off">
                                                                                    <option value="_self">
                                                                                        <?php echo app('translator')->get('_self'); ?></option>
                                                                                    <option value="_blank">
                                                                                        <?php echo app('translator')->get('_blank'); ?></option>
                                                                                </select>

                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="style_header"
                                                                                    class="control-label">
                                                                                    <?php echo app('translator')->get('Style'); ?>
                                                                                </label>
                                                                                <select name="json_params[style]"
                                                                                    id="style_header"
                                                                                    class="form-control select2"
                                                                                    autocomplete="off">
                                                                                    <option value="">
                                                                                        <?php echo app('translator')->get('Please select'); ?>
                                                                                    </option>
                                                                                    <?php $__currentLoopData = $style_header; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key_style => $val_style): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <option
                                                                                            value="<?php echo e($key_style); ?>">
                                                                                            <?php echo app('translator')->get($val_style); ?></option>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="link-status"
                                                                                    class="control-label">
                                                                                    <?php echo app('translator')->get('Status'); ?>
                                                                                </label>

                                                                                <div class="form-control">
                                                                                    <?php $__currentLoopData = App\Consts::STATUS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <label>
                                                                                            <input type="radio"
                                                                                                name="status"
                                                                                                value="<?php echo e($value); ?>"
                                                                                                <?php echo e($loop->index == 0 ? 'checked' : ''); ?>>
                                                                                            <small
                                                                                                class="mr-15"><?php echo e(__($value)); ?></small>
                                                                                        </label>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </div>

                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label
                                                                                    class="control-label">
                                                                                    <?php echo app('translator')->get('Icon'); ?>
                                                                                </label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="<?php echo app('translator')->get('Icon'); ?>"
                                                                                    name="json_params[icon]"
                                                                                    autocomplete="off">
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label><?php echo app('translator')->get('Image'); ?></label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn">
                                                                                        <a data-input="image_add"
                                                                                            data-preview="image-holder_add"
                                                                                            class="btn btn-primary lfm"
                                                                                            data-type="cms-image">
                                                                                            <i class="fa fa-picture-o"></i>
                                                                                            <?php echo app('translator')->get('Select'); ?>
                                                                                        </a>
                                                                                    </span>
                                                                                    <input id="image_add"
                                                                                        class="form-control"
                                                                                        type="text"
                                                                                        name="json_params[image]"
                                                                                        placeholder="<?php echo app('translator')->get('Image source'); ?>">
                                                                                </div>
                                                                                <div id="image-holder_add"
                                                                                    style="margin-top:15px;max-height:100px;">
                                                                                    <?php if(old('image') != ''): ?>
                                                                                        <img style="height: 5rem;"
                                                                                            src="<?php echo e(old('image')); ?>">
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="text-end">
                                                                            <div class="btn-group btn-group-devided">
                                                                                <button type="submit"
                                                                                    class="btn-add-to-menu btn btn-primary">
                                                                                    <span class="text"><i
                                                                                            class="fa fa-plus"></i>
                                                                                        <?php echo app('translator')->get('Add to menu'); ?></span>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">
                                                <?php echo app('translator')->get('Menu structure'); ?>
                                            </h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="table-responsive">
                                                <div class="dd" id="menu-sort">
                                                    <ol class="dd-list">
                                                        <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($item->parent_id == $detail->id): ?>
                                                                <li class="dd-item dd3-item "
                                                                    data-id="<?php echo e($item->id); ?>">
                                                                    <div class="dd-handle dd3-handle"></div>
                                                                    <div class="dd3-content">
                                                                        <span class="text float-start"
                                                                            data-update="title"><?php echo e($item->json_params->name->$lang ?? $item->name); ?></span>
                                                                        <span
                                                                            class="text float-end"><?php echo app('translator')->get($status[$item->status]); ?></span>
                                                                        <a data-toggle="collapse"
                                                                            href="#item-details-<?php echo e($item->id); ?>"
                                                                            role="button" aria-expanded="false"
                                                                            aria-controls="item-details-<?php echo e($item->id); ?>"
                                                                            class="show-item-details">
                                                                            <i class="fa fa-angle-down"></i>
                                                                        </a>
                                                                        <div class="clearfix"></div>
                                                                    </div>
                                                                    <div class="item-details collapse multi-collapse"
                                                                        id="item-details-<?php echo e($item->id); ?>">
                                                                        <form role="form"
                                                                            action="<?php echo e(route(Request::segment(2) . '.update', $item->id)); ?>"
                                                                            method="POST">
                                                                            <?php echo csrf_field(); ?>
                                                                            <?php echo method_field('PUT'); ?>
                                                                            <?php if(Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale): ?>
                                                                                <input type="hidden" name="lang"
                                                                                    value="<?php echo e(Request::get('lang')); ?>">
                                                                            <?php endif; ?>
                                                                            <div class="form-body">
                                                                                
                                                                                <div class="form-group mb-3">
                                                                                    <label for="menu-node-title-1"
                                                                                        class="control-label"
                                                                                        data-update="title"><?php echo app('translator')->get('Title'); ?></label>
                                                                                    <input class="form-control"
                                                                                        placeholder="Title"
                                                                                        data-old="Home"
                                                                                        id="menu-node-title-1"
                                                                                        v-pre="" name="name"
                                                                                        type="text"
                                                                                        value="<?php echo e($item->json_params->name->$lang ?? $item->name); ?>">
                                                                                </div>
                                                                                <div class="form-group mb-3">
                                                                                    <label for="menu-node-url-1"
                                                                                        class="control-label"
                                                                                        data-update="custom-url"><?php echo app('translator')->get('Url'); ?></label>
                                                                                    <input class="form-control"
                                                                                        placeholder="URL" data-old="/"
                                                                                        id="menu-node-url-1"
                                                                                        v-pre="" name="url_link"
                                                                                        type="text"
                                                                                        value="<?php echo e($item->url_link); ?>">

                                                                                </div>
                                                                                <div class="form-group mb-3">
                                                                                    <label for="link-target"
                                                                                        class="control-label">
                                                                                        <?php echo app('translator')->get('Select target'); ?>
                                                                                    </label>

                                                                                    <select name="json_params[target]"
                                                                                        id="link-target"
                                                                                        class="form-control select2"
                                                                                        autocomplete="off">
                                                                                        <option value="_self" selected>
                                                                                            <?php echo app('translator')->get('_self'); ?></option>
                                                                                        <option value="_blank">
                                                                                            <?php echo app('translator')->get('_blank'); ?></option>
                                                                                    </select>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="style_header_<?php echo e($item->id); ?>"
                                                                                        class="control-label">
                                                                                        <?php echo app('translator')->get('Style'); ?>
                                                                                    </label>
                                                                                    <select name="json_params[style]"
                                                                                        id="style_header_<?php echo e($item->id); ?>"
                                                                                        class="form-control select2"
                                                                                        autocomplete="off">
                                                                                        <option value="">
                                                                                            <?php echo app('translator')->get('Please select'); ?></option>
                                                                                        <?php $__currentLoopData = $style_header; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key_style => $val_style): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <option
                                                                                                value="<?php echo e($key_style); ?>"
                                                                                                <?php echo e(isset($item->json_params->style) && $item->json_params->style == $key_style ? 'selected' : ''); ?>>
                                                                                                <?php echo app('translator')->get($val_style); ?></option>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        class="control-label">
                                                                                        <?php echo app('translator')->get('Icon'); ?>
                                                                                    </label>
                                                                                    <input type="text" class="form-control"

                                                                                        placeholder="<?php echo app('translator')->get('Icon'); ?>"
                                                                                        name="json_params[icon]"
                                                                                        autocomplete="off"
                                                                                        value="<?php echo e($item->json_params->icon??old('json_params[icon]')); ?>">
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label><?php echo app('translator')->get('Image'); ?></label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn">
                                                                                            <a data-input="image-<?php echo e($item->id); ?>"
                                                                                                data-preview="image-holder-<?php echo e($item->id); ?>"
                                                                                                class="btn btn-primary lfm"
                                                                                                data-type="cms-image">
                                                                                                <i
                                                                                                    class="fa fa-picture-o"></i>
                                                                                                <?php echo app('translator')->get('Select'); ?>
                                                                                            </a>
                                                                                        </span>
                                                                                        <input
                                                                                            id="image-<?php echo e($item->id); ?>"
                                                                                            class="form-control"
                                                                                            type="text"
                                                                                            name="json_params[image]"
                                                                                            placeholder="<?php echo app('translator')->get('Image source'); ?>"
                                                                                            value="<?php echo e($item->json_params->image ?? ''); ?>">
                                                                                    </div>
                                                                                    <div id="image-holder-<?php echo e($item->id); ?>"
                                                                                        style="margin-top:15px;max-height:100px;">
                                                                                        <?php if(isset($item->json_params->image) && $item->json_params->image != ''): ?>
                                                                                            <img style="height: 5rem;"
                                                                                                src="<?php echo e($item->json_params->image ?? ''); ?>">
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group mb-3">
                                                                                    <label for="link-status"
                                                                                        class="control-label">
                                                                                        <?php echo app('translator')->get('Status'); ?>
                                                                                    </label>

                                                                                    <div class="form-control">
                                                                                        <?php $__currentLoopData = App\Consts::STATUS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <label
                                                                                                class=" col-12 col-xl-6">
                                                                                                <input type="radio"
                                                                                                    name="status"
                                                                                                    value="<?php echo e($value); ?>"
                                                                                                    <?php echo e($item->status == $value ? 'checked' : ''); ?>>
                                                                                                <small
                                                                                                    class="mr-15"><?php echo e(__($value)); ?></small>
                                                                                            </label>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                            <div class="clearfix"></div>
                                                                            <div class="text-end mt-2">

                                                                                <button
                                                                                    class="btn btn-primary btn-sm"><?php echo app('translator')->get('Save'); ?></button>
                                                                                <p class="btn btn-danger remove_menu btn-sm"
                                                                                    data-id="<?php echo e($item->id); ?>">
                                                                                    <?php echo app('translator')->get('Remove'); ?></p>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                    <?php if($item->sub > 0): ?>
                                                                        <ol class="dd-list">
                                                                            <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_sub_1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <?php if($item_sub_1->parent_id == $item->id): ?>
                                                                                    <li class="dd-item dd3-item"
                                                                                        data-id="<?php echo e($item_sub_1->id); ?>">
                                                                                        <div class="dd-handle dd3-handle">
                                                                                        </div>
                                                                                        <div class="dd3-content">
                                                                                            <span class="text float-start"
                                                                                                data-update="title"><?php echo e($item_sub_1->json_params->name->$lang ?? $item_sub_1->name); ?></span>
                                                                                            <span
                                                                                                class="text float-end"><?php echo app('translator')->get($status[$item_sub_1->status]); ?></span>
                                                                                            <a data-toggle="collapse"
                                                                                                href="#item-details-<?php echo e($item_sub_1->id); ?>"
                                                                                                role="button"
                                                                                                aria-expanded="false"
                                                                                                aria-controls="item-details-<?php echo e($item_sub_1->id); ?>"
                                                                                                class="show-item-details">
                                                                                                <i
                                                                                                    class="fa fa-angle-down"></i>
                                                                                            </a>
                                                                                            <div class="clearfix">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="item-details collapse multi-collapse"
                                                                                            id="item-details-<?php echo e($item_sub_1->id); ?>">
                                                                                            <form role="form"
                                                                                                action="<?php echo e(route(Request::segment(2) . '.update', $item_sub_1->id)); ?>"
                                                                                                method="POST">
                                                                                                <?php echo csrf_field(); ?>
                                                                                                <?php echo method_field('PUT'); ?>
                                                                                                <?php if(Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale): ?>
                                                                                                    <input type="hidden"
                                                                                                        name="lang"
                                                                                                        value="<?php echo e(Request::get('lang')); ?>">
                                                                                                <?php endif; ?>
                                                                                                <div class="form-body">
                                                                                                    
                                                                                                    <div
                                                                                                        class="form-group mb-3">
                                                                                                        <label
                                                                                                            for="menu-node-title-1"
                                                                                                            class="control-label"
                                                                                                            data-update="title"><?php echo app('translator')->get('Title'); ?></label>
                                                                                                        <input
                                                                                                            class="form-control"
                                                                                                            placeholder="Title"
                                                                                                            data-old="Home"
                                                                                                            id="menu-node-title-1"
                                                                                                            name="name"
                                                                                                            type="text"
                                                                                                            value="<?php echo e($item_sub_1->json_params->name->$lang ?? $item_sub_1->name); ?>">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="form-group mb-3">
                                                                                                        <label
                                                                                                            for="menu-node-url-1"
                                                                                                            class="control-label"
                                                                                                            data-update="custom-url"><?php echo app('translator')->get('Url'); ?></label>
                                                                                                        <input
                                                                                                            class="form-control"
                                                                                                            placeholder="URL"
                                                                                                            data-old="/"
                                                                                                            id="menu-node-url-1"
                                                                                                            name="url_link"
                                                                                                            type="text"
                                                                                                            value="<?php echo e($item_sub_1->url_link); ?>">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="form-group mb-3">
                                                                                                        <label
                                                                                                            for="link-target"
                                                                                                            class="control-label">
                                                                                                            <?php echo app('translator')->get('Select target'); ?>
                                                                                                        </label>

                                                                                                        <select
                                                                                                            name="json_params[target]"
                                                                                                            id="link-target"
                                                                                                            class="form-control select2"
                                                                                                            autocomplete="off">
                                                                                                            <option
                                                                                                                value="_self"
                                                                                                                selected>
                                                                                                                <?php echo app('translator')->get('_self'); ?>
                                                                                                            </option>
                                                                                                            <option
                                                                                                                value="_blank">
                                                                                                                <?php echo app('translator')->get('_blank'); ?>
                                                                                                            </option>
                                                                                                        </select>

                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="form-group">
                                                                                                        <label
                                                                                                            for="style_header_<?php echo e($item_sub_1->id); ?>"
                                                                                                            class="control-label">
                                                                                                            <?php echo app('translator')->get('Style'); ?>
                                                                                                        </label>
                                                                                                        <select
                                                                                                            name="json_params[style]"
                                                                                                            id="style_header_<?php echo e($item_sub_1->id); ?>"
                                                                                                            class="form-control select2"
                                                                                                            autocomplete="off">
                                                                                                            <option
                                                                                                                value="">
                                                                                                                <?php echo app('translator')->get('Please select'); ?>
                                                                                                            </option>
                                                                                                            <?php $__currentLoopData = $style_header; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key_style => $val_style): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                <option
                                                                                                                    value="<?php echo e($key_style); ?>"
                                                                                                                    <?php echo e(isset($item_sub_1->json_params->style) && $item_sub_1->json_params->style == $key_style ? 'selected' : ''); ?>>
                                                                                                                    <?php echo app('translator')->get($val_style); ?>
                                                                                                                </option>
                                                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                    <div class="form-group">
                                                                                                        <label
                                                                                                            class="control-label">
                                                                                                            <?php echo app('translator')->get('Icon'); ?>
                                                                                                        </label>
                                                                                                        <input type="text" class="form-control"

                                                                                                            placeholder="<?php echo app('translator')->get('Icon'); ?>"
                                                                                                            name="json_params[icon]"
                                                                                                            autocomplete="off"
                                                                                                            value="<?php echo e($item_sub_1->json_params->icon??old('json_params[icon]')); ?>">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="form-group">
                                                                                                        <label><?php echo app('translator')->get('Image'); ?></label>
                                                                                                        <div
                                                                                                            class="input-group">
                                                                                                            <span
                                                                                                                class="input-group-btn">
                                                                                                                <a data-input="image-<?php echo e($item_sub_1->id); ?>"
                                                                                                                    data-preview="image-holder-<?php echo e($item_sub_1->id); ?>"
                                                                                                                    class="btn btn-primary lfm"
                                                                                                                    data-type="cms-image">
                                                                                                                    <i
                                                                                                                        class="fa fa-picture-o"></i>
                                                                                                                    <?php echo app('translator')->get('Select'); ?>
                                                                                                                </a>
                                                                                                            </span>
                                                                                                            <input
                                                                                                                id="image-<?php echo e($item_sub_1->id); ?>"
                                                                                                                class="form-control"
                                                                                                                type="text"
                                                                                                                name="json_params[image]"
                                                                                                                placeholder="<?php echo app('translator')->get('Image source'); ?>"
                                                                                                                value="<?php echo e($item_sub_1->json_params->image ?? ''); ?>">
                                                                                                        </div>
                                                                                                        <div id="image-holder-<?php echo e($item_sub_1->id); ?>"
                                                                                                            style="margin-top:15px;max-height:100px;">
                                                                                                            <?php if(isset($item_sub_1->json_params->image) && $item_sub_1->json_params->image != ''): ?>
                                                                                                                <img style="height: 5rem;"
                                                                                                                    src="<?php echo e($item_sub_1->json_params->image ?? ''); ?>">
                                                                                                            <?php endif; ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="form-group mb-3">
                                                                                                        <label
                                                                                                            for="link-status"
                                                                                                            class="control-label">
                                                                                                            <?php echo app('translator')->get('Status'); ?>
                                                                                                        </label>

                                                                                                        <div
                                                                                                            class="form-control">
                                                                                                            <?php $__currentLoopData = App\Consts::STATUS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                <label
                                                                                                                    class="col-12 col-xl-6">
                                                                                                                    <input
                                                                                                                        type="radio"
                                                                                                                        name="status"
                                                                                                                        value="<?php echo e($value); ?>"
                                                                                                                        <?php echo e($item_sub_1->status == $value ? 'checked' : ''); ?>>
                                                                                                                    <small
                                                                                                                        class="mr-15"><?php echo e(__($value)); ?></small>
                                                                                                                </label>
                                                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                        </div>

                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="clearfix">
                                                                                                </div>
                                                                                                <div class="text-end mt-2">
                                                                                                    <button type="submit"
                                                                                                        class="btn btn-primary btn-sm"><?php echo app('translator')->get('Save'); ?></button>
                                                                                                    <p class="btn btn-danger remove_menu btn-sm"
                                                                                                        data-id="<?php echo e($item_sub_1->id); ?>">
                                                                                                        <?php echo app('translator')->get('Remove'); ?>
                                                                                                    </p>

                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                        <div class="clearfix"></div>
                                                                                        <?php if($item_sub_1->sub > 0): ?>
                                                                                            <ol class="dd-list d-none">
                                                                                                <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_sub_2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <?php if($item_sub_2->parent_id == $item_sub_1->id): ?>
                                                                                                        <li class="dd-item"
                                                                                                            data-id="<?php echo e($item_sub_2->id); ?>">
                                                                                                            <div
                                                                                                                class="dd-handle dd3-handle">
                                                                                                            </div>
                                                                                                            <div
                                                                                                                class="dd3-content">
                                                                                                                <span
                                                                                                                    class="text float-start"
                                                                                                                    data-update="title"><?php echo e($item_sub_2->json_params->name->$lang ?? $item_sub_2->name); ?></span>
                                                                                                                <span
                                                                                                                    class="text float-end"><?php echo app('translator')->get($status[$item_sub_2->status]); ?></span>
                                                                                                                <a data-toggle="collapse"
                                                                                                                    href="#item-details-<?php echo e($item_sub_2->id); ?>"
                                                                                                                    role="button"
                                                                                                                    aria-expanded="false"
                                                                                                                    aria-controls="item-details-<?php echo e($item_sub_2->id); ?>"
                                                                                                                    class="show-item-details">
                                                                                                                    <i
                                                                                                                        class="fa fa-angle-down"></i>
                                                                                                                </a>
                                                                                                                <div
                                                                                                                    class="clearfix">
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="item-details collapse multi-collapse"
                                                                                                                id="item-details-<?php echo e($item_sub_2->id); ?>">
                                                                                                                <form
                                                                                                                    role="form"
                                                                                                                    action="<?php echo e(route(Request::segment(2) . '.update', $item_sub_2->id)); ?>"
                                                                                                                    method="POST">
                                                                                                                    <?php echo csrf_field(); ?>
                                                                                                                    <?php echo method_field('PUT'); ?>
                                                                                                                    <?php if(Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale): ?>
                                                                                                                        <input
                                                                                                                            type="hidden"
                                                                                                                            name="lang"
                                                                                                                            value="<?php echo e(Request::get('lang')); ?>">
                                                                                                                    <?php endif; ?>
                                                                                                                    <div
                                                                                                                        class="form-body">
                                                                                                                        
                                                                                                                        <div
                                                                                                                            class="form-group mb-3">
                                                                                                                            <label
                                                                                                                                for="menu-node-title-1"
                                                                                                                                class="control-label"
                                                                                                                                data-update="title"><?php echo app('translator')->get('Title'); ?></label>
                                                                                                                            <input
                                                                                                                                class="form-control"
                                                                                                                                placeholder="Title"
                                                                                                                                data-old="Home"
                                                                                                                                id="menu-node-title-1"
                                                                                                                                name="name"
                                                                                                                                type="text"
                                                                                                                                value="<?php echo e($item_sub_2->json_params->name->$lang ?? $item_sub_2->name); ?>">
                                                                                                                        </div>
                                                                                                                        <div
                                                                                                                            class="form-group mb-3">
                                                                                                                            <label
                                                                                                                                for="menu-node-url-1"
                                                                                                                                class="control-label"
                                                                                                                                data-update="custom-url"><?php echo app('translator')->get('Url'); ?></label>
                                                                                                                            <input
                                                                                                                                class="form-control"
                                                                                                                                placeholder="URL"
                                                                                                                                data-old="/"
                                                                                                                                id="menu-node-url-1"
                                                                                                                                name="url_link"
                                                                                                                                type="text"
                                                                                                                                value="<?php echo e($item_sub_2->url_link); ?>">
                                                                                                                        </div>
                                                                                                                        <div
                                                                                                                            class="form-group mb-3">
                                                                                                                            <label
                                                                                                                                for="link-target"
                                                                                                                                class="control-label">
                                                                                                                                <?php echo app('translator')->get('Select target'); ?>
                                                                                                                            </label>

                                                                                                                            <select
                                                                                                                                name="json_params[target]"
                                                                                                                                id="link-target"
                                                                                                                                class="form-control select2"
                                                                                                                                autocomplete="off">
                                                                                                                                <option
                                                                                                                                    value="_self"
                                                                                                                                    selected>
                                                                                                                                    <?php echo app('translator')->get('_self'); ?>
                                                                                                                                </option>
                                                                                                                                <option
                                                                                                                                    value="_blank">
                                                                                                                                    <?php echo app('translator')->get('_blank'); ?>
                                                                                                                                </option>
                                                                                                                            </select>
                                                                                                                        </div>
                                                                                                                        <div
                                                                                                                            class="form-group">
                                                                                                                            <label
                                                                                                                                for="style_header_<?php echo e($item_sub_2->id); ?>"
                                                                                                                                class="control-label">
                                                                                                                                <?php echo app('translator')->get('Style'); ?>
                                                                                                                            </label>
                                                                                                                            <select
                                                                                                                                name="json_params[style]"
                                                                                                                                id="style_header_<?php echo e($item_sub_2->id); ?>"
                                                                                                                                class="form-control select2"
                                                                                                                                autocomplete="off">
                                                                                                                                <option
                                                                                                                                    value="">
                                                                                                                                    <?php echo app('translator')->get('Please select'); ?>
                                                                                                                                </option>
                                                                                                                                <?php $__currentLoopData = $style_header; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key_style => $val_style): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                    <option
                                                                                                                                        value="<?php echo e($key_style); ?>"
                                                                                                                                        <?php echo e(isset($item_sub_2->json_params->style) && $item_sub_2->json_params->style == $key_style ? 'selected' : ''); ?>>
                                                                                                                                        <?php echo app('translator')->get($val_style); ?>
                                                                                                                                    </option>
                                                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                                            </select>
                                                                                                                        </div>
                                                                                                                        <div class="form-group">
                                                                                                                            <label
                                                                                                                                class="control-label">
                                                                                                                                <?php echo app('translator')->get('Icon'); ?>
                                                                                                                            </label>
                                                                                                                            <input type="text" class="form-control"

                                                                                                                                placeholder="<?php echo app('translator')->get('Icon'); ?>"
                                                                                                                                name="json_params[icon]"
                                                                                                                                autocomplete="off"
                                                                                                                                value="<?php echo e($item_sub_2->json_params->icon??old('json_params[icon]')); ?>">
                                                                                                                        </div>
                                                                                                                        <div
                                                                                                                            class="form-group">
                                                                                                                            <label><?php echo app('translator')->get('Image'); ?></label>
                                                                                                                            <div
                                                                                                                                class="input-group">
                                                                                                                                <span
                                                                                                                                    class="input-group-btn">
                                                                                                                                    <a data-input="image-<?php echo e($item_sub_2->id); ?>"
                                                                                                                                        data-preview="image-holder-<?php echo e($item_sub_2->id); ?>"
                                                                                                                                        class="btn btn-primary lfm"
                                                                                                                                        data-type="cms-image">
                                                                                                                                        <i
                                                                                                                                            class="fa fa-picture-o"></i>
                                                                                                                                        <?php echo app('translator')->get('Select'); ?>
                                                                                                                                    </a>
                                                                                                                                </span>
                                                                                                                                <input
                                                                                                                                    id="image-<?php echo e($item_sub_2->id); ?>"
                                                                                                                                    class="form-control"
                                                                                                                                    type="text"
                                                                                                                                    name="json_params[image]"
                                                                                                                                    placeholder="<?php echo app('translator')->get('Image source'); ?>"
                                                                                                                                    value="<?php echo e($item_sub_2->json_params->image ?? ''); ?>">
                                                                                                                            </div>
                                                                                                                            <div id="image-holder-<?php echo e($item_sub_2->id); ?>"
                                                                                                                                style="margin-top:15px;max-height:100px;">
                                                                                                                                <?php if(isset($item_sub_2->json_params->image) && $item_sub_2->json_params->image != ''): ?>
                                                                                                                                    <img style="height: 5rem;"
                                                                                                                                        src="<?php echo e($item_sub_2->json_params->image ?? ''); ?>">
                                                                                                                                <?php endif; ?>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <div
                                                                                                                            class="form-group mb-3">
                                                                                                                            <label
                                                                                                                                for="link-status"
                                                                                                                                class="control-label">
                                                                                                                                <?php echo app('translator')->get('Status'); ?>
                                                                                                                            </label>

                                                                                                                            <div
                                                                                                                                class="form-control">
                                                                                                                                <?php $__currentLoopData = App\Consts::STATUS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                    <label
                                                                                                                                        class="col-12 col-xl-6">
                                                                                                                                        <input
                                                                                                                                            type="radio"
                                                                                                                                            name="status"
                                                                                                                                            value="<?php echo e($value); ?>"
                                                                                                                                            <?php echo e($item_sub_2->status == $value ? 'checked' : ''); ?>>
                                                                                                                                        <small
                                                                                                                                            class="mr-15"><?php echo e(__($value)); ?></small>
                                                                                                                                    </label>
                                                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                                            </div>

                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div
                                                                                                                        class="clearfix">
                                                                                                                    </div>
                                                                                                                    <div
                                                                                                                        class="text-end mt-2">

                                                                                                                        <button
                                                                                                                            type="submit"
                                                                                                                            class="btn btn-primary btn-cancel btn-sm"><?php echo app('translator')->get('Save'); ?></button>
                                                                                                                        <p class="btn btn-danger remove_menu btn-sm"
                                                                                                                            data-id="<?php echo e($item_sub_2->id); ?>">
                                                                                                                            <?php echo app('translator')->get('Remove'); ?>
                                                                                                                        </p>

                                                                                                                    </div>
                                                                                                                </form>
                                                                                                            </div>
                                                                                                            <div
                                                                                                                class="clearfix <?php echo e($item_sub_2->sub); ?>">
                                                                                                            </div>
                                                                                                            <?php if($item_sub_2->sub > 0): ?>
                                                                                                                <ol
                                                                                                                    class="dd-list d-none">
                                                                                                                    <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_sub_3): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                        <?php if($item_sub_3->parent_id == $item_sub_2->id): ?>
                                                                                                                            <li class="dd-item"
                                                                                                                                data-id="<?php echo e($item_sub_3->id); ?>">
                                                                                                                                <div
                                                                                                                                    class="dd-handle dd3-handle">
                                                                                                                                </div>
                                                                                                                                <div
                                                                                                                                    class="dd3-content">
                                                                                                                                    <span
                                                                                                                                        class="text float-start"
                                                                                                                                        data-update="title"><?php echo e($item_sub_3->json_params->name->$lang ?? $item_sub_3->name); ?></span>
                                                                                                                                    <span
                                                                                                                                        class="text float-end"><?php echo app('translator')->get($status[$item_sub_3->status]); ?></span>
                                                                                                                                    <a data-toggle="collapse"
                                                                                                                                        href="#item-details-<?php echo e($item_sub_3->id); ?>"
                                                                                                                                        role="button"
                                                                                                                                        aria-expanded="false"
                                                                                                                                        aria-controls="item-details-<?php echo e($item_sub_3->id); ?>"
                                                                                                                                        class="show-item-details">
                                                                                                                                        <i
                                                                                                                                            class="fa fa-angle-down"></i>
                                                                                                                                    </a>
                                                                                                                                    <div
                                                                                                                                        class="clearfix">
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div class="item-details collapse multi-collapse"
                                                                                                                                    id="item-details-<?php echo e($item_sub_3->id); ?>">
                                                                                                                                    <form
                                                                                                                                        role="form"
                                                                                                                                        action="<?php echo e(route(Request::segment(2) . '.update', $item_sub_3->id)); ?>"
                                                                                                                                        method="POST">
                                                                                                                                        <?php echo csrf_field(); ?>
                                                                                                                                        <?php echo method_field('PUT'); ?>
                                                                                                                                        <?php if(Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale): ?>
                                                                                                                                            <input
                                                                                                                                                type="hidden"
                                                                                                                                                name="lang"
                                                                                                                                                value="<?php echo e(Request::get('lang')); ?>">
                                                                                                                                        <?php endif; ?>
                                                                                                                                        <div
                                                                                                                                            class="form-body">

                                                                                                                                            <div
                                                                                                                                                class="form-group mb-3">
                                                                                                                                                <label
                                                                                                                                                    for="menu-node-title-<?php echo e($item_sub_3->id); ?>"
                                                                                                                                                    class="control-label"
                                                                                                                                                    data-update="title"><?php echo app('translator')->get('Title'); ?></label>
                                                                                                                                                <input
                                                                                                                                                    class="form-control"
                                                                                                                                                    placeholder="Title"
                                                                                                                                                    data-old="Home"
                                                                                                                                                    id="menu-node-title-<?php echo e($item_sub_3->id); ?>"
                                                                                                                                                    name="name"
                                                                                                                                                    type="text"
                                                                                                                                                    value="<?php echo e($item_sub_3->json_params->name->$lang ?? $item_sub_3->name); ?>">
                                                                                                                                            </div>
                                                                                                                                            <div
                                                                                                                                                class="form-group mb-3">
                                                                                                                                                <label
                                                                                                                                                    for="menu-node-url-<?php echo e($item_sub_3->id); ?>"
                                                                                                                                                    class="control-label"
                                                                                                                                                    data-update="custom-url"><?php echo app('translator')->get('Url'); ?></label>
                                                                                                                                                <input
                                                                                                                                                    class="form-control"
                                                                                                                                                    placeholder="URL"
                                                                                                                                                    data-old="/"
                                                                                                                                                    id="menu-node-url-<?php echo e($item_sub_3->id); ?>"
                                                                                                                                                    name="url_link"
                                                                                                                                                    type="text"
                                                                                                                                                    value="<?php echo e($item_sub_2->url_link); ?>">
                                                                                                                                            </div>
                                                                                                                                            <div
                                                                                                                                                class="form-group mb-3">
                                                                                                                                                <label
                                                                                                                                                    for="link-target-<?php echo e($item_sub_3->id); ?>"
                                                                                                                                                    class="control-label">
                                                                                                                                                    <?php echo app('translator')->get('Select target'); ?>
                                                                                                                                                </label>

                                                                                                                                                <select
                                                                                                                                                    name="json_params[target]"
                                                                                                                                                    id="link-target-<?php echo e($item_sub_3->id); ?>"
                                                                                                                                                    class="form-control select2"
                                                                                                                                                    autocomplete="off">
                                                                                                                                                    <option
                                                                                                                                                        value="_self"
                                                                                                                                                        selected>
                                                                                                                                                        <?php echo app('translator')->get('_self'); ?>
                                                                                                                                                    </option>
                                                                                                                                                    <option
                                                                                                                                                        value="_blank">
                                                                                                                                                        <?php echo app('translator')->get('_blank'); ?>
                                                                                                                                                    </option>
                                                                                                                                                </select>

                                                                                                                                            </div>
                                                                                                                                            <div
                                                                                                                                                class="form-group">
                                                                                                                                                <label
                                                                                                                                                    for="style_header_<?php echo e($item_sub_3->id); ?>"
                                                                                                                                                    class="control-label">
                                                                                                                                                    <?php echo app('translator')->get('Style'); ?>
                                                                                                                                                </label>
                                                                                                                                                <select
                                                                                                                                                    name="json_params[style]"
                                                                                                                                                    id="style_header_<?php echo e($item_sub_3->id); ?>"
                                                                                                                                                    class="form-control select2"
                                                                                                                                                    autocomplete="off">
                                                                                                                                                    <option
                                                                                                                                                        value="">
                                                                                                                                                        <?php echo app('translator')->get('Please select'); ?>
                                                                                                                                                    </option>
                                                                                                                                                    <?php $__currentLoopData = $style_header; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key_style => $val_style): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                                        <option
                                                                                                                                                            value="<?php echo e($key_style); ?>"
                                                                                                                                                            <?php echo e(isset($item_sub_3->json_params->style) && $item_sub_3->json_params->style == $key_style ? 'selected' : ''); ?>>
                                                                                                                                                            <?php echo app('translator')->get($val_style); ?>
                                                                                                                                                        </option>
                                                                                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                                </select>
                                                                                                                                            </div>
                                                                                                                                            <div
                                                                                                                                                class="form-group">
                                                                                                                                                <label><?php echo app('translator')->get('Image'); ?></label>
                                                                                                                                                <div
                                                                                                                                                    class="input-group">
                                                                                                                                                    <span
                                                                                                                                                        class="input-group-btn">
                                                                                                                                                        <a data-input="image-<?php echo e($item_sub_3->id); ?>"
                                                                                                                                                            data-preview="image-holder-<?php echo e($item_sub_3->id); ?>"
                                                                                                                                                            class="btn btn-primary lfm"
                                                                                                                                                            data-type="cms-image">
                                                                                                                                                            <i
                                                                                                                                                                class="fa fa-picture-o"></i>
                                                                                                                                                            <?php echo app('translator')->get('Select'); ?>
                                                                                                                                                        </a>
                                                                                                                                                    </span>
                                                                                                                                                    <input
                                                                                                                                                        id="image-<?php echo e($item_sub_3->id); ?>"
                                                                                                                                                        class="form-control"
                                                                                                                                                        type="text"
                                                                                                                                                        name="json_params[image]"
                                                                                                                                                        placeholder="<?php echo app('translator')->get('Image source'); ?>"
                                                                                                                                                        value="<?php echo e($item_sub_3->json_params->image ?? ''); ?>">
                                                                                                                                                </div>
                                                                                                                                                <div id="image-holder-<?php echo e($item_sub_3->id); ?>"
                                                                                                                                                    style="margin-top:15px;max-height:100px;">
                                                                                                                                                    <?php if(isset($item_sub_3->json_params->image) && $item_sub_3->json_params->image != ''): ?>
                                                                                                                                                        <img style="height: 5rem;"
                                                                                                                                                            src="<?php echo e($item_sub_3->json_params->image ?? ''); ?>">
                                                                                                                                                    <?php endif; ?>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                            <div
                                                                                                                                                class="form-group mb-3">
                                                                                                                                                <label
                                                                                                                                                    class="control-label">
                                                                                                                                                    <?php echo app('translator')->get('Status'); ?>
                                                                                                                                                </label>

                                                                                                                                                <div
                                                                                                                                                    class="form-control">
                                                                                                                                                    <?php $__currentLoopData = App\Consts::STATUS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                                        <label
                                                                                                                                                            class="col-12 col-xl-6">
                                                                                                                                                            <input
                                                                                                                                                                type="radio"
                                                                                                                                                                name="status"
                                                                                                                                                                value="<?php echo e($value); ?>"
                                                                                                                                                                <?php echo e($item_sub_3->status == $value ? 'checked' : ''); ?>>
                                                                                                                                                            <small
                                                                                                                                                                class="mr-15"><?php echo e(__($value)); ?></small>
                                                                                                                                                        </label>
                                                                                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                                </div>

                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                        <div
                                                                                                                                            class="clearfix">
                                                                                                                                        </div>
                                                                                                                                        <div
                                                                                                                                            class="text-end mt-2">
                                                                                                                                            <p class="btn btn-danger remove_menu btn-sm"
                                                                                                                                                data-id="<?php echo e($item_sub_3->id); ?>">
                                                                                                                                                <?php echo app('translator')->get('Remove'); ?>
                                                                                                                                            </p>
                                                                                                                                            <button
                                                                                                                                                type="submit"
                                                                                                                                                class="btn btn-primary btn-cancel btn-sm"><?php echo app('translator')->get('Save'); ?></button>
                                                                                                                                        </div>
                                                                                                                                    </form>
                                                                                                                                </div>
                                                                                                                                <div
                                                                                                                                    class="clearfix">
                                                                                                                                </div>
                                                                                                                            </li>
                                                                                                                        <?php endif; ?>
                                                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                                </ol>
                                                                                                            <?php endif; ?>
                                                                                                        </li>
                                                                                                    <?php endif; ?>
                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                            </ol>
                                                                                        <?php endif; ?>
                                                                                    </li>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </ol>
                                                                    <?php endif; ?>
                                                                </li>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <a class="btn btn-warning btn-flat menu-sort-save btn-sm"
                                                title="<?php echo app('translator')->get('Save'); ?>">
                                                <i class="fa fa-floppy-o"></i>
                                                <?php echo app('translator')->get('Save sort'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="box-footer">

                </div>

            </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#menu-sort').nestable({
            group: 0,
            maxDepth: 2,
        });
        $('.menu-sort-save').click(function() {
            $('#loading').show();
            let serialize = $('#menu-sort').nestable('serialize');
            let menu = JSON.stringify(serialize);
            $.ajax({
                    url: '<?php echo e(route('menus.update_sort')); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        menu: menu,
                        root_id: <?php echo e($detail->id); ?>

                    },
                })
                .done(function(data) {
                    $('#loading').hide();
                    if (data.error == 0) {
                        location.reload();
                    } else {
                        alert(data.msg);
                        location.reload();
                    }
                });
        });
        $('.remove_menu').click(function() {
            if (confirm("<?php echo app('translator')->get('confirm_action'); ?>")) {
                let _root = $(this).closest('.dd-item');
                let id = $(this).data('id');
                $.ajax({
                    method: 'post',
                    url: '<?php echo e(route('menus.delete')); ?>',
                    data: {
                        id: id,
                        _token: '<?php echo e(csrf_token()); ?>',
                    },
                    success: function(data) {
                        if (data.error == 1) {
                            alert(data.msg);
                        } else {
                            _root.remove();
                        }
                    }
                });
            }
        });
        var menus = <?php echo json_encode($menus ?? [], 15, 512) ?>;
        $('.edit_menu').click(function() {
            $('.dd-handle').removeClass('active-item');
            let _root = $(this).closest('.dd-handle');
            let _form = $('#form-main');
            let id = $(this).data('id');
            let item = menus.find(menu => menu.id === id);
            if (!$.isEmptyObject(item)) {
                _form.find('#link-title').text("<?php echo e(__('Edit link for menu')); ?>");
                _form.find('.submit_form').text("<?php echo __('Save & update'); ?>");
                _form.find('#link-parent_id').val(item.parent_id)
                _form.find('#link-name').val(item.name);
                _form.find('#link-url_link').val(item.url_link);
                if (item.json_params) {
                    _form.find('#link-target').val(item.json_params.target || '_self');
                }
                _form.find('input[name=status][value=' + item.status + ']').prop('checked', true);
                _form.attr('action', '<?php echo e(route(Request::segment(2) . '.index')); ?>/' + item.id);
                _form.find('input[name=_method]').val('PUT');
                _form.find('input[name=_token]').val('<?php echo e(csrf_token()); ?>');
            }
            $(".select2").select2();
            _root.addClass('active-item');
        });
        $('.reset_form').click(function() {
            $('.dd-handle').removeClass('active-item');
            let _form = $('#form-main');
            _form.find('#link-title').text("<?php echo e(__('Add new link to menu')); ?>");
            _form.find('.submit_form').text("<?php echo __('Add new'); ?>");
            _form.find('#link-parent_id').val(<?php echo e($detail->id); ?>)
            _form.find('#link-name').val('');
            _form.find('#link-url_link').val('');
            _form.find('#link-target').val('_self');
            _form.find('input[name=status][value=active]').prop('checked', true);
            _form.attr('action', '<?php echo e(route(Request::segment(2) . '.store')); ?>');
            _form.find('input[name=_method]').val('POST');
            _form.find('input[name=_token]').val('<?php echo e(csrf_token()); ?>');
            $(".select2").select2();
        });

        var no_image_link = '<?php echo e(url('themes/admin/img/no_image.jpg')); ?>';

        $('.add-gallery-image').click(function(event) {
            let keyRandom = new Date().getTime();
            let elementParent = $(this).parents('.box_gallery').find('.list-gallery-image');
            let elementAppend =
                '<div class="col-lg-2 col-md-3 col-sm-4 mb-1 gallery-image">';
            elementAppend += '<div id="image-holder_' + keyRandom +
                '" style="width: 150px; height: 150px;"><img width="150px" height="150px" class="img-width" ';
            elementAppend += 'src="' + no_image_link + '"> </div>';
            elementAppend +=
                '<input type="text" name="json_params[gallery_image][' + keyRandom +
                ']" class="input_hidden hidden" id="gallery_image_' +
                keyRandom +
                '">';

            elementAppend += '<div class="btn-action">';
            elementAppend +=
                '<span class="btn btn-sm btn-success btn-upload lfm mr-5" data-input="gallery_image_' +
                keyRandom +
                '" data-type="cms-image" data-preview="image-holder_' + keyRandom + '">';
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
        $('.list-gallery-image').on('change', '.input_hidden', function() {
            alert(1);
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

    });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/menus/edit.blade.php ENDPATH**/ ?>