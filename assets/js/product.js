document.addEventListener("DOMContentLoaded", function () {
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
    });
  });

  const writeReviewBtn = document.getElementById("writeReview");
  const reviewFormContainer = document.getElementById("reviewForm");
  const reviewMessage = document.getElementById("reviewMessage");

  writeReviewBtn.addEventListener("click", function () {
    if (
      reviewFormContainer.style.display === "none" ||
      reviewFormContainer.style.display === ""
    ) {
      reviewFormContainer.style.display = "block";
      writeReviewBtn.textContent = "Cancel";
    } else {
      reviewFormContainer.style.display = "none";
      writeReviewBtn.textContent = "Write Your Review";
      reviewForm.reset();
      photoPreview.innerHTML = "";
      ratingInput.value = 0;
      stars.forEach((s) => s.classList.remove("selected"));
    }
  });

  const stars = document.querySelectorAll(".stars-input span");
  const ratingInput = document.getElementById("reviewRating");

  stars.forEach((star) => {
    star.addEventListener("click", function () {
      const value = parseInt(this.dataset.value);
      ratingInput.value = value;

      stars.forEach((s) => {
        if (parseInt(s.dataset.value) <= value) {
          s.classList.add("selected");
        } else {
          s.classList.remove("selected");
        }
      });
    });
  });

  const photoInput = document.getElementById("reviewPhotos");
  const photoPreview = document.getElementById("photoPreview");
  const photoError = document.getElementById("photoError");

  photoInput.addEventListener("change", function () {
    photoPreview.innerHTML = "";
    photoError.textContent = "";
    const files = Array.from(this.files);

    if (files.length > 3) {
      photoError.textContent = "You can upload up to 3 photos only.";
      this.value = "";
      return;
    }

    files.forEach((file) => {
      if (!file.type.startsWith("image/")) return;
      const reader = new FileReader();
      reader.onload = function (e) {
        const container = document.createElement("div");
        container.classList.add("photo-item");
        container.style.position = "relative";

        const img = document.createElement("img");
        img.src = e.target.result;

        const btn = document.createElement("button");
        btn.type = "button";
        btn.textContent = "×";

        btn.addEventListener("click", () => {
          container.remove();
          const dt = new DataTransfer();
          Array.from(photoInput.files)
            .filter((f) => f.name !== file.name)
            .forEach((f) => dt.items.add(f));
          photoInput.files = dt.files;
        });

        container.appendChild(img);
        container.appendChild(btn);
        photoPreview.appendChild(container);
      };
      reader.readAsDataURL(file);
    });
  });

  const reviewForm = document.getElementById("customReviewForm");

  reviewForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(reviewForm);
    formData.append("action", "submit_custom_review");
    formData.append("product_id", woocommerce_params.product_id);

    fetch(woocommerce_params.ajax_url, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showMessage(
            "Review submitted! It will appear after moderation.",
            true
          );
          reviewForm.reset();
          document.getElementById("photoPreview").innerHTML = "";
          document.getElementById("reviewRating").value = 0;
          document
            .querySelectorAll(".stars-input span")
            .forEach((s) => s.classList.remove("selected"));
          reviewFormContainer.style.display = "none";
          writeReviewBtn.textContent = "Write your Review";
        } else {
          showMessage(data.data || "Error submitting review.", false);
        }
      })
      .catch(() => showMessage("AJAX request failed.", false));
  });

  function showMessage(text, success = true) {
    reviewMessage.style.display = "block";
    reviewMessage.style.color = success ? "green" : "red";
    reviewMessage.textContent = text;

    setTimeout(() => {
      reviewMessage.style.display = "none";
    }, 4000);
  }
});
