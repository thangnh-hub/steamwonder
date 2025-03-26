<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($detail->cv_title ?? "Lebenslauf"); ?></title>
    <link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('themes/frontend/education/plugins/font-awesome-4.7.0/css/font-awesome.min.css')); ?>">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            padding: 20px;
            background-color: #555;
            display: flex;
            justify-content: center;
        }
        .container {
            max-width: 800px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: row;
        }
        .left {
            width: 30%;
            padding-right: 20px;
            text-align: center;
            background-color: #f3c271;
            color: #000;
            padding: 20px;
            border-radius: 10px 0 0 10px;
        }
        .left img {
            width: 100%;
            /* border-radius: 50%; */
            margin-bottom: 20px;
            border: 3px solid white;
        }
        .right {
            width: 70%;
            padding: 20px 0px 20px 20px;
        }
        h1, h2 {
            color: #cc6600;
        }
        .section {
            margin-bottom: 20px;
        }
        .section p{
            text-align: left;
            line-height: 24px
        }
        .left .section p{
            text-align: center;
            line-height: 20px
        }
        .section2 {
            background: #cc6600;
            padding: 10px 20px;
            border-radius: 0px 20px 20px 0px;
        }
        .section2 h1 {
            color: #000 !important;
            width: 35%;
            line-height: 56px
        }
        .section h2 {
            border-bottom: 2px solid #cc6600;
            padding-bottom: 5px;
        }
        .info {
            margin-bottom: 10px;
        }
        .info span {
            font-weight: bold;
        }
        .mt-3{
            margin-top: 3rem
        }
        .color-picker {
            margin-bottom: 20px;
            
        }
        html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: auto;
        }
        .pdf_down button {
            background: #cc6600 !important;
        }
        .back_cv button  {
            margin-top: 10px;
            padding: 5px 10px;
            background: #17a2b8;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .pdf_down {
            margin-right: 20px;
        }
        .back_cv {
            margin-right: 20px;
        }
        .color-picker input{
            cursor: pointer;
            margin-top: 10px
        }
        @media  print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .hide-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="back_cv hide-print">
        <a href="<?php echo e(route('profiles.index')); ?>">
            <button type="button"><i class="fa fa-arrow-left"></i> <?php echo app('translator')->get('Quay lại Quản lý hồ sơ'); ?></button>
        </a>  
        <div class="pdf_down">
            <button type="button"  onclick="window.print()">Tải PDF</button>
        </div> 
        <div class="color-picker">
            <input type="color" id="color" value="#ffcc66">
        </div>
    </div>
        
    <div class="container">
        <div class="left" style="background-color: #f3c271;">
            <img src="<?php echo e(isset($detail->json_params->upload_image->avatar) && $detail->json_params->upload_image->avatar!=""?($detail->json_params->upload_image->avatar) : asset('/uploads/no_image.jpg')); ?>" alt="Profilbild">
            <div class="section">
                <h2><?php echo app('translator')->get('PROFIL'); ?></h2>

                <?php if(isset($detail->json_params->profile->country)): ?>
                    <p><strong><i class="fa fa-globe"></i> <?php echo app('translator')->get('Nationalität'); ?>:</strong></p>
                    <p><?php echo e($detail->json_params->profile->country ?? ""); ?></p>
                <?php endif; ?>

                <?php if(isset($detail->json_params->profile->birthday)): ?>
                    <p><strong><i class="fa fa-birthday-cake"></i> <?php echo app('translator')->get('Geburtsdatum'); ?>:</strong> </p>
                    <p><?php echo e($detail->json_params->profile->birthday ?? ""); ?></p>
                <?php endif; ?>

                <?php if(isset($detail->json_params->profile->marital)): ?>
                    <p><strong><i class="fa fa-heart"></i> <?php echo app('translator')->get('Familienstand'); ?>:</strong></p>
                    <p><?php echo e($detail->json_params->profile->marital ?? ""); ?></p>
                <?php endif; ?>

                <?php if(isset($detail->json_params->profile->phone)): ?>
                    <p><strong><i class="fa fa-phone"></i> <?php echo app('translator')->get('Telefon'); ?>:</strong></p>
                    <p><?php echo e($detail->json_params->profile->phone ?? ""); ?></p>
                <?php endif; ?>

                <?php if(isset($detail->json_params->profile->mail)): ?>
                    <p><strong><i class="fa fa-envelope"></i> <?php echo app('translator')->get('Email'); ?>:</strong></p>
                    <p><?php echo e($detail->json_params->profile->mail ?? ""); ?></p>
                <?php endif; ?>
            </div>
            <div class="section mt-3">
                <h2><?php echo app('translator')->get('INFORMATION'); ?></h2>

                <?php if(isset($detail->json_params->profile->zalo)): ?>
                    <p><strong><i class="fa fa-comments"></i> <?php echo app('translator')->get('Zalo'); ?>:</strong> </p>
                    <p> <?php echo e($detail->json_params->profile->zalo ?? ""); ?></p>
                <?php endif; ?>

                <?php if(isset($detail->json_params->profile->born)): ?>
                    <p><strong><i class="fa fa-flag"></i> <?php echo app('translator')->get('Geburtsort'); ?>:</strong> </p>
                    <p><?php echo e($detail->json_params->profile->born ?? ""); ?></p>
                <?php endif; ?>

                <?php if(isset($detail->json_params->profile->address)): ?>
                    <p><strong><i class="fa fa-home"></i> <?php echo app('translator')->get('Adresse'); ?>:</strong></p>
                    <p><?php echo e($detail->json_params->profile->address ?? ""); ?></p>
                <?php endif; ?>

                <?php if(isset($detail->json_params->profile->brief)): ?>
                    <p><strong><i class="fa fa-user"></i> <?php echo app('translator')->get('Beschreibe dich'); ?>:</strong></p>
                    <p><?php echo e($detail->json_params->profile->brief ?? ""); ?></p>
                <?php endif; ?>

            </div>
        </div>
        <div class="right">
            <div class="section2">
                <h1><?php echo e($detail->json_params->profile->user_name ?? "error name"); ?></h1>
            </div>
            
            
            <?php if(isset($detail->json_params->learning_process)): ?>
            <div class="section">
                <h2><i class="fa fa-graduation-cap"></i> <?php echo app('translator')->get('Bildungsweg'); ?></h2>

                <?php if(isset($detail->json_params->learning_process->school_1st)): ?>
                    <p><strong> <?php echo app('translator')->get('Grundschule'); ?>:</strong> <?php echo e($detail->json_params->learning_process->school_1st ?? ""); ?></p>
                <?php endif; ?>

                <?php if(isset($detail->json_params->learning_process->school_2nd)): ?>
                    <p><strong> <?php echo app('translator')->get('Mittelschule'); ?>:</strong> <?php echo e($detail->json_params->learning_process->school_2nd ?? ""); ?></p>
                <?php endif; ?>

                <?php if(isset($detail->json_params->learning_process->school_3rd)): ?>
                    <p><strong> <?php echo app('translator')->get('Oberschule'); ?> 
                        <?php echo e(isset($detail->json_params->learning_process->school_3rd_time) ? date('m.Y', strtotime($detail->json_params->learning_process->school_3rd_time)) : ""); ?>:</strong> 
                        <?php echo e($detail->json_params->learning_process->school_3rd ?? ""); ?>

                    </p>
                <?php endif; ?>

                <?php if(isset($detail->json_params->learning_process->university)): ?>
                    <p><strong>
                        <?php echo e(isset($detail->json_params->learning_process->university_start) ? date('m.Y', strtotime($detail->json_params->learning_process->university_start)) : ""); ?> 
                        <?php echo e(isset($detail->json_params->learning_process->university_start) && isset($detail->json_params->learning_process->university_end) ? "-" : ""); ?>

                        <?php echo e(isset($detail->json_params->learning_process->university_end) ? date('m.Y', strtotime($detail->json_params->learning_process->university_end)) : ""); ?>:</strong> 
                        <?php echo e(isset($detail->json_params->learning_process->field_university) ? $detail->json_params->learning_process->field_university : ""); ?> - <?php echo e($detail->json_params->learning_process->university ?? ""); ?>

                    </p>
                <?php endif; ?>

                <?php if(isset($detail->json_params->learning_process->other)): ?>
                    <?php $__currentLoopData = $detail->json_params->learning_process->other; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $other): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><strong>
                            <?php echo e(isset($other->university_start) ? date('m.Y', strtotime($other->university_start)) : ""); ?> 
                            <?php echo e((isset($other->university_start) && isset($other->university_end)) ? "-" : ""); ?>

                            <?php echo e(isset($other->university_end) ? date('m.Y', strtotime($other->university_end)) : ""); ?>:</strong> 
                            <?php echo e(isset($other->field_university) ? $other->field_university : ""); ?> - <?php echo e(isset($other->university) ? $other->university : ""); ?>

                        </p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            
            <?php if(isset($detail->json_params->experience)): ?>
            <div class="section">
                <h2><i class="fa fa-briefcase"></i> <?php echo app('translator')->get('Berufserfahrung'); ?></h2>

                <?php if(isset($detail->json_params->experience->company)): ?>
                    <p><strong>
                        <?php echo e(isset($detail->json_params->experience->company_start) ? date('m.Y', strtotime($detail->json_params->experience->company_start)) : ""); ?> 
                        <?php echo e((isset($detail->json_params->experience->company_start) && isset($detail->json_params->experience->company_end)) ? "-" : ""); ?>

                        <?php echo e(isset($detail->json_params->experience->company_end) ? date('m.Y', strtotime($detail->json_params->experience->company_end)) : ""); ?>:</strong> 
                        <?php echo e(isset($detail->json_params->experience->company_position) ? $detail->json_params->experience->company_position : ""); ?> in <?php echo e(isset($detail->json_params->experience->company) ?$detail->json_params->experience->company: ""); ?>: <?php echo e(isset($detail->json_params->experience->content_company)?$detail->json_params->experience->content_company:""); ?>

                    </p>
                <?php endif; ?>

                <?php if(isset($detail->json_params->experience->other)): ?>
                    <?php $__currentLoopData = $detail->json_params->experience->other; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $other): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><strong>
                            <?php echo e(isset($other->company_start) ? date('m.Y', strtotime($other->company_start)) : ""); ?> 
                            <?php echo e((isset($other->company_start) && isset($other->company_end))  ? "-" : ""); ?>

                            <?php echo e(isset($other->company_end) ? date('m.Y', strtotime($other->company_end)) : ""); ?>:</strong> 
                            <?php echo e(isset($other->company_position) ?$other->company_position: ""); ?> in <?php echo e(isset($other->company)  ?$other->company: ""); ?>: <?php echo e(isset($other->content_company) ? $other->content_company:""); ?>

                        </p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            
            <?php if(isset($detail->json_params->qualification)): ?>
            <div class="section">
                <h2><i class="fa fa-certificate"></i> <?php echo app('translator')->get('Sprachen'); ?></h2>
                <p><strong><?php echo app('translator')->get('Deutsch'); ?> :</strong><?php echo e(isset($detail->json_params->qualification->germany_level) ? $detail->json_params->qualification->germany_level: ""); ?>, <?php echo e(isset($detail->json_params->qualification->germany_start) ? date('m.Y', strtotime($detail->json_params->qualification->germany_start)) : ""); ?> 
                    in <?php echo e(isset($detail->json_params->qualification->city_learn) ?$detail->json_params->qualification->city_learn:""); ?></p>
                <p><strong><?php echo app('translator')->get('Englisch'); ?> :</strong> <?php echo e(isset($detail->json_params->qualification->english_level)?$detail->json_params->qualification->english_level:""); ?></p>

                <?php if(isset($detail->json_params->qualification->other)): ?>
                    <?php $__currentLoopData = $detail->json_params->qualification->other; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $other): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><strong><?php echo e(isset($other->language) ? $other->language: ""); ?> :</strong> <?php echo e(isset($other->level) ? $other->level: ""); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            

            <?php if(isset($detail->json_params->hobby)): ?>
            <div class="section">
                <h2><i class="fa fa-smile-o"></i> <?php echo app('translator')->get('Hobbys'); ?></h2>
                    <p> <?php echo e(isset($detail->json_params->hobby->hobby1) ? $detail->json_params->hobby->hobby1: ""); ?>, <?php echo e(isset($detail->json_params->hobby->hobby2) ? $detail->json_params->hobby->hobby2 : ""); ?>, <?php echo e(isset($detail->json_params->hobby->hobby3) ?$detail->json_params->hobby->hobby3: ""); ?></p>
                    <p> <?php echo e(isset($detail->json_params->hobby->quality1) ? $detail->json_params->hobby->quality1: ""); ?>, <?php echo e(isset($detail->json_params->hobby->quality2) ? $detail->json_params->hobby->quality2 : ""); ?>, <?php echo e(isset($detail->json_params->hobby->quality3) ? $detail->json_params->hobby->quality3 : ""); ?>, <?php echo e(isset($detail->json_params->hobby->quality4) ?$detail->json_params->hobby->quality4: ""); ?></p>
                    <?php if(isset($detail->json_params->hobby->other)): ?>
                        <?php $__currentLoopData = $detail->json_params->hobby->other; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hobby): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p> <?php echo e($hobby ??""); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
</body>
<script src="<?php echo e(asset('themes/frontend/dwn/js/vendor/jquery-2.2.4.min.js')); ?>"></script>
<script>
    $('#color').on('input', function() {
        let color = $(this).val();
        $('.left').css('background-color', color);
        $('.right h1, .right h2').css('color', color);
        $('.section2').css('background', color);
        $('.right .section h2').css({'border-bottom': `2px solid ${color}` });
    });
</script>
</html>
<?php /**PATH D:\project\steamwonders\resources\views/admin/pages/profiles/show.blade.php ENDPATH**/ ?>