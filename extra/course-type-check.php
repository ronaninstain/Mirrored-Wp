<?php
/*
 * Template Name: Course Type Check
 */
get_header();
?>

<section class="testParent">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                $post_3 = 40059;
                $course3 = get_post_meta('40059');


                $meta_keys_to_remove = [
                    '_elementor_edit_mode',
                    '_elementor_template_type',
                    '_elementor_version',
                    '_elementor_css',
                    '_elementor_pro_version'
                ];
                $meta_key2 = 'bbconv_not_converted';
                $meta_value = '1';

                foreach ($meta_keys_to_remove as $meta_key) {
                    delete_post_meta($post_3, $meta_key);
                }
                update_post_meta($post_3, $meta_key2,  $meta_value);

                ?>
                <h3>course 3</h3>
                <pre>
                    <?php var_dump($course3); ?>
                </pre>

            </div>
        </div>
    </div>
</section>

<?php
get_footer();
?>