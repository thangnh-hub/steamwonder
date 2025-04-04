<!-- Dependency Styles -->












<!-- New Styles  -->
<link rel="stylesheet" type="text/css"
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css?v=<?php echo e($ver); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('themes/frontend/education/plugins/font-awesome-4.7.0/css/font-awesome.min.css')); ?>?v=<?php echo e($ver); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('themes/frontend/education/plugins/OwlCarousel2-2.2.1/owl.carousel.css')); ?>?v=<?php echo e($ver); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('themes/frontend/education/plugins/OwlCarousel2-2.2.1/owl.theme.default.css')); ?>?v=<?php echo e($ver); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('themes/frontend/education/plugins/OwlCarousel2-2.2.1/animate.css')); ?>?v=<?php echo e($ver); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('themes/frontend/education/styles/main_styles.css')); ?>?v=<?php echo e($ver); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('themes/frontend/education/styles/responsive.css')); ?>?v=<?php echo e($ver); ?>">
<link rel="stylesheet" href="<?php echo e(asset('themes/frontend/dwn/css/sweetalert2.min.css')); ?>?v=<?php echo e($ver); ?>"
    type="text/css">

<style>
    .navbar-size {
        position: fixed;
        background-color: #fff;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 15;
        display: none;
    }

    .navbar-size .navbar-size-wrapper {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
    }

    .navbar-size .navbar-size-item {
        border-right: 1px solid #e3e3e3;
        padding: 14px 0 12px;
        color: #170a64;
    }

    .navbar-size .navbar-size-item a {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
        flex-direction: column;
        color: #ff6600;
    }

    .navbar-size .navbar-size-item .icon {
        width: 19px;
        font-size: 20px;
    }

    .navbar-size .navbar-size-item .text {
        font-size: 12px;
        line-height: 14px;
        font-weight: 700;

        text-transform: uppercase;
        transition: all ease .5s;
    }

    .navbar-size .navbar-size-item.active {
        color: #ef0276;
    }



    .modal-header {
        .close {
            margin-top: -1.5rem;
        }
    }

    .form-title {
        margin: -2rem 0rem 2rem;
    }

    .btn-round {
        border-radius: 3rem;
    }

    .delimiter {
        padding: 1rem;
    }

    .social-buttons {
        .btn {
            margin: 0 0.5rem 1rem;
        }
    }

    .signup-section {
        padding: 0.3rem 0rem;
    }
    #couserModal .img{
        width: 100%;
        max-width: 300px;
    }
    @media (min-width: 576px) {
        #couserModal .modal-dialog {
            max-width: 50%;
        }
        .modal-dialog {
            max-width: 400px;
        }

        .modal-content {
            padding: 1rem;
        }

    }
    @media  only screen and (max-width: 576px) {
        .navbar-size {
            display: block
        }

        .logo_img {
            width: 100px;
        }
    }


</style>
<?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/frontend/panels/styles.blade.php ENDPATH**/ ?>