<?php
?>
<Script>
    function displayCourses(courses) {
        coursesContainer.innerHTML = ''; // Clear existing courses

        courses.forEach(course => {
            // Fetch price and update the price span
            fetchPriceAndUrl(course.id).then(priceData => {
                if (priceData.success && priceData.data.price !== 'N/A' && priceData.data.price !== 'Error fetching price') {
                    const courseElement = document.createElement('div');
                    const rating = course.meta.average_rating;
                    courseElement.classList.add('col-xl-4', 'col-sm-6'); // Bootstrap column classes

                    const thumbnailUrl = course.thumbnail ? course.thumbnail : '<?php echo get_template_directory_uri(); ?>/assets/design_4/images/course/pro-6.jpg';
                    const ratingCount = course.meta.average_rating ? course.meta.average_rating : 0;
                    const author = course.author ? course.author : 'Unknown Author';
                    const courseTitle = course.title ? course.title : 'No title available';
                    const lessons = course.meta.units ? course.meta.units : 'N/A';
                    const price = priceData.data.price;

                    // Create the course HTML structure
                    courseElement.innerHTML = `
                    <div class="course-default border radius-md mb-25">
                        <figure class="course-img">
                            <a href="javascript:void(0)" title="Image" target="_self" class="lazy-container ratio ratio-2-3">
                                <img class="lazyload" src="${thumbnailUrl}" data-src="${thumbnailUrl}" alt="course">
                            </a>
                            <div class="hover-show">
                                <a href="javascript:void(0)" class="btn btn-md btn-primary rounded-pill course-link-${course.id}" title="Enroll Now" target="_self">Enroll Now</a>
                            </div>
                        </figure>
                        <div class="course-details">
                            <div class="p-3">
                                <a href="javascript:void(0)" target="_self" title="Category"  class="tag font-sm color-primary mb-1 course-link-${course.id}">${course.categories[0]}</a>
                                <h6 class="course-title lc-2 mb-0">
                                    <a href="javascript:void(0)" target="_self" class="course-link-${course.id}" title="${courseTitle}">
                                        ${courseTitle}
                                    </a>
                                </h6>
                                <div class="authors mt-15">
                                    <div class="author">
                                    </div>
                                    <span class="font-sm icon-start"><i class="fas fa-star"></i>${ratingCount}</span>
                                </div>
                            </div>
                            <div class="course-bottom-info px-3 py-2">
                                <span class="font-sm"><i class="fas fa-usd-circle"></i><span class="price-${course.id}">${price}</span></span>
                                <span class="font-sm"><i class="fas fa-book-alt"></i>${lessons} Lessons</span>
                            </div>
                        </div>
                    </div>
                `;

                    // Append the course element
                    coursesContainer.appendChild(courseElement);
                }
            });
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
            .then(data => {
                return data;
            })
            .catch(error => {
                console.error('Error fetching price:', error);
                return {
                    success: false,
                    data: {
                        price: 'Error fetching price',
                        product_url: `/single-course?course_id=${courseId}`
                    }
                };
            });
    }
</Script>
<?php
