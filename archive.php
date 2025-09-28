<?php
get_header();
?>

<main>
    <section class="hero section-decorated-light" id="hero">
        <div class="hero__bg-wrapper">
            <video class="hero__bg-video" autoplay muted loop playsinline>
                <source src="./assets/video/kids_quests_banner.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>

            <!-- <img class="hero__bg-image" src="./assets/images/blog-page/blog-hero.webp" alt="blog-hero background"> -->
        </div>

        <div class="hero__content">
            <h1 class="hero__title">Mystery Blog — Secrets Waiting at Home</h1>
            <p class="hero__subtitle">Discover tips, stories and magical ideas to turn your evenings into
                adventures.</p>
            <a href="#blogArticles" class="btn btn-hero">Read our news</a>
        </div>
    </section>
    <section class="blog-article__main section-common" id="blogArticles">
        <div class="blog__wrapper container">
            <h2 class="text-align">Our stories & Mysteries</h2>
            <div class="blog__items">
                <?php
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );

                $query = new WP_Query($args);
                if ($query->have_posts()) :
                    $i = 0;
                    while ($query->have_posts()) : $query->the_post();
                        $i++;
                        if ($i === 1) : ?>
                            <article class="blog__item blog-article__item">
                                <div class="blog__item-content">
                                    <a href="<?php the_permalink(); ?>" class="blog__item-image-main">
                                        <?php if (has_post_thumbnail()) {
                                            the_post_thumbnail('blog-large');
                                        } ?>
                                    </a>
                                    <div class="blog__item-descr">
                                        <a href="<?php the_permalink(); ?>">
                                            <h3 class="blog__item-title"><?php the_title(); ?></h3>
                                        </a>
                                        <p class="blog__item-date"><?php echo get_the_date('F j, Y'); ?></p>
                                        <p class="blog__item-text"><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
                                    </div>
                                </div>
                            </article>
                        <?php else : ?>
                            <article class="blog__item">
                                <div class="blog__item-content">
                                    <a href="<?php the_permalink(); ?>" class="blog__item-image">
                                        <?php if (has_post_thumbnail()) {
                                            the_post_thumbnail('blog-thumb');
                                        } ?>
                                    </a>
                                    <a href="<?php the_permalink(); ?>">
                                        <h3 class="blog__item-title"><?php the_title(); ?></h3>
                                    </a>
                                    <p class="blog__item-date"><?php echo get_the_date('F j, Y'); ?></p>
                                    <p class="blog__item-text"><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="blog__link">
                                    <span>READ MORE</span>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/learn-more-arrow.svg" alt="arrow right">
                                </a>
                            </article>
                <?php endif;
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>No posts found</p>';
                endif;
                ?>
            </div>
        </div>
    </section>
    <section class="form section-common" id="form">
        <div class="container">
            <div class="form__wrapper">
                <img class="form__image" src="./assets/images/printing form.png" alt="Printing form">
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
                            <label for="agree">By subscribing, you agree to our <a href="/privacy-policy.html"
                                    target="_blank">Privacy Policy</a></label>
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