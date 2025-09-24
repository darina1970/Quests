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

    // Временный путь
    if (is_page_template('single-product.php')) {
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

