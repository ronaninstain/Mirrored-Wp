// Tabs transform to dropdown
document.addEventListener("DOMContentLoaded", function () {
  const tabsWrapper = document.querySelector(".elementor-tabs-wrapper");
  const tabs = tabsWrapper.querySelectorAll(".elementor-tab-title");
  const isMobile = window.innerWidth <= 768; // Mobile breakpoint at 768px

  if (isMobile) {
    // Create select element for dropdown
    const select = document.createElement("select");
    select.classList.add("mobile-dropdown");

    // Populate dropdown with tab titles
    tabs.forEach((tab, index) => {
      const option = document.createElement("option");
      option.value = index;
      option.textContent = tab.querySelector("a").textContent.trim();
      select.appendChild(option);
    });

    // Insert dropdown before tabs wrapper
    tabsWrapper.parentNode.insertBefore(select, tabsWrapper);

    // Hide original tabs on mobile
    tabsWrapper.style.display = "none";

    // Function to display selected tab content
    const showTabContent = (index) => {
      const contentId = tabs[index].getAttribute("aria-controls");
      const content = document.getElementById(contentId);

      // Hide all tab contents
      document.querySelectorAll(".elementor-tab-content").forEach((el) => {
        el.style.display = "none";
      });

      // Show selected content
      if (content) {
        content.style.display = "block";
      }
    };

    // Show first tab content by default
    showTabContent(0);

    // Update content on dropdown change
    select.addEventListener("change", function () {
      showTabContent(this.value);
    });
  }
});
