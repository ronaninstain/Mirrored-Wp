<?php
/*
 * Template Name: All Courses
 */
get_header();

// Initialize variables
$woocommerce_active = class_exists('WooCommerce');
$current_currency = $woocommerce_active ? get_woocommerce_currency_symbol() : '';

// Get the current page number, sorting type, and other query parameters
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : 'general';
$per_page = 10;

// Define the API endpoint with pagination and sorting parameters
$api_url = add_query_arg(
    array(
        'type' => $type,
        'page' => $current_page,
        'per_page' => $per_page
    ),
    'https://www.libm.co.uk/wp-json/custom/v1/posts/'
);

// Fetch data from the API
$response = wp_remote_get($api_url);

if (is_wp_error($response)) {
    error_log('API error: ' . $response->get_error_message());
    echo '<p>Unable to retrieve courses at this time. Please try again later.</p>';
    get_footer();
    exit;
}

$data = json_decode(wp_remote_retrieve_body($response), true);

$courses = $data['courses'] ?? [];
$total_pages = intval($data['total_pages'] ?? 1);
$total_posts = intval($data['total_posts'] ?? 0);
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
            <div class="row g-4 justify-content-center row-cols-xl-3 row-cols-md-2 row-cols-1" id="course-list">
                <?php if (empty($courses)): ?>
                    <p>No courses found.</p>
                <?php else: ?>
                    <?php foreach ($courses as $course): ?>
                        <div class="col">
                            <div class="course-item">
                                <div class="course-inner">
                                    <div class="course-thumb">
                                        <img src="<?php echo esc_url($course['thumbnail']); ?>" alt="course">
                                    </div>
                                    <div class="course-content">
                                        <?php if (!empty($course['meta']['sale_price'])): ?>
                                            <div class="course-price">
                                                <?php echo esc_html($current_currency) . esc_html($course['meta']['sale_price']); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($course['meta']['regular_price']) && $course['meta']['regular_price'] !== $course['meta']['sale_price']): ?>
                                            <div class="course-price">
                                                <?php echo esc_html($current_currency) . esc_html($course['meta']['regular_price']); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="course-category">
                                            <?php foreach ($course['categories'] as $category): ?>
                                                <div class="course-cate">
                                                    <?php $category_link = home_url('/display-courses-by-category?category=' . urlencode($category)); ?>
                                                    <a
                                                        href="<?php echo esc_url($category_link); ?>"><?php echo esc_html($category); ?></a>
                                                </div>
                                            <?php endforeach; ?>
                                            <div class="course-review">
                                                <?php if (!empty($course['meta']['average_rating'])): ?>
                                                    <span class="rating">
                                                        <?php echo esc_html($course['meta']['average_rating']); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($course['meta']['rating_count'])): ?>
                                                    <span
                                                        class="rating-count"><?php echo esc_html($course['meta']['rating_count']); ?>
                                                        reviews</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if (!empty($course['excerpt'])): ?>
                                            <a href="<?php echo esc_url(home_url('single-course?course_id=' . $course['id'])); ?>">
                                                <h5><?php echo esc_html($course['title']); ?></h5>
                                            </a>
                                        <?php endif; ?>
                                        <div class="course-details">
                                            <?php if (!empty($course['meta']['units'])): ?>
                                                <div class="course-count">
                                                    <i class="icofont-video-alt"></i>
                                                    <?php echo esc_html($course['meta']['units']); ?> Lessons
                                                </div>
                                            <?php endif; ?>
                                            <div class="course-topic">
                                                <i class="icofont-signal"></i> Online Class
                                            </div>
                                        </div>
                                        <div class="course-footer">
                                            <div class="course-btn">
                                                <a href="<?php echo esc_url(home_url('single-course?course_id=' . $course['id'])); ?>"
                                                    class="lab-btn-text">Read More <i class="icofont-external-link"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if ($total_pages > 1): ?>
                <ul class="default-pagination lab-ul">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li <?php echo ($i == $current_page) ? 'class="active"' : ''; ?>>
                            <a href="#" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- course section ending here -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get references to the sort select and pagination links
        const sortSelect = document.getElementById('sort_type');
        const paginationLinks = document.querySelectorAll('.default-pagination a');

        // Function to handle data fetching and updating the page
        function fetchData(pageNumber, sortType) {
            fetch(`https://www.libm.co.uk/wp-json/custom/v1/posts/?type=${sortType}&page=${pageNumber}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    updatePageContent(data);
                    history.pushState(null, '', `?type=${sortType}&page=${pageNumber}`);
                })
                .catch(error => console.error('Error:', error));
        }

        // Function to update the page content
        function updatePageContent(data) {
            const courseList = document.getElementById('course-list');
            courseList.innerHTML = '';

            if (data.courses.length === 0) {
                courseList.innerHTML = '<p>No courses found.</p>';
            } else {
                data.courses.forEach(course => {
                    const courseItem = document.createElement('div');
                    courseItem.classList.add('col');

                    courseItem.innerHTML = `
                    <div class="course-item">
                        <div class="course-inner">
                            <div class="course-thumb">
                                <img src="${course.thumbnail}" alt="course">
                            </div>
                            <div class="course-content">
                                ${course.meta.sale_price ? `<div class="course-price">${course.meta.sale_price}</div>` : ''}
                                ${course.meta.regular_price && course.meta.regular_price !== course.meta.sale_price ? `<div class="course-price">${course.meta.regular_price}</div>` : ''}
                                <div class="course-category">
                                    ${course.categories.map(category => `<div class="course-cate"><a href="${('https://b2btestsa.wpenginepowered.com/display-courses-by-category?category=' + encodeURIComponent(category))}">${category}</a></div>`).join('')}
                                    <div class="course-review">
                                        ${course.meta.average_rating ? `<span class="rating">${course.meta.average_rating}</span>` : ''}
                                        ${course.meta.rating_count ? `<span class="rating-count">${course.meta.rating_count} reviews</span>` : ''}
                                    </div>
                                </div>
                                ${course.excerpt ? `<a href="<?php echo home_url('single-course?course_id='); ?>${course.id}"><h5>${course.title}</h5></a>` : ''}
                                <div class="course-details">
                                    ${course.meta.units ? `<div class="course-count"><i class="icofont-video-alt"></i> ${course.meta.units} Lessons</div>` : ''}
                                    <div class="course-topic"><i class="icofont-signal"></i> Online Class</div>
                                </div>
                                <div class="course-footer">
                                    <div class="course-btn">
                                        <a href="<?php echo home_url('single-course?course_id='); ?>${course.id}" class="lab-btn-text">Read More <i class="icofont-external-link"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                    courseList.appendChild(courseItem);
                });
            }
        }

        // Event handler for both sorting and pagination
        function handleEvent(e) {
            const target = e.target;

            if (target.matches('.default-pagination a')) {
                e.preventDefault();
                const pageNumber = target.getAttribute('data-page');
                const sortType = document.getElementById('sort_type').value;
                fetchData(pageNumber, sortType);
            } else if (target.matches('#sort_type')) {
                e.preventDefault();
                const currentUrl = new URL(window.location.href);
                const selectedSort = target.value;
                const pageNumber = currentUrl.searchParams.get('page') || 1; // Default to page 1 if not present
                fetchData(pageNumber, selectedSort);
            }
            // Allow default behavior for other links, such as the "Read More" links
        }

        // Initialize the current state based on URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = urlParams.get('page') || 1;  // Default to page 1 if not present
        const currentSort = urlParams.get('type') || 'general';

        // Set the sort select box to the current sort type
        sortSelect.value = currentSort;

        // Set active class for the current page
        paginationLinks.forEach(link => {
            if (link.getAttribute('data-page') === currentPage) {
                link.classList.add('active');
            }
        });

        // Attach the single event listener
        document.addEventListener('click', handleEvent);

        // Fetch initial data using the currentPage dynamically retrieved from the URL
        fetchData(currentPage, currentSort);
    });

</script>

<?php
get_footer();
?>