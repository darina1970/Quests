document.addEventListener("DOMContentLoaded", () => {
  const burger = document.querySelector(".header__burger");
  const nav = document.querySelector(".header__nav");
  const navLinks = document.querySelectorAll(".header__nav a");

  burger.addEventListener("click", () => {
    burger.classList.toggle("active");
    nav.classList.toggle("active");
    document.body.classList.toggle("lock");
  });

  navLinks.forEach((link) => {
    link.addEventListener("click", () => {
      burger.classList.remove("active");
      nav.classList.remove("active");
      document.body.classList.remove("lock");
    });
  });

  const ageFilter = document.getElementById("filter-age");
  const themeFilter = document.getElementById("filter-theme");
  const sortFilter = document.getElementById("sort-price");
  const productsWrapper = document.querySelector(".product-items__wrapper");

  function fetchProducts() {
    const data = new FormData();
    data.append("action", "filter_products");
    data.append("age", ageFilter.value);
    data.append("theme", themeFilter.value);
    data.append("sort", sortFilter.value);

    fetch(woocommerce_params.ajax_url, {
      method: "POST",
      body: data,
    })
      .then((res) => res.text())
      .then((html) => {
        productsWrapper.innerHTML = html;
      })
      .catch((err) => console.error("AJAX error:", err));
  }

  ageFilter.addEventListener("change", fetchProducts);
  themeFilter.addEventListener("change", fetchProducts);
  sortFilter.addEventListener("change", fetchProducts);

  const faqItems = document.querySelectorAll(".faq-item");

  faqItems.forEach((item) => {
    const wrapper = item.querySelector(".faq-question__wrapper");
    const answer = item.querySelector(".faq-answer");

    wrapper.addEventListener("click", () => {
      faqItems.forEach((i) => {
        if (i !== item) {
          i.querySelector(".faq-answer").classList.remove("show");
          i.querySelector(".faq-question__wrapper").classList.remove("active");
        }
      });
      wrapper.classList.toggle("active");
      answer.classList.toggle("show");
    });
  });

  const slider = document.querySelector(".reviews__slider");
  const cards = document.querySelectorAll(".review-card");
  let currentIndex = 0;

  function scrollSlider() {
    const cardWidth = cards[0].offsetWidth + 30; // ширина + gap
    const visibleCards =
      window.innerWidth >= 1024 ? 3 : window.innerWidth >= 768 ? 2 : 1;
    const maxIndex = cards.length - visibleCards;

    currentIndex = currentIndex + 1 > maxIndex ? 0 : currentIndex + 1;
    slider.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
  }

  setInterval(scrollSlider, 4000); // каждые 4 секунды

  // Gallery-slider
  const track = document.getElementById("slider-track");
  const slides = Array.from(track.children);

  slides.forEach((slide) => {
    const clone = slide.cloneNode(true);
    track.appendChild(clone);
  });

  track.addEventListener("touchstart", () => {
    track.style.animationPlayState = "paused";
  });

  track.addEventListener("touchend", () => {
    track.style.animationPlayState = "running";
  });

  track.addEventListener("mouseenter", () => {
    track.style.animationPlayState = "paused";
  });

  track.addEventListener("mouseleave", () => {
    track.style.animationPlayState = "running";
  });
});
