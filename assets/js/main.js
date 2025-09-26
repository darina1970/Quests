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


  //Form
  const form = document.querySelector('#subscribe-form');
  const wrapper = document.querySelector('#form-wrapper');
  const message = document.querySelector('#form-message');

  if (!form || !wrapper || !message) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    formData.append('action', 'alena_subscribe_form');

    try {
      const response = await fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        const withQuest = result.data.with_quest === 'yes';

        // Скрываем форму и текст
        wrapper.querySelectorAll('form, .form__text, .form__image').forEach(el => {
          if (el) el.style.display = 'none';
        });

        // Показываем сообщение
        message.innerHTML = withQuest
          ? 'Thanks for subscribing! 💌\n A free quest will be sent to your email.'
          : 'Thanks for subscribing! 💌';

        message.style.display = 'block';

        // Через 5 секунд форма возвращается
        setTimeout(() => {
          wrapper.querySelectorAll('form, .form__text, .form__image').forEach(el => {
            if (el) el.style.display = '';
          });
          message.style.display = 'none';
          form.reset();
        }, 5000);
      } else {
        message.innerHTML = 'An error has occurred. Try again.';
        message.style.display = 'block';
      }
    } catch (err) {
      console.error('Ошибка отправки:', err);
      message.innerHTML = 'Network error. Please try again later.';
      message.style.display = 'block';
    }
        
});
});


/* МОЯ СТАРАЯ ФОРМА
<section class="form section-common" id="form">
        <div class="container">
            <div class="form__wrapper">
                <?php if ( get_field('show_form_image') ) : ?>
                    <?php $image = get_field('form_image'); ?>
                    <?php if( $image ) : ?>
                        <img class="form__image" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                    <?php endif; ?>
                <?php endif; ?>
                <div class="form__text">
                    <?php if ( get_field('form_title') ) : ?>
                        <h2 class="text-align"><?php the_field('form_title'); ?></h2>
                    <?php endif; ?>
                    <?php if ( get_field('form_subtitle') ) : ?>
                        <h3 class="text-align"><?php the_field('form_subtitle'); ?></h3>
                    <?php endif; ?>
                    <?php if ( get_field('show_form_paragraph') && get_field('form_paragraph') ) : ?>
                        <p class="text-align"><?php the_field('form_paragraph'); ?></p>
                    <?php endif; ?>
                </div>
                <form>
                    <div class="form__content">
                        <div class="form__input">
                            <input class="name-input" type="text" placeholder="First Name" required>
                            <input class="adress-input" type="text" placeholder="Email Address" required>
                        </div>
                        <div class="form__button_wrapper">
                            <button class="btn-form btn" type="submit">Subscribe</button>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" id="agree" required>
                            <label for="agree">By subscribing, you agree to our <a href="<?php echo get_permalink( get_page_by_path('privacy-policy') ); ?>" target="_blank">Privacy Policy</a></label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
*/