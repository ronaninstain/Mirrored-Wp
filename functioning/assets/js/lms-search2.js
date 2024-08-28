document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("course-search-input");
  const searchButton = document.getElementById("course-search-button");
  const searchResults = document.getElementById("course-search-results");
  const maxResults = 10; // Maximum number of suggestions to display

  searchButton.addEventListener("click", function () {
    const query = searchInput.value.trim();

    if (query.length > 2) {
      // Only search when the input has more than 2 characters
      fetch(
        `https://SOURCE/wp-json/custom/v1/search-courses?term=${encodeURIComponent(
          query
        )}`
      )
        .then((response) => response.json())
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

              li.addEventListener("click", function () {
                window.location.href = `<?php echo home_url('single-course?course_id='); ?>${course.id}`;
              });

              searchResults.appendChild(li);
            });

            searchResults.style.display = "block";
          } else {
            searchResults.style.display = "none";
          }
        })
        .catch((error) =>
          console.error("Error fetching search results:", error)
        );
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
