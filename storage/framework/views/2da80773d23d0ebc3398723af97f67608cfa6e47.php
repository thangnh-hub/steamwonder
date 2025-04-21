

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
                <div class="col-lg-8">
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
                        <!-- form start -->
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
                                        <div class="row">
                                            <?php if(Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale): ?>
                                                <input type="hidden" name="lang" value="<?php echo e(Request::get('lang')); ?>">
                                            <?php endif; ?>
                                            <?php
                                                $route = $detail->json_params->route_name ?? 'post.detail';
                                                $route_default = collect($route_name)->first(function (
                                                    $item,
                                                    $key
                                                ) use ($route) {
                                                    return $item['name'] == $route;
                                                });
                                            ?>
                                            <?php if($route_default): ?>
                                                <input type="hidden" name="json_params[route_name]"
                                                    value="<?php echo e($route_default['name']); ?>">
                                            <?php endif; ?>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Title'); ?> <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="<?php echo app('translator')->get('Title'); ?>"
                                                        value="<?php echo e($detail->json_params->name->$lang ?? $detail->name); ?>"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>URL tùy chọn</label>
                                                    <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                                                    <small class="form-text">
                                                        (
                                                        <i class="fa fa-info-circle"></i>
                                                        Maximum 100 characters in the group: "A-Z", "a-z", "0-9" and
                                                        "-_" )
                                                    </small>
                                                    <input name="alias" class="form-control"
                                                        value="<?php echo e($detail->alias ?? old('alias')); ?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Brief'); ?></label>
                                                    <textarea name="json_params[brief][<?php echo e($lang); ?>]" class="form-control" rows="5"><?php echo e($detail->json_params->brief->$lang ?? old('json_params[brief][' . $lang . ']')); ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Content'); ?></label>
                                                        <textarea name="json_params[content][<?php echo e($lang); ?>]" class="form-control" id="content_vi"><?php echo e($detail->json_params->content->$lang ?? old('json_params[content][' . $lang . ']')); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('seo_title'); ?></label>
                                                    <input name="json_params[seo_title][<?php echo e($lang); ?>]"
                                                        class="form-control"
                                                        value="<?php echo e($detail->json_params->seo_title->$lang ?? old('json_params[seo_title][' . $lang . ']')); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('seo_keyword'); ?></label>
                                                    <input name="json_params[seo_keyword][<?php echo e($lang); ?>]"
                                                        class="form-control"
                                                        value="<?php echo e($detail->json_params->seo_keyword->$lang ?? old('json_params[seo_keyword][' . $lang . ']')); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('seo_description'); ?></label>
                                                    <input name="json_params[seo_description][<?php echo e($lang); ?>]"
                                                        class="form-control"
                                                        value="<?php echo e($detail->json_params->seo_description->$lang ?? old('json_params[seo_description][' . $lang . ']')); ?>">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->

                        </div>
                    </div>
                </div>
                <div class="col-lg-4">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Status'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="status" class=" form-control select2">
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($detail->status) && $detail->status == $val ? 'checked' : ''); ?>>
                                            <?php echo app('translator')->get($val); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border sw_featured d-flex-al-center">
                            <label class="switch ">
                                <input id="sw_featured" name="is_featured" value="1" type="checkbox"
                                    <?php echo e(isset($detail->is_featured) && $detail->is_featured == '1' ? 'checked' : ''); ?>>
                                <span class="slider round"></span>
                            </label>
                            <label class="box-title ml-1" for="sw_featured"><?php echo app('translator')->get('Is featured'); ?></label>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Order'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <input type="number" class="form-control" name="iorder"
                                    placeholder="<?php echo app('translator')->get('Order'); ?>" value="<?php echo e($detail->iorder ?? old('iorder')); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Categories'); ?> <small class="text-red">*</small></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <ul class="list-relation">
                                    <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($item->parent_id == 0 || $item->parent_id == null): ?>
                                            <li>
                                                <label for="page-<?php echo e($item->id); ?>">
                                                    <input id="page-<?php echo e($item->id); ?>" name="relation[]"
                                                        <?php echo e(isset($relationship) && collect($relationship)->firstWhere('taxonomy_id', $item->id) != null ? 'checked' : ''); ?>

                                                        type="checkbox" value="<?php echo e($item->id); ?>">
                                                    <?php echo e($item->json_params->name->$lang ?? $item->name); ?>

                                                </label>
                                                <ul class="list-relation">
                                                    <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($item1->parent_id == $item->id): ?>
                                                            <li>
                                                                <label for="page-<?php echo e($item1->id); ?>">
                                                                    <input id="page-<?php echo e($item1->id); ?>"
                                                                        name="relation[]" type="checkbox"
                                                                        <?php echo e(isset($relationship) && collect($relationship)->firstWhere('taxonomy_id', $item1->id) != null ? 'checked' : ''); ?>

                                                                        value="<?php echo e($item1->id); ?>">
                                                                    <?php echo e($item1->json_params->name->$lang ?? $item1->name); ?>

                                                                </label>
                                                                <ul class="list-relation">
                                                                    <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if($item2->parent_id == $item1->id): ?>
                                                                            <li>
                                                                                <label for="page-<?php echo e($item2->id); ?>">
                                                                                    <input id="page-<?php echo e($item2->id); ?>"
                                                                                        name="relation[]" type="checkbox"
                                                                                        <?php echo e(isset($relationship) && collect($relationship)->firstWhere('taxonomy_id', $item2->id) != null ? 'checked' : ''); ?>

                                                                                        value="<?php echo e($item2->id); ?>">
                                                                                    <?php echo e($item2->json_params->name->$lang ?? $item2->name); ?>

                                                                                </label>
                                                                            </li>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </ul>
                                                            </li>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Image'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group box_img_right <?php echo e(isset($detail->image) ? 'active' : ''); ?>">
                                <div id="image-holder">
                                    <?php if($detail->image != ''): ?>
                                        <img src="<?php echo e($detail->image); ?>">
                                    <?php else: ?>
                                        <img src="<?php echo e(url('themes/admin/img/no_image.jpg')); ?>">
                                    <?php endif; ?>
                                </div>
                                <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-trash"></i></span>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm"
                                            data-type="cms-image">
                                            <i class="fa fa-picture-o"></i> <?php echo app('translator')->get('Choose'); ?>
                                        </a>
                                    </span>
                                    <input id="image" class="form-control inp_hidden" type="hidden" name="image"
                                        placeholder="<?php echo app('translator')->get('Image source'); ?>" value="<?php echo e($detail->image ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Page config'); ?></h3>
                        </div>
                        <div class="box-body">

                            <div class="form-group">
                                <label><?php echo app('translator')->get('Template'); ?></label>
                                <small class="text-red">*</small>
                                <select name="json_params[template]" id="template" class="form-control select2"
                                    style="width:100%" required autocomplete="off">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php if(isset($route_default['template'])): ?>
                                        <?php $__currentLoopData = $route_default['template']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item['name']); ?>"
                                                <?php echo e(isset($detail->json_params->template) && $detail->json_params->template == $item['name'] ? 'selected' : ''); ?>>
                                                <?php echo app('translator')->get($item['title']); ?>
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>

                                </select>
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
                                <a class="btn btn-success " href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                    <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                                </a>
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
        // Change to filter
        $(document).ready(function() {
            var no_image_link = '<?php echo e(url('themes/admin/img/no_image.jpg')); ?>';
            $('.inp_hidden').on('change', function() {
                $(this).parents('.box_img_right').addClass('active');
            });

            $('.box_img_right').on('click', '.btn-remove', function() {
                let par = $(this).parents('.box_img_right');
                par.removeClass('active');
                par.find('img').attr('src', no_image_link);
                par.find('.inp_hidden').val("");
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/cms_posts/edit.blade.php ENDPATH**/ ?>