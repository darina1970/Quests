document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".product-tab");
  const tabContents = document.querySelectorAll(".tab-content");

  function activateTab(targetId) {
    tabs.forEach((t) => t.classList.remove("active"));
    tabContents.forEach((tc) => tc.classList.remove("active"));

    const targetTab = document.querySelector(`[data-tab="${targetId}"]`);
    const targetContent = document.getElementById(targetId);

    if (targetTab && targetContent) {
      targetTab.classList.add("active");
      targetContent.classList.add("active");
    }
  }

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const target = tab.dataset.tab;
      activateTab(target);

      // tabs.forEach((t) => t.classList.remove("active"));
      // tabContents.forEach((tc) => tc.classList.remove("active"));

      // tab.classList.add("active");
      // document.getElementById(target).classList.add("active");
    });
  });

  const reviewLink = document.querySelector('a[href="#reviews"]');
  if (reviewLink) {
    reviewLink.addEventListener("click", (e) => {
      e.preventDefault();
      activateTab("reviews");
      document.getElementById("reviews").scrollIntoView({ behavior: "smooth" });
    });
  }

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

  // превью фото
  // const photoInput = document.getElementById("reviewPhotos");
  // const photoPreview = document.getElementById("photoPreview");
  // const photoError = document.getElementById("photoError");
  // const maxFiles = 3;

  // photoInput.addEventListener("change", () => {
  //   photoPreview.innerHTML = "";
  //   photoError.textContent = "";
  //   let files = Array.from(photoInput.files);

  //   if (files.length > maxFiles) {
  //     photoError.textContent = `You can upload up to ${maxFiles} images.`;
  //     files = files.slice(0, maxFiles);
  //   }

  //   files.forEach((file) => {
  //     const reader = new FileReader();
  //     reader.onload = function (e) {
  //       const img = document.createElement("img");
  //       img.src = e.target.result;
  //       img.style.width = "80px";
  //       img.style.height = "80px";
  //       img.style.objectFit = "cover";
  //       img.style.margin = "5px";
  //       img.style.borderRadius = "4px";
  //       photoPreview.appendChild(img);
  //     };
  //     reader.readAsDataURL(file);
  //   });
  // });

  // submitBtn.addEventListener("click", (e) => {
  //   e.preventDefault();

  //   const name = document.getElementById("reviewName").value.trim();
  //   const text = document.getElementById("reviewText").value.trim();

  //   if (!name || !text || selectedRating === 0) {
  //     alert("Please fill in your name, review, and select a rating");
  //     return;
  //   }

  //   const newReview = document.createElement("div");
  //   newReview.classList.add("review");

  //   const date = new Date();
  //   const formattedDate = date.toLocaleDateString("en-GB");

  //   let starsHTML = "";
  //   for (let i = 0; i < 5; i++) {
  //     if (i < selectedRating) {
  //       starsHTML += `<img src="./assets/icons/star-full.svg" alt="star">`;
  //     } else {
  //       starsHTML += `<img src="./assets/icons/star.svg" alt="star">`;
  //     }
  //   }

  //   newReview.innerHTML = `
  //       <div class="review-header">
  //           <div class="review-stars">${starsHTML}</div>
  //           <p class="review-date">${formattedDate}</p>
  //       </div>
  //       <div class="review__user-info">
  //           <img src="./assets/icons/review-icon.svg" alt="user icon">
  //           <p>${name}</p>
  //       </div>
  //       <p>${text}</p>
  //   `;

  //   reviewList.prepend(newReview);

  //   reviewForm.classList.add("hidden");
  //   writeBtn.textContent = "Write Your Review";
  //   document.getElementById("reviewName").value = "";
  //   document.getElementById("reviewText").value = "";
  //   selectedRating = 0;
  //   starsInput.forEach((s) => s.classList.remove("selected"));

  //   const thanksMsg = document.createElement("p");
  //   thanksMsg.textContent = "Thank you for your feedback!";
  //   thanksMsg.style.color = "green";
  //   reviewForm.parentElement.insertBefore(thanksMsg, reviewForm);
  //   setTimeout(() => thanksMsg.remove(), 3000);
  // });

  // Фото с превью, лимитом и удалением
  const photoInput = document.getElementById("reviewPhotos");
  const photoPreview = document.getElementById("photoPreview");
  const photoError = document.getElementById("photoError");
  const maxFiles = 3;

  photoInput.addEventListener("change", () => {
    photoError.textContent = "";

    let existingPhotos = photoPreview.querySelectorAll(".photo-item").length;
    let files = Array.from(photoInput.files);

    if (existingPhotos + files.length > maxFiles) {
      photoError.textContent = `You can upload up to ${maxFiles} images.`;
      files = files.slice(0, maxFiles - existingPhotos);
    }

    files.forEach((file) => {
      const reader = new FileReader();
      reader.onload = (e) => {
        const container = document.createElement("div");
        container.classList.add("photo-item");
        container.style.position = "relative";

        const img = document.createElement("img");
        img.src = e.target.result;

        const btn = document.createElement("button");
        btn.type = "button";
        btn.textContent = "×";
        btn.addEventListener("click", () => container.remove());

        container.appendChild(img);
        container.appendChild(btn);
        photoPreview.appendChild(container);
      };
      reader.readAsDataURL(file);
    });
  });

  // Отправка отзыва
  // const submitBtn = document.getElementById("submit-review");
  // const reviewList = document.querySelector(".reviews-list");

  submitBtn.addEventListener("click", (e) => {
    e.preventDefault();

    const name = document.getElementById("reviewName").value.trim();
    const text = document.getElementById("reviewText").value.trim();
    const files = Array.from(photoPreview.querySelectorAll("img"));

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
      starsHTML += `<img src="./assets/icons/${
        i < selectedRating ? "star-full" : "star"
      }.svg" alt="star">`;
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
          <div class="review-photos"></div>
      `;

    const reviewPhotosContainer = newReview.querySelector(".review-photos");

    files.forEach((img) => {
      const reviewImg = document.createElement("img");
      reviewImg.src = img.src;
      reviewPhotosContainer.appendChild(reviewImg);
    });

    reviewList.prepend(newReview);

    // Очистка формы
    reviewForm.classList.add("hidden");
    writeBtn.textContent = "Write Your Review";
    document.getElementById("reviewName").value = "";
    document.getElementById("reviewText").value = "";
    photoPreview.innerHTML = "";
    photoError.textContent = "";
    selectedRating = 0;
    starsInput.forEach((s) => s.classList.remove("selected"));

    const thanksMsg = document.createElement("p");
    thanksMsg.textContent = "Thank you for your feedback!";
    thanksMsg.style.color = "green";
    reviewForm.parentElement.insertBefore(thanksMsg, reviewForm);
    setTimeout(() => thanksMsg.remove(), 3000);
  });

  /* Slider */
  const current = document.getElementById("current");
  const sliderContainer = document.querySelector(".images-slider");
  const allImages = Array.from(sliderContainer.querySelectorAll(".img"));

  const leftArrow = document.querySelector(".left-arrow");
  const rightArrow = document.querySelector(".right-arrow");

  const lightbox = document.getElementById("lightbox");
  const lightboxImg = document.getElementById("lightbox-img");
  const closeBtn = document.querySelector(".lightbox .btn-close");
  const prevBtn = document.querySelector(".lightbox .btn-prev");
  const nextBtn = document.querySelector(".lightbox .btn-next");

  let currentIndex = 0;

  function renderThumbnails() {
    sliderContainer.innerHTML = "";

    const thumbnails = [];
    for (let i = 0; i < 4; i++) {
      const thumbIndex = (currentIndex + i) % allImages.length;
      const thumb = document.createElement("img");
      thumb.src = allImages[thumbIndex].src;
      thumb.className = "img";
      thumb.alt = `Thumbnail ${thumbIndex}`;

      if (thumbIndex === currentIndex) {
        thumb.classList.add("active");
      }

      thumb.addEventListener("click", () => {
        currentIndex = thumbIndex;
        updateMainImage();
      });
      thumbnails.push(thumb);
    }
    thumbnails.forEach((thumb) => sliderContainer.appendChild(thumb));
  }

  function updateMainImage() {
    current.src = allImages[currentIndex].src;
    lightboxImg.src = allImages[currentIndex].src;
    renderThumbnails();
  }

  /* Arrows */
  function showNext() {
    currentIndex = (currentIndex + 1) % allImages.length;
    updateMainImage();
  }

  function showPrev() {
    currentIndex = (currentIndex - 1 + allImages.length) % allImages.length;
    updateMainImage();
  }

  /* Lightbox */
  function openLightbox() {
    if (window.innerWidth > 1320) {
      lightbox.style.display = "flex";
      lightboxImg.src = allImages[currentIndex].src;
    }
  }

  function closeLightbox() {
    lightbox.style.display = "none";
  }

  current.addEventListener("click", openLightbox);
  closeBtn?.addEventListener("click", closeLightbox);
  nextBtn?.addEventListener("click", showNext);
  prevBtn?.addEventListener("click", showPrev);

  lightbox?.addEventListener("click", (e) => {
    if (e.target === lightbox) closeLightbox();
  });

  rightArrow?.addEventListener("click", showNext);
  leftArrow?.addEventListener("click", showPrev);

  let startX = 0;
  current.addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
  });
  current.addEventListener("touchend", (e) => {
    const endX = e.changedTouches[0].clientX;
    if (startX - endX > 50) showNext();
    else if (endX - startX > 50) showPrev();
  });

  updateMainImage();
});
