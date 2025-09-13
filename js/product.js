document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".product-tab");
  const tabContents = document.querySelectorAll(".tab-content");

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const target = tab.dataset.tab;

      tabs.forEach((t) => t.classList.remove("active"));
      tabContents.forEach((tc) => tc.classList.remove("active"));

      tab.classList.add("active");
      document.getElementById(target).classList.add("active");
    });
  });

  const writeBtn = document.getElementById("writeReview");
  const reviewForm = document.getElementById("reviewForm");
  const submitBtn = document.getElementById("submit-review");
  const reviewList = document.querySelector(".reviews-list");

  writeBtn.addEventListener("click", () => {
    const isHidden = reviewForm.classList.contains("hidden");

    if (isHidden) {
      reviewForm.classList.remove("hidden");
      writeBtn.textContent = "Cancel Your Review";
    } else {
      reviewForm.classList.add("hidden");
      writeBtn.textContent = "Write Your Review";
    }
  });

  const starsInput = document.querySelectorAll(".stars-input span");
  let selectedRating = 0;

  starsInput.forEach((star, index) => {
    star.addEventListener("click", () => {
      selectedRating = index + 1;
      starsInput.forEach((s, i) => {
        if (i < selectedRating) {
          s.classList.add("selected");
        } else {
          s.classList.remove("selected");
        }
      });
    });
  });

  submitBtn.addEventListener("click", (e) => {
    e.preventDefault();

    const name = document.getElementById("reviewName").value.trim();
    const text = document.getElementById("reviewText").value.trim();

    if (!name || !text || selectedRating === 0) {
      alert("Please fill in your name, review, and select a rating");
      return;
    }

    const newReview = document.createElement("div");
    newReview.classList.add("review");

    const date = new Date();
    const formattedDate = date.toLocaleDateString("en-GB");

    let starsHTML = "";
    for (let i = 0; i < 5; i++) {
      if (i < selectedRating) {
        starsHTML += `<img src="./assets/icons/star-full.svg" alt="star">`;
      } else {
        starsHTML += `<img src="./assets/icons/star.svg" alt="star">`;
      }
    }

    newReview.innerHTML = `
        <div class="review-header">
            <div class="review-stars">${starsHTML}</div>
            <p class="review-date">${formattedDate}</p>
        </div>
        <div class="review__user-info">
            <img src="./assets/icons/review-icon.svg" alt="user icon">
            <p>${name}</p>
        </div>
        <p>${text}</p>
    `;

    reviewList.prepend(newReview);

    reviewForm.classList.add("hidden");
    writeBtn.textContent = "Write Your Review";
    document.getElementById("reviewName").value = "";
    document.getElementById("reviewText").value = "";
    selectedRating = 0;
    starsInput.forEach((s) => s.classList.remove("selected"));

    const thanksMsg = document.createElement("p");
    thanksMsg.textContent = "Thank you for your feedback!";
    thanksMsg.style.color = "green";
    reviewForm.parentElement.insertBefore(thanksMsg, reviewForm);
    setTimeout(() => thanksMsg.remove(), 3000);
  });
});
