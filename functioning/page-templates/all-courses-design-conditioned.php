<?php
if (function_exists('acf_add_options_page')) {
    $page_header_section_background_image = get_field('page_header_section_background_image', 'option');
}
?>
<!-- Breadcrumb start -->
<?php
$background_image = $page_header_section_background_image
    ? esc_attr($page_header_section_background_image)
    : get_template_directory_uri() . '/assets/design_4/images/breadcrumb-bg.jpg';
?>
<div class="breadcrumb-area bg-img bg-cover" data-bg-image="<?php echo $background_image; ?>">
    <div class="container">
        <div class="content text-center">
            <h2><?php echo the_title(); ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo the_title(); ?></li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- Breadcrumb end -->

<!-- Course-area start -->
<div class="course-area pt-60 pb-75">
    <div class="container">
        <div class="row gx-xl-5">
            <div class="col-lg-4 col-xl-3">
                <!-- Spacer -->
                <div class="pb-40 d-none d-lg-block"></div>
                <div class="widget-offcanvas offcanvas-lg offcanvas-start" tabindex="-1" id="widgetOffcanvas"
                    aria-labelledby="widgetOffcanvas">
                    <div class="offcanvas-header px-20">
                        <h4 class="offcanvas-title">Filter</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            data-bs-target="#widgetOffcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body p-0">
                        <aside class="widget-area px-20" data-aos="fade-up">
                            <div class="widget widget-categories py-20">
                                <h5 class="title">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#categories">
                                        Categories
                                    </button>
                                </h5>
                                <div id="categories" class="collapse show">
                                    <div class="accordion-body mt-20 scroll-y">
                                        <ul class="list-group" id="category-select">
                                            <!-- Categories will populate here -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
                <div class="widget widget-type py-20">
                    <?php
                    if (is_active_sidebar("all-course-sidebar")) {
                        dynamic_sidebar("all-course-sidebar");
                    }
                    ?>
                </div>
            </div>
            <div class="col-lg-8 col-xl-9">
                <!-- Spacer -->
                <div class="pb-40"></div>
                <div class="sort-area" data-aos="fade-up">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h5 class="mb-20 course-showing-count-part">Counting <span class="color-primary">
                                    Courses...</span></h5>
                        </div>
                        <div class="col-6 d-lg-none">
                            <button class="btn btn-sm btn-outline icon-end radius-sm mb-20" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#widgetOffcanvas"
                                aria-controls="widgetOffcanvas">
                                Filter <i class="fal fa-filter"></i>
                            </button>
                        </div>
                        <div class="col-6">
                            <ul class="sort-list list-unstyled mb-20 text-end">
                                <li class="item">
                                    <div class="sort-item d-flex align-items-center ">
                                        <label class="me-2 font-sm">Sort By:</label>
                                        <div class="sort-select-pr">
                                            <select id="sort-select">
                                                <option value="general">General</option>
                                                <option value="latest">Latest</option>
                                                <option value="alphabetical">Alphabetical</option>
                                            </select>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="loader">
                    <div class="loading">
                        <svg class="pl" width="240" height="240" viewBox="0 0 240 240">
                            <circle class="pl__ring pl__ring--a" cx="120" cy="120" r="105" fill="none" stroke="#000"
                                stroke-width="20" stroke-dasharray="0 660" stroke-dashoffset="-330"
                                stroke-linecap="round"></circle>
                            <circle class="pl__ring pl__ring--b" cx="120" cy="120" r="35" fill="none" stroke="#000"
                                stroke-width="20" stroke-dasharray="0 220" stroke-dashoffset="-110"
                                stroke-linecap="round"></circle>
                            <circle class="pl__ring pl__ring--c" cx="85" cy="120" r="70" fill="none" stroke="#000"
                                stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"></circle>
                            <circle class="pl__ring pl__ring--d" cx="155" cy="120" r="70" fill="none" stroke="#000"
                                stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"></circle>
                        </svg>
                    </div>
                </div>
                <div id="show-courses-container" class="row" data-aos="fade-up">
                    <!-- Courses will populate here -->
                </div>
                <nav class="pagination-nav mt-15 mb-25" data-aos="fade-up">
                    <ul id="pagination-container" class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="courses.html" aria-label="Previous">
                                <i class="far fa-angle-left"></i>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="courses.html">1</a></li>
                        <li class="page-item"><a class="page-link" href="courses.html">2</a></li>
                        <li class="page-item"><a class="page-link" href="courses.html">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="courses.html" aria-label="Next">
                                <i class="far fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Course-area end -->
<?php
global $wpdb;
$table_name = $wpdb->prefix . 'ptc_items';

// Retrieve all rows from the table.
$results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

$course_ids = [];

foreach ($results as $result) {
    $course_ids[] = $result["course_id"];
}
?>
<!-- Script For All courses starts -->
<script>
    const clientID = '<?php echo get_option('client_id'); ?>';
    const secretKey = '<?php echo get_option('secret_key'); ?>';
    // Updated endpoint for connected courses
    const apiUrl = 'https://somesites.com/wp-json/custom/v1/connected-courses/';
    const coursesContainer = document.getElementById('show-courses-container');
    const sortSelect = document.getElementById('sort-select');
    const categorySelect = document.getElementById('category-select');
    const loader = document.getElementById('loader');
    const clientCourseIds = <?php echo json_encode($course_ids); ?>;

    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Fetch connected courses from the API
    function fetchCourses(cpage = 1, type = 'general', category = '') {
        const perPage = 9; // Number of courses per page
        const resultsCountElement = document.querySelector('.course-showing-count-part');
        coursesContainer.style.display = 'none';

        // Use "general" for API if alphabetical sort is selected,
        // but keep the query parameter as "alphabetical" in the URL
        const effectiveType = (type === 'alphabetical') ? 'general' : type;
        const newUrl = `${window.location.pathname}?cpage=${cpage}&type=${type}&category=${category}`;
        window.history.pushState({ path: newUrl }, '', newUrl);

        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${clientID}:${secretKey}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                cpage: cpage,
                per_page: perPage,
                type: effectiveType,
                category: category,
                course_ids: clientCourseIds
            })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Invalid API key or other error');
                }
                return response.json();
            })
            .then(async data => {
                if (data.courses) {
                    loader.style.display = 'block';
                    resultsCountElement.innerHTML = `Counting <span class="color-primary"> Courses...</span>`;

                    // Fetch price data for each course concurrently
                    const coursesWithPrice = await Promise.all(data.courses.map(async course => {
                        const priceData = await fetchPriceAndUrl(course.id);
                        course.priceData = priceData;
                        return course;
                    }));

                    // Filter courses based on valid price
                    let validCourses = coursesWithPrice.filter(course =>
                        course.priceData.success &&
                        course.priceData.data.price !== 'N/A' &&
                        course.priceData.data.price !== 'Error fetching price'
                    );

                    // If alphabetical sort is requested, sort the courses by title
                    if (type === 'alphabetical') {
                        validCourses.sort((a, b) => a.title.localeCompare(b.title));
                    }

                    displayCourses(validCourses);

                    // Use total_pages from API response instead of calculating based on returned courses
                    const totalPages = data.total_pages;
                    if (totalPages >= 1) {
                        setupPagination(totalPages, cpage, type, category);
                    } else {
                        console.error('No pagination data found in response');
                    }

                    const totalCourses = data.total_posts;
                    resultsCountElement.innerHTML = `Found <span class="color-primary">${totalCourses}</span> Courses`;
                    coursesContainer.style.display = 'flex';
                } else {
                    console.error('No courses found in response');
                }
            })
            .catch(error => {
                console.error('Error fetching courses:', error);
                alert('There was an issue fetching the courses. Please try again later.');
            });
    }

    function displayCourses(courses) {
        coursesContainer.innerHTML = ''; // Clear existing courses
        courses.forEach(course => {
            const courseElement = document.createElement('div');
            courseElement.classList.add('col-xl-4', 'col-sm-6');

            const thumbnailUrl = course.thumbnail ? course.thumbnail : '<?php echo get_template_directory_uri(); ?>/assets/design_4/images/course/pro-6.jpg';
            const ratingCount = course.meta.average_rating ? course.meta.average_rating : 0;
            const courseTitle = course.title ? course.title : 'No title available';
            const lessons = course.meta.units ? course.meta.units : 'N/A';
            const price = course.priceData && course.priceData.success ? course.priceData.data.price : 'N/A';
            const productUrl = course.priceData && course.priceData.success ? course.priceData.data.product_url : `/single-course?course_id=${course.id}`;

            courseElement.innerHTML = `
                <div class="course-default border radius-md mb-25">
                    <figure class="course-img">
                        <a href="${productUrl}" title="Image" target="_self" class="lazy-container ratio ratio-2-3">
                            <img class="lazyload" src="${thumbnailUrl}" data-src="${thumbnailUrl}" alt="course">
                        </a>
                        <div class="hover-show">
                            <a href="${productUrl}" class="btn btn-md btn-primary rounded-pill" title="Enroll Now" target="_self">Enroll Now</a>
                        </div>
                    </figure>
                    <div class="course-details">
                        <div class="p-3">
                            <a href="${productUrl}" target="_self" title="Category" class="tag font-sm color-primary mb-1">${course.categories[0]}</a>
                            <h6 class="course-title lc-2 mb-0">
                                <a href="${productUrl}" target="_self" title="${courseTitle}">
                                    ${courseTitle}
                                </a>
                            </h6>
                            <div class="authors mt-15">
                                <div class="author"></div>
                                <span class="font-sm icon-start"><i class="fas fa-star"></i>${ratingCount}</span>
                            </div>
                        </div>
                        <div class="course-bottom-info px-3 py-2">
                            <span class="font-sm"><i class="fas fa-usd-circle"></i>${price}</span>
                            <span class="font-sm"><i class="fas fa-book-alt"></i>${lessons} Lessons</span>
                        </div>
                    </div>
                </div>
            `;
            coursesContainer.appendChild(courseElement);
        });
        loader.style.display = 'none';
    }

    function fetchPriceAndUrl(courseId) {
        return fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'get_course_price',
                course_id: courseId,
            }),
        })
            .then(response => response.json())
            .then(data => data)
            .catch(error => {
                console.error('Error fetching price:', error);
                return { success: false };
            });
    }

    function setupPagination(totalPages, currentCpage, type, category) {
        const paginationContainer = document.getElementById('pagination-container');
        paginationContainer.innerHTML = '';
        const maxPagesToShow = 3;
        let startPage = Math.max(1, currentCpage - Math.floor(maxPagesToShow / 2));
        let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);
        if (endPage - startPage + 1 < maxPagesToShow) {
            startPage = Math.max(1, endPage - maxPagesToShow + 1);
        }
        for (let i = startPage; i <= endPage; i++) {
            const pageItem = document.createElement('li');
            const pageLink = document.createElement('a');
            pageLink.setAttribute('href', '#');
            pageItem.classList.add('page-item');
            pageLink.textContent = i;
            pageLink.classList.add('page-link');
            if (i === currentCpage) {
                pageItem.classList.add('active');
            }
            pageLink.addEventListener('click', () => {
                loader.style.display = 'block';
                fetchCourses(i, type, category);
            });
            pageItem.appendChild(pageLink);
            paginationContainer.appendChild(pageItem);
        }
        if (currentCpage > 1) {
            const prevItem = document.createElement('li');
            const prevButton = document.createElement('a');
            prevButton.setAttribute('href', '#');
            prevItem.classList.add('page-item');
            prevButton.classList.add('page-link');
            prevButton.innerHTML = '<i class="far fa-angle-left"></i>';
            prevButton.addEventListener('click', () => {
                loader.style.display = 'block';
                fetchCourses(currentCpage - 1, type, category);
            });
            prevItem.appendChild(prevButton);
            paginationContainer.insertBefore(prevItem, paginationContainer.firstChild);
        }
        if (currentCpage < totalPages) {
            const nextItem = document.createElement('li');
            const nextButton = document.createElement('a');
            nextButton.setAttribute('href', '#');
            nextItem.classList.add('page-item');
            nextButton.classList.add('page-link');
            nextButton.innerHTML = '<i class="far fa-angle-right"></i>';
            nextButton.addEventListener('click', () => {
                loader.style.display = 'block';
                fetchCourses(currentCpage + 1, type, category);
            });
            nextItem.appendChild(nextButton);
            paginationContainer.appendChild(nextItem);
        }
    }

    let selectedCategory = getUrlParameter('category') || '';
    sortSelect.addEventListener('change', (e) => {
        const currentCpage = 1;
        const newSortType = e.target.value;
        loader.style.display = 'block';
        fetchCourses(currentCpage, newSortType, selectedCategory);
    });

    categorySelect.addEventListener('click', (e) => {
        if (e.target.tagName === 'A') {
            e.preventDefault();
            loader.style.display = 'block';
            selectedCategory = e.target.id;
            const currentCpage = 1;
            const sortType = sortSelect.value;
            fetchCourses(currentCpage, sortType, selectedCategory);
        }
    });

    const initialCpage = parseInt(getUrlParameter('cpage')) || 1;
    const initialType = getUrlParameter('type') || 'general';
    fetchCourses(initialCpage, initialType, selectedCategory);

    // Load course categories
    document.addEventListener('DOMContentLoaded', function () {
        function loadCategories() {
            fetch('https://somesites.com/wp-json/custom/v1/course-categories/', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${clientID}:${secretKey}`,
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Invalid API key or other error');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const categories = data.data;
                        let options = '';
                        categories.forEach(category => {
                            options += `<li class="list-item">
                                        <a class="category-toggle" id="${category.slug}" href="courses.html" title="link" target="_self">${category.name}</a>
                                    </li>`;
                        });
                        categorySelect.innerHTML += options;
                        const showMoreButton = document.getElementById('show-more-button');
                        if (showMoreButton) {
                            showMoreButton.style.display = 'none';
                        }
                    } else {
                        console.error('Failed to fetch categories:', data);
                    }
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                });
        }
        loadCategories();
    });
</script>