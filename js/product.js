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

// Миниатюры
/*const imagesSlider = document.querySelectorAll(".img");
const current = document.getElementById("current");

imagesSlider.forEach(img => {
  img.addEventListener("click", () => {
    current.src = img.src;
    imagesSlider.forEach(t => t.classList.remove("active"));
    img.classList.add("active");
  });
});*/

/*const current = document.getElementById("current");
const sliderContainer = document.querySelector(".images-slider");

// Собираем ВСЕ картинки со страницы (главная + миниатюры)
let allImages = [current.src, ...Array.from(sliderContainer.querySelectorAll("img")).map(img => img.src)];

let currentIndex = 0; // индекс текущей главной

// функция отрисовки 4 миниатюр
function renderThumbnails() {
  sliderContainer.innerHTML = "";

  // берём следующие 4 после currentIndex
  for (let i = 1; i <= 4; i++) {
    const thumbIndex = (currentIndex + i) % allImages.length;

    // если совпадает с главной — пропускаем (чтобы не дублировать)
    if (thumbIndex === currentIndex) continue;

    const img = document.createElement("img");
    img.src = allImages[thumbIndex];
    img.classList.add("img");

    // событие по клику → делаем этой картинкой главную
    img.addEventListener("click", () => {
      currentIndex = thumbIndex;
      current.src = allImages[currentIndex];
      renderThumbnails();
    });

    sliderContainer.appendChild(img);
  }
}

// инициализация
renderThumbnails();*/

const current = document.getElementById("current");
const sliderContainer = document.querySelector(".images-slider");
let allImages = Array.from(sliderContainer.querySelectorAll("img"));

let currentIndex = 0;

// 👉 первая картинка из HTML = главная
if (allImages.length > 0) {
  current.src = allImages[0].src;
  currentIndex = 0;
}

function renderThumbnails() {
  sliderContainer.innerHTML = "";

  // показываем максимум 4 миниатюры после текущей
  let thumbsToShow = Math.min(4, allImages.length - 1);
  let count = 0;
  let i = currentIndex + 1;

  while (count < thumbsToShow) {
    const thumbIndex = i % allImages.length;
    if (thumbIndex !== currentIndex) {
      const img = document.createElement("img");
      img.src = allImages[thumbIndex].src;
      img.classList.add("img");

      img.addEventListener("click", () => {
        currentIndex = thumbIndex;
        current.src = allImages[currentIndex].src;
        renderThumbnails();
      });

      sliderContainer.appendChild(img);
      count++;
    }
    i++;
  }
}

// --- Лайтбокс ---
const lightbox = document.getElementById("lightbox");
const lightboxImg = document.getElementById("lightbox-img");
const closeBtn = document.querySelector(".lightbox .btn-close");
const prevBtn = document.querySelector(".lightbox .btn-prev");
const nextBtn = document.querySelector(".lightbox .btn-next");

function openLightbox() {
  lightbox.style.display = "flex";
  lightboxImg.src = allImages[currentIndex].src;
}

function closeLightbox() {
  lightbox.style.display = "none";
}

function showNext() {
  currentIndex = (currentIndex + 1) % allImages.length;
  current.src = allImages[currentIndex].src;
  lightboxImg.src = allImages[currentIndex].src;
  renderThumbnails();
}

function showPrev() {
  currentIndex = (currentIndex - 1 + allImages.length) % allImages.length;
  current.src = allImages[currentIndex].src;
  lightboxImg.src = allImages[currentIndex].src;
  renderThumbnails();
}

current.addEventListener("click", openLightbox);
closeBtn.addEventListener("click", closeLightbox);
nextBtn.addEventListener("click", showNext);
prevBtn.addEventListener("click", showPrev);

lightbox.addEventListener("click", (e) => {
  if (e.target === lightbox) closeLightbox();
});

// инициализация
renderThumbnails();




