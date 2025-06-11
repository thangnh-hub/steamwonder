<?php if($block): ?>
    <?php
        $title = $block->json_params->title->{$locale} ?? $block->title;
        $brief = $block->json_params->brief->{$locale} ?? $block->brief;
        $des = $block->json_params->des->{$locale} ?? '';
        $content = $block->json_params->content->{$locale} ?? $block->content;
        $image = $block->image != '' ? $block->image : null;
        $image_background = $block->image_background != '' ? $block->image_background : null;
        $url_link = $block->url_link != '' ? $block->url_link : '';
        $url_link_title = $block->json_params->url_link_title->{$locale} ?? $block->url_link_title;
        // Filter all blocks by parent_id
        $block_childs = $blocks->filter(function ($item, $key) use ($block) {
            return $item->parent_id == $block->id;
        });
    ?>

    <div class="join py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section_title text-center">
                        <h2><?php echo e($title); ?></h2>
                    </div>
                    <div class="section_subtitle"><?php echo e($brief); ?></div>
                </div>
            </div>
            
        </div>
        <?php if(!$user_auth && $url_link != '' && $url_link_title != ''): ?>
            <div class="button join_button"><a href="<?php echo e($url_link); ?>" data-toggle="modal"><?php echo e($url_link_title); ?><div
                        class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></div></a></div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/frontend/blocks/custom/styles/block_join.blade.php ENDPATH**/ ?>