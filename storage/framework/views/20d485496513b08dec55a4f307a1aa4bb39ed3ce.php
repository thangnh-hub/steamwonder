
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

    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo e($module_name); ?>

            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2) . '.create')); ?>">
                <i class="fa fa-plus"></i>
                <?php echo app('translator')->get('Add'); ?>
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
            <!-- form start -->
            <form role="form" onsubmit=" return check_nestb()"
                action="<?php echo e(route(Request::segment(2) . '.update', $detail->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <?php if(Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale): ?>
                    <input type="hidden" name="lang" value="<?php echo e(Request::get('lang')); ?>">
                <?php endif; ?>
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
                                            <label><?php echo app('translator')->get('Title'); ?></label>
                                            <small class="text-red">*</small>
                                            <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                                            <input type="text" class="form-control" name="title"
                                                placeholder="<?php echo app('translator')->get('Title'); ?>"
                                                value="<?php echo e(old('title') ?? ($detail->json_params->title->$lang ?? $detail->title)); ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Url customize'); ?></label>
                                            <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                                            <small class="form-text">
                                                (
                                                <i class="fa fa-info-circle"></i>
                                                <?php echo app('translator')->get('Maximum 100 characters in the group: "A-Z", "a-z", "0-9" and "-_"'); ?>
                                                )
                                            </small>
                                            <input type="text" class="form-control" name="alias"
                                                placeholder="<?php echo app('translator')->get('Url customize'); ?>"
                                                value="<?php echo e(old('alias') ?? $detail->alias); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Keyword'); ?></label>
                                            <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                                            <input type="text" class="form-control" name="keyword"
                                                placeholder="<?php echo app('translator')->get('Keyword'); ?>"
                                                value="<?php echo e(old('keyword') ?? $detail->keyword); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Description'); ?></label>
                                            <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                                            <textarea type="text" class="form-control" name="description" placeholder="<?php echo app('translator')->get('Description'); ?>"><?php echo e(old('description') ?? ($detail->json_params->description->$lang ?? $detail->description)); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Content Page'); ?></label>
                                            <textarea type="text" class="form-control" name="content" id="content"><?php echo e(old('content') ?? ($detail->json_params->content->$lang ?? $detail->content)); ?></textarea>
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
                                                placeholder="<?php echo app('translator')->get('Order'); ?>"
                                                value="<?php echo e(old('iorder') ?? $detail->iorder); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Route Name'); ?></label>
                                            <small class="text-red">*</small>
                                            <select name="route_name" id="route_name" class="form-control select2"
                                                style="width:100%" required autocomplete="off">
                                                <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                <?php $__currentLoopData = App\Consts::ROUTE_NAME; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(isset($item['is_config']) && $item['is_config']): ?>
                                                        <option value="<?php echo e($item['name']); ?>"
                                                            <?php echo e($detail->route_name == $item['name'] ? 'selected' : ''); ?>>
                                                            <?php echo e(__($item['title'])); ?>

                                                        </option>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php
                                        $route = $detail->route_name;
                                        $templates = collect(App\Consts::ROUTE_NAME);
                                        $template = $templates->first(function ($item, $key) use ($route) {
                                            return $item['name'] == $route;
                                        });
                                    ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Template'); ?></label>
                                            <small class="text-red">*</small>
                                            <select name="json_params[template]" id="template"
                                                class="form-control select2" style="width:100%" required
                                                autocomplete="off">
                                                <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                <?php if(isset($template['template'])): ?>
                                                    <?php $__currentLoopData = $template['template']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item['name']); ?>"
                                                            <?php echo e(isset($detail->json_params->template) && $detail->json_params->template == $item['name'] ? 'selected' : ''); ?>>
                                                            <?php echo e(__($item['title'])); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="box box-primary">
                                            <div class="box-header with-border">
                                                <h3>
                                                    <?php echo app('translator')->get('Setting Block Content'); ?>
                                                    <a type="button"
                                                        class="btn btn-sm btn-warning pull-right"
                                                        data-title="<?php echo app('translator')->get('Add Block Content'); ?>" data-page="<?php echo e($detail->id); ?>"
                                                        href="<?php echo e(route('block_contents.create',['page' => $detail->id])); ?>">
                                                        <?php echo app('translator')->get('Add Block Content'); ?>
                                                    </a>
                                                </h3>
                                            </div>

                                            <div class="box-body">
                                                <div class="table-responsive">
                                                    <div class="dd" id="block-sort">
                                                        <ol class="dd-list">
                                                            <?php $__currentLoopData = $block_selected; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li class="dd-item" data-id="<?php echo e($item->id); ?>">
                                                                    <div class="dd-handle ">
                                                                        <?php echo e($item->title); ?>

                                                                        <span class="dd-nodrag pull-right">
                                                                            <small>(<?php echo app('translator')->get($item->status); ?>)</small>
                                                                            <a
                                                                                class="cursor"
                                                                                data-title="<?php echo app('translator')->get('Edit Block'); ?>"
                                                                                href="<?php echo e(route('block_contents.edit', [$item->id,'page'=>Request::segment(3)])); ?>">
                                                                                <i class="fa fa-edit fa-edit"></i>
                                                                            </a>
                                                                            <a data-id="<?php echo e($item->id); ?>"
                                                                                data-page="<?php echo e($detail->id); ?>"
                                                                                class="remove_block cursor text-danger"
                                                                                title="<?php echo app('translator')->get('Delete'); ?>">
                                                                                <i class="fa fa-trash fa-edit"></i>
                                                                            </a>
                                                                        </span>
                                                                    </div>
                                                                    <?php if($item->sub > 0): ?>
                                                                        <ol class="dd-list">
                                                                            <?php $__currentLoopData = $blockContents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_sub_1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <?php if($item_sub_1->parent_id == $item->id): ?>
                                                                                <li class="dd-item"
                                                                                        data-id="<?php echo e($item_sub_1->id); ?>">
                                                                                        <div class="dd-handle ">
                                                                                            <?php echo e($item_sub_1->title); ?>

                                                                                            <span
                                                                                                class="dd-nodrag pull-right">
                                                                                                <small>(<?php echo app('translator')->get($item_sub_1->status); ?>)</small>
                                                                                                <a
                                                                                                    class="cursor"
                                                                                                    data-title="<?php echo app('translator')->get('Edit Block'); ?>"
                                                                                                    href="<?php echo e(route('block_contents.edit', [$item_sub_1->id,'page'=>Request::segment(3)])); ?>">
                                                                                                    <i
                                                                                                        class="fa fa-edit fa-edit"></i>
                                                                                                </a>
                                                                                                <a data-id="<?php echo e($item_sub_1->id); ?>"
                                                                                                    class="remove_block cursor text-danger"
                                                                                                    title="<?php echo app('translator')->get('Delete'); ?>">
                                                                                                    <i
                                                                                                        class="fa fa-trash fa-edit"></i>
                                                                                                </a>
                                                                                            </span>
                                                                                        </div>
                                                                                        <?php if($item_sub_1->sub > 0): ?>
                                                                                            <ol class="dd-list">
                                                                                                <?php $__currentLoopData = $blockContents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_sub_2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <?php if($item_sub_2->parent_id == $item_sub_1->id): ?>
                                                                                                        <li class="dd-item"
                                                                                                            data-id="<?php echo e($item_sub_2->id); ?>">
                                                                                                            <div
                                                                                                                class="dd-handle">
                                                                                                                <?php echo e($item_sub_2->title); ?>

                                                                                                                <span
                                                                                                                    class="dd-nodrag pull-right">
                                                                                                                    <small>(<?php echo app('translator')->get($item_sub_2->status); ?>)</small>
                                                                                                                    <a
                                                                                                                        class="cursor"
                                                                                                                        data-title="<?php echo app('translator')->get('Edit Block'); ?>"
                                                                                                                        href="<?php echo e(route('block_contents.edit', [$item_sub_2->id,'page'=>Request::segment(3)])); ?>">
                                                                                                                        <i
                                                                                                                            class="fa fa-edit fa-edit"></i>
                                                                                                                    </a>
                                                                                                                    <a data-id="<?php echo e($item_sub_2->id); ?>"
                                                                                                                        class="remove_block cursor text-danger"
                                                                                                                        title="<?php echo app('translator')->get('Delete'); ?>">
                                                                                                                        <i
                                                                                                                            class="fa fa-trash fa-edit"></i>
                                                                                                                    </a>
                                                                                                                </span>
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
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="output_block" name="output_block" value="">
                <div class="box-footer">
                    <a class="btn btn-sm btn-success" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                        <i class="fa fa-bars"></i>
                        <?php echo app('translator')->get('List'); ?>
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm pull-right">
                        <i class="fa fa-floppy-o"></i>
                        <?php echo app('translator')->get('Save'); ?>
                    </button>
                </div>
            </form>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        CKEDITOR.replace('content', ck_options);

        function check_nestb() {
            $('#output_block').val(JSON.stringify($('#block-sort').nestable('serialize')));
            return true;
        }
        $(document).ready(function() {
            $('#block-sort').nestable({
                group: 0,
                maxDepth: 3,
            });
            $('#reset_witget').click(function() {
                $('.val_widget').prop('checked', false);
            });
            $('.remove_block').click(function() {
                if (confirm("<?php echo app('translator')->get('confirm_action'); ?>")) {
                    let _root = $(this).closest('.dd-item');
                    let id = $(this).data('id');
                    let page = $(this).data('page');
                    $.ajax({
                        method: 'post',
                        url: '<?php echo e(route('block.delete')); ?>',
                        data: {
                            id: id,
                            page: page,
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



        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\dwn\resources\views/admin/pages/pages/edit.blade.php ENDPATH**/ ?>