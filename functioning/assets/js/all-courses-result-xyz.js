// Function to get URL parameters
function getUrlParameter(name) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(name);
}

// Function to update the "Showing X-Y of Z results" text
function updateShowingText(startIndex, endIndex, total) {
  const showingText = `Showing ${startIndex}-${endIndex} of ${total} results`;
  document.querySelector(".course-showing-part-left p").textContent =
    showingText;
}

// Function to fetch courses and update the "Showing" text
async function fetchCoursesResponse(
  page = 1,
  perPage = 9,
  type = "general",
  category = ""
) {
  const response = await fetch(
    `${apiUrl}?cpage=${page}&per_page=${perPage}&type=${type}&category=${category}`,
    {
      method: "GET",
      headers: {
        Authorization: `Bearer ${clientID}:${secretKey}`,
        "Content-Type": "application/json",
      },
    }
  );
  const data = await response.json();

  const totalCourses = data.total_posts;
  const startIndex = (page - 1) * perPage + 1;
  const endIndex = Math.min(page * perPage, totalCourses);

  // Update the showing text
  updateShowingText(startIndex, endIndex, totalCourses);

  // Further code to handle displaying the courses...
  console.log(data.courses); // Example of how you can handle the course data
}

// Get page and perPage from URL parameters
const page = parseInt(getUrlParameter("page")) || 1; // Default to 1 if not present
const perPage = parseInt(getUrlParameter("perPage")) || 9; // Default to 9 if not present

// Fetch courses with dynamic page and perPage values
fetchCoursesResponse(page, perPage);
