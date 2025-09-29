<section class="hero section-decorated-light" id="hero">
  <div class="hero__bg-wrapper">
    <?php if (get_field('hero_background_type') === 'video' && get_field('hero_background_video')) : ?>
      <video class="hero__bg-video" autoplay muted loop playsinline>
        <source src="<?php echo esc_url(get_field('hero_background_video')); ?>" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    <?php elseif (get_field('hero_background_image')) : ?>
      <img class="hero__bg-image" src="<?php echo esc_url(get_field('hero_background_image')['url']); ?>" alt="<?php echo esc_attr(get_field('hero_background_image')['alt']); ?>">
    <?php endif; ?>
  </div>

  <div class="hero__content">
    <?php if (get_field('hero_title')) : ?>
      <h1 class="hero__title"><?php echo esc_html(get_field('hero_title')); ?></h1>
    <?php endif; ?>

    <?php if (get_field('hero_subtitle')) : ?>
      <p class="hero__subtitle"><?php echo esc_html(get_field('hero_subtitle')); ?></p>
    <?php endif; ?>

    <?php if (get_field('hero_button_url') && get_field('hero_button_text')) : ?>
      <a href="<?php echo esc_url(get_field('hero_button_url')); ?>" class="btn btn-hero">
        <?php echo esc_html(get_field('hero_button_text')); ?>
      </a>
    <?php endif; ?>
  </div>
</section>
