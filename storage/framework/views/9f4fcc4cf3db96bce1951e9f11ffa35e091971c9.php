<?php if($paginator->hasPages()): ?>
    <div class="row">
        <div class="col">
            <div class="news_pagination mb-3">
                <ul>
                    
                    <?php if($paginator->onFirstPage()): ?>
                        <li><a href="#" onclick="event.preventDefault();"><i class="fa fa-arrow-left"
                                    aria-hidden="true"></i></a></li>
                    <?php else: ?>
                        <li><a href="<?php echo e($paginator->previousPageUrl()); ?>"><i class="fa fa-arrow-left"
                                    aria-hidden="true"></i></a></li>
                    <?php endif; ?>

                    
                    <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php if(is_string($element)): ?>
                            <li><a href="#" onclick="event.preventDefault();"><?php echo e($element); ?></a>
                            </li>
                        <?php endif; ?>
                        
                        <?php if(is_array($element)): ?>
                            <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($page == $paginator->currentPage()): ?>
                                    <li class="active"><a href="#"
                                            onclick="event.preventDefault();"><?php echo e($page); ?></a></li>
                                <?php else: ?>
                                    <li><a href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <?php if($paginator->hasMorePages()): ?>
                        <li><a href="<?php echo e($paginator->nextPageUrl()); ?>"><i class="fa fa-arrow-right"
                                    aria-hidden="true"></i></a></li>
                    <?php else: ?>
                        <li><a href="#" onclick="event.preventDefault();"><i class="fa fa-arrow-right"
                                    aria-hidden="true"></i></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\project\dwn\resources\views/frontend/pagination/default.blade.php ENDPATH**/ ?>