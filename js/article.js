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
    for (let i = 1; i <= 4; i++) {
      const thumbIndex = (currentIndex + i) % allImages.length;
      const thumb = document.createElement("img");
      thumb.src = allImages[thumbIndex].src;
      thumb.className = "img";
      thumb.alt = `Thumbnail ${thumbIndex}`;
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
