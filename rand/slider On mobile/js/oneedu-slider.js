// oneedu-slider.js
document.addEventListener('DOMContentLoaded', () => {
  const instances = new Map();

  function initSwiper(container) {
    container.classList.add('swiper');
    const wrapper = document.createElement('div');
    wrapper.classList.add('swiper-wrapper');
    Array.from(container.children).forEach(card => {
      card.classList.add('swiper-slide');
      wrapper.appendChild(card);
    });
    container.appendChild(wrapper);

    const pagination = document.createElement('div');
    pagination.classList.add('swiper-pagination');
    const prevBtn = document.createElement('div');
    prevBtn.classList.add('swiper-button-prev');
    const nextBtn = document.createElement('div');
    nextBtn.classList.add('swiper-button-next');
    container.append(pagination, prevBtn, nextBtn);

    const swiper = new Swiper(container, {
      slidesPerView: 1.2,
      spaceBetween: 12,
      pagination: { el: pagination, clickable: true },
      navigation: { prevEl: prevBtn, nextEl: nextBtn },
    });

    instances.set(container, { swiper, wrapper, pagination, prevBtn, nextBtn });
  }

  function destroySwiper(container) {
    const data = instances.get(container);
    if (!data) return;
    data.swiper.destroy(true, true);
    container.classList.remove('swiper');

    Array.from(data.wrapper.children).forEach(slide => {
      slide.classList.remove('swiper-slide');
      container.appendChild(slide);
    });
    data.wrapper.remove();
    data.pagination.remove();
    data.prevBtn.remove();
    data.nextBtn.remove();
    instances.delete(container);
  }

  function updateSwipers() {
    instances.forEach(({ swiper }) => {
      swiper.update();
      if (swiper.pagination && swiper.pagination.render) {
        swiper.pagination.render();
        swiper.pagination.update();
      }
      if (swiper.navigation) {
        swiper.navigation.update();
      }
    });
  }

  function setupSwipers() {
    document.querySelectorAll('.srs_cards_pr').forEach(container => {
      const mobile = window.innerWidth <= 768;
      const has = instances.has(container);

      if (mobile && !has) {
        initSwiper(container);
      } else if (!mobile && has) {
        destroySwiper(container);
      }
    });
    updateSwipers();
  }

  // initial + resize
  setupSwipers();
  window.addEventListener('resize', setupSwipers);

  // Elementor desktop tabs
  document.querySelectorAll('.elementor-tab-title').forEach(tab =>
    tab.addEventListener('click', () => setTimeout(setupSwipers, 200))
  );

  // **Mobile dropdown tabs**:
  const dd = document.querySelector('.mobile-dropdown');
  if (dd) {
    dd.addEventListener('change', () => {
      // small delay to let Elementor show the correct panel
      setTimeout(setupSwipers, 200);
    });
  }
});
