<?php
// Подключаем стили и скрипты
add_action('wp_enqueue_scripts', function () {

    // Общие стили
    wp_enqueue_style('questtime-style', get_stylesheet_uri());
    wp_enqueue_style('questtime-main', get_template_directory_uri() . '/assets/css/style.css', [], null);

    // JS для главной страницы
    if (is_front_page()) {
        wp_enqueue_script('questtime-home', get_template_directory_uri() . '/assets/js/main.js', [], null, true);
    }

    // JS для страницы продукта
    if (is_singular('product')) {
        wp_enqueue_script('questtime-product', get_template_directory_uri() . '/assets/js/product.js', [], null, true);
    }

    // JS для 404 страницы
    if (is_404()) {
        wp_enqueue_script('questtime-404', get_template_directory_uri() . '/assets/js/404.js', [], null, true);
    }
});

add_theme_support('custom-logo');
add_theme_support('post-thumbnails');
add_theme_support('title-tag');

// Включаем поддержку WooCommerce
function questtime_add_woocommerce_support() {
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'questtime_add_woocommerce_support');
add_action('woocommerce_after_add_to_cart_button', function() {
    global $product;

    // Получаем ID текущего товара
    $product_id = $product->get_id();

    // Проверяем корзину на наличие этого товара
    $in_cart = false;
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        if ( $cart_item['product_id'] == $product_id ) {
            $in_cart = true;
            break;
        }
    }

    // Если товар в корзине, показываем кнопку
    if ( $in_cart ) {
        echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="btn btn-go-cart" style="margin-left:10px;">Go to Cart</a>';
    }
});

// Убираем стандартные стили WooCommerce (чтобы они не ломали верстку)
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// AJAX-обновление иконки корзины в хедере
add_filter('woocommerce_add_to_cart_fragments', function($fragments) {
    ob_start(); ?>
    <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php
    $fragments['.cart-count'] = ob_get_clean();
    return $fragments;
});

add_filter( 'woocommerce_product_single_add_to_cart_text', function() {
    return __( 'Add to Cart', 'woocommerce' );
});

add_filter('locale', function($locale) {
    if (is_admin()) {
        return $locale;
    }

    if (is_woocommerce() || is_cart() || is_checkout() || is_account_page() || is_product()) {
        return 'en_US';
    }

    return $locale;
});
// Убираем хлебные крошки WooCommerce
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
