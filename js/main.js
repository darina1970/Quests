document.addEventListener("DOMContentLoaded", () => {
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

  const slider = document.querySelector('.reviews__slider');
const cards = document.querySelectorAll('.review-card');
let currentIndex = 0;

function scrollSlider() {
  const cardWidth = cards[0].offsetWidth + 30; // ширина + gap
  const visibleCards = window.innerWidth >= 1024 ? 3 : window.innerWidth >= 768 ? 2 : 1;
  const maxIndex = cards.length - visibleCards;

  currentIndex = (currentIndex + 1) > maxIndex ? 0 : currentIndex + 1;
  slider.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
}

setInterval(scrollSlider, 4000); // каждые 4 секунды

});

/* Parallax */
document.addEventListener("DOMContentLoaded", () => {
  const img = document.querySelector(".parallax__mobile .parallax__img");
  if (!img) return;

  window.addEventListener("scroll", () => {
    const block = img.parentElement;
    const rect = block.getBoundingClientRect();
    const windowHeight = window.innerHeight;

    if (rect.top < windowHeight && rect.bottom > 0) {
      const blockHeight = block.offsetHeight;
      const progress = (windowHeight - rect.top) / (windowHeight + blockHeight);
      const maxShift = blockHeight * 0.6;
      img.style.transform = `translateY(${(progress - 0.5) * 2 * maxShift}px)`;
    }
  });
});


