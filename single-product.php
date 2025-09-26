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
    <section class="product-tabs__info section-common" id="productTabsInfo">
        <div class="product-tabs__wrapper container">
            <div class="product-tabs">
                <div class="product-tab active" data-tab="reviews">Reviews</div>
                <div class="product-tab" data-tab="description">Description</div>
            </div>
            <div class="tab-content active" id="reviews">
                <div class="reviews-header">
                    <p class="reviews-header-title"><span id="reviewsCount">3 REVIEWS on </span><span class="review-game-name">Halloween Home Quest: Frank and his Spooky Gang (Ages 6-11)</span></p>
                    <button class="btn btn-form btn-review-form" id="writeReview">Write Your Review</button>
                </div>
                <div id="reviewMessage" class="review-message"></div>
                <div id="reviewForm" class="review-form">
                    <img class="form-bg" src="<?php echo get_template_directory_uri(); ?>/assets/images/product-page/piece-of-paper.png" alt="Form background">
                    <form id="customReviewForm" class="review-form__overlay" enctype="multipart/form-data">
                        <div class="rating__wrapper">
                            <p class="text-align">RATING *</p>
                            <div class="stars-input">
                                <span data-value="1"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/star-full.svg" alt="star one"></span>
                                <span data-value="2"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/star-full.svg" alt="star two"></span>
                                <span data-value="3"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/star-full.svg" alt="star three"></span>
                                <span data-value="4"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/star-full.svg" alt="star four"></span>
                                <span data-value="5"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/star-full.svg" alt="star five"></span>
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
                </div>
                <div class="reviews-list">
                    <?php
                    $comments = get_comments([
                        'post_id' => get_the_ID(),
                        'status'  => 'approve',
                        'type'    => 'review',
                        'order'   => 'DESC',
                    ]);

                    if ($comments) :
                        foreach ($comments as $comment) :
                            $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));
                            $photos = get_comment_meta($comment->comment_ID, 'review_photos', true);
                            if (!is_array($photos)) $photos = [];
                            ?>
                            <div class="review">
                                <div class="review-header">
                                    <div class="review-stars">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <?php if ($i <= $rating) : ?>
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/star-full.svg" alt="star">
                                            <?php else : ?>
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/star.svg" alt="star">
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="review-date"><?php echo get_comment_date('d/m/y', $comment); ?></p>
                                </div>

                                <div class="review__user-info">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/review-icon.svg" alt="user icon">
                                    <p><?php echo esc_html($comment->comment_author); ?></p>
                                </div>

                                <p><?php echo esc_html($comment->comment_content); ?></p>

                                <?php if ($photos) : ?>
                                    <div class="review-photos">
                                        <?php foreach ($photos as $photo) : ?>
                                            <img src="<?php echo esc_url($photo); ?>" alt="review photo">
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php
                                $replies = get_comments([
                                    'parent' => $comment->comment_ID,
                                    'status' => 'approve',
                                    'order'  => 'ASC',
                                ]);

                                if ($replies) :
                                    foreach ($replies as $reply) : ?>
                                        <div class="review review-reply">
                                            <div class="reply__author">
                                                <strong>QuestTime</strong>
                                            </div>
                                            <p><?php echo esc_html($reply->comment_content); ?></p>
                                            <p class="reply-date"><?php echo get_comment_date('d/m/y', $reply); ?></p>
                                        </div>
                                    <?php endforeach;
                                endif;
                                ?>
                            </div>
                        <?php endforeach;
                    else :
                        echo '<p>No reviews yet.</p>';
                    endif;
                    ?>
                </div>
                
            </div>
            <div class="tab-content" id="description">
                <div class="description__wrapper">
                    <?php 
                    global $product; 
                    echo wp_kses_post( $product->get_description() ); 
                    ?>
                </div>
            </div>
        </div>
    </section>
    <section class="product-recommend section-common" id="productRecommend">
        <div class="products__wrapper container">
            <h3 class="recommend-title">You may also like</h3>
            <div class="product-items__wrapper">
                <article class="product-card">
                    <a class="product-card__image" href="product.html">
                        <img src="./assets/images/card-1.webp" alt="Product card">
                    </a>
                    <div class="product-card__content">
                        <a class="product-card__title-link" href="product.html">
                            <h4 class="product-card__title">Frank and his Spooky Gang</h4>
                        </a>
                        <div class="product-card__meta">
                            <img class="product-card__icon" src="./assets/icons/user.svg" alt="User Icon">
                            <span class="product-card__age">6+</span>
                        </div>
                        <div class="product-card__bottom">
                            <span class="product-card__price">€20</span>
                            <a href="product.html" class="btn btn-card">Learn more</a>
                        </div>
                    </div>
                </article>
                <article class="product-card">
                    <a class="product-card__image" href="product.html">
                        <img src="./assets/images/card-2.webp" alt="Product card">
                    </a>
                    <div class="product-card__content">
                        <a class="product-card__title-link" href="product.html">
                            <h4 class="product-card__title">Frank and his Spooky Gang</h4>
                        </a>
                        <div class="product-card__meta">
                            <img class="product-card__icon" src="./assets/icons/user.svg" alt="User Icon">
                            <span class="product-card__age">6+</span>
                        </div>
                        <div class="product-card__bottom">
                            <span class="product-card__price">€20</span>
                            <a href="product.html" class="btn btn-card">Learn more</a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>