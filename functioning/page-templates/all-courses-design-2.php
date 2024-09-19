<!-- breadcrumb start -->
<div class="breadcrumb-area bg-overlay" style="background-image:url('assets/img/bg/3.png')">
    <div class="container">
        <div class="breadcrumb-inner">
            <div class="section-title mb-0 text-center">
                <h2 class="page-title">Courses</h2>
                <ul class="page-list">
                    <li><a href="/">Home</a></li>
                    <li>Courses</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb end -->

<!-- blog area start -->
<div class="blog-area pd-top-120 pd-bottom-120">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <div class="course-showing-part-left">
                <p>
                    <!-- results will be displayed here -->
                </p>
            </div>

            <div class="course-showing-part-right d-flex flex-wrap align-items-center">
                <span>Sort by :</span>
                <div class="select-item">
                    <select id="sort-select">
                        <option value="general">General</option>
                        <option value="latest">Latest</option>
                        <option value="alphabetical">Alphabetical</option>
                    </select>
                    <div class="select-icon">
                        <i class="icofont-rounded-down"></i>
                    </div>
                </div>
            </div>
        </div>
        <div id="loader" style="display: none;">Loading...</div>
        <style>
            #category-parent li a {
                cursor: pointer;
            }
        </style>
        <div class="row">
            <div class="col-lg-8 order-lg-12">
                <div id="show-courses-container" class="row">

                    <!-- Course Will Populated here -->

                </div>
                <nav class="td-page-navigation">
                    <ul id="pagination-container" class="pagination">
                        <!-- <li class="pagination-arrow"><a href="#"><i class="fa fa-angle-double-left"></i></a></li>
                        <li><a href="#">1</a></li>
                        <li><a class="active" href="#">2</a></li>
                        <li><a href="#">...</a></li>
                        <li><a href="#">3</a></li>
                        <li class="pagination-arrow"><a href="#"><i class="fa fa-angle-double-right"></i></a></li> -->
                    </ul>
                </nav>
            </div>
            <div class="col-lg-4 order-lg-1 col-12">
                <div class="td-sidebar mt-5 mt-lg-0">
                    <div class="course-search widget widget_search_course">
                        <forms class="search-form single-input-inner">
                            <input type="text" class="course-search-input" placeholder="Search here">
                            <button class="btn btn-base w-100 mt-3 course-search-button">
                                <i class="fa fa-search"></i> SEARCH
                            </button>
                        </forms>
                        <ul class="course-search-results" style="display:none; position: absolute; background: white; border: 1px solid #ccc; width: 100%; max-height: 200px; overflow-y: auto; z-index: 1000;">
                            <!-- Search results will be displayed here -->
                        </ul>
                    </div>
                    <div class="widget widget_catagory">
                        <h4 class="widget-title">Catagory</h4>
                        <ul id="category-parent" class="catagory-items">
                            <div id="loading-message" style="display: none;">Loading categories...</div>
                        </ul>
                        <style>
                            .widget_catagory ul {
                                margin-left: 0;
                            }

                            li#show-more-button {
                                cursor: pointer;
                                list-style: none;
                                transition: all 0.4s ease;
                                border: 1px solid #E3E3E3;
                                border-radius: 5px;
                                padding: 11px 12px 11px 11px;
                                margin-top: 15px;
                                text-align: center;
                            }
                        </style>
                        <li id="show-more-button" style="display: none;">Show More</li>
                    </div>
                </div>
                <?php
                if (function_exists('register_sidebar')) {
                    register_sidebar(array(
                        'name' => __('Single Course Sidebar', 'sa-e-learning'),
                        'id' => 'single-course-sidebar',
                        'description' => __('A custom sidebar for course single page', 'sa-e-learning'),
                        'before_widget' => '<div class="widget %2$s">',
                        'after_widget' => '</div>',
                        'before_title' => '<h2 class="widgettitle">',
                        'after_title' => '</h2>',
                    ));
                    register_sidebar(array(
                        'name' => __('All Course Sidebar', 'sa-e-learning'),
                        'id' => 'all-course-sidebar',
                        'description' => __('A custom sidebar for all courses', 'sa-e-learning'),
                        'before_widget' => '<div class="widget %2$s">',
                        'after_widget' => '</div>',
                        'before_title' => '<h2 class="widgettitle">',
                        'after_title' => '</h2>',
                    ));
                    register_sidebar(array(
                        'name' => __('Single Blog Sidebar', 'sa-e-learning'),
                        'id' => 'single-blog-sidebar',
                        'description' => __('A custom sidebar for blog single', 'sa-e-learning'),
                        'before_widget' => '<div class="widget %2$s">',
                        'after_widget' => '</div>',
                        'before_title' => '<h2 class="widgettitle">',
                        'after_title' => '</h2>',
                    ));
                    register_sidebar(array(
                        'name' => __('All Blog Sidebar', 'sa-e-learning'),
                        'id' => 'all-blog-sidebar',
                        'description' => __('A custom sidebar for all blogs', 'sa-e-learning'),
                        'before_widget' => '<div class="widget %2$s">',
                        'after_widget' => '</div>',
                        'before_title' => '<h2 class="widgettitle">',
                        'after_title' => '</h2>',
                    ));
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- blog area end -->





<!-- Script Starts Here -->


<script>
    const clientID = '<?php echo get_option('client_id'); ?>';
    const secretKey = '<?php echo get_option('secret_key'); ?>';


    const categorySelect = document.getElementById('category-parent');
    const loader = document.getElementById('loader');
    const apiUrl = 'https://course-dashboard.com/wp-json/custom/v1/posts/';
    const coursesContainer = document.getElementById('show-courses-container');
    const sortSelect = document.getElementById('sort-select');


    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    function fetchCourses(cpage = 1, type = 'general', category = '') {
        const perPage = 8; // Number of courses per page

        coursesContainer.style.display = 'none';

        const newUrl = `${window.location.pathname}?cpage=${cpage}&type=${type}&category=${category}`;
        window.history.pushState({
            path: newUrl
        }, '', newUrl);

        console.log(`${apiUrl}?cpage=${cpage}&per_page=${perPage}&type=${type}&category=${category}`);

        fetch(`${apiUrl}?cpage=${cpage}&per_page=${perPage}&type=${type}&category=${category}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${clientID}:${secretKey}`, // Ensure this is the correct format
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
                console.log(data);

                if (data.courses) {
                    displayCourses(data.courses);
                    loader.style.display = 'none';
                } else {
                    console.error('No courses found in response');
                }

                if (data.total_pages) {
                    setupPagination(data.total_pages, cpage, type, category);
                    loader.style.display = 'none';
                } else {
                    console.error('No pagination data found in response');
                }

                coursesContainer.style.display = 'flex';
            })
            .catch(error => {
                console.error('Error fetching courses:', error);
                alert('There was an issue fetching the courses. Please try again later.');
            });
    }


    function displayCourses(courses) {
        coursesContainer.innerHTML = ''; // Clear container

        courses.forEach(course => {
            const courseElement = document.createElement('div');
            const rating = course.meta.average_rating;
            courseElement.classList.add('col-md-6');


            const thumbnailUrl = course.thumbnail ? course.thumbnail : 'https://dummyimage.com/770x450/ecdcdc/333030.png';
            const ratingCount = course.meta.average_rating ? course.meta.average_rating : 0;


            // Create course HTML structure
            courseElement.innerHTML = `
        <div class="single-course-inner">
                                <div class="thumb">
                                    <img src="${thumbnailUrl}" alt="img">
                                </div>
                                <div class="details">
                                    <div class="details-inner">
                                        <h6><a href="<?php echo home_url('single-course?course_id='); ?>${course.id}">${course.title}</a></h6>
                                    </div>
                                    <div class="emt-course-meta">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="rating">
                                                    <i class="fa fa-star"></i> ${course.meta.average_rating}
                                                    <span>(${ratingCount})</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="price text-right">
                                                    Price: <span id="price-${course.id}">Loading price...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        </div>
        `;

            // Append the course element
            coursesContainer.appendChild(courseElement);

            fetchPrice(course.id);
        });
    }

    function fetchPrice(courseId) {
        const priceContainer = document.getElementById(`price-${courseId}`);

        fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
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
            .then(data => {
                if (data.success) {
                    priceContainer.innerHTML = data.data.price;
                } else {
                    priceContainer.innerHTML = 'N/A';
                }
            })
            .catch(error => {
                console.error('Error fetching price:', error);
                priceContainer.innerHTML = 'Error fetching price';
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
            const pageItem = document.createElement('li'); // Create li element
            const pageLink = document.createElement('a'); // Create a element
            pageLink.textContent = i;
            pageLink.classList.add('pagination-link');
            if (i === currentCpage) {
                pageLink.classList.add('active');
            }
            pageLink.addEventListener('click', () => fetchCourses(i, type, category));

            pageItem.appendChild(pageLink); // Append a to li
            paginationContainer.appendChild(pageItem); // Append li to container
        }

        // Optional: Add "Previous" button with icon
        if (currentCpage > 1) {
            const prevItem = document.createElement('li');
            const prevButton = document.createElement('a');
            prevButton.innerHTML = '<i class="fa fa-angle-double-left"></i>'; // Use icon for Previous
            prevButton.addEventListener('click', () => fetchCourses(currentCpage - 1, type, category));

            prevItem.appendChild(prevButton);
            paginationContainer.insertBefore(prevItem, paginationContainer.firstChild);
        }

        // Optional: Add "Next" button with icon
        if (currentCpage < totalPages) {
            const nextItem = document.createElement('li');
            const nextButton = document.createElement('a');
            nextButton.innerHTML = '<i class="fa fa-angle-double-right"></i>'; // Use icon for Next
            nextButton.addEventListener('click', () => fetchCourses(currentCpage + 1, type, category));

            nextItem.appendChild(nextButton);
            paginationContainer.appendChild(nextItem);
        }
    }

    sortSelect.addEventListener('change', (e) => {
        const currentCpage = parseInt(getUrlParameter('cpage')) || 1;
        const newSortType = e.target.value;
        const selectedCategory = categorySelect.value;
        fetchCourses(currentCpage, newSortType, selectedCategory);
    });

    categorySelect.addEventListener('click', (e) => {
        if (e.target.tagName === 'A') {
            e.preventDefault(); // Prevent the default behavior of the anchor tag

            const selectedCategoryId = e.target.id; // Extract the id of the clicked anchor tag
            const currentCpage = parseInt(getUrlParameter('cpage')) || 1;
            const sortType = sortSelect.value;

            // Use the selectedCategoryId in your fetch
            fetchCourses(currentCpage, sortType, selectedCategoryId);
        }
    });

    const initialCpage = parseInt(getUrlParameter('cpage')) || 1;
    const initialType = getUrlParameter('type') || 'general';
    const initialCategory = getUrlParameter('category') || '';
    sortSelect.value = initialType;
    categorySelect.value = initialCategory;
    fetchCourses(initialCpage, initialType, initialCategory);

    // Category showing
    document.addEventListener('DOMContentLoaded', function() {
        const initialCount = 4; // Number of categories to show initially
        let displayedCount = 0; // Number of currently displayed categories

        function loadCategories(start = 0, count = initialCount) {
            fetch('https://course-dashboard.com/wp-json/custom/v1/course-categories/', {
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
                        const totalCategories = categories.length;

                        // Calculate end index for this load
                        const end = Math.min(start + count, totalCategories);

                        // Append categories to the list
                        let options = '';
                        for (let i = start; i < end; i++) {
                            options += `<li><a id="${categories[i].slug}">${categories[i].name} <i class="fa fa-caret-right"></i></a></li>`;
                        }

                        categorySelect.innerHTML += options;

                        // Update the count of displayed categories
                        displayedCount = end;

                        // Show or hide the "Show More" button
                        const showMoreButton = document.getElementById('show-more-button');
                        if (displayedCount >= totalCategories) {
                            // Hide button if all categories are shown
                            showMoreButton.style.display = 'none';
                        } else {
                            // Show button if more categories are available
                            showMoreButton.style.display = 'block';
                        }
                    } else {
                        console.error('Failed to fetch categories:', data);
                    }
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                });
        }

        // Initial load
        loadCategories(0, initialCount);

        // "Show More" button click event
        document.getElementById('show-more-button').addEventListener('click', function() {
            loadCategories(displayedCount, initialCount);
        });
    });
</script>



<!-- Script Ends Here -->