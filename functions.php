<?php

// Подключаем стили и скрипты
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('questtime-style', get_stylesheet_uri());
    wp_enqueue_style('questtime-main', get_template_directory_uri() . '/assets/css/style.css', [], null);

    if (is_front_page()) {
        wp_enqueue_script('questtime-home', get_template_directory_uri() . '/assets/js/main.js', [], null, true);
        wp_localize_script('questtime-home', 'woocommerce_params', [
            'ajax_url'   => admin_url('admin-ajax.php'),
        ]);
    }

    if (is_singular('product')) {
        wp_enqueue_script('questtime-product', get_template_directory_uri() . '/assets/js/product.js', ['jquery'], null, true);

        // Передаём AJAX и ID товара в JS
        wp_localize_script('questtime-product', 'woocommerce_params', [
            'ajax_url'   => admin_url('admin-ajax.php'),
            'product_id' => get_the_ID(),
        ]);
    }

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

// Поддержка WooCommerce
function questtime_add_woocommerce_support()
{
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'questtime_add_woocommerce_support');

// Убираем стандартные стили WooCommerce
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// AJAX обновление корзины
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    ob_start(); ?>
    <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
<?php
    $fragments['.cart-count'] = ob_get_clean();
    return $fragments;
});

add_filter('locale', function ($locale) {
    if (is_admin()) {
        return $locale;
    }

    if (is_woocommerce() || is_cart() || is_checkout() || is_account_page() || is_product()) {
        return 'en_US';
    }

    return $locale;
});

// Кастомная форма отзывов
remove_action('woocommerce_review_before_comment_form', 'woocommerce_review_form', 10);
add_action('woocommerce_review_before_comment_form', function () { ?>
    <form id="customReviewForm" class="review-form__overlay" enctype="multipart/form-data">
        <div class="rating__wrapper">
            <p class="text-align">RATING *</p>
            <div class="stars-input">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span data-value="<?php echo $i; ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/star-full.svg" alt="star <?php echo $i; ?>">
                    </span>
                <?php endfor; ?>
            </div>
            <input type="hidden" id="reviewRating" name="reviewRating" value="0">
        </div>

        <div class="review__wrapper">
            <p class="text-align">REVIEW *</p>
            <textarea class="review-text" name="reviewText" id="reviewText" placeholder="Text your message here"></textarea>
        </div>

        <div class="photo__wrapper">
            <p class="text-align">UPLOAD PHOTOS</p>
            <div class="file-upload">
                <input type="file" id="reviewPhotos" name="reviewPhotos[]" accept="image/*" multiple>
                <label for="reviewPhotos" class="btn btn-form cursor-scale">Выбрать файлы</label>
            </div>
            <div id="photoPreview" class="photo-preview"></div>
            <p id="photoError" class="photo-error" style="color: red; margin-top: 5px;"></p>
        </div>

        <div class="name__wrapper">
            <p class="text-align">YOUR NAME</p>
            <input type="text" id="reviewName" name="reviewName" placeholder="Your name">
        </div>

        <div class="email__wrapper">
            <p class="text-align">YOUR EMAIL</p>
            <input type="email" id="reviewEmail" name="reviewEmail" placeholder="Your email">
        </div>

        <p class="text-align notion">Your email address will not be published. Required fields are marked *</p>
        <button type="submit" class="btn btn-form btn-review-form" id="submitReview">Submit Your Review</button>
    </form>
<?php });

// AJAX обработка отправки отзыва
add_action('wp_ajax_submit_custom_review', 'handle_custom_review');
add_action('wp_ajax_nopriv_submit_custom_review', 'handle_custom_review');

function handle_custom_review()
{
    // Проверка Product ID
    if (!isset($_POST['product_id'])) wp_send_json_error("Product ID missing.");
    $product_id = intval($_POST['product_id']);

    // Сбор и очистка данных
    $author  = sanitize_text_field($_POST['reviewName'] ?? '');
    $email   = sanitize_email($_POST['reviewEmail'] ?? '');
    $content = sanitize_textarea_field($_POST['reviewText'] ?? '');
    $rating  = intval($_POST['reviewRating'] ?? 0);

    if (!$content || !$rating) wp_send_json_error("Please fill in review and rating.");

    // Вставляем комментарий (отзыв)
    $commentdata = [
        'comment_post_ID'      => $product_id,
        'comment_author'       => $author,
        'comment_author_email' => $email,
        'comment_content'      => $content,
        'comment_type'         => 'review',
        'comment_approved'     => 0,
    ];
    $comment_id = wp_insert_comment($commentdata);
    if (!$comment_id) wp_send_json_error("Can't insert comment.");

    // Сохраняем рейтинг
    update_comment_meta($comment_id, 'rating', $rating);

    // --- Обработка файлов ---
    if (!empty($_FILES['reviewPhotos']['name'][0])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $files = $_FILES['reviewPhotos'];
        $attachments = [];

        foreach ($files['name'] as $key => $value) {
            if ($files['error'][$key] === 0) {
                $file = [
                    'name'     => $files['name'][$key],
                    'type'     => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error'    => $files['error'][$key],
                    'size'     => $files['size'][$key] ?? 0,
                ];

                $upload = wp_handle_upload($file, ['test_form' => false]);

                if (!isset($upload['error']) && isset($upload['url'])) {

                    $attachments[] = $upload['url'];
                } else {
                    error_log('Upload error: ' . ($upload['error'] ?? 'unknown'));
                }
            }
        }

        if (!empty($attachments)) {
            update_comment_meta($comment_id, 'review_photos', $attachments);
        }
    }

    wp_send_json_success("Review submitted.");
}
// Вывод всех отзывов с фото и рейтингом
function render_reviews_list()
{ ?>
    <div class="reviews-list">
        <?php
        // Берём только отзывы WooCommerce
        $comments = get_comments([
            'post_id' => get_the_ID(),
            'status' => 'approve',
            'type' => 'review',
            'order' => 'DESC',
        ]);

        if ($comments) {
            foreach ($comments as $comment):

                // Получаем рейтинг
                $rating = get_comment_meta($comment->comment_ID, 'rating', true);
                $rating = intval($rating);
                if ($rating < 0) $rating = 0;
                if ($rating > 5) $rating = 5;

                // Получаем фото
                $photos = get_comment_meta($comment->comment_ID, 'review_photos', true);
                if (!is_array($photos)) $photos = [];

        ?>
                <div class="review">
                    <div class="review-header">
                        <div class="review-stars">
                            <?php
                            for ($i = 1; $i <= 5; $i++):
                                $star_class = ($i <= $rating) ? 'selected' : '';
                                echo '<img src="' . get_template_directory_uri() . '/assets/icons/star-full.svg" class="' . $star_class . '" alt="star">';
                            endfor;
                            ?>
                        </div>
                        <p class="review-date"><?php echo get_comment_date('d/m/y', $comment); ?></p>
                    </div>

                    <div class="review__user-info">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/review-icon.svg" alt="user icon">
                        <p><?php echo esc_html($comment->comment_author); ?></p>
                    </div>

                    <p><?php echo esc_html($comment->comment_content); ?></p>

                    <?php if ($photos): ?>
                        <div class="review-photos">
                            <?php foreach ($photos as $photo): ?>
                                <img src="<?php echo esc_url($photo); ?>" alt="review photo">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
        <?php endforeach;
        } else {
            echo '<p>No reviews yet.</p>';
        } ?>
    </div>
    <?php }

// Добавляем метабокс для отзывов WooCommerce
add_action('add_meta_boxes', function () {
    add_meta_box(
        'wc_review_photos',
        'Review Photos',
        'render_wc_review_photos_metabox',
        'comment',
        'normal',
        'high'
    );
});

// Callback метабокса
function render_wc_review_photos_metabox($comment)
{
    if ($comment->comment_type !== 'review') return;

    $photos = get_comment_meta($comment->comment_ID, 'review_photos', true);
    echo '<div style="display:flex;gap:10px;flex-wrap:wrap;">';
    if ($photos && is_array($photos)) {
        foreach ($photos as $photo) {
            echo '<img src="' . esc_url($photo) . '" style="width:80px;height:80px;object-fit:cover;border:1px solid #ccc;">';
        }
    } else {
        echo '<p>No photos uploaded.</p>';
    }
    echo '</div>';
}

add_action('wp_ajax_filter_products', 'filter_products_callback');
add_action('wp_ajax_nopriv_filter_products', 'filter_products_callback');

function filter_products_callback()
{
    $args = [
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'tax_query'      => ['relation' => 'AND'],
    ];

    // Фильтр по возрасту (атрибут pa_age)
    if (!empty($_POST['age'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'pa_age',
            'field'    => 'name',
            'terms'    => sanitize_text_field($_POST['age']),
        ];
    }

    // Фильтр по теме (атрибут pa_theme)
    if (!empty($_POST['theme'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'pa_theme',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_POST['theme']),
        ];
    }

    // Сортировка
    if (!empty($_POST['sort'])) {
        if ($_POST['sort'] === 'asc') {
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
            $args['meta_key'] = '_price';
        } elseif ($_POST['sort'] === 'desc') {
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            $args['meta_key'] = '_price';
        }
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            global $product;
    ?>

            <article class="product-card">
                <a class="product-card__image" href="<?php the_permalink(); ?>">
                    <?php echo woocommerce_get_product_thumbnail('medium'); ?>
                </a>
                <div class="product-card__content">
                    <a class="product-card__title-link" href="<?php the_permalink(); ?>">
                        <h4 class="product-card__title"><?php the_title(); ?></h4>
                    </a>
                    <div class="product-card__middle">
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
                            <p>(<?php echo $reviews_count; ?> reviews)</p>
                        </div>
                        <div class="product-card__meta">
                            <span>Age: </span>
                            <span>
                                <?php
                                $age = $product->get_attribute('age');
                                echo $age ? esc_html($age) : '—';
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="product-card__bottom">
                        <?php if ($product->is_on_sale()) : ?>
                            <span class="product-card__price">
                                €<?php echo $product->get_sale_price(); ?>
                                <span class="old-price">€<?php echo $product->get_regular_price(); ?></span>
                            </span>
                        <?php else : ?>
                            <span class="product-card__price">
                                €<?php echo $product->get_regular_price(); ?>
                            </span>
                        <?php endif; ?>

                        <a href="<?php echo get_permalink($product->get_id()); ?>" class="btn btn-card">Learn more</a>
                    </div>
                </div>
            </article>

<?php
        }
    } else {
        echo '<p>No products found.</p>';
    }

    wp_die();
}

// Регистрация кастомного типа записи "Слайдер"
add_action('after_setup_theme', function() {
    add_theme_support('post-thumbnails');
});

add_action('init', function() {
    register_post_type('gallery_slider', array(
        'labels' => array(
            'name'          => 'Слайдер',
            'singular_name' => 'Фото',
            'add_new'       => 'Добавить фото',
            'add_new_item' => 'Добавить новое фото',
        ),
        'public'      => true,
        'menu_icon'   => 'dashicons-format-gallery',
        'supports'    => array('title', 'thumbnail'),
        'show_in_rest'=> true,
    ));
});


// Форма подписки
add_action('wp_ajax_form_subscribe_ajax', 'form_subscribe_handle');
add_action('wp_ajax_nopriv_form_subscribe_ajax', 'form_subscribe_handle');

function form_subscribe_handle() {
    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $with_quest = isset($_POST['with_quest']) && $_POST['with_quest'] === '1' ? 'yes' : 'no';

    // Отправка в Brevo
    if (function_exists('send_subscriber_to_brevo')) {
        send_subscriber_to_brevo(
            $email,
            $name,
            $with_quest === 'yes'
        );
    }

    // Ответ в JS
    wp_send_json_success([
        'with_quest' => $with_quest
    ]);
}

function send_subscriber_to_brevo($email, $name = '', $with_quest = false) {
    $api_key = defined('BREVO_API_KEY') ? constant('BREVO_API_KEY') : null;
    $group_id_quest = defined('BREVO_GROUP_ID_QUEST') ? constant('BREVO_GROUP_ID_QUEST') : null;
    $group_id_no_quest = defined('BREVO_GROUP_ID_NO_QUEST') ? constant('BREVO_GROUP_ID_NO_QUEST') : null;

    if (!$api_key || !$group_id_quest || !$group_id_no_quest) {
        return false;
    }

    $group_id = $with_quest ? $group_id_quest : $group_id_no_quest;

    $body = [
        'email' => $email,
        'attributes' => [
            'FIRSTNAME'  => $name,
            'WITH_QUEST' => $with_quest ? 'true' : 'false'
        ],
        'listIds' => [(int) $group_id],
        'updateEnabled' => true
    ];

    $response = wp_remote_post('https://api.brevo.com/v3/contacts', [
        'headers' => [
            'Content-Type' => 'application/json',
            'api-key'      => $api_key,
        ],
        'body' => json_encode($body),
    ]);

    if (is_wp_error($response)) {
        return false;
    }

    $code = wp_remote_retrieve_response_code($response);

    // С этой ошибкой письмо отправляется
    if (!in_array($code, [200, 201, 204])) {
        return false;
    }

    return true;
}

// Вывод вопросов и ответов блока FAQ
function register_faq_post_type()
{
    register_post_type('faq', [
        'labels' => [
            'name' => 'FAQs',
            'singular_name' => 'FAQ',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New FAQ',
            'edit_item' => 'Edit FAQ',
            'new_item' => 'New FAQ',
            'view_item' => 'View FAQ',
            'search_items' => 'Search FAQs',
            'not_found' => 'No FAQs found',
            'menu_name' => 'FAQ',
        ],
        'public' => true,
        'has_archive' => false,
        'rewrite' => ['slug' => 'faq'],
        'menu_position' => 5,
        'menu_icon' => 'dashicons-editor-help',
        'supports' => ['title'],
    ]);
}
add_action('init', 'register_faq_post_type');

