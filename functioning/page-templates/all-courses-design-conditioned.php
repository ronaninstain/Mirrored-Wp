<section class="ds-5-courses-parent">
    <div class="ds-5-header-section">
        <div class="container">
            <div class="ds-5-filter-area">
                <ul>
                    <!-- <li class="switch_view">
      <div class="grid_list_wrapper">
      <a id="list_view" class="active"><i class="icon-list-1"></i></a>
      <a id="grid_view"><i class="icon-grid"></i></a>
      </div>
      </li> -->
                    <li id="course-order-select" class="last filter">

                        <div class="dropdown" id="category-select">
                            <button onclick="csdmyFunction()" id="dropbtn">
                                Category:<span id="drpdwntxt">All Courses</span>
                            </button>
                            <div id="catDropdown" class="dropdown-content">
                                <a href="#" onclick="csdsetDropDownItem(this)">Accounting &amp; Finance</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">Administration</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">Animal Care</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">Business</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">Childcare</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">Design</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">Employability</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">Engineering</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">English</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">Fashion &amp; Beauty</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">Fitness &amp; First Aid</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">Health &amp; Safety</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">Hospitality</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">Language</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">Law</a>
                                <a href="#" onclick="csdsetDropDownItem(this)">Lifestyle</a>
                            </div>
                        </div>


                    </li>
                </ul>
                <div class="ds-5-search-wrapper">
                    <div id="search" class="search-form">
                        <input type="hidden" name="post_type" value="course" />
                        <input type="text" class="s course-search-input" id="s" name="s" placeholder="Search courses.."
                            value="" autocomplete="off" />
                        <button type="submit" class="sbtn course-search-button">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <!-- <i class="fa fa-search"></i> -->
                            <!-- <img src="/wp-content/themes/wplmsblankchildhtheme/assets/imgs/search-normal.png" alt=""> -->
                        </button>
                    </div>
                    <ul class="course-search-results"
                        style="display:none; position: absolute; background: white; border: 1px solid #ccc; width: 100%; max-width: 585px; max-height: 200px; overflow-y: auto; z-index: 1000;">
                        <!-- Search results will be displayed here -->
                    </ul>
                </div>

            </div>
            <div class="title-course" id="taf-title-course">
                <h1>All Courses</h1>
            </div>
        </div>
    </div>
    <div class="ds-5-course-section">
        <div class="container">
            <div id="loader">
                <div class="lds-roller">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
            <div id="show-courses-container" class="ds-5-course-wrapper">

                <div class="e-all-course-page-single-card">
                    <div class="e-all-course-single-card-img">
                        <!-- <img src="assets/imgs/course-img.png" alt="course-img" /> -->
                        <img src="../assets/imgs/all-courses/demo.jpg" alt="Estate Agent Diploma" />
                    </div>
                    <div class="e-all-course-single-card-title">
                        <a href="#">Estate Agent Diploma</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ds-5-pagination-section " id="pagination-container">
        <div class="container">
            <div class="pagination-area">
                <div class="pag-count" id="course-dir-count-bottom">
                    Viewing page 1 of 79
                </div>
                <div class="pagination-links" id="course-dir-pag-bottom">
                    <span aria-current="page" class="page-numbers current">1</span>
                    <a class="page-numbers" href="#">2</a>
                    <span class="page-numbers dots">…</span>
                    <a class="page-numbers" href="#">79</a>
                    <a class="next page-numbers" href="#">→</a>
                </div>
            </div>
        </div>
    </div>
</section>




<!-- Script For All courses starts -->

<script>
    const clientID = '<?php echo get_option('client_id'); ?>';
    const secretKey = '<?php echo get_option('secret_key'); ?>';

    const apiUrl = 'https://somesite.com/wp-json/custom/v1/posts/';
    const coursesContainer = document.getElementById('show-courses-container');
    const loader = document.getElementById('loader');
    const categorySelect = document.getElementById('category-select');

    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    function fetchCourses(cpage = 1, type = 'general', category = '') {
        const perPage = 16; // Number of courses per page

        loader.style.display = 'flex';
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
                // Filter courses to only include those with a product_url
                filterCoursesWithProductUrl(data.courses).then(filteredCourses => {
                    displayCourses(filteredCourses);
                    setupPagination(data.total_pages, cpage, type, category);
                    loader.style.display = 'none';
                    coursesContainer.style.display = 'grid';
                });
            })
            .catch(error => {
                console.error('Error fetching courses:', error);
                loader.style.display = 'none';
            });
    }

    // Function to filter courses that have a product_url
    function filterCoursesWithProductUrl(courses) {
        return Promise.all(
            courses.map(course => {
                return fetchPrice(course.id).then(productUrl => {
                    if (productUrl) {
                        return {
                            ...course,
                            productUrl
                        }; // Include the productUrl in the course object
                    } else {
                        return null; // Exclude courses without a product_url
                    }
                });
            })
        ).then(results => results.filter(course => course !== null)); // Filter out null values
    }

    function displayCourses(courses) {
        const coursesContainer = document.getElementById('show-courses-container');
        coursesContainer.innerHTML = '';

        courses.forEach(course => {
            const courseElement = document.createElement('div');
            courseElement.classList.add('e-all-course-page-single-card');
            courseElement.id = `course-${course.id}`;
            courseElement.innerHTML = `
            <div class="e-all-course-single-card-img">
                <a class="course-link" href="${course.productUrl}"><img src="${course.thumbnail}" alt="${course.title}" /></a>
            </div>
            <div class="e-all-course-single-card-title">
                <a class="course-link" href="${course.productUrl}">${course.title}</a>
                <p class="course-price"></p>
            </div>
        `;

            coursesContainer.appendChild(courseElement);

            // Optionally, fetch and update additional details like price
            updatePrice(course.id);
        });
    }

    function fetchPrice(courseId) {
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
            .then(data => {
                if (data.success && data.data.product_url) {
                    return data.data.product_url; // Return the product URL
                } else {
                    return null; // Return null if no product URL is found
                }
            })
            .catch(error => {
                console.error('Error fetching price:', error);
                return null; // Return null if there's an error
            });
    }

    function updatePrice(courseId) {
        const priceContainers = document.querySelectorAll(`#course-${courseId} .course-price`);

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
                    priceContainers.forEach(priceContainer => {
                        priceContainer.innerHTML = data.data.price;
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching price:', error);
            });
    }

    function setupPagination(totalPages, currentCpage, type, category) {
        const paginationContainer = document.getElementById('course-dir-pag-bottom');
        const paginationCount = document.getElementById('course-dir-count-bottom');

        // Update the page count text
        paginationCount.textContent = `Viewing page ${currentCpage} of ${totalPages}`;

        // Clear the existing pagination
        paginationContainer.innerHTML = '';

        const maxPagesToShow = 3;
        let startPage = Math.max(1, currentCpage - Math.floor(maxPagesToShow / 2));
        let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

        if (endPage - startPage + 1 < maxPagesToShow) {
            startPage = Math.max(1, endPage - maxPagesToShow + 1);
        }

        // Optional: Add "Previous" button
        if (currentCpage > 1) {
            const prevButton = document.createElement('a');
            prevButton.classList.add('page-numbers', 'prev');
            prevButton.textContent = '←';
            prevButton.addEventListener('click', () => fetchCourses(currentCpage - 1, type, category));
            paginationContainer.appendChild(prevButton);
        }

        // Add the page links
        for (let i = startPage; i <= endPage; i++) {
            const pageLink = document.createElement('a');
            pageLink.textContent = i;
            pageLink.classList.add('page-numbers');
            if (i === currentCpage) {
                pageLink.classList.add('current');
                pageLink.setAttribute('aria-current', 'page');
            } else {
                pageLink.href = '#';
                pageLink.addEventListener('click', () => fetchCourses(i, type, category));
            }
            paginationContainer.appendChild(pageLink);
        }

        // Optional: Add "Next" button
        if (currentCpage < totalPages) {
            const nextButton = document.createElement('a');
            nextButton.classList.add('page-numbers', 'next');
            nextButton.textContent = '→';
            nextButton.addEventListener('click', () => fetchCourses(currentCpage + 1, type, category));
            paginationContainer.appendChild(nextButton);
        }
    }

    categorySelect.addEventListener('change', (e) => {
        const currentCpage = parseInt(getUrlParameter('cpage')) || 1;
        const selectedCategory = e.target.value;
        const sortType = 'general'; // Default sort type
        fetchCourses(currentCpage, sortType, selectedCategory);
    });

    const initialCpage = parseInt(getUrlParameter('cpage')) || 1;
    const initialType = getUrlParameter('type') || 'general';
    const initialCategory = getUrlParameter('category') || '';
    categorySelect.value = initialCategory;
    fetchCourses(initialCpage, initialType, initialCategory);

    document.addEventListener('DOMContentLoaded', function() {
        // Function to load all categories and update the dropdown
        function loadCategories() {
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

                        // Select the dropdown and its text display
                        const dropdownContent = document.getElementById('catDropdown');
                        const dropBtnText = document.getElementById('drpdwntxt');

                        // Clear existing items in the dropdown before appending new ones
                        dropdownContent.innerHTML = '';

                        // Add a default 'All Courses' option to the dropdown
                        dropdownContent.innerHTML += `<a href="#" onclick="csdsetDropDownItem(this)">All Courses</a>`;

                        // Append each category as a dropdown item
                        categories.forEach(category => {
                            dropdownContent.innerHTML += `
                            <a href="#" onclick="csdsetDropDownItem(this)" id="${category.slug}">
                                ${category.name}
                            </a>`;
                        });

                        // Optionally, if needed, update the button text when the category is selected
                        dropdownContent.addEventListener('click', function(e) {
                            if (e.target.tagName === 'A') {
                                const selectedCategory = e.target.innerText;
                                dropBtnText.textContent = selectedCategory; // Update the button text with the selected category
                                const currentCpage = 1; // Reset page to 1 when category is changed
                                let newSelectedCategory = e.target.id;
                                fetchCourses(currentCpage, 'general', newSelectedCategory); // Fetch courses with the new category
                            }
                        });
                    } else {
                        console.error('Failed to fetch categories:', data);
                    }
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                });
        }

        // Load categories when the page loads
        loadCategories();
    });
</script>