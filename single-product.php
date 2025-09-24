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

                <div class="slider-arrows">
                    <button class="left-arrow"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/left-arrow.png" alt="left-arrow"></button>
                    <button class="right-arrow"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/right-arrow.png" alt="right-arrow"></button>
                </div>
            </div>

            <div id="lightbox" class="lightbox">
                <span class="btn-close">&times;</span>
                <span class="btn-prev">&#10094;</span>
                <img class="lightbox-content" id="lightbox-img" alt="lightbox">
                <span class="btn-next">&#10095;</span>
            </div>

            <div class="product-hero__description">
                <div class="product-hero__text">
                    <h3 class="product-hero__title">
                        <span><?php echo esc_html($product->get_attribute('theme')); ?> Home Quests: </span>
                        <span><?php echo $product->get_name(); ?> (Ages <?php echo $product->get_attribute('age'); ?>)</span>
                    </h3>

                    <div class="rating-info">
                        <div class="rating-stars">
                            <?php
                            $rating = (float) $product->get_average_rating();
                            $reviews_count = $product->get_review_count();
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= floor($rating)) {
                                    echo '<img src="' . get_template_directory_uri() . '/assets/icons/star-full.svg" alt="star">';
                                } else {
                                    echo '<img src="' . get_template_directory_uri() . '/assets/icons/star.svg" alt="star">';
                                }
                            }
                            ?>
                        </div>
                        <a href="#reviews">(<?php echo $reviews_count; ?> reviews)</a>
                    </div>

                    <p>
                        <strong>Price:</strong> 
                        <?php if ($product->is_on_sale()): ?>
                            €<?php echo $product->get_sale_price(); ?>
                            <span class="old-price">€<?php echo $product->get_regular_price(); ?></span>
                        <?php else: ?>
                            €<?php echo $product->get_regular_price(); ?>
                        <?php endif; ?>
                    </p>
                    <p><strong>Get a ready-made <?php echo esc_html($product->get_attribute('theme')); ?> quest for your child!</strong></p>

                    <?php
                    $features = get_post_meta($product->get_id(), 'features', true);
                    if ($features) {
                        $items = explode("\n", $features);
                        echo '<ul>';
                        foreach ($items as $item) {
                            $item = trim($item);
                            if ($item) {
                                echo '<li><img src="' . get_template_directory_uri() . '/assets/icons/tick.svg" alt="tick"> ' . esc_html($item) . '</li>';
                            }
                        }
                        echo '</ul>';
                    }
                    ?>

                    <p><strong>Recommended age:</strong> <?php echo esc_html($product->get_attribute('age')); ?> years</p>
                    <p><strong>Number of players:</strong> <?php echo esc_html($product->get_attribute('players')); ?></p>
                    <p><strong>Duration:</strong> ~<?php echo esc_html($product->get_attribute('duration')); ?> minutes</p>

                    <div class="product-hero__add-to-cart">
                        <?php woocommerce_template_single_add_to_cart(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>