<?php

/**
 * Template Name: SB Dash
 *
 */
//get_header(vibe_get_header());
$user_id = get_current_user_id();
$subscriptions = wcs_get_users_subscriptions($user_id);
//var_dump($subscriptions);
/* if( $subuscriptions ){
       
    }else{
        wp_redirect( home_url() );
        exit;
    } */

global $wpdb;
global $post;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Subscription Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-WLXDHXZ');
    </script>
    <!-- End Google Tag Manager -->





    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <style>
        #profile-navBar ul {
            list-style: none;
        }

        li.user-avatar {
            display: flex;
            align-items: center;
        }

        li.user-avatar img {
            width: 64px;
            height: 64px;
        }

        ul.user_info {
            list-style: none;
            padding: 0 20px;
            margin: 0;
        }

        ul.user_info li {
            font-family: "Open Sans";
            font-style: normal;
            font-weight: 600;
            font-size: 14px;
            line-height: 20px;
            letter-spacing: 0.236364px;
            color: #ffffff;
        }

        #profile-navBar {
            background: #a3a7d1 !important;
            padding: 0;
        }

        ul.user-setting li a {
            font-family: "Open Sans";
            font-style: normal;
            font-weight: 600;
            font-size: 14px;
            line-height: 20px;
            letter-spacing: 0.236364px;
            color: #ffffff;
        }

        ul.user-setting {
            background: #9b9bc4;
            padding: 19px;
            margin: 0;
        }

        ul.avatar-section {
            padding: 15px 11px;
            margin: 0;
        }

        ul.user-setting li a i {
            float: right;
        }

        ul.user-setting li {
            border-bottom: 1px solid #b2b2c7;
            padding: 5px 0;
        }

        span#admin-panel {
            display: flex;
            justify-content: flex-end;
            color: #ffffff;
            align-items: center;
            padding-right: 20px;
            padding-bottom: 5px;
        }

        span#admin-panel i {
            color: #ffffff;
        }

        .profile-button {
            border: none;
        }
    </style>
    <style>
        /* Navbar Start */
        nav.navbar.navbar-default {
            z-index: 99;
        }

        div#bs-example-navbar-collapse-1 {
            background: aliceblue;
            /*  margin-top: 24px; */
        }

        ul.nav.navbar-nav.navbar-right {
            margin-top: 10px;
        }

        form.navbar-form.navbar-left {
            margin-top: 20px !important;
        }

        a.navbar-brand.logo img {
            height: 56px;
            margin-top: 6px;
        }

        a.navbar-brand.logo {
            padding: 0;
        }

        .navbar-header {}

        nav.navbar {
            height: 80px;
            background: aliceblue;
        }

        nav.navbar .container {
            /* background: aqua; */
            height: 80px;
        }

        ul.nav.navbar-nav.navbar-right {
            margin-top: 12px;
            font-weight: 600;
        }

        ul.nav.navbar-nav.navbar-right li a {
            color: #2f2d2d;
        }

        /* Navbar End */
        a.course_button.full.button.subscribe-btn1 {
            background: #4caf50 !important;
        }

        section.rec-courses {
            margin: 10px 20px;
            /*  max-width: 962px; */
            margin: 0 auto;
        }

        a.cs-title h3 {
            font-size: 20px;
            height: 66px;
            margin: 10px 0 0 0;
            font-weight: 600;
        }

        /* .rec-title {
            background: #d4154c;
            padding: 7px 7px 7px 15px;
            color: #fff;
            font-size: 20px;
            font-weight: 600;
            height: 25px;
            display: inline-table;
            width: 100%;
            border-radius: 5px;
            } */
        .rec-title {
            color: #27316b;
            font-size: 23px;
            font-weight: 600;
            height: 25px;
            display: inline-table;
            width: 100%;
            border-radius: 5px;
            margin-bottom: 18px;
            text-shadow: 0 0 1px #929292;
        }

        .card-text-area {
            padding: 8px;
        }

        .duration_students p {
            font-weight: 500;
            font-size: 12px;
        }

        .duration_students p {
            font-weight: 500;
            font-size: 12px;
            margin: 0;
            color: #4a4a4a;
        }

        .cs_duration {
            text-align: right;
            font-size: 12px;
            font-weight: 600;
            color: #4a4a4a;
        }

        img.play-button {
            /*  min-height: 206px !important; */
            /*  display: inline-block; */
        }

        img.play-button {
            border-radius: 10px 10px 0 0;
            display: inline-block;
        }

        span.main-price {
            font-size: 24px;
            font-weight: bold;
            color: #8bc34a;
        }

        header.mooc.fix {
            background: #3d2c46 !important;
        }

        .btn1,
        .btn2 {
            background: #d4154c;
            text-align: center;
            display: block;
            text-align: center;
            margin: 0 auto;
            color: #fff;
            height: 25px;
            margin-bottom: 10px !important;
            line-height: 25px;
            border-radius: 5px;
            border: 1px solid #d4154c;
        }

        .btn1:hover,
        .btn2:hover {
            background: transparent;
            color: #d4154c;
        }

        .card-info-mid a h3 {
            font-size: 15px;
            margin: 0;
            color: #002333;
            line-height: 21px;
            margin-top: 6px;
            font-weight: 600;
            height: 45px;
        }

        .discount-price {
            font-size: 14px;
            display: inline-block;
            vertical-align: text-bottom;
            color: #908383;
            position: relative;
        }

        .discount-price:before {
            position: absolute;
            content: '';
            height: 2px;
            width: 100%;
            background: #545050;
            top: 9px;
        }

        .card-info-mid h3 {
            display: block;
            /* or inline-block */
            text-overflow: ellipsis;
            word-wrap: break-word;
            overflow: hidden;
            max-height: 3.6em;
            line-height: 1.8em;
        }

        /* .card-grid.ctm {
            border: 1px solid #f1ecec;
            padding: 10px;
            border-radius: 10px;
            min-height: 350px !important;
            margin-bottom: 30px;
            max-width: 323px;
            max-width: 278px;
            transition: 0.5;
            } */
        .card-grid.ctm {
            border: 1px solid #efefef;
            /* padding: 10px; */
            border-radius: 10px;
            /* min-height: 350px !important; */
            margin-bottom: 13px;
            /* max-width: 323px; */
            max-width: 278px;
            transition: 0.5 !important;
            box-shadow: 0 0 8px #d4d2d2;
        }

        .card-grid.ctm:hover {
            background: #f9f0fb !important;
            border: 1px solid #d4154c;
            transition: 0.5s;
            box-shadow: 0 0 5px #d1b9dc;
        }

        /*  p.students span {
            color: #d4154c;
            font-weight: 800;
            font-size: 20px;
            }
            p.students {
            vertical-align: middle;
            display: block;
            padding: 0 !important;
            margin: 5px 0 0 0;
            text-align: right;
            font-size: 15px;
            font-weight: 400;
            color: #ff7085;
            } */
        /* .item {
            max-width: 323px;
            } */
        .carousel-wrap {
            position: relative;
        }

        button.owl-prev {
            position: absolute;
            background: #ffffff !important;
            width: 40px;
            left: -51px;
            top: 35%;
            z-index: 9999;
            height: 40px;
            border-radius: 50px !important;
            box-shadow: 0 1px 5px #dedede;
        }

        button.owl-next {
            position: absolute;
            background: #ffffff !important;
            width: 40px;
            right: -51px;
            top: 35%;
            z-index: 9999;
            height: 40px;
            border-radius: 50px !important;
            box-shadow: 0 1px 5px #dedede;
        }

        button.owl-prev span,
        button.owl-next span {
            font-size: 31px;
            line-height: 22px;
            color: #4a4a4a;
        }

        button.owl-dot span {
            background: #d0c9c9 !IMPORTANT;
            height: 4px !important;
            width: 17px !important;
            border-radius: 2px !important;
            margin: 0 4px !important;
        }

        button.owl-dot.active span {
            background: #3722d3 !important;
        }

        /*  .card-info-bottom {
            display: none;
            } */
        /* User COurse Info*/
        .circle {
            height: 1em;
            width: 1em;
            position: relative;
            display: inline-block;
            border-radius: 50%;
            font-size: 100%;
            box-sizing: content-box;
            font-size: 200px;
        }

        .circle:after {
            content: "";
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0%;
            left: 0%;
            border-radius: 50%;
            box-sizing: border-box;
            border: solid 0.1em #cccccc;
            z-index: -1;
        }

        .circle>span {
            width: 100%;
            height: 100%;
            text-align: center;
            display: block;
            font-size: 0.2em;
            position: absolute;
            top: 0;
            left: 0;
            line-height: 5em;
            z-index: 9999;
            color: var(--color);
            white-space: nowrap;
            box-sizing: content-box;
            border-radius: 50%;
        }

        .circle>.bar {
            height: 100%;
            width: 100%;
            position: absolute;
            box-sizing: content-box;
        }

        .circle>.bar:before,
        .circle>.bar:after {
            content: '';
            height: 80%;
            width: 80%;
            position: absolute;
            border: solid 0.1em var(--color);
            border-radius: 50%;
            box-sizing: content-box;
            clip: rect(0, 0.5em, 1em, 0);
        }

        .card {
            width: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 2rem;
            background: var(--greyLight-1);
            box-shadow: 0px 20px 30px rgba(100, 131, 177, 0.2);
            padding: 2rem 5rem;
            margin: 0 10px;
            border: 1px solid white;
        }

        .card_percent {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .percentage_box {
            /*   display: flex; */
            justify-content: center;
        }

        /* p {
            font-size: 20px;
            } */
        .card_percent p {
            font-size: 15px;
            font-weight: 600;
            color: #27316b;
            text-align: center;
            margin-top: 12px;
            min-height: 43px;
        }


        .sub-cta-btn a.course_button.full.button.subscribe-btn1,
        input.course_button.full.button,
        .member-course-add {
            background: #EB5B77 !important;
            text-align: center;
            display: block;
            padding: 5px 15px;
            margin: 0 auto !important;
            max-width: 220px;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
            border: none;
            text-transform: capitalize !important;
        }

        section.rec-courses .sub-cta-btn {
            display: none;
        }
    </style>
    <style>
        /* #popup_this {
            top: 50%;
            left: 50%;
            text-align:center;
            margin-top: -50px;
            margin-left: -100px;
            position: fixed;
            background: #fff;
            padding: 30px;
            } */
        #popup_this {
            top: 50%;
            left: 50%;
            text-align: center;
            margin-top: -50px;
            position: fixed;
            background: #fff;
            padding: 30px;
            left: 0 !important;
            right: 0;
            max-width: 754px;
            margin: 0 auto;
        }

        /*   #popup_this {
            top: 0;
            left: 0;
            text-align: center;
            position: fixed;
            background: #fff;
            padding: 30px;
            right: 0;
            height: 100vh;
            z-index: 99;
            }    */
        .b-close {
            position: absolute;
            right: 0;
            top: 0;
            cursor: pointer;
            color: #fff;
            background: #ff0000;
            padding: 5px 10px;
        }

        /* Tag */
        .button-group-pills .btn.active {
            border-color: #14a4be;
            background-color: #ececec;
            color: #272323;
            box-shadow: 1px 1px 1px gainsboro;
            font-size: 14px;
        }

        .button-group-pills .btn {
            border-radius: 0;
            line-height: 1.2;
            margin-bottom: 15px;
            margin-left: 10px;
            border-color: #bbbbbb;
            background-color: #FFF;
            color: #14a4be;
        }

        .button-group-pills .btn:hover {
            border-color: #158b9f;
            background-color: #158b9f;
            color: #FFF;
        }

        #popup_this .cat_lable {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: 700;
            font-size: 12px;
            height: 33px;
            line-height: 12px;
            padding: 10px !important;
            font-family: cursive;
            margin: 6px;
        }
    </style>
    <style>
        .mini-sec {
            max-width: 570px !important;
            border: 1px solid #efefef;
            border-radius: 10px;
            box-shadow: 0 2px 5px #e6e6e6;
            margin-bottom: 20px;
        }

        img.mini-sec-img {
            width: 100%;
        }

        /* .mini-sec .col-md-5, .mini-sec .col-md-7 {
            padding: 0 5px;
            } */
        .progress {
            margin-bottom: 0;
            height: 14px;
            margin-top: -13px;
            z-index: 999;
            position: relative;
            border-radius: 0;
        }

        .progress-bar {
            font-size: 11px !important;
            line-height: 15px;
            background-color: #673ab7;
        }

        .col-md-7.right-side a h4 {
            font-size: 17px;
            color: #27316b;
            font-weight: 600;
        }

        .percentage_box .cs_duration {
            text-align: left !important;
        }
    </style>
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WLXDHXZ"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <nav class="navbar navbar-default">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand logo" href="https://www.oneeducation.org.uk/sb-dash-sa/">
                    <img src="https://www.oneeducation.org.uk/wp-content/uploads/2021/09/Group-9515-1.png" height="100px" alt="One Education">
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <!-- <ul class="nav navbar-nav">
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                        </li>
                        </ul> -->
                <!--  <form class="navbar-form navbar-left">
                        <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                        </form> -->
                <form class="navbar-form navbar-left" action="https://www.oneeducation.org.uk">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Course search" name="s">
                        <input type="hidden" name="post_type" value="course">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                        </div>
                    </div>
                </form>
                <!--     <form method="GET" action="https://www.oneeducation.org.uk">
                        <input type="hidden" name="post_type" value="course">                                    <input type="text" name="s" placeholder="Search courses.." value="">
                        <button type="submit" id="searchsubmit" class="ctm-search-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                        
                        </form> -->
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="https://www.oneeducation.org.uk/my-classes/">My Classes</a></li>
                    <li><a href="https://www.oneeducation.org.uk/courses/">All Courses</a></li>
                    <!-- <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                            <div class="dropdown-menu" 
                                id="profile-navBar"
                                aria-labelledby="dropdownMenu1"
                                >
                                <ul class="avatar-section">
                                    <li class="user-avatar">
                                        <img
                                            src="https://www.oneeducation.org.uk/wp-content/uploads/2021/07/avatar-one-.png"
                                            alt=""
                                            />
                                        <ul class="user_info">
                                            <li>Ellis Horton</li>
                                            <li>View profile</li>
                                            <li>LOGOUT</li>
                                        </ul>
                                    </li>
                                </ul>
                                <span id="admin-panel"
                                    ><a href="#"><i class="glyphicon glyphicon-cog"></i></a
                                    ></span>
                                <ul class="user-setting">
                                    <li>
                                        <a href="#">
                                        Dashboard <i class="glyphicon glyphicon-dashboard"></i
                                            ></a>
                                    </li>
                                    <li>
                                        <a href="#">Courses <i class="glyphicon glyphicon-book"></i></a>
                                    </li>
                                    <li>
                                        <a href="#">Stats <i class="glyphicon glyphicon-stats"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            >Notifications
                                        <i class="glyphicon glyphicon-exclamation-sign"></i
                                            ></a>
                                    </li>
                                    <li>
                                        <a href="#">Settings <i class="glyphicon glyphicon-cog"></i></a>
                                    </li>
                                    <li>
                                        <a href="#">Gifts <i class="glyphicon glyphicon-gift"></i></a>
                                    </li>
                                    <li>
                                        <a href="#">My Orders <i class="glyphicon glyphicon-list"></i></a>
                                    </li>
                                </ul>
                            </div>
                           
                        </li> -->
                    <?php
                    if (is_user_logged_in()) {
                    ?>
                        <li><a href="<?php echo $profile_link =  bp_loggedin_user_domain(); ?>"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
                        <li><a href="<?php echo esc_url(wp_logout_url()); ?>"><span class="glyphicon  glyphicon-off"></span> Logout</a></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>


    <section class="my-courses-section">
        <div class="container">
            <div class="row">
                <?php
                $user_info = get_userdata($user_id);
                $first_name = $user_info->first_name;
                $last_name = $user_info->last_name;
                $enrolled_sub_courses = get_user_meta($user_id, '_enrolled_courses', false);
                //  $profile_link =  bp_loggedin_user_domain();

                ?>
                <h2 class="rec-title">Welcome back <?php if (is_user_logged_in()) {
                                                        echo "$first_name $last_name";
                                                    } else {
                                                        echo "Guest";
                                                    } ?>, ready for your next lesson?</h2>
                <div class="percentage_box">
                    <div class="carousel-wrap">
                        <div class="owl-carousel owl-theme profile-course">
                            <?php
                            $color_code = array("#00AF91", "#C0E218", "#7C83FD");
                            $color_code_number = 0;
                            foreach ($enrolled_sub_courses as $enrolled_course) {
                                $percentage = bp_course_get_user_progress($user_id, $enrolled_course);

                            ?>
                                <div class="-col-md-6 item">
                                    <div class="mini-sec">
                                        <div class="row">
                                            <div class="col-md-5 left-side">
                                                <a href="#"><img class="mini-sec-img" src="<?php echo  get_the_post_thumbnail_url($enrolled_course, 'medium') ?>"></a>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: <?php if (!empty($percentage)) {
                                                                                                                    echo $percentage;
                                                                                                                } else {
                                                                                                                    echo 0;
                                                                                                                } ?>%;" aria-valuenow="<?php if (!empty($percentage)) {
                                                                                                                                                                                                        echo $percentage;
                                                                                                                                                                                                    } else {
                                                                                                                                                                                                        echo 0;
                                                                                                                                                                                                    } ?>" aria-valuemin="0" aria-valuemax="100"><?php if (!empty($percentage)) {
                                                                                                                                                                                                                                                                                                                    echo $percentage;
                                                                                                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                                                                                                    echo 0;
                                                                                                                                                                                                                                                                                                                } ?>%</div>
                                                </div>
                                            </div>
                                            <div class="col-md-7 right-side">
                                                <!-- <div class="first-inner">
                                                    <h5 class="five">LESSON 8 OF 11</h5>
                                                    <h5 class="three"><img class="mini-two-img" src="https://www.oneeducation.org.uk/wp-content/uploads/2021/09/Screenshot_763.png"></h5>
                                                    </div> -->
                                                <a href="<?php echo get_permalink($enrolled_course); ?>">
                                                    <h4><?php echo get_the_title($enrolled_course); ?></h4>
                                                </a>
                                                <div class="cs_duration">
                                                    <?php
                                                    // Duration
                                                    $course_id = $enrolled_course;
                                                    $course_curriculum = bp_course_get_curriculum($course_id);
                                                    $units = bp_course_get_curriculum_units($course_id);
                                                    $duration = $total_duration = 0;
                                                    foreach ($units as $unit) {
                                                        $duration = get_post_meta($unit, 'vibe_duration', true);
                                                        if (get_post_type($unit) == 'unit') {
                                                            $unit_duration_parameter = apply_filters('vibe_unit_duration_parameter', 60, $unit);
                                                        } elseif (get_post_type($unit) == 'quiz') {
                                                            $unit_duration_parameter = apply_filters('vibe_quiz_duration_parameter', 60, $unit);
                                                        }
                                                        $total_duration = $total_duration + $duration * $unit_duration_parameter;
                                                    }
                                                    //  echo 'Duration: '.gmdate("H:i",$total_duration);
                                                    $hour =  gmdate("g", $total_duration);
                                                    $min =  gmdate("i", $total_duration);
                                                    if (!empty($hour)) {
                                                        echo $hour . 'h ';
                                                    }
                                                    if (!empty($hour)) {
                                                        echo $min . 'm';
                                                    }
                                                    ?>
                                                </div>
                                                <div class="second-inner">
                                                    <h6 class="one"><?php echo get_the_excerpt(); ?></h6>
                                                    <!--  <h6 class="two">28m left</h6> -->
                                                    <!--  <?php sa_membeship_button($enrolled_course); ?> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Most Popular -->
    <section class="rec-courses">
        <div class="container">
            <div class="row">
                <h2 class="rec-title">Most Popular</h2>
                <div class="carousel-wrap">
                    <div class="owl-carousel owl-theme">
                        <?php
                        $args = array(
                            'post_type'         => 'course',
                            'post_status'       => 'publish',
                            'posts_per_page'    => 12,
                            //'post__not_in' =>   $user_course_List,
                            'meta_key'          => 'vibe_students',
                            'orderby'           => 'meta_value_num',
                            'order'             => 'DSC',
                            'tax_query' => array(
                                'relation' => 'OR',
                                array(
                                    'taxonomy' => 'popularity',
                                    'field' => 'id',
                                    'terms' => array('54567'),
                                ),

                            ),
                            'meta_query' => array(
                                array(
                                    'key' => 'vibe_product',
                                    'value'   => array(''),
                                    'compare' => 'NOT IN'
                                )
                            )
                        );
                        if (function_exists('vibe_get_option')) {
                            $excluded_courses = vibe_get_option('hide_courses');
                            $args['post__not_in'] = $excluded_courses;
                        }
                        $loop = new WP_Query($args);
                        ?>
                        <?php
                        while ($loop->have_posts()) : $loop->the_post(); ?>
                            <div class="item">
                                <div class="card-grid ctm">
                                    <div class="img-mastercover">
                                        <a href="<?php echo get_the_permalink(get_the_id()); ?>">
                                            <img src="<?php echo  get_the_post_thumbnail_url(get_the_id(), 'medium'); ?>" width="350" alt="Play button" class="play-button">
                                        </a>
                                    </div>
                                    <div class="card-text-area">
                                        <div class="duration_students">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <p class="students">
                                                        <?php
                                                        $number_of_student = bp_course_count_students_pursuing(get_the_id());
                                                        if ($number_of_student > 1) {
                                                        ?>
                                                            <span>
                                                                <?php echo $number_of_student; ?>
                                                            </span> students
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <span>
                                                                <?php echo $number_of_student; ?>
                                                            </span> student
                                                        <?php
                                                        }
                                                        ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="cs_duration">
                                                        <?php
                                                        // Duration
                                                        $course_id = get_the_ID();
                                                        $course_curriculum = bp_course_get_curriculum($course_id);
                                                        $units = bp_course_get_curriculum_units($course_id);
                                                        $duration = $total_duration = 0;
                                                        foreach ($units as $unit) {
                                                            $duration = get_post_meta($unit, 'vibe_duration', true);
                                                            if (get_post_type($unit) == 'unit') {
                                                                $unit_duration_parameter = apply_filters('vibe_unit_duration_parameter', 60, $unit);
                                                            } elseif (get_post_type($unit) == 'quiz') {
                                                                $unit_duration_parameter = apply_filters('vibe_quiz_duration_parameter', 60, $unit);
                                                            }
                                                            $total_duration = $total_duration + $duration * $unit_duration_parameter;
                                                        }
                                                        //  echo 'Duration: '.gmdate("H:i",$total_duration);
                                                        $hour =  gmdate("g", $total_duration);
                                                        $min =  gmdate("i", $total_duration);
                                                        if (!empty($hour)) {
                                                            echo $hour . 'h ';
                                                        }
                                                        if (!empty($hour)) {
                                                            echo $min . 'm';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-info-mid">
                                            <a href="<?php echo get_the_permalink(get_the_id()) ?>" class="cs-title">
                                                <h3><?php the_title() ?></h3>
                                            </a>
                                        </div>
                                        <div class="card-info-bottom">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="sub-cta-btn"> <?php sa_membeship_button(get_the_id()); ?></div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<script src="/path/to/jquery.bpopup.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bPopup/0.11.0/jquery.bpopup.min.js"></script>
<script>
    $('.rec-courses .owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        responsive: {
            0: {
                items: 2
            },
            600: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    })
    $('.rec-courses .profile-owl').owlCarousel({
        loop: false,
        margin: 10,
        nav: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    })

    $('.my-courses-section .profile-course').owlCarousel({
        loop: false,
        margin: 10,
        nav: true,
        dots: true,
        margin: 10,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 2
            }
        }
    })
</script>
</html>