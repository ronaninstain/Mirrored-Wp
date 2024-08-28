<?php /* Template Name: Test Template */ 
get_header();
?>

<div id="output"></div>
<script>
    // Define the API URL

const apiUrl = 'https://SOURCE/wp-json/custom/v1/posts/?page=2&per_page=2';
const outputElement = document.getElementById('output');

fetch(apiUrl)
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(data => {
    // Display data in an HTML element
    console.log(data);
    outputElement.textContent = JSON.stringify(data, null, 2);
  })
  .catch(error => {
    console.error(error);
    console.error('Error:', error);
  });
</script>

<?php get_footer(); ?>