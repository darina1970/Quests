<?php
get_header();
?>

<main>
    <section class="hero section-decorated-light" id="hero">
        <img class="hero__bg" src="<?php echo get_template_directory_uri(); ?>/assets/images/hero.webp">
        <div class="hero__content">
            <h1 class="hero__title">Unlock the Secrets Hidden at Home</h1>
            <p class="hero__subtitle">The mystery begins right at your doorstep</p>
            <a href="#products" class="btn btn-hero">See Our Products</a>
        </div>
    </section>
    <section class="products section-common" id="products">
        <div class="products__wrapper container">
            <h2 class="text-align">Our Home mysteries</h2>
            <div class="products__controls">
                <div class="products__filters">
                    <span class="products__filters-label">Filter:</span>
                    <label for="filter-age">Age</label>
                    <select name="filter-age" id="filter-age">
                        <option value="">All</option>
                        <?php
                            $ages = get_terms([
                                'taxonomy'   => 'pa_age',
                                'hide_empty' => true,
                            ]);
                            if (!empty($ages) && !is_wp_error($ages)) {
                                foreach ($ages as $age) {
                                    echo '<option value="' . esc_attr($age->name) . '">' . esc_html($age->name) . '</option>';
                                }
                            }
                        ?>
                    </select>
                    <div class="filter-theme__wrapper">
                        <label for="filter-theme">Theme</label>
                        <select name="filter-theme" id="filter-theme">
                            <option value="">All</option>
                            <?php
                                $themes = get_terms([
                                    'taxonomy'   => 'pa_theme',
                                    'hide_empty' => true,
                                ]);
                                if (!empty($themes) && !is_wp_error($themes)) {
                                    foreach ($themes as $theme) {
                                        echo '<option value="' . esc_attr($theme->slug) . '">' . esc_html($theme->name) . '</option>';
                                    }
                                }
                                ?>
                        </select>
                    </div>
                </div>
                <div class="products__sort">
                    <span class="products__filters-label">Sort by:</span>
                    <label for="sort-price">Price</label>
                    <select name="sort-price" id="sort-price">
                        <option value="asc">Low → High</option>
                        <option value="desc">High → Low</option>
                    </select>
                </div>
            </div>
            <div class="product-items__wrapper">
                <?php
                
                $args = [
                    'post_type'      => 'product',
                    'posts_per_page' => -1,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ];

                $loop = new WP_Query($args);

                if ($loop->have_posts()) :
                    while ($loop->have_posts()) : $loop->the_post();
                        global $product; ?>
                        
                        <article class="product-card">
                            <a class="product-card__image" href="<?php the_permalink(); ?>">
                                <?php if (has_post_thumbnail()) {
                                    the_post_thumbnail('medium');
                                } ?>
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
                                    <?php if ( $product->is_on_sale() ) : ?>
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
                        
                    <?php endwhile;
                else :
                    echo '<p>No products found</p>';
                endif;

                wp_reset_postdata(); 
                
                ?>
            </div>
        </div>
    </section>
    <?php
    $reviews = new WP_Query(array(
        'post_type' => 'review',
        'posts_per_page' => -1
    ));

    if ($reviews->have_posts()) :
    ?>
    <section class="reviews section-decorated-dark section-common" id="reviews">
        <div class="container">
            <h2 class="text-align">REVIEWS</h2>
            <div class="reviews__slider-wrapper">
                <div class="reviews__slider">
                    <?php while ($reviews->have_posts()) : $reviews->the_post(); ?>
                        <div class="review-card">
                            <div class="review-card__stars">★★★★★</div>
                            <div class="review-card__author"><?php the_title(); ?></div>
                            <p class="review-card__text"><?php the_content(); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; wp_reset_postdata(); ?>
    <section class="blog section-special" id="blog">
        <div class="blog__wrapper container">
            <h2 class="text-align">Blog Posts</h2>
            <div class="blog__items">
                <article class="blog__item">
                    <a href="article.html" class="blog__item-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/card-8.webp" alt="article">
                    </a>
                    <a href="article.html">
                        <h3 class="blog__item-title">How to host a home quest party</h3>
                    </a>
                    <p class="blog__item-date">August 28, 2025</p>
                    <p class="blog__item-text">Bring the thrill of adventure right into your living room! Hosting a
                        home quest party is a
                        fun and creative way to gather friends and family, solve puzzles together, and turn an
                        ordinary evening into an unforgettable mystery.</p>
                </article>
                <article class="blog__item">
                    <a href="article.html" class="blog__item-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/card-8.webp" alt="article">
                    </a>
                    <a href="article.html">
                        <h3 class="blog__item-title">How to host a home quest party</h3>
                    </a>
                    <p class="blog__item-date">August 28, 2025</p>
                    <p class="blog__item-text">Bring the thrill of adventure right into your living room! Hosting a
                        home quest party is a
                        fun and creative way to gather friends and family, solve puzzles together, and turn an
                        ordinary evening into an unforgettable mystery.</p>
                </article>
                <article class="blog__item">
                    <a href="article.html" class="blog__item-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/card-8.webp" alt="article">
                    </a>
                    <a href="article.html">
                        <h3 class="blog__item-title">How to host a home quest party</h3>
                    </a>
                    <p class="blog__item-date">August 28, 2025</p>
                    <p class="blog__item-text">Bring the thrill of adventure right into your living room! Hosting a
                        home quest party is a
                        fun and creative way to gather friends and family, solve puzzles together, and turn an
                        ordinary evening into an unforgettable mystery.</p>
                </article>
            </div>
            <a href="blog.html" class="blog__link">
                <span>Learn more in our Blog</span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/learn-more-arrow.svg" alt="arrow right">
            </a>
        </div>
    </section>
    <section class="gallery-slider" id="gallery-slider">
        <div class="gallery-slider__wrapper">
            <div class="slider-track" id="slider-track">
                <div class="slide"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-page/quest1-1.webp" alt="Product image" /></div>
                <div class="slide"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-page/quest1-2.webp" alt="Product image" /></div>
                <div class="slide"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-page/quest1-3.webp" alt="Product image" /></div>
                <div class="slide"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-page/quest1-4.webp" alt="Product image" /></div>
                <div class="slide"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-page/quest1-5.webp" alt="Product image" /></div>
                <div class="slide"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-page/quest1-6.webp" alt="Product image" /></div>
            </div>
        </div>
    </section>
    <section class="faq section-common" id="faq">
        <div class="faq__wrapper">
            <h2 class="text-align">Frequently Asked Questions</h2>
            <div class="faq-item">
                <div class="faq-question__wrapper">
                    <div class="faq-question cursor-scale">
                        <img class="faq-icon" src="<?php echo get_template_directory_uri(); ?>/assets/icons/question.svg" alt="question icon">
                        <span class="faq-text">How will I receive the quest after purchase?</span>
                    </div>
                    <img class="faq-arrow cursor-scale" src="<?php echo get_template_directory_uri(); ?>/assets/icons/arrow-down.svg" alt="arrow-down">
                </div>
                <div class="faq-answer">
                    <p>After purchase, the quest will be sent to the email address you provided.</p>
                    <div class="faq-video">
                        <iframe width="100%" height="200"
                            src="<?php echo get_template_directory_uri(); ?>/assets/6049036_Birthday_Birthday_Party_1280x720.mp4" title="FAQ Video"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question__wrapper">
                    <div class="faq-question cursor-scale">
                        <img class="faq-icon" src="<?php echo get_template_directory_uri(); ?>/assets/icons/question.svg" alt="question icon">
                        <span class="faq-text">How will I receive the quest after purchase?</span>
                    </div>
                    <img class="faq-arrow cursor-scale" src="<?php echo get_template_directory_uri(); ?>/assets/icons/arrow-down.svg" alt="arrow-down">
                </div>
                <div class="faq-answer">
                    <p>After purchase, the quest will be sent to the email address you provided.</p>
                    <div class="faq-video">
                        <iframe width="100%" height="200"
                            src="<?php echo get_template_directory_uri(); ?>/assets/6049036_Birthday_Birthday_Party_1280x720.mp4" title="FAQ Video"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question__wrapper">
                    <div class="faq-question cursor-scale">
                        <img class="faq-icon" src="<?php echo get_template_directory_uri(); ?>/assets/icons/question.svg" alt="question icon">
                        <span class="faq-text">How will I receive the quest after purchase?</span>
                    </div>
                    <img class="faq-arrow cursor-scale" src="<?php echo get_template_directory_uri(); ?>/assets/icons/arrow-down.svg" alt="arrow-down">
                </div>
                <div class="faq-answer">
                    <p>After purchase, the quest will be sent to the email address you provided.</p>
                    <div class="faq-video">
                        <iframe width="100%" height="200"
                            src="<?php echo get_template_directory_uri(); ?>/assets/6049036_Birthday_Birthday_Party_1280x720.mp4" title="FAQ Video"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question__wrapper">
                    <div class="faq-question cursor-scale">
                        <img class="faq-icon" src="<?php echo get_template_directory_uri(); ?>/assets/icons/question.svg" alt="question icon">
                        <span class="faq-text">How will I receive the quest after purchase?</span>
                    </div>
                    <img class="faq-arrow cursor-scale" src="<?php echo get_template_directory_uri(); ?>/assets/icons/arrow-down.svg" alt="arrow-down">
                </div>
                <div class="faq-answer">
                    <p>After purchase, the quest will be sent to the email address you provided.</p>
                    <div class="faq-video">
                        <iframe width="100%" height="200"
                            src="<?php echo get_template_directory_uri(); ?>/assets/6049036_Birthday_Birthday_Party_1280x720.mp4" title="FAQ Video"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="form section-common" id="form">
        <div class="container">
            <div class="form__wrapper">
                <img class="form__image" src="<?php echo get_template_directory_uri(); ?>/assets/images/printing form.png" alt="Printing form">
                <div class="form__text">
                    <h2 class="text-align">Keep Up with QuestTime</h2>
                    <h3 class="text-align">Subscribe to our Newsletter</h3>
                    <p class="text-align">Get a free quest</p>
                </div>
                <form>
                    <div class="form__content">
                        <div class="form__input">
                            <input class="name-input" type="text" placeholder="First Name" required>
                            <input class="adress-input" type="text" placeholder="Email Address" required>
                        </div>
                        <div class="form__button_wrapper">
                            <button class="btn-form btn" type="submit">Subscribe</button>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" id="agree" required>
                            <label for="agree">By subscribing, you agree to our <a href="<?php echo get_permalink( get_page_by_path('privacy-policy') ); ?>" target="_blank">Privacy Policy</a></label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
?>