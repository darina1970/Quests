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

// 1. Вывод кастомных полей (рейтинг и фото) в форме
add_action('comment_form_logged_in_after', 'custom_review_fields');
add_action('comment_form_after_fields', 'custom_review_fields');
function custom_review_fields() {
    ?>

    <div class="rating__wrapper">
        <p class="text-align">RATING *</p>
        <div class="stars-input">
            <span data-value="1"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/star-full.svg" alt="1"></span>
            <span data-value="2"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/star-full.svg" alt="2"></span>
            <span data-value="3"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/star-full.svg" alt="3"></span>
            <span data-value="4"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/star-full.svg" alt="4"></span>
            <span data-value="5"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/star-full.svg" alt="5"></span>
        </div>
        <input type="hidden" name="rating" id="ratingValue" value="0" required>
    </div>

    <div class="photo__wrapper">
        <p class="text-align">UPLOAD PHOTOS (up to 3)</p>
        <input type="file" name="review_photos[]" accept="image/*" multiple>
        <div id="photoPreview" class="photo-preview"></div>
    </div>
    <?php
}

// 2. Сохраняем кастомные поля (рейтинг и фото) при отправке отзыва
add_action('comment_post', function($comment_id, $comment_approved, $commentdata){
    // рейтинг
    if ( isset($_POST['rating']) ) {
        update_comment_meta($comment_id, 'rating', intval($_POST['rating']));
    }

    // фото
    if ( isset($_FILES['review_photos']) && !empty($_FILES['review_photos']['name'][0]) ) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $files = $_FILES['review_photos'];
        $attachments = [];
        for ($i=0; $i<count($files['name']); $i++) {
            if ($files['error'][$i] === 0) {
                $file_array = ['name'=>$files['name'][$i],'tmp_name'=>$files['tmp_name'][$i]];
                $upload = wp_handle_upload($file_array, ['test_form'=>false]);
                if (!isset($upload['error'])) $attachments[] = $upload['url'];
            }
        }
        if ($attachments) update_comment_meta($comment_id, 'review_photos', $attachments);
    }
}, 10, 3);

// 3. Вывод кастомной формы под твою верстку вместо стандартной
remove_action('woocommerce_review_before_comment_form', 'woocommerce_review_form', 10);
add_action('woocommerce_review_before_comment_form', function() {
    comment_form([
        'title_reply' => 'Write Your Review',
        'fields' => [
            'author' => '<div class="name__wrapper"><p class="text-align">YOUR NAME</p><input type="text" name="author" placeholder="Your name"></div>',
            'email'  => '<div class="email__wrapper"><p class="text-align">YOUR EMAIL</p><input type="email" name="email" placeholder="Your email"></div>'
        ],
        'comment_field' => '<div class="review__wrapper"><p class="text-align">REVIEW *</p><textarea name="comment" placeholder="Text your message here" required></textarea></div>
                            <div class="photo__wrapper">
                                <p class="text-align">UPLOAD PHOTOS (up to 3)</p>
                                <input type="file" name="review_photos[]" accept="image/*" multiple>
                            </div>',
        'submit_field' => '<p class="form-submit">%1$s %2$s</p>',
        'class_submit' => 'btn btn-form btn-review-form',
        'form_id'      => 'commentform',
        'class_form'   => 'custom-comment-form',
        'enctype'      => 'multipart/form-data', // вот это важно!
    ]);
});

