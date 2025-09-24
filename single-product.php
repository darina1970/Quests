<?php
get_header();

// Получаем объект продукта
global $post;
$product = wc_get_product( $post->ID );

if ( ! $product ) {
    echo '<p>Товар не найден</p>';
    get_footer();
    exit;
}

$attachment_ids = $product->get_gallery_image_ids();
$main_image_url = $product->get_image_id() ? wp_get_attachment_url($product->get_image_id()) : '';
?>

<main>
    <section class="product-hero section-common" id="hero">
        <div class="product-hero__wrapper container">
            <div class="product-hero__slider">
                <div class="product__main-image">
                    <?php if ($main_image_url): ?>
                        <img id="current" src="<?php echo esc_url($main_image_url); ?>" alt="Main image">
                    <?php endif; ?>
                </div>
                <div class="images-slider">
                    <?php if ($main_image_url): ?>
                        <img src="<?php echo esc_url($main_image_url); ?>" class="img active" alt="img">
                    <?php endif; ?>
                    <?php foreach ($attachment_ids as $id): ?>
                        <img src="<?php echo wp_get_attachment_url($id); ?>" class="img" alt="img">
                    <?php endforeach; ?>
                </div>
                <!-- стрелки -->
                <div class="slider-arrows">
                    <button class="left-arrow"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/left-arrow.png" alt="left-arrow"></button>
                    <button class="right-arrow"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/right-arrow.png" alt="right-arrow"></button>
                </div>
            </div>
            <!-- Лайтбокс -->
            <div id="lightbox" class="lightbox">
                <span class="btn-close">&times;</span>
                <span class="btn-prev">&#10094;</span>
                <img class="lightbox-content" id="lightbox-img" alt="lightbox">
                <span class="btn-next">&#10095;</span>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>