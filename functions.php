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

// Регистрируем кастомный тип записей "Отзывы"
function register_reviews_cpt() {
    register_post_type('review', array(
        'labels' => array(
            'name' => 'Отзывы',
            'singular_name' => 'Отзыв',
            'add_new' => 'Добавить отзыв',
            'add_new_item' => 'Добавить новый отзыв',
            'edit_item' => 'Редактировать отзыв',
            'new_item' => 'Новый отзыв',
            'view_item' => 'Просмотреть отзыв',
            'search_items' => 'Искать отзывы',
            'not_found' => 'Не найдено',
            'not_found_in_trash' => 'В корзине не найдено',
        ),
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-format-chat',
        'supports' => array('title', 'editor'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'register_reviews_cpt');

// === WooCommerce настройка ===

// Включаем поддержку WooCommerce
function questtime_add_woocommerce_support() {
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'questtime_add_woocommerce_support');

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

// (Опционально) Убираем хлебные крошки WooCommerce, если не нужны
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
