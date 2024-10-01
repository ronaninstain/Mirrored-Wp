<?php

/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header('shop'); ?>

<?php

$user = get_current_user();

if (is_user_logged_in()) {
}
// Get the course ID from the query string
// $course_id = get_query_var('course_id');
$product_id = get_the_ID();

global $wpdb;
$table_name = $wpdb->prefix . 'ptc_items';
$course_id = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT course_id FROM $table_name WHERE product_id = %d",
        $product_id
    )
);
// Define the source site's API endpoint
$api_url = 'https://course-dashboard.com/wp-json/custom-api/v1/courses/' . $course_id;

$client_id = get_option('client_id'); // Update as necessary
$secret_key = get_option('secret_key'); // Update as necessary

// Add the Authorization header
$args = array(
    'headers' => array(
        'Authorization' => 'Bearer ' . $client_id . ':' . $secret_key,
    ),
);

// Fetch data from the API with the Authorization header
$response = wp_remote_get($api_url, $args);

if (is_wp_error($response)) {
    echo '<p>Unable to retrieve course at this time.</p>';
    get_footer();
    exit;
}

$body = wp_remote_retrieve_body($response);
$data = json_decode($body, true);
$course = $data;

// Check if course data is empty
if (empty($course)) {
    echo '<p>Course not found.</p>';
} else {
?>
    <!-- Page Header section start here -->
    <div class="pageheader-section style-2">
        <div class="container">
            <div class="row justify-content-center justify-content-lg-between align-items-center flex-row-reverse">
                <div class="col-lg-7 col-12">
                    <div class="pageheader-thumb">
                        <img src="<?php echo esc_url($course['thumbnail']); ?>" alt="Course Thumbnail" class="w-100">
                    </div>
                </div>
                <div class="col-lg-5 col-12">
                    <div class="pageheader-content">
                        <div class="course-category">
                            <?php
                            $category_count = 0;
                            foreach ($course['categories'] as $category):
                                if ($category_count >= 2) {
                                    break;
                                }
                            ?>
                                <div class="course-cate">
                                    <?php $category_link = home_url('/display-courses-by-category?category=' . urlencode($category)); ?>
                                    <a href="<?php echo esc_url($category_link); ?>"><?php echo esc_html($category); ?></a>
                                </div>
                            <?php
                                $category_count++;
                            endforeach;
                            ?>

                        </div>
                        <h2 class="phs-title"><?php echo esc_html($course['title']); ?></h2>
                        <p class="phs-desc"><?php echo wp_kses_post($course['excerpt']); ?></p>


                        <div class="phs-thumb">
                            <div class="course-reiew">
                                <span class="ratting">
                                    <?php
                                    $rating = $course['meta']['average_rating'];
                                    for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($rating >= $i): ?>
                                            <i class="icofont-ui-rating"></i>
                                        <?php elseif ($rating > $i - 1 && $rating < $i): ?>
                                            <i class="icofont-ui-rate-blank" style="position: relative;">
                                                <i class="icofont-ui-rating"
                                                    style="position: absolute; top: 0; left: 0; width: 50%; overflow: hidden;"></i>
                                            </i>
                                        <?php else: ?>
                                            <i class="icofont-ui-rate-blank"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>

                                </span>
                                <span class="ratting-count">
                                    <span class="rating-count"><?php echo esc_html($course['meta']['rating_count']); ?>
                                        reviews</span>
                                </span>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header section ending here -->

    <!-- Course section start here -->
    <div class="course-single-section padding-tb section-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="main-part">
                        <div class="course-item">
                            <div class="course-inner">

                                <?php

                                // get highlight text 
                                $highlight = get_field("course_highlight");
                                if ($highlight) {
                                    echo '<h2>' . $highlight . '</h2>';
                                }

                                // get requirements filed
                                $requirements = the_field('requirements');
                                if ($requirements) {
                                    echo $requirements;
                                }
                                // get career path filed
                                $careerpath = the_field('career_path');
                                if ($careerpath) {
                                    echo $careerpath;
                                }

                                // get faq repeater content
                                $rows = get_field('faq_section');
                                if ($rows) {
                                    echo '<ul class="slides">';
                                    foreach ($rows as $row) {
                                        $question = $row['questions'];
                                        $answer = $row['answers'];
                                        echo '<li>';
                                        echo wp_kses_post(wpautop($question));
                                        echo wp_kses_post(wpautop($answer));
                                        echo '</li>';
                                    }
                                    echo '</ul>';
                                }
                                ?>

                                <?php
                                // get dynamic sidebar
                                if (is_active_sidebar('sidebar-1')) : ?>
                                    <ul id="sidebar">
                                        <?php dynamic_sidebar('sidebar-1'); ?>
                                    </ul>
                                <?php endif; ?>

                                <div class="course-content">
                                    <?php echo wp_kses_post($course['content']); ?>
                                </div>
                            </div>
                        </div>

                        <div class="course-video">
                            <div class="course-video-title">
                                <h4>Course Video List</h4>
                            </div>
                            <div class="course-video-content">
                                <div class="accordion" id="accordionExample">

                                    <?php
                                    // Initialize a counter for unique IDs
                                    $accordionIndex = 1;
                                    foreach ($course['curriculum'] as $sectionTitle => $items) {
                                        // Generate unique IDs for each section
                                        $accordionId = "accordion" . str_pad($accordionIndex, 2, '0', STR_PAD_LEFT);
                                        $collapseId = "videolist" . $accordionIndex;

                                        // Display the section header
                                        echo '<div class="accordion-item">';
                                        echo '<div class="accordion-header" id="' . htmlspecialchars($accordionId) . '">';
                                        echo '<button class="d-flex flex-wrap justify-content-between" data-bs-toggle="collapse" data-bs-target="#' . htmlspecialchars($collapseId) . '" aria-expanded="true" aria-controls="' . htmlspecialchars($collapseId) . '">';
                                        echo '<span>' . htmlspecialchars($sectionTitle) . '</span>';
                                        echo '<span>' . count($items) . ' lessons</span>'; // Adjust as needed
                                        echo '</button>';
                                        echo '</div>';

                                        // Display the section content
                                        echo '<div id="' . htmlspecialchars($collapseId) . '" class="accordion-collapse collapse" aria-labelledby="' . htmlspecialchars($accordionId) . '" data-bs-parent="#accordionExample">';
                                        echo '<ul class="lab-ul video-item-list">';

                                        foreach ($items as $index => $item) {
                                            // Generate a unique ID for each item if needed
                                            $itemIndex = $index + 1;
                                            echo '<li class="d-flex flex-wrap justify-content-between">';
                                            echo '<div class="video-item-title">' . htmlspecialchars($item) . '</div>';
                                            echo '<div class="video-item-icon"><a data-rel="lightcase"><i class="icofont-play-alt-2"></i></a></div>';
                                            echo '</li>';
                                        }

                                        echo '</ul>';
                                        echo '</div>';
                                        echo '</div>';

                                        // Increment counter for the next section
                                        $accordionIndex++;
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Sidebar and Enroll Section -->
                    <div class="sidebar-part">
                        <div class="course-side-detail">
                            <div class="csd-title">
                                <div class="csdt-left">
                                    <h4 class="mb-0">
                                        <?php
                                        if ($product_id) {
                                            // Get the product object
                                            $product = wc_get_product($product_id);
                                            if ($product) {
                                                $price = $product->get_sale_price() ?: $product->get_regular_price();
                                                echo '<sup>' . get_woocommerce_currency_symbol() . '</sup>' . esc_html($price);
                                            } else {
                                                echo 'Product not found.';
                                            }
                                        } else {
                                            echo 'No product associated with this course.';
                                        }
                                        ?>
                                    </h4>
                                </div>
                                <div class="csdt-right">
                                    <p class="mb-0"><i class="icofont-clock-time"></i>Limited time offer</p>
                                </div>
                            </div>
                            <div class="csd-content">
                                <div class="csdc-lists">
                                    <ul class="lab-ul">
                                        <li>
                                            <div class="csdc-left"><i class="icofont-ui-alarm"></i>Course Level</div>
                                            <div class="csdc-right">
                                                <?php
                                                // Ensure 'course_levels' is set and is an array
                                                if (!empty($course['course_levels']) && is_array($course['course_levels'])) {
                                                    foreach ($course['course_levels'] as $level) {
                                                        echo esc_html($level);
                                                    }
                                                } else {
                                                    echo '<p>No course levels found.</p>';
                                                }
                                                ?>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="csdc-left"><i class="icofont-book-alt"></i>Course Duration</div>
                                            <div class="csdc-right">
                                                <?php echo esc_html($course['course_duration'] ?: 'N/A'); ?>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="csdc-left"><i class="icofont-signal"></i>Online Class</div>
                                            <div class="csdc-right">
                                                Fully Online
                                            </div>
                                        </li>
                                        <li>
                                            <div class="csdc-left"><i class="icofont-video-alt"></i>Lessons</div>
                                            <div class="csdc-right">
                                                <?php echo esc_html($course['meta']['units'] ?: 'N/A'); ?>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="csdc-left"><i class="icofont-abacus-alt"></i>Quizzes</div>
                                            <div class="csdc-right">
                                                <?php echo esc_html($course['course_quiz'] ?: 'N/A'); ?>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="csdc-left"><i class="icofont-certificate"></i>Certificate</div>
                                            <div class="csdc-right">
                                                <?php echo (!empty($course['meta']['certificate_check'])) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="sidebar-payment">
                                    <div class="sp-title">
                                        <h6>Secure Payment:</h6>
                                    </div>
                                    <div class="sp-thumb">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/pyment/01.jpg' ?>"
                                            alt="CodexCoder">
                                    </div>
                                </div>
                                <div class="csd-button">
                                    <div class="course-enroll"><a class="lab-btn"
                                            href="<?php echo get_site_url(); ?>/cart/?add-to-cart=<?php echo $product_id; ?>">
                                            <span>
                                                Add
                                                To Cart
                                            </span>
                                        </a></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Sidebar end here -->
                </div>
            </div>
        </div>
    </div>
    <!-- Course section end here -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Step 1: Find the <h4> element that contains the text "Sneak Peek"
            const headers = document.querySelectorAll('h4');
            let sneakPeekHeader = null;

            headers.forEach(header => {
                if (header.textContent.includes('Sneak Peek')) {
                    sneakPeekHeader = header;
                }
            });

            // If no "Sneak Peek" header is found, exit
            if (!sneakPeekHeader) return;

            // Step 2: Select all <a> tags with <img> elements after the Sneak Peek header
            let nextElement = sneakPeekHeader.nextElementSibling;

            const imageLinks = [];

            while (nextElement && nextElement.tagName === 'A') {
                const imgTag = nextElement.querySelector('img');
                if (imgTag) {
                    imageLinks.push(nextElement); // Collect the <a> tags with images
                }
                nextElement = nextElement.nextElementSibling;
            }

            // Step 3: Create a wrapper div for the image gallery and move the selected <a> tags inside it
            const galleryWrapper = document.createElement('div');
            galleryWrapper.classList.add('image-gallery');
            imageLinks.forEach(link => galleryWrapper.appendChild(link));

            // Insert the wrapper just after the "Sneak Peek" header
            sneakPeekHeader.after(galleryWrapper);

            // Step 4: Apply flexbox styling to align the images horizontally
            const css = `
    .image-gallery {
        display: flex;
        flex-wrap: nowrap;
        gap: 10px;
    }
    
    .image-gallery a {
        display: inline-block;
    }
    
    .image-gallery img {
        max-width: 150px;
        height: auto;
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .image-gallery img:hover {
        transform: scale(1.1);
    }
    
    /* Popup gallery styles */
    .popup-gallery {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        justify-content: center;
        align-items: center;
    }
    
    .popup-gallery img {
        max-width: 80%;
        max-height: 80%;
    }
    
    #popup-close {
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }
    
    #popup-close:hover {
        color: #ccc;
    }`;

            // Step 5: Add the CSS to the page dynamically
            const styleSheet = document.createElement("style");
            styleSheet.type = "text/css";
            styleSheet.innerText = css;
            document.head.appendChild(styleSheet);

            // Step 6: Create the popup HTML dynamically
            const popupHTML = `
    <div id="popup-gallery" class="popup-gallery">
        <span id="popup-close">&times;</span>
        <img class="popup-content" id="popup-img">
    </div>`;

            document.body.insertAdjacentHTML('beforeend', popupHTML);

            // Step 7: Implement the popup gallery functionality
            const popup = document.getElementById('popup-gallery');
            const popupImg = document.getElementById('popup-img');
            const popupClose = document.getElementById('popup-close');

            imageLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    popupImg.src = this.href; // Show the large image in the popup
                    popup.style.display = 'flex';
                });
            });

            // Close the popup when clicking the close button or outside the image
            popupClose.addEventListener('click', function() {
                popup.style.display = 'none';
            });

            popup.addEventListener('click', function(event) {
                if (event.target === popup) {
                    popup.style.display = 'none';
                }
            });
        });
    </script>

<?php
}

?>

<?php
get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */