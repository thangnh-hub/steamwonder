<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
<link href="{{ asset('themes/frontend/education/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet"
    type="text/css" />
<link rel="stylesheet" href="{{ asset('themes/frontend/dwn/css/sweetalert2.min.css') }}" type="text/css">

<style>
    @import url('https://fonts.googleapis.com/css?family=Montserrat:300,400,400i,500,600,700,800,900');

    body {
        font-family: 'Montserrat', sans-serif;
    }

    p {
        margin: 0;
        padding: 0;
    }

    img {
        width: 100%;
    }

    div {
        display: block;
        position: relative;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    .header {
        background: #2c2b31;
        color: #fff;
        height: 50px;
        position: relative;
        z-index: 999;
    }

    .learning-center {
        width: 100%;
        padding: 0px 8.5%;
    }

    .box_video {
        background: #000;
    }

    .video {
        width: 100%;
    }

    .loader {
        width: 40px;
        height: 40px;
        margin: 0 auto;
    }

    .header .btn-back {
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        width: 60px;
        height: 50px;
        color: #fff;
    }

    .f-w-b {
        font-weight: bold;
    }

    .box_logo {
        width: 60px;
        padding: 0px 10px;
    }

    .header .title {
        font-size: 16px;
        font-weight: 700;
        margin-left: 16px;
        color: var(--white-color);
        overflow: hidden;
        word-wrap: break-word;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
    }

    .mr-24 {
        margin-right: 24px;
    }

    .gap-10 {
        gap: 10px;
    }

    .learn_player {
        position: fixed;
        top: 0px;
        left: 0px;
        overflow-y: auto;
        margin-top: 50px;
        bottom: 50px;
        width: 100%;
    }

    .full_screen .learn_player {
        width: 70%;
    }

    .learn-playlist {
        position: fixed;
        top: 0;
        right: 0;
        margin-top: 50px;
        bottom: 50px;
        overflow-y: auto;
        width: 30%;
        border-left: 1px solid #e7e7e7;
        display: none;
    }

    .full_screen .learn-playlist {
        display: block;
        padding: 0 15px;
    }

    .toggle_bottom {
        position: fixed;
        bottom: 0px;
        left: 0px;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 50px;
        gap: 16px;
        background-color: #f0f0f0;
        box-shadow: #0000001a 0 -2px 3px;
    }

    .lesson-content {
        margin-bottom: 10px;
        border-bottom: 1px dashed #333;
        padding-bottom: 10px;
    }

    .lesson-content h3 {
        font-size: 15px;
    }

    .lesson-content .content {
        font-size: 13px;
    }

    .btn_toggle {
        display: inline-block;
        min-width: 148px;
        height: 32px;
        border-radius: 99px;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.8;
        letter-spacing: 1px;
        text-transform: uppercase;
        white-space: nowrap;
        overflow: hidden;
        background-color: #5ebbff;
        background: linear-gradient(#5ebbff80 40%, #a174ff80);
        transition: opacity 0.2s;
        padding: 0;
        cursor: pointer;
    }

    .btn-pre .btn-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        width: 100%;
        height: 100%;
        padding: 4px 16px;
        border-radius: inherit;
        border: 1px solid transparent;
        color: #5ebbff;
        background-color: #fff;
        background-clip: padding-box;
    }

    .btn-pre .btn-title {
        color: transparent;
        background: linear-gradient(to bottom right, #5ebbff, #a174ff);
        -webkit-background-clip: text;
        background-clip: text;
    }

    .btn-next .btn-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        width: 100%;
        height: 100%;
        padding: 4px 16px;
        border-radius: inherit;
        color: #fff;
        background-clip: padding-box;
        border: none;
        background-color: #0093fc;
    }

    .btn-next .btn-title {
        background-image: none;
        color: #fff;
    }

    html *::-webkit-scrollbar {
        border-radius: 0;
        width: 8px;
        height: 8px;
    }

    html *::-webkit-scrollbar-thumb {
        border-radius: 4px;
        background-color: #00000026;
    }

    .accordions {
        margin-top: 5px;
    }

    .learn-playlist .title {
        padding: 15px 0px;
        font-size: 20px;
        line-height: 20px;
        font-weight: 600;
    }

    .accordion_container:not(:last-child) {
        margin-bottom: 14px;
    }

    .accordions_tabs {
        width: 100%;
        background: #FFFFFF;
        margin-top: 100px;
    }

    .accordion_container:not(:last-child) {
        margin-bottom: 14px;
    }

    .accordion {
        min-height: 50px;
        width: 100%;
        background: #f2f1f8;
        padding-left: 15px;
        cursor: pointer;
        color: #44425a;
        font-size: 16px;
        font-weight: 600;
        -webkit-transition: all 200ms ease;
        -moz-transition: all 200ms ease;
        -ms-transition: all 200ms ease;
        -o-transition: all 200ms ease;
        transition: all 200ms ease;
        justify-content: space-between;
        padding-right: 50px;
    }

    .accordion div {
        max-width: 90%;
        overflow: hidden;
        white-space: nowrap;
    }

    .accordion:active {
        background: #fffbfa !important;
    }

    .accordion::after {
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        transform: translateY(-50%);
        right: 12px;
        width: 23px;
        height: 23px;
        background: #ff6600;
        content: '+';
        font-size: 16px;
        color: #FFFFFF;
        font-weight: 600;
        -webkit-transition: all 200ms ease;
        -moz-transition: all 200ms ease;
        -ms-transition: all 200ms ease;
        -o-transition: all 200ms ease;
        transition: all 200ms ease;
    }

    .accordion.active::after {
        content: '-';
    }

    .accordion:hover::after {
        color: #FFFFFF;
    }

    .accordion:hover {
        background: #e9e8ef;
    }

    .accordion_panel {
        padding-right: 8px;
        max-height: 0px;
        overflow: hidden;
        -webkit-transition: all 500ms ease;
        -moz-transition: all 500ms ease;
        -ms-transition: all 500ms ease;
        -o-transition: all 500ms ease;
        transition: all 500ms ease;
    }

    .accordion_panel>div {
        padding-bottom: 11px;
    }

    .accordion_panel p {
        padding-top: 10px;
        color: #333;
        line-height: 1.5;
    }

    .tabs {
        width: 100%;
        margin-top: 40px;
        background: #FFFFFF;
    }

    .tabs_container {
        width: 100%;
    }

    .tab {
        height: 50px;
        background: #f2f1f8;
        font-size: 16px;
        color: #44425a;
        font-weight: 600;
        line-height: 50px;
        padding-left: 25px;
        padding-right: 25px;
        text-align: center;
        cursor: pointer;
        margin-left: 2px;
        margin-right: 7px;
        white-space: nowrap;
        margin-bottom: 9px;
        -webkit-transition: all 200ms ease;
        -moz-transition: all 200ms ease;
        -ms-transition: all 200ms ease;
        -o-transition: all 200ms ease;
        transition: all 200ms ease;
    }

    .tab::after {
        display: block;
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 3px;
        background: #ff6600;
        content: '';
        visibility: hidden;
        opacity: 0;
        -webkit-transition: all 200ms ease;
        -moz-transition: all 200ms ease;
        -ms-transition: all 200ms ease;
        -o-transition: all 200ms ease;
        transition: all 200ms ease;
    }

    .tab.active::after,
    .tab:hover::after {
        visibility: visible;
        opacity: 1;
    }

    .tab_panels {
        padding-left: 2px;
        padding-right: 2px;
        padding-top: 20px;
        padding-bottom: 32px;
    }

    .tab_panel {
        display: none !important;
        width: 100%;
        height: 100%;
        -webkit-animation: fadeEffect 1s;
        animation: fadeEffect 1s;
    }

    /* Fade in tabs */
    @-webkit-keyframes fadeEffect {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes fadeEffect {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .tab_panel.active {
        display: block !important;
    }

    .tab_panel_content p:last-of-type {
        margin-bottom: 0;
    }

    .tab_text_title {
        font-size: 16px;
        font-weight: 600;
        color: #44425a;
    }

    .tab_text {
        margin-top: 33px;
    }

    .tab_text p {
        -webkit-transform: translateY(-6px);
        -moz-transform: translateY(-6px);
        -ms-transform: translateY(-6px);
        -o-transform: translateY(-6px);
        transform: translateY(-6px);
    }

    .tab_image {
        width: 100%;
    }

    .tab_image img {
        max-width: 100%;
    }

    .check {
        color: #5db85c;
    }

    .overflow-x {
        overflow-x: auto;
    }

    .box-bars {
        color: #000;
        position: absolute;
        right: 24px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .box-bars .btn_bars {
        width: 30px;
        height: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #fff;
        border-radius: 15px;
    }

    p a {
        color: #212529;
    }

    .intro-lesson {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-right: 40px;
        border-bottom: 1px solid #efefefde;
    }

    .intro-lesson:last-child {
        border-bottom: unset;
    }



    .f-right {
        float: right;
    }

    .box_grammar ul {
        list-style: circle;
        padding-left: 20px
    }

    .item_vocabulary .img {
        width: 70px;
        max-height: 70px;
        object-fit: cover
    }

    .item_vocabulary .audio {
        width: 140px;
    }

    .item_vocabulary .img img {
        width: 100%
    }

    .item_vocabulary {
        padding: 15px 15px 15px;
        border-bottom: 1px dotted #b5b5b590;
        line-height: 24px;
    }

    .fw-bold {
        font-weight: bold
    }

    .box_quiz input[type=text] {
        width: 60px;
        border: none;
        border-bottom: 1px solid #000;
        text-align: center;
    }

    .question img {
        width: 100% !important;
        height: auto !important;
    }



    .percent_poid {
        color: #12db31;
        margin-right: 15px
    }

    .percent_poid i {
        margin-right: 3px
    }

    .li_item_lesson .title_lesson {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .sound,
    .soundTextToSpeech {
        display: flex;
        color: rgb(28, 176, 246)
    }

    .audio {
        visibility: hidden;
        position: absolute;
        z-index: -1;
        right: 0px
    }

    .btn_answer.active,
    .btn_quiz_speak.active,
    .btn_quiz_choice.active {
        color: #fff;
        background: #28a745;
        transition: all ease 0.5s;
    }

    .btn_answer.deactive {
        color: #000;
        background: unset;
        border: 1px solid #000;
        opacity: 30%;
        transition: all ease 1s;
    }

    .btn_answer.error {
        color: #fff;
        background: #c82333;
        transition: all ease 1s;
    }

    .box_anser_order {
        border-bottom: 1px solid #000
    }

    .box_anser_order .btn_anser_order {
        color: #fff;
        background: #28a745;
    }

    .btn_quiz_order.deactive {
        opacity: 50%;
    }

    .btn_answer {
        display: -webkit-box;
        max-width: 100%;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        background-color: #f8f9fa;
    }

    .btn_answer:hover {
        color: #212529;
        background-color: #e2e6ea;
        border-color: #dae0e5;
    }

    .btn_null {
        opacity: 0.5;
        cursor: default;
    }

    .intro-lesson {
        padding-left: 24px;
        position: relative;
    }

    .intro-lesson.active::before {
        content: "\f0a4";
        font-family: "FontAwesome";
        position: absolute;
        left: 5px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
    }

    .box_title {
        display: inline-block;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-bottom: 15px;
    }

    .box_title:hover {
        color: #ff6600;
    }

    .vocabulary-content .title {
        font-weight: bold;
    }

    .icon-volume {
        width: 30px;
        display: inline-block;
    }

    .voc_img {
        width: 20%;
    }

    button.close {
        position: absolute;
        right: 0px;
        top: 0px;
        padding: 2p 5px;
        z-index: 1;
    }

    .icon-volume {
        cursor: pointer;
    }

    #voc_audio {
        position: absolute;
        z-index: 0;
        opacity: 0;
    }

    .modal-dialog {
        max-width: 800px;
    }

    .box_vocabulary {
        margin-right: 8px;
        padding: 4px 16px;
        border-radius: 16px;
        opacity: .9;
        cursor: pointer;
        margin-bottom: 8px;
        border: 1px solid #b7b7b7;
        display: inline-block;
        max-width: 180px;
        word-break: break-word;
        color: unset;
        text-decoration: unset;
    }

    @media screen and (max-width: 767px) {
        .header .btn-back {
            width: 40px;
        }

        .learning-center {
            padding: 0px 3.5%;
        }

        .loader {
            margin-right: 20px;
        }

        .full_screen .learn_player {
            width: 100%;
        }

        .learn-playlist {
            width: 100%;
            background: #fff;
            z-index: 2;
            bottom: 0px;
            border: none;
        }

        html *::-webkit-scrollbar {
            border-radius: 0;
            width: 8px;
            height: 1px;
        }

        .toggle_bottom {
            gap: 5px;
            padding-right: 40px;
        }

        .box-bars {
            right: 13px;
        }

        .tab {
            height: 40px;
            line-height: 40px;
            padding-left: 20px;
            padding-right: 20px;
        }

        .li_item_lesson .title_lesson {
            width: calc(100% - 65px);
            margin-bottom: 5px;
        }

        .course-details-area .title {
            margin-top: 0px;
        }
    }
</style>
