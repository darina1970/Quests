<?php 
$page_id = get_the_ID();

$background_type  = strtolower(trim(get_field('hero_background_type', $page_id)));
$background_video = get_field('hero_background_video', $page_id); // URL
$background_image = get_field('hero_background_image', $page_id); // Массив
$hero_title       = get_field('hero_title', $page_id);
$hero_subtitle    = get_field('hero_subtitle', $page_id);
$hero_button_url  = get_field('hero_button_url', $page_id);
$hero_button_text = get_field('hero_button_text', $page_id);
?>

<section class="hero section-decorated-light" id="hero">
  <div class="hero__bg-wrapper">
    <?php if ($background_type === 'video' && $background_video) : ?>
      <video class="hero__bg-video" autoplay muted loop playsinline>
        <source src="<?php echo esc_url($background_video); ?>" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    <?php elseif ($background_image && isset($background_image['url'])) : ?>
      <img class="hero__bg-image" src="<?php echo esc_url($background_image['url']); ?>" alt="<?php echo esc_attr($background_image['alt']); ?>">
    <?php else : ?>
      <div class="hero__bg-fallback" style="background-color: #000;"></div>
    <?php endif; ?>
  </div>

  <div class="hero__content">
    <?php if ($hero_title) : ?>
      <h1 class="hero__title"><?php echo esc_html($hero_title); ?></h1>
    <?php endif; ?>

    <?php if ($hero_subtitle) : ?>
      <p class="hero__subtitle"><?php echo esc_html($hero_subtitle); ?></p>
    <?php endif; ?>

    <?php if ($hero_button_url && $hero_button_text) : ?>
      <a href="<?php echo esc_url($hero_button_url); ?>" class="btn btn-hero">
        <?php echo esc_html($hero_button_text); ?>
      </a>
    <?php endif; ?>
  </div>
</section>