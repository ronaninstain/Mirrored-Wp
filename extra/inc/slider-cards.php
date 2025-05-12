<?php
    function slider_cards_function($atts)
    {
        $atts = shortcode_atts(
            [
                'id' => '',
            ], $atts, 'slider_cards'
        );

        $ids = array_filter(array_map('trim', explode(',', $atts['id'])));
        if (empty($ids)) {
            return '<p>No Course ID provided.</p>';
        }

        // Prepare args
        $args = [
            'post_type'      => 'course',
            'posts_per_page' => 30,
            'post__in'       => $ids,
            'orderby'        => 'post__in',
            'post_status'    => 'publish',
        ];

        $loop = new WP_Query($args);
        if (! $loop->have_posts()) {
            return '<p>No Course Found</p>';
        }

        ob_start();

        // Choose wrapper based on device
        $wrapper_class = wp_is_mobile() ? 'a2n-course-slider' : 'a2n_nxt-container';
    ?>
<div class="<?php echo esc_attr($wrapper_class); ?>">
    <?php while ($loop->have_posts()): $loop->the_post(); ?>
    <?php
        $course_ID      = get_the_ID();
        $average_rating = get_post_meta($course_ID, 'average_rating', true);
        $stds = get_post_meta($course_ID, 'vibe_students', true);
        $rating_count   = get_post_meta($course_ID, 'rating_count', true);
        $ProductID   = get_post_meta($course_ID, 'vibe_product', true);
        $course_title   = get_the_title();
        $course_link    = get_the_permalink();
        $course_img     = get_the_post_thumbnail_url($course_ID, 'medium');
        $percentage     = is_numeric($average_rating) ? ($average_rating / 5) * 100 : 0;
        $image_url      = get_the_post_thumbnail_url($course_ID);
        ?>
    <div class="a2n-course-item">
        <div class="nxt-start">
            <?php
             if (is_image_broken($image_url)) {
            $placeholder_img = get_stylesheet_directory_uri() . '/assets/img/default-image.webp';
            ?>
            <img width="100%" src="<?php echo $placeholder_img; ?>" alt="img">
            <?php
            } else {
            ?>
            <img src="<?php echo $course_img ?>" alt="<?php echo $course_title; ?>" />
            <?php
            }
            ?>
        </div>

        <div class="nxt-contents">
            <div class="nxt_ratings">
                <h3><?php echo $average_rating ?></h3>
                <div class="a2n-ratings-container bp_blank_stars">
                    <div style="width:<?php echo $percentage ?>%" class="bp_filled_stars">
                    </div>
                </div>
            </div>
            <a class="nxt_title" href="<?php echo $courseLink ?>"><?php echo $course_title ?></a>
            <div class="nxtPriceAndStds">
                <div class="stds">
                    <span><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12"
                            fill="none">
                            <path
                                d="M5.25 10.5C5.25 10.5 4.5 10.5 4.5 9.75C4.5 9 5.25 6.75 8.25 6.75C11.25 6.75 12 9 12 9.75C12 10.5 11.25 10.5 11.25 10.5H5.25ZM8.25 6C8.84674 6 9.41903 5.76295 9.84099 5.34099C10.2629 4.91903 10.5 4.34674 10.5 3.75C10.5 3.15326 10.2629 2.58097 9.84099 2.15901C9.41903 1.73705 8.84674 1.5 8.25 1.5C7.65326 1.5 7.08097 1.73705 6.65901 2.15901C6.23705 2.58097 6 3.15326 6 3.75C6 4.34674 6.23705 4.91903 6.65901 5.34099C7.08097 5.76295 7.65326 6 8.25 6ZM3.912 10.5C3.80087 10.2658 3.74542 10.0091 3.75 9.75C3.75 8.73375 4.26 7.6875 5.202 6.96C4.73189 6.81483 4.24198 6.74397 3.75 6.75C0.75 6.75 0 9 0 9.75C0 10.5 0.75 10.5 0.75 10.5H3.912ZM3.375 6C3.87228 6 4.34919 5.80246 4.70083 5.45083C5.05246 5.09919 5.25 4.62228 5.25 4.125C5.25 3.62772 5.05246 3.15081 4.70083 2.79917C4.34919 2.44754 3.87228 2.25 3.375 2.25C2.87772 2.25 2.40081 2.44754 2.04917 2.79917C1.69754 3.15081 1.5 3.62772 1.5 4.125C1.5 4.62228 1.69754 5.09919 2.04917 5.45083C2.40081 5.80246 2.87772 6 3.375 6Z"
                                fill="#2B354E" />
                        </svg> <?php echo $stds . ' Student'; ?></span>
                </div>
                <div class="nxt-end_tag">
                    <?php bp_course_credits(); ?>
                </div>
            </div>
            <div class="addToAndDetailsbtns">
                <div class="nxt-end">
                    <a href="<?php echo $courseLink; ?>" class="nxt_button">View Details</a>
                </div>
                <a href="<?php echo $site_url; ?>/cart/?add-to-cart=<?php echo $ProductID; ?>" class="addtobtn">Add to
                    Cart</a>
            </div>

        </div>
    </div>
    <?php endwhile;
                    wp_reset_postdata(); ?>
</div>

<?php if (wp_is_mobile()): ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.a2n-course-slider').slick({
            arrows: true,
            dots: true,
            adaptiveHeight: true,
            prevArrow: '<button type="button" class="slick-prev">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 52 52" fill="none">' +
                '<defs>' +
                '<filter id="filter0_d_4653_28347" x="0" y="0" width="52" height="52" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">' +
                '<feFlood flood-opacity="0" result="BackgroundImageFix"/>' +
                '<feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>' +
                '<feOffset/>' +
                '<feGaussianBlur stdDeviation="5"/>' +
                '<feComposite in2="hardAlpha" operator="out"/>' +
                '<feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.2 0"/>' +
                '<feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_4653_28347"/>' +
                '<feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_4653_28347" result="shape"/>' +
                '</filter>' +
                '</defs>' +
                '<g filter="url(#filter0_d_4653_28347)">' +
                '<rect x="42" y="42" width="32" height="32" rx="16" transform="rotate(-180 42 42)" fill="white"/>' +
                '<path d="M28.6667 20.6667L23.3333 26L28.6667 31.3333" stroke="#00378B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' +
                '</g>' +
                '</svg>' +
                '</button>',
            nextArrow: '<button type="button" class="slick-next">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="51" height="52" viewBox="0 0 51 52" fill="none">' +
                '<defs>' +
                '<filter id="filter0_d_4653_28344" x="0" y="0" width="51" height="52" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">' +
                '<feFlood flood-opacity="0" result="BackgroundImageFix"/>' +
                '<feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>' +
                '<feOffset/>' +
                '<feGaussianBlur stdDeviation="5"/>' +
                '<feComposite in2="hardAlpha" operator="out"/>' +
                '<feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.2 0"/>' +
                '<feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_4653_28344"/>' +
                '<feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_4653_28344" result="shape"/>' +
                '</filter>' +
                '</defs>' +
                '<g filter="url(#filter0_d_4653_28344)">' +
                '<rect width="31" height="32" rx="15.5" transform="matrix(1 0 0 -1 10 42)" fill="white"/>' +
                '<path d="M22.9167 20.6667L28.0833 26L22.9167 31.3333" stroke="#00378B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' +
                '</g>' +
                '</svg>' +
                '</button>',
            slidesToShow: 1,
            centerMode: true,
            centerPadding: '15%', // ~.7 of next slide peeking in
            responsive: [{
                    // up to 768px (tablet)
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2, // 2 slides with padding
                        centerMode: true,
                        centerPadding: '15%' // ~.7 of 3rd slide
                    }
                },
                {
                    // up to 480px (small phones)
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        centerMode: true,
                        centerPadding: '10%' // bigger peek
                    }
                }
            ]
        });
    });
</script>


<?php endif; ?>

<?php
        return ob_get_clean();
        }
        add_shortcode('slider_cards', 'slider_cards_function');
    ?>