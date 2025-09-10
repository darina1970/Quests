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
});
