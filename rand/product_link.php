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
                        <div class="row g-2 row-cols-lg-4 row-cols-sm-2 row-cols-1 justify-content-between">
                            <div class="col">
                                <div class="select-item-cat select-item">
                                    <select id="category-select">
                                        <option value="">All Categories</option>
                                        <!-- Options will be populated dynamically -->
                                    </select>
                                    <div class="select-icon">
                                        <i class="icofont-rounded-down"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="course-search widget widget_search_course">
                                    <forms class="search-form single-input-inner">
                                        <input type="text" class="course-search-input" placeholder="Search here">
                                        <button class="btn btn-base w-100 mt-3 course-search-button">
                                            <i class="fa fa-search"></i> SEARCH
                                        </button>
                                    </forms>
                                    <ul class="course-search-results"
                                        style="display:none; position: absolute; background: white; border: 1px solid #ccc; width: 100%; max-height: 200px; overflow-y: auto; z-index: 1000;">
                                        <!-- Search results will be displayed here -->
                                    </ul>
                                </div>

                            </div>
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
            </div>
            <div id="loader" style="display: none;">Loading...</div>
            <style>
                div#show-courses-container {
                    display: flex !important;
                    align-content: space-between;
                    flex-direction: row;
                    justify-content: center;
                    align-items: center;
                }
            </style>
            <div id="show-courses-container"
                class="row g-4 justify-content-center row-cols-xl-3 row-cols-md-2 row-cols-1">


            </div>

            <div id="pagination-container" class="default-pagination lab-ul">
                <!-- results will be populated here -->
            </div>
        </div>
    </div>
</div>
<!-- course section ending here -->

<script>
    const clientID = '<?php echo get_option('client_id'); ?>';
    const secretKey = '<?php echo get_option('secret_key'); ?>';

    const apiUrl = 'https://course-dashboard.com/wp-json/custom/v1/posts/';
    const coursesContainer = document.getElementById('show-courses-container');
    const loader = document.getElementById('loader');
    const categorySelect = document.getElementById('category-select');
    const sortSelect = document.getElementById('sort-select');

    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    function fetchCourses(cpage = 1, type = 'general', category = '') {
        const perPage = 9; // Number of courses per page

        loader.style.display = 'block';
        coursesContainer.style.display = 'none';

        const newUrl = `${window.location.pathname}?cpage=${cpage}&type=${type}&category=${category}`;
        window.history.pushState({
            path: newUrl
        }, '', newUrl);

        fetch(`${apiUrl}?cpage=${cpage}&per_page=${perPage}&type=${type}&category=${category}`, {
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
                displayCourses(data.courses);
                setupPagination(data.total_pages, cpage, type, category);
                loader.style.display = 'none';
                coursesContainer.style.display = 'block';
            })
            .catch(error => {
                console.error('Error fetching courses:', error);
                loader.style.display = 'none';
            });
    }

    function displayCourses(courses) {
        coursesContainer.innerHTML = '';

        courses.forEach(course => {
            const courseElement = document.createElement('div');
            const rating = course.meta.average_rating;
            courseElement.classList.add('col');
            courseElement.id = `course-${course.id}`; // Add a unique ID for each course

            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                if (rating >= i) {
                    starsHtml += '<i class="icofont-ui-rating"></i>';
                } else if (rating > i - 1 && rating < i) {
                    starsHtml += `
                    <i class="icofont-ui-rate-blank" style="position: relative;">
                        <i class="icofont-ui-rating" style="position: absolute; top: 0; left: 0; width: 50%; overflow: hidden;"></i>
                    </i>
                `;
                } else {
                    starsHtml += '<i class="icofont-ui-rate-blank"></i>';
                }
            }

            courseElement.innerHTML = `
            <div class="course-item">
                <div class="course-inner">
                    <div class="course-thumb">
                        <img src="${course.thumbnail}" alt="course">
                    </div>
                    <div class="course-content">
                        <div class="course-price" id="price-${course.id}">Loading price...</div> <!-- Placeholder for price -->
                        <div class="course-category">
                            ${course.categories.map(category => `<div class="course-cate"><a>${category}</a></div>`).join('')}
                            <div class="course-reiew">
                                ${course.meta.average_rating ? `<span class="ratting">${starsHtml}</span>` : ''}
                                ${course.meta.rating_count ? `<span class="rating-count">${course.meta.rating_count} reviews</span>` : ''}
                            </div>
                        </div>
                        
                        ${course.excerpt ? `<a href="#" class="course-title-link"><h5>${course.title}</h5></a>` : ''}  <!-- Placeholder for course title -->
                        <div class="course-details">
                            ${course.meta.units ? `<div class="course-count"><i class="icofont-video-alt"></i> ${course.meta.units} Lessons</div>` : ''}
                            <div class="course-topic"><i class="icofont-signal"></i> Online Class</div>
                        </div>
                        <div class="course-footer">
                            <div class="course-btn">
                                <a href="#" class="lab-btn-text">Read More <i class="icofont-external-link"></i></a>  <!-- Placeholder for Read More button -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

            coursesContainer.appendChild(courseElement);

            // Fetch the price and product URL using AJAX
            fetchPrice(course.id);
        });
    }

    function fetchPrice(courseId) {
        const priceContainer = document.getElementById(`price-${courseId}`);
        const readMoreButton = document.querySelector(`#course-${courseId} .lab-btn-text`);
        const courseTitleLink = document.querySelector(`#course-${courseId} .course-title-link`);


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
                    const productUrl = data.data.product_url;

                    // Update the Read More button and course title link to point to the product page
                    readMoreButton.href = productUrl;
                    courseTitleLink.href = productUrl;
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
            prevButton.innerHTML = '<i class="icofont-rounded-left"></i>'; // Use icon for Previous
            prevButton.addEventListener('click', () => fetchCourses(currentCpage - 1, type, category));

            prevItem.appendChild(prevButton);
            paginationContainer.insertBefore(prevItem, paginationContainer.firstChild);
        }

        // Optional: Add "Next" button with icon
        if (currentCpage < totalPages) {
            const nextItem = document.createElement('li');
            const nextButton = document.createElement('a');
            nextButton.innerHTML = '<i class="icofont-rounded-right"></i>'; // Use icon for Next
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

    categorySelect.addEventListener('change', (e) => {
        const currentCpage = parseInt(getUrlParameter('cpage')) || 1;
        const selectedCategory = e.target.value;
        const sortType = sortSelect.value;
        fetchCourses(currentCpage, sortType, selectedCategory);
    });

    const initialCpage = parseInt(getUrlParameter('cpage')) || 1;
    const initialType = getUrlParameter('type') || 'general';
    const initialCategory = getUrlParameter('category') || '';
    sortSelect.value = initialType;
    categorySelect.value = initialCategory;
    fetchCourses(initialCpage, initialType, initialCategory);

    document.addEventListener('DOMContentLoaded', function() {
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
                    let options = '<option value="">All Categories</option>';

                    data.data.forEach(category => {
                        options += `<option value="${category.slug}">${category.name}</option>`;
                    });

                    categorySelect.innerHTML = options;
                } else {
                    console.error('Failed to fetch categories:', data);
                }
            })
            .catch(error => {
                console.error('Error fetching categories:', error);
            });
    });
</script>