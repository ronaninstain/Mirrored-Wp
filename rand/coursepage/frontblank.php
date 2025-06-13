<?php

/**
 * The template for displaying Course font
 *
 * Override this template by copying it to yourtheme/course/single/front.php
 *
 * @author 		VibeThemes
 * @package 	vibe-course-module/templates
 * @version     2.0
 */

if (!defined('ABSPATH'))
    exit;
global $post;
$id = get_the_ID();
$courseID = get_the_ID();
$courseTitle = get_the_title($courseID);
$courseExcerpt = get_the_excerpt($courseID);
$average_rating = get_post_meta($courseID, 'average_rating', true);
$countRating = get_post_meta($courseID, 'rating_count', true);
$courseStudents = get_post_meta($courseID, 'vibe_students', true);
?>
<div class="adminBarIstudy">

    <?php
    // $user = wp_get_current_user();
    $roles = (array) $user->roles;

    // var_dump($roles );
    $notAllowedRoles = array('Subscriber', 'Student');

    if (is_user_logged_in()) {
        if (!in_array($user->roles, $notAllowedRoles)) {
    ?>

            <div class="item-nav">
                <div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
                    <div id="item-body">
                        <!-- Admin nav start -->
                        <ul>
                            <?php bp_get_options_nav(); ?>
                            <?php
                            if (function_exists('bp_course_nav_menu'))
                                bp_course_nav_menu();
                            ?>
                            <?php do_action('bp_course_options_nav'); ?>
                        </ul>

                        <!-- Admin nav end -->
                    </div>
                </div>
            </div>



    <?php
        }
    }
    ?>
</div>
<div class="main-content-25">
    <div class="col-md-8">
        <?php if (!wp_is_mobile()) {
        ?>
            <div class="course-intro-25">
                <div class="breadcrumbs-rh">
                    <?php vibe_breadcrumbs(); ?>
                </div>
                <div class="single-course-title">
                    <h1><?php echo $courseTitle; ?></h1>
                </div>
                <div class="single-course-excerpt">
                    <p><?php echo $courseExcerpt; ?></p>
                </div>
                <div class="intro-ratings-stds-25">
                    <div class="a2n-rating__container">
                        <div class="a2n_ratings bp_blank_stars">
                            <div style="width: <?php echo $average_rating ? 20 * $average_rating : 0; ?>"
                                class="bp_filled_stars">
                            </div>
                        </div>
                        <p><?php echo $average_rating; ?></p>
                        <span>(<?php echo $countRating . ' Rating'; ?>)</span>
                    </div>
                    <div class="studetns_count course_meta">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.2" d="M8.25 15C10.9424 15 13.125 12.8174 13.125 10.125C13.125 7.43261 10.9424 5.25 8.25 5.25C5.55761 5.25 3.375 7.43261 3.375 10.125C3.375 12.8174 5.55761 15 8.25 15Z" fill="#1D2026" />
                            <path d="M8.25 15C10.9424 15 13.125 12.8174 13.125 10.125C13.125 7.43261 10.9424 5.25 8.25 5.25C5.55761 5.25 3.375 7.43261 3.375 10.125C3.375 12.8174 5.55761 15 8.25 15Z" fill="#D9D9D9" stroke="#1D2026" stroke-width="1.5" stroke-miterlimit="10" />
                            <path d="M14.5698 5.43158C15.2403 5.24266 15.9436 5.19962 16.6321 5.30537C17.3207 5.41111 17.9786 5.66318 18.5615 6.04459C19.1444 6.42601 19.6389 6.92791 20.0115 7.5165C20.3841 8.10509 20.6263 8.7667 20.7217 9.45676C20.8171 10.1468 20.7635 10.8493 20.5645 11.5169C20.3655 12.1845 20.0258 12.8018 19.5682 13.327C19.1107 13.8523 18.5458 14.2734 17.9118 14.562C17.2777 14.8505 16.5892 14.9999 15.8926 15" stroke="#1D2026" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M1.49951 18.5059C2.26089 17.4229 3.27166 16.539 4.4465 15.9288C5.62133 15.3186 6.92574 15.0001 8.24959 15C9.57344 14.9999 10.8779 15.3184 12.0528 15.9285C13.2276 16.5386 14.2385 17.4225 14.9999 18.5054" stroke="#1D2026" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M15.8926 15C17.2166 14.999 18.5213 15.3171 19.6962 15.9273C20.8712 16.5375 21.8819 17.4218 22.6426 18.5054" stroke="#1D2026" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span><?php echo 'Students Enrolled ' . '<p>' . $courseStudents . '</p>'; ?></span>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        <div class="tabs-rh">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#tab1" aria-expanded="true">Overview</a>
                </li>
                <li class="">
                    <a data-toggle="tab" href="#tab2" aria-expanded="false">Curriculum</a>
                </li>
                <li class="">
                    <a data-toggle="tab" href="#tab4" aria-expanded="false">Certificate</a>
                </li>
                <li class="">
                    <a data-toggle="tab" href="#tab3" aria-expanded="false">Reviews</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="tab1" class="tab-pane fade active in">
                    <?php echo do_shortcode('[elementor-template id="256590"]'); ?>
                    <h2>Course Description</h2>
                    <p>
                        <?php the_content(); ?>
                    </p>
                </div>
                <div id="tab2" class="tab-pane fade">
                    <?php
                    function createMultidimensionalArray()
                    {
                        $curriculums = bp_course_get_curriculum(get_the_ID());
                        $resultArray = array();
                        $currentParent = null;

                        if (!empty($curriculums)) {
                            if (is_numeric($curriculums[0])) {
                                $currentParent = get_the_title(get_the_ID());
                                $resultArray[$currentParent] = array();
                            }

                            foreach ($curriculums as $item) {
                                if (!is_numeric($item)) {
                                    $currentParent = $item;
                                    $resultArray[$currentParent] = array();
                                } else {
                                    if ($currentParent !== null) {
                                        $resultArray[$currentParent][] = intval($item);
                                    }
                                }
                            }
                        }
                        return $resultArray;
                    }

                    $multidimensionalArray = createMultidimensionalArray();
                    $id = 1;

                    $totalSections = count($multidimensionalArray);
                    $totalLectures = 0;
                    $totalDurationSeconds = 0;

                    foreach ($multidimensionalArray as $section => $items) {
                        foreach ($items as $item_id) {
                            $post_type = get_post_type($item_id);
                            if ($post_type == 'unit') {
                                $totalLectures++;
                                $durationMinutes = get_post_meta($item_id, 'vibe_duration', true);
                                if (!empty($durationMinutes)) {
                                    $totalDurationSeconds += $durationMinutes * 60;
                                }
                            }
                        }
                    }

                    $totalHours = floor($totalDurationSeconds / 3600);
                    $remainingSeconds = $totalDurationSeconds % 3600;
                    $totalMinutes = floor($remainingSeconds / 60);
                    $totalDurationFormatted = $totalHours . ' hours ' . $totalMinutes . ' mins';
                    ?>

                    <div class="course-summary">
                        <h6>
                            <?php echo $totalSections; ?> Sections <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                                <path d="M10 16.1404L8.938 15.0784L13.125 10.8904H4V9.39038H13.125L8.938 5.20238L10 4.14038L16 10.1404L10 16.1404Z" fill="#144345" />
                            </svg>
                            <?php echo $totalLectures; ?> lectures <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                                <path d="M10 16.1404L8.938 15.0784L13.125 10.8904H4V9.39038H13.125L8.938 5.20238L10 4.14038L16 10.1404L10 16.1404Z" fill="#144345" />
                            </svg>
                            <?php echo $totalDurationFormatted; ?> in total
                        </h6>
                    </div>

                    <div class="panel-group" id="accordion">
                        <?php
                        $upward_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
  <mask id="mask0_3919_10709" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="23" height="23">
    <rect x="22.1665" y="22.2295" width="21.909" height="21.909" transform="rotate(-180 22.1665 22.2295)" fill="#D9D9D9"/>
  </mask>
  <g mask="url(#mask0_3919_10709)">
    <path d="M11.212 8.19468L16.6892 13.6719L15.4112 14.95L11.212 10.7507L7.01275 14.95L5.73472 13.6719L11.212 8.19468Z" fill="#00ABD0"/>
  </g>
</svg>';
                        $downward_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
  <mask id="mask0_3919_10719" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="23" height="23">
    <rect x="0.302246" y="0.536865" width="21.909" height="21.909" fill="#D9D9D9"/>
  </mask>
  <g mask="url(#mask0_3919_10719)">
    <path d="M11.2568 14.5719L5.77954 9.09467L7.05756 7.81665L11.2568 12.0159L15.456 7.81665L16.734 9.09467L11.2568 14.5719Z" fill="#00ABD0"/>
  </g>
</svg>';

                        foreach ($multidimensionalArray as $key => $items) :
                            $sectionLectures = 0;
                            $sectionDurationSeconds = 0;

                            foreach ($items as $item_id) {
                                $post_type = get_post_type($item_id);
                                if ($post_type == 'unit') {
                                    $sectionLectures++;
                                    $durationMinutes = get_post_meta($item_id, 'vibe_duration', true);
                                    if (!empty($durationMinutes)) {
                                        $sectionDurationSeconds += $durationMinutes * 60;
                                    }
                                }
                            }

                            if ($sectionDurationSeconds >= 3600) {
                                $sectionHours = floor($sectionDurationSeconds / 3600);
                                $sectionMinutes = floor(($sectionDurationSeconds % 3600) / 60);
                                $sectionDurationFormatted = $sectionHours . ' hour ' . $sectionMinutes . ' mins';
                            } else {
                                $sectionMinutes = floor($sectionDurationSeconds / 60);
                                $sectionSeconds = $sectionDurationSeconds % 60;
                                $sectionDurationFormatted = $sectionMinutes . ' m: ' . $sectionSeconds . ' s';
                            }
                        ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $id; ?>"
                                        aria-expanded="<?php echo ($id == 1) ? 'true' : 'false'; ?>"
                                        class="<?php echo ($id == 1) ? '' : 'collapsed'; ?>">
                                        <div class="section-first">
                                            <span class="icon upward-arrow"><?php echo $upward_svg; ?></span>
                                            <span class="icon downward-arrow"><?php echo $downward_svg; ?></span>
                                            <span class="section-title"><?php echo $key; ?></span>
                                        </div>
                                        <span class="section-details">
                                            <?php echo $sectionLectures; ?> <?php echo ($sectionLectures == 1) ? 'lecture' : 'lectures'; ?> •
                                            <?php echo $sectionDurationFormatted; ?>
                                        </span>
                                    </a>
                                </div>
                                <div id="collapse<?php echo $id; ?>"
                                    class="panel-collapse <?php echo ($id == 1) ? 'collapse in' : 'collapse'; ?>"
                                    aria-expanded="<?php echo ($id == 1) ? 'true' : 'false'; ?>">
                                    <div class="panel-body">
                                        <ul>
                                            <?php foreach ($items as $item_id) :
                                                $post_type = get_post_type($item_id);
                                                if ($post_type == 'unit') : ?>
                                                    <li>
                                                        <div class="videoTitle">
                                                            <img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/svg/video.svg' ?>" alt="video">
                                                            <?php echo get_the_title($item_id); ?>
                                                        </div>
                                                        <div class="videoDuration">
                                                            <?php
                                                            $curriculumnDuration = get_post_meta($item_id, 'vibe_duration', true);
                                                            if (!empty($curriculumnDuration)) {
                                                                $seconds = $curriculumnDuration * 60;
                                                                $datetime = new DateTime("@$seconds");
                                                                echo $datetime->format('H:i:s');
                                                            }
                                                            ?>
                                                        </div>
                                                    </li>
                                                <?php elseif ($post_type == 'quiz') : ?>
                                                    <li>
                                                        <div class="videoTitle">
                                                            <img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/svg/video.svg' ?>" alt="quiz">
                                                            <?php echo get_the_title($item_id); ?>
                                                        </div>
                                                    </li>
                                            <?php endif;
                                            endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php $id++; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div id="tab3" class="tab-pane fade"><?php comments_template('/course-review.php', true); ?></div>
                <div id="tab4" class="tab-pane fade">
                    <div class="certificate-25">
                        <?php echo do_shortcode('[elementor-template id="256969"]'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>