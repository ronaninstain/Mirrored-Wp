<?php
/*
 * Template Name: All Courses
 */
get_header();

// Initialize variables
$current_currency = '';
$woocommerce_active = false;

// Check if WooCommerce is active
if (class_exists('WooCommerce')) {
    $woocommerce_active = true;
    $current_currency = get_woocommerce_currency_symbol();
}

// Get the current page number, sorting type, and other query parameters
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : 'general';
$per_page = 10;

// Define the source site's API endpoint with pagination and sorting parameters
$api_url = add_query_arg(
    array(
        'type' => $type,
        'page' => $current_page,
        'per_page' => $per_page
    ),
    'https://victoriaeducation.co.uk/wp-json/custom/v1/posts/'
);

// Fetch data from the API
$response = wp_remote_get($api_url);

if (is_wp_error($response)) {
    error_log('API error: ' . $response->get_error_message());
    echo '<p>Unable to retrieve courses at this time. Please try again later.</p>';
    get_footer();
    exit;
}

$body = wp_remote_retrieve_body($response);
$data = json_decode($body, true);

$courses = isset($data['courses']) ? $data['courses'] : array();
$total_pages = isset($data['total_pages']) ? intval($data['total_pages']) : 1;
$total_posts = isset($data['total_posts']) ? intval($data['total_posts']) : 0;
?>

<!-- Page Header section start here -->
<div class="pageheader-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="pageheader-content text-center">
                    <h2>Archives: Courses</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Course Page</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page Header section ending here -->

<!-- group select section start here -->
<div class="group-select-section">
    <div class="container">
        <div class="section-wrapper">
            <div class="row align-items-center g-4">
                <div class="col-md-1">
                    <div class="group-select-left">
                        <i class="icofont-abacus-alt"></i>
                        <span>Filters</span>
                    </div>
                </div>
                <div class="col-md-11">
                    <div class="group-select-right">
                        <div class="row g-2 row-cols-lg-4 row-cols-sm-2 row-cols-1">
                            <!-- Categories, Languages, Prices, Skills -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- group select section ending here -->

<!-- course section start here -->
<div class="course-section padding-tb section-bg">
    <div class="container">
        <div class="section-wrapper">
            <div class="course-showing-part">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="course-showing-part-left">
                        <p>Showing
                            <?php echo (($current_page - 1) * $per_page + 1) . '-' . min($current_page * $per_page, $total_posts) . ' of ' . $total_posts; ?>
                            results
                        </p>
                    </div>
                    <div class="course-showing-part-right d-flex flex-wrap align-items-center">
                        <span>Sort by :</span>
                        <div class="select-item">
                            <select id="sort_type">
                                <option value="general" <?php selected($type, 'general'); ?>>General</option>
                                <option value="latest" <?php selected($type, 'latest'); ?>>Latest</option>
                                <option value="alphabetical" <?php selected($type, 'alphabetical'); ?>>Alphabetical
                                </option>
                            </select>
                            <div class="select-icon">
                                <i class="icofont-rounded-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 justify-content-center row-cols-xl-3 row-cols-md-2 row-cols-1">
                <?php
                if (empty($courses)) {
                    echo '<p>No courses found.</p>';
                } else {
                    foreach ($courses as $course) {
                        $courseID = $course['id'];
                        $average_rating = $course['meta']['average_rating'];
                        $countRating = $course['meta']['rating_count'];
                        $courseStds = $course['meta']['vibe_students'];
                        $product_id = $course['meta']['vibe_product'];
                        $regular_price = $course['meta']['regular_price'];
                        $sale_price = $course['meta']['sale_price'];
                        $units = $course['meta']['units'];
                        $courseImg = $course['thumbnail'];

                        $categories = $course['categories'];
                        ?>
                        <div class="col">
                            <div class="course-item">
                                <div class="course-inner">
                                    <div class="course-thumb">
                                        <img src="<?php echo $courseImg; ?>" alt="course">
                                    </div>
                                    <div class="course-content">
                                        <?php if ($sale_price): ?>
                                            <div class="course-price">
                                                <?php echo esc_html($current_currency) . esc_html($sale_price); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($regular_price && $regular_price != $sale_price): ?>
                                            <div class="course-price">
                                                <?php echo esc_html($current_currency) . esc_html($regular_price); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="course-category">
                                            <?php if (!empty($categories)): ?>
                                                <?php foreach ($categories as $category): ?>
                                                    <div class="course-cate">
                                                        <?php $category_link = home_url('/display-courses-by-category?category=' . urlencode($category));
                                                        echo '<a href="' . esc_url($category_link) . '">' . esc_html($category) . '</a>'; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <div class="course-review">
                                                <?php if ($average_rating): ?>
                                                    <span class="rating">
                                                        <?php echo esc_html($average_rating); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($countRating): ?>
                                                    <span class="rating-count"><?php echo esc_html($countRating); ?> reviews</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if (!empty($course['excerpt'])): ?>
                                            <a href="course-single.html">
                                                <h5><?php echo esc_html($course['title']); ?></h5>
                                            </a>
                                        <?php endif; ?>
                                        <div class="course-details">
                                            <?php if ($units): ?>
                                                <div class="course-count"><i class="icofont-video-alt"></i>
                                                    <?php echo $units; ?> Lessons</div>
                                            <?php endif; ?>
                                            <div class="course-topic"><i class="icofont-signal"></i> Online Class</div>
                                        </div>
                                        <div class="course-footer">
                                            <div class="course-btn">
                                                <a href="course-single.html" class="lab-btn-text">Read More <i
                                                        class="icofont-external-link"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

            <?php
            // Pagination links
            if ($total_pages > 1) {
                echo '<ul class="default-pagination lab-ul">';
                for ($i = 1; $i <= $total_pages; $i++) {
                    $class = ($i == $current_page) ? 'class="active"' : '';
                    echo '<li ' . $class . '><a href="#" data-page="' . $i . '">' . $i . '</a></li>';
                }
                echo '</ul>';
            }
            ?>
        </div>
    </div>
</div>
<!-- course section ending here -->

<script>
   document.addEventListener('DOMContentLoaded', function () {
    const paginationLinks = document.querySelectorAll('.default-pagination a');
    const courseSection = document.querySelector('.course-section');
    const sortType = document.getElementById('sort_type');

    // Handle pagination clicks
    paginationLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const pageNumber = this.dataset.page;
            console.log(`Fetching page ${pageNumber}`);

            fetch(`https://victoriaeducation.co.uk/wp-json/custom/v1/posts/?page=${pageNumber}`)
                .then(response => response.json())
                .then(data => {
                    // Update the page content
                    const courses = data.courses;
                    const totalPosts = data.total_posts;
                    const totalPages = data.total_pages;

                    // Generate the new HTML for courses
                    let courseHtml = '';
                    courses.forEach(course => {
                        courseHtml += `
                        <div class="col">
                            <div class="course-item">
                                <div class="course-inner">
                                    <div class="course-thumb">
                                        <img src="${course.thumbnail}" alt="course">
                                    </div>
                                    <div class="course-content">
                                        ${course.sale_price ? `<div class="course-price">${current_currency}${course.sale_price}</div>` : ''}
                                        ${course.regular_price && course.regular_price != course.sale_price ? `<div class="course-price">${current_currency}${course.regular_price}</div>` : ''}
                                        <div class="course-category">
                                            ${course.categories.map(category => `<div class="course-cate"><a href="/display-courses-by-category?category=${encodeURIComponent(category)}">${category}</a></div>`).join('')}
                                            <div class="course-review">
                                                ${course.meta.average_rating ? `<span class="rating">${course.meta.average_rating}</span>` : ''}
                                                ${course.meta.rating_count ? `<span class="rating-count">${course.meta.rating_count} reviews</span>` : ''}
                                            </div>
                                        </div>
                                        ${course.excerpt ? `<a href="course-single.html"><h5>${course.title}</h5></a>` : ''}
                                        <div class="course-details">
                                            ${course.meta.units ? `<div class="course-count"><i class="icofont-video-alt"></i>${course.meta.units} Lessons</div>` : ''}
                                            <div class="course-topic"><i class="icofont-signal"></i> Online Class</div>
                                        </div>
                                        <div class="course-footer">
                                            <div class="course-btn">
                                                <a href="course-single.html" class="lab-btn-text">Read More <i class="icofont-external-link"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    });

                    // Update the course section
                    courseSection.querySelector('.row.g-4').innerHTML = courseHtml;

                    // Update the pagination
                    let paginationHtml = '';
                    for (let i = 1; i <= totalPages; i++) {
                        const classActive = i === parseInt(pageNumber) ? 'class="active"' : '';
                        paginationHtml += `<li ${classActive}><a href="#" data-page="${i}">${i}</a></li>`;
                    }
                    courseSection.querySelector('.default-pagination').innerHTML = paginationHtml;

                    // Update URL without reloading the page
                    history.pushState(null, '', `?page=${pageNumber}`);
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Handle sorting change
    sortType.addEventListener('change', function () {
        const selectedSort = this.value;
        console.log(`Sorting by ${selectedSort}`);
        const url = new URL(window.location.href);
        url.searchParams.set('type', selectedSort);
        url.searchParams.set('page', 1); // Reset to page 1 when sorting changes
        history.pushState(null, '', url.toString()); // Update URL without reloading
        loadCourses(1); // Load courses for the first page with new sorting
    });

    // Function to load courses based on the current URL
    function loadCourses(pageNumber) {
        fetch(`https://victoriaeducation.co.uk/wp-json/custom/v1/posts/?page=${pageNumber}`)
            .then(response => response.json())
            .then(data => {
                const courses = data.courses;
                const totalPosts = data.total_posts;
                const totalPages = data.total_pages;

                let courseHtml = '';
                courses.forEach(course => {
                    courseHtml += `
                    <div class="col">
                        <div class="course-item">
                            <div class="course-inner">
                                <div class="course-thumb">
                                    <img src="${course.thumbnail}" alt="course">
                                </div>
                                <div class="course-content">
                                    ${course.sale_price ? `<div class="course-price">${current_currency}${course.sale_price}</div>` : ''}
                                    ${course.regular_price && course.regular_price != course.sale_price ? `<div class="course-price">${current_currency}${course.regular_price}</div>` : ''}
                                    <div class="course-category">
                                        ${course.categories.map(category => `<div class="course-cate"><a href="/display-courses-by-category?category=${encodeURIComponent(category)}">${category}</a></div>`).join('')}
                                        <div class="course-review">
                                            ${course.meta.average_rating ? `<span class="rating">${course.meta.average_rating}</span>` : ''}
                                            ${course.meta.rating_count ? `<span class="rating-count">${course.meta.rating_count} reviews</span>` : ''}
                                        </div>
                                    </div>
                                    ${course.excerpt ? `<a href="course-single.html"><h5>${course.title}</h5></a>` : ''}
                                    <div class="course-details">
                                        ${course.meta.units ? `<div class="course-count"><i class="icofont-video-alt"></i>${course.meta.units} Lessons</div>` : ''}
                                        <div class="course-topic"><i class="icofont-signal"></i> Online Class</div>
                                    </div>
                                    <div class="course-footer">
                                        <div class="course-btn">
                                            <a href="course-single.html" class="lab-btn-text">Read More <i class="icofont-external-link"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                });

                courseSection.querySelector('.row.g-4').innerHTML = courseHtml;

                let paginationHtml = '';
                for (let i = 1; i <= totalPages; i++) {
                    const classActive = i === parseInt(pageNumber) ? 'class="active"' : '';
                    paginationHtml += `<li ${classActive}><a href="#" data-page="${i}">${i}</a></li>`;
                }
                courseSection.querySelector('.default-pagination').innerHTML = paginationHtml;
            })
            .catch(error => console.error('Error:', error));
    }
});

</script>

<?php
get_footer();
