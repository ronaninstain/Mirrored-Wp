document.addEventListener("DOMContentLoaded", function () {
  // Select all forms
  const searchForms = document.querySelectorAll(".search-form");

  searchForms.forEach(function (form) {
    const searchInput = form.querySelector(".course-search-input");
    const searchButton = form.querySelector(".course-search-button");
    const searchResults = form.nextElementSibling; // Assuming the results are immediately after the form
    const clientID = 5;
    const secretKey = "SAKEY20240919";
    const maxResults = 10; // Maximum number of suggestions to display

    // Add event listener for the search button inside this form
    searchButton.addEventListener("click", function (event) {
      event.preventDefault(); // Prevent form submission

      const query = searchInput.value.trim();

      if (query.length > 2) {
        // Only search when the input has more than 2 characters
        fetch(
          `https://course-dashboard.com/wp-json/custom/v1/search-courses?term=${encodeURIComponent(
            query
          )}`,
          {
            method: "GET",
            headers: {
              Authorization: `Bearer ${clientID}:${secretKey}`,
              "Content-Type": "application/json",
            },
          }
        )
          .then((response) => {
            if (!response.ok) {
              throw new Error("Invalid API key or other error");
            }
            return response.json();
          })
          .then((data) => {
            searchResults.innerHTML = ""; // Clear previous results
            searchResults.style.display = "none";

            if (data && data.length > 0) {
              data.slice(0, maxResults).forEach((course) => {
                const li = document.createElement("li");
                li.style.display = "flex";
                li.style.alignItems = "center";
                li.style.padding = "5px";
                li.style.cursor = "pointer";

                const img = document.createElement("img");
                img.src = course.thumbnail;
                img.alt = course.title;
                img.style.width = "40px";
                img.style.height = "40px";
                img.style.objectFit = "cover";
                img.style.marginRight = "10px";

                const span = document.createElement("span");
                span.textContent = course.title;

                li.appendChild(img);
                li.appendChild(span);

                // Fetch product URL via AJAX
                fetchProductUrl(course.id, li);

                searchResults.appendChild(li);
              });

              searchResults.style.display = "block";
            } else {
              searchResults.style.display = "none";
            }
          })
          .catch((error) => {
            console.error("Error fetching search results:", error);
          });
      } else {
        searchResults.innerHTML = ""; // Clear results if the input is too short
        searchResults.style.display = "none";
      }
    });

    // Hide suggestions if clicked outside
    document.addEventListener("click", function (event) {
      if (
        !searchResults.contains(event.target) &&
        event.target !== searchInput &&
        event.target !== searchButton
      ) {
        searchResults.style.display = "none";
      }
    });
  });
});

// Function to fetch product URL via AJAX
function fetchProductUrl(courseId, listItem) {
  const requestData = new FormData();
  requestData.append("action", "get_course_price");
  requestData.append("course_id", courseId);

  // Make the AJAX request to fetch the product URL
  fetch("/wp-admin/admin-ajax.php", {
    method: "POST",
    body: requestData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success && data.data.product_url) {
        // Add click event to redirect to product page instead of course page
        listItem.addEventListener("click", function () {
          window.location.href = data.data.product_url; // Use the product URL
        });
      } else {
        console.error("No product URL found, defaulting to course URL.");
        // If product URL is not found, default to the course URL
        listItem.addEventListener("click", function () {
          window.location.href = `single-course?course_id=${courseId}`;
        });
      }
    })
    .catch((error) => {
      console.error("Error fetching product URL:", error);
      // If there's an error, default to the course URL
      listItem.addEventListener("click", function () {
        window.location.href = `single-course?course_id=${courseId}`;
      });
    });
}
