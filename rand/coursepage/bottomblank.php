<?php
if (!defined('ABSPATH'))
    exit;
$courseID = get_the_ID();
$terms = get_the_terms($courseID, 'level');
$units = bp_course_get_curriculum_units($courseID);
$product_id = get_post_meta($courseID, 'vibe_product', true);
$price = get_post_meta($product_id, '_regular_price', true);
$sale = get_post_meta($product_id, '_sale_price', true);
$totalDiscount = ($price > 0) ? (100 - ((100 * $sale) / $price)) : 0;
function get_number_of_quizzes($courseID)
{
    $units = bp_course_get_curriculum_units($courseID);
    $quizCount = 0;
    foreach ($units as $unit) {
        if (get_post_type($unit) == 'quiz') {
            $quizCount++;
        }
    }
    return $quizCount;
}
$quiz_count = get_number_of_quizzes($courseID);

foreach ($units as $unit) {
    $duration = get_post_meta($unit, 'vibe_duration', true);
    $duration = empty($duration) ? 0 : $duration;

    $unit_duration_parameter = (get_post_type($unit) == 'unit')
        ? apply_filters('vibe_unit_duration_parameter', 60, $unit)
        : apply_filters('vibe_quiz_duration_parameter', 60, $unit);

    $total_duration += $duration * $unit_duration_parameter;
}

$courseDuration = tofriendlytime(($total_duration));

// Define SVGs for sharing buttons
$copy_link_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15.75 15.7493H20.25V3.74933H8.25V8.24933" stroke="#3E4759" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.7498 8.24951H3.74976V20.2495H15.7498V8.24951Z" stroke="#3E4759" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$messenger_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><g clip-path="url(#clip0_3919_9890)"><path d="M9.99755 0.0253906C4.48496 0.0253906 0.0200195 4.16606 0.0200195 9.27955C0.0200195 12.198 1.46676 14.7921 3.73665 16.4883L3.78654 19.731C3.78654 19.9056 3.98609 20.0054 4.13575 19.9306L7.25373 18.1845C8.12676 18.409 9.04968 18.5337 10.0225 18.5337C15.5351 18.5337 20 14.393 20 9.27955C20 4.16606 15.4852 0.0253906 9.99755 0.0253906ZM10.7209 12.2978L8.60069 10.0778C8.47598 9.95303 8.27642 9.92809 8.12676 10.0029L4.38519 12.0234C4.23553 12.0982 4.11081 11.9236 4.21058 11.7989L8.62564 7.10944C8.75036 6.98472 8.97485 6.98472 9.09957 7.10944L11.2198 9.37932C11.3445 9.50404 11.5441 9.55393 11.6937 9.45415L15.3605 7.45865C15.5101 7.38382 15.6349 7.55843 15.5351 7.68314L11.1949 12.2978C11.0701 12.4225 10.8456 12.4225 10.7209 12.2978Z" fill="#3E4759"/></g><defs><clipPath id="clip0_3919_9890"><rect width="20" height="20" fill="white"/></clipPath></defs></svg>';
$whatsapp_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><g clip-path="url(#clip0_3919_9894)"><path fill-rule="evenodd" clip-rule="evenodd" d="M16.6322 3.34007C14.878 1.58386 12.5451 0.616264 10.0598 0.615234C4.93861 0.615234 0.770668 4.78306 0.768608 9.90566C0.767921 11.5432 1.1957 13.1417 2.0088 14.5507L0.690674 19.3652L5.6161 18.0732C6.97325 18.8135 8.50115 19.2036 10.0561 19.2041H10.06C15.1806 19.2041 19.349 15.0359 19.351 9.9131C19.352 7.43042 18.3864 5.09617 16.6322 3.34007ZM10.0598 17.635H10.0566C8.67098 17.6344 7.312 17.262 6.12616 16.5586L5.84429 16.3911L2.92147 17.1579L3.70161 14.3082L3.51794 14.016C2.74489 12.7865 2.33668 11.3654 2.33736 9.90623C2.33897 5.64846 5.80332 2.18445 10.0629 2.18445C12.1256 2.18513 14.0646 2.98943 15.5226 4.44912C16.9805 5.90881 17.783 7.84904 17.7823 9.91253C17.7805 14.1706 14.3164 17.635 10.0598 17.635ZM14.2958 11.8513C14.0637 11.735 12.9222 11.1736 12.7094 11.096C12.4967 11.0185 12.3418 10.9799 12.1872 11.2122C12.0323 11.4445 11.5875 11.9675 11.452 12.1224C11.3165 12.2773 11.1812 12.2968 10.949 12.1805C10.7168 12.0644 9.96886 11.8191 9.08205 11.0282C8.39197 10.4126 7.92609 9.6524 7.79059 9.42009C7.65532 9.18754 7.78944 9.07402 7.89244 8.9463C8.14375 8.63422 8.39541 8.30704 8.47277 8.1522C8.55025 7.99724 8.51145 7.86163 8.45331 7.74547C8.39541 7.62932 7.93112 6.48662 7.73772 6.02165C7.54912 5.56915 7.35789 5.63026 7.21529 5.62317C7.08002 5.61642 6.92519 5.61504 6.77035 5.61504C6.61562 5.61504 6.36408 5.67306 6.15122 5.90561C5.93848 6.13804 5.33881 6.6996 5.33881 7.84229C5.33881 8.98498 6.17068 10.0889 6.28672 10.2438C6.40276 10.3988 7.9238 12.7437 10.2526 13.7492C10.8064 13.9886 11.2388 14.1313 11.5761 14.2383C12.1322 14.415 12.6382 14.39 13.0383 14.3303C13.4844 14.2636 14.4117 13.7686 14.6053 13.2264C14.7987 12.6841 14.7987 12.2193 14.7406 12.1224C14.6827 12.0256 14.5278 11.9675 14.2958 11.8513Z" fill="#3E4759"/></g><defs><clipPath id="clip0_3919_9894"><rect width="20" height="20" fill="white"/></clipPath></defs></svg>';
$linkedin_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M19 18.9995V12.4071C19 9.16721 18.3025 6.69226 14.5225 6.69226C12.7 6.69226 11.485 7.68224 10.99 8.62722H10.945V6.98475H7.36743V18.9995H11.1025V13.0371C11.1025 11.4622 11.395 9.9547 13.33 9.9547C15.2425 9.9547 15.265 11.7322 15.265 13.1271V18.977H19V18.9995Z" fill="#3E4759"/><path d="M1.29272 6.98438H5.02775V18.9991H1.29272V6.98438Z" fill="#3E4759"/><path d="M3.16001 1C1.96751 1 1 1.96748 1 3.15996C1 4.35243 1.96751 5.34241 3.16001 5.34241C4.35252 5.34241 5.32002 4.35243 5.32002 3.15996C5.32002 1.96748 4.35252 1 3.16001 1Z" fill="#3E4759"/></svg>';
?>
<div class="side-content-25">
    <div class="col-md-4">
        <div class="the-sidebar-rh">
            <div class="price-and-feature-card">
                <div class="course-video-image">
                    <?php bp_course_avatar(); ?>
                </div>
                <div class="course-price">
                    <?php bp_course_credits(); ?>
                </div>
                <h4>
                    <span>SAVE Upto <?php echo number_format($totalDiscount, 0, '.', '') . '%' ?></span>
                    - Ends Soon!
                </h4>
                <div class="timerMaileriStudy">
                    <img src="https://i.countdownmail.com/45zvvr.gif" alt="timer">
                </div>
                <div class="theFeatures-div">
                    <div class="theFeatures">
                        <div class="imgSvg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M9.99982 13.6V11.8M13.5998 6.40001H6.39981C4.41158 6.40001 2.7998 8.01179 2.7998 10V15.4C2.7998 17.3883 4.41158 19 6.39981 19H13.5998C15.5881 19 17.1998 17.3883 17.1998 15.4V10C17.1998 8.01179 15.5881 6.40001 13.5998 6.40001ZM13.5998 6.40001L13.5999 4.60001C13.5999 2.61178 11.9881 1 9.99985 1C8.66733 1 7.50391 1.72396 6.88145 2.8" stroke="#28303F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Access
                        </div>
                        <div class="text-h">
                            <span>
                                01 Year
                            </span>
                        </div>
                    </div>
                    <div class="theFeatures">
                        <div class="imgSvg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <g clip-path="url(#clip0_3919_9856)">
                                    <path d="M11.4286 5.71387H8.57143C8.19255 5.71387 7.82919 5.86438 7.56128 6.13229C7.29337 6.4002 7.14286 6.76356 7.14286 7.14244V17.8567C7.14286 18.2356 7.29337 18.599 7.56128 18.8669C7.82919 19.1348 8.19255 19.2853 8.57143 19.2853H11.4286C11.8075 19.2853 12.1708 19.1348 12.4387 18.8669C12.7066 18.599 12.8571 18.2356 12.8571 17.8567V7.14244C12.8571 6.76356 12.7066 6.4002 12.4387 6.13229C12.1708 5.86438 11.8075 5.71387 11.4286 5.71387ZM8.57143 17.8567V7.14244H11.4286V17.8567H8.57143ZM18.5714 0.713867H15.7143C15.3354 0.713867 14.972 0.864377 14.7041 1.13229C14.4362 1.4002 14.2857 1.76356 14.2857 2.14244V17.8567C14.2857 18.2356 14.4362 18.599 14.7041 18.8669C14.972 19.1348 15.3354 19.2853 15.7143 19.2853H18.5714C18.9503 19.2853 19.3137 19.1348 19.5816 18.8669C19.8495 18.599 20 18.2356 20 17.8567V2.14244C20 1.76356 19.8495 1.4002 19.5816 1.13229C19.3137 0.864377 18.9503 0.713867 18.5714 0.713867ZM15.7143 17.8567V2.14244H18.5714V17.8567H15.7143ZM4.28571 10.7139H1.42857C1.04969 10.7139 0.686328 10.8644 0.418419 11.1323C0.15051 11.4002 0 11.7636 0 12.1424V17.8567C0 18.2356 0.15051 18.599 0.418419 18.8669C0.686328 19.1348 1.04969 19.2853 1.42857 19.2853H4.28571C4.6646 19.2853 5.02796 19.1348 5.29587 18.8669C5.56378 18.599 5.71429 18.2356 5.71429 17.8567V12.1424C5.71429 11.7636 5.56378 11.4002 5.29587 11.1323C5.02796 10.8644 4.6646 10.7139 4.28571 10.7139ZM1.42857 17.8567V12.1424H4.28571V17.8567H1.42857Z" fill="#28303F" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_3919_9856">
                                        <rect width="20" height="20" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                            Level
                        </div>
                        <div class="text-h">
                            <span>
                                <?php
                                if ($terms && !is_wp_error($terms)) {
                                    foreach ($terms as $term) {
                                ?>
                                        <a href="<?php echo home_url(); ?>/level/<?php echo $term->slug; ?>"
                                            rel="tag"><?php echo $term->name; ?></a>
                                <?php
                                        break;
                                    }
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                    <?php
                    if (!has_term('bundle', 'course-cat', $courseID)) {
                    ?>
                        <div class="theFeatures">
                            <div class="imgSvg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M10.6504 7.51979C10.3168 7.93679 9.68256 7.93679 9.34895 7.51979L7.74947 5.52044C7.31296 4.9748 7.70144 4.16652 8.4002 4.16652L11.5992 4.16652C12.2979 4.16652 12.6864 4.9748 12.2499 5.52044L10.6504 7.51979Z" stroke="#28303F" stroke-width="1.25" />
                                    <path d="M15.333 14.3332C16.5691 15.9813 15.3931 18.3332 13.333 18.3332L6.66634 18.3332C4.60623 18.3332 3.43028 15.9813 4.66634 14.3332L6.79134 11.4999C7.45801 10.611 7.45801 9.38875 6.79134 8.49986L4.66635 5.66653C3.43028 4.01844 4.60623 1.66653 6.66635 1.66653L13.333 1.66653C15.3931 1.66653 16.5691 4.01844 15.333 5.66653L13.208 8.49986C12.5413 9.38875 12.5413 10.611 13.208 11.4999L15.333 14.3332Z" stroke="#28303F" stroke-width="1.5" />
                                </svg>
                                Course Duration
                            </div>
                            <div class="text-h">
                                <span> <?php echo $courseDuration; ?> </span>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <?php
                if (function_exists('sa_membeship_button')) {
                    $course_id = get_the_ID();
                    sa_membeship_button($course_id);
                } else {
                    the_course_button();
                }
                ?>
                <p class="or">or</p>
                <a href="<?php echo site_url(); ?>/yearly-subscription/" class="take-all-courses">
                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2025/04/Group-1000016716.svg" alt="offer">
                    All courses now <strong> £49 </strong>
                    <!-- <del>£149</del> -->
                </a>
                <div class="share-section">
                    <h3>Share this course:</h3>
                    <div class="share-buttons">
                        <!-- Copy Link Button -->
                        <button class="share-button copy-link" onclick="copyCourseLink(this)">
                            <?php echo $copy_link_svg; ?>
                            <span class="button-text">Copy Link</span>
                        </button>
                        <!-- Share on Messenger (using Facebook sharing URL) -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="share-button">
                            <?php echo $messenger_svg; ?>
                        </a>
                        <!-- Share on WhatsApp -->
                        <a href="https://wa.me/?text=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="share-button">
                            <?php echo $whatsapp_svg; ?>
                        </a>
                        <!-- Share on LinkedIn -->
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="share-button">
                            <?php echo $linkedin_svg; ?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="a2n_sponsored__wrapper">
                <div class="a2n_cpd"><img src="<?php echo get_theme_file_uri() . '/assets/images/webp/cpd-logo.webp' ?>"
                        alt="cpd"></div>
                <div class="a2n_mb"><img src="<?php echo get_theme_file_uri() . '/assets/images/webp/money-back-g.webp' ?>"
                        alt="money-back-14days"></div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="related-courses-rh">
    <h3 class="rltd-title">
        Related Courses
    </h3>
    <div class="related-courses-cards">
        <?php echo do_shortcode('[related_course]'); ?>
    </div>
</div>

</div>
</div>
</div>
</section>
</div>
<!-- JavaScript for Copy Link Functionality -->
<script>
    function copyCourseLink(button) {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            const textSpan = button.querySelector('.button-text');
            textSpan.textContent = 'Copied!';
            setTimeout(() => {
                textSpan.textContent = 'Copy Link';
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
</script>