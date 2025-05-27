<?php

function oneEdu_card_shortcode_slider( $atts ) {
    $atts = shortcode_atts(
        [
            'id'       => '',
            'category' => '',
        ],
        $atts
    );

    ob_start();

    // Base query
    $args = [
        'post_type'      => 'course',
        'posts_per_page' => 9,
        'post_status'    => 'publish',
    ];

    // Filter by IDs?
    if ( ! empty( $atts['id'] ) ) {
        $ids             = array_map( 'intval', explode( ',', $atts['id'] ) );
        $args['post__in'] = $ids;
        $args['orderby']  = 'post__in';
    }
    // Or by category?
    elseif ( ! empty( $atts['category'] ) ) {
        $cats             = array_map( 'intval', explode( ',', $atts['category'] ) );
        $args['tax_query'] = [
            [
                'taxonomy' => 'course-cat',
                'field'    => 'term_id',
                'terms'    => $cats,
            ],
        ];
        $args['meta_key'] = 'vibe_students';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
    }

    $loop = new WP_Query( $args );

    if ( $loop->have_posts() ) : ?>

        <div class="srs_cards_pr">
            <?php while ( $loop->have_posts() ) : $loop->the_post(); 
                $course_ID  = get_the_ID();
                $img_url    = get_the_post_thumbnail_url( $course_ID, 'medium' ) ?: '';
                $avg_rating = get_post_meta( $course_ID, 'average_rating', true );
                $pct        = is_numeric( $avg_rating ) ? ( $avg_rating / 5 * 100 ) : 0;
                $title      = get_the_title();
                $students   = get_post_meta( $course_ID, 'vibe_students', true );
                $permalink  = get_permalink();
            ?>
                <div class="srs_card_pr">
                    <div class="srs_img_wrapper">
                        <a href="<?php echo esc_url( $permalink ); ?>">
                            <img src="<?php echo esc_url( $img_url ); ?>" alt="">
                        </a>
                    </div>
                    <div class="srs_content_wrapper">
                        <a class="srs_title" href="<?php echo esc_url( $permalink ); ?>">
                            <?php echo esc_html( $title ); ?>
                        </a>
                        <div class="srs_meta_data">
                            <div class="srs_student_count">
                                <img src="<?php echo site_url( '/wp-content/uploads/2024/05/bi_people-fill.png' ); ?>" alt="">
                                <p><?php echo intval( $students ); ?> Students</p>
                            </div>
                            <div class="rating_count">
                                <div class="srs-ratings-container bp_blank_stars">
                                    <div class="bp_filled_stars" style="width: <?php echo esc_attr( $pct ); ?>%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="srs_price_container">
                            <?php bp_course_credits(); ?>
                            <a class="srs_btn" href="<?php echo esc_url( $permalink ); ?>">View More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

    <?php else : ?>
        <p>No Course Found</p>
    <?php endif;

    return ob_get_clean();
}
add_shortcode( 'oneEdu_card_slider', 'oneEdu_card_shortcode_slider' );