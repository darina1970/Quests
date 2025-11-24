<?php
/*
Template Name: Blog
*/
get_header();
?>

<main>
    <?php get_template_part('hero'); ?>
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
    <!--Form-->
    <?php
    $has_image = get_field('show_form_image');
    $has_paragraph = get_field('show_form_paragraph');
    $with_quest = ($has_image || $has_paragraph) ? '1' : '0';
    ?>

    <section class="form section-common" id="form">
        <div class="container">
            <div class="form__wrapper" id="form-wrapper">

                <?php if (get_field('show_form_image')) :
                    $image = get_field('form_image');
                    if ($image) : ?>
                        <img class="form__image" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                <?php endif;
                endif; ?>

                <div class="form__text">
                    <?php if (get_field('form_title')) : ?>
                        <h2 class="text-align"><?php the_field('form_title'); ?></h2>
                    <?php endif; ?>

                    <?php if (get_field('form_subtitle')) : ?>
                        <h3 class="text-align"><?php the_field('form_subtitle'); ?></h3>
                    <?php endif; ?>

                    <?php if (get_field('show_form_paragraph') && get_field('form_paragraph')) : ?>
                        <p class="text-align"><?php the_field('form_paragraph'); ?></p>
                    <?php endif; ?>
                </div>

                <form method="post" action="" id="subscribe-form">
                    <div class="form__content">
                        <div class="form__input">
                            <input class="name-input" name="name" type="text" placeholder="First Name" required>
                            <input class="adress-input" name="email" type="email" placeholder="Email Address" required>
                        </div>
                        <div class="form__button_wrapper">
                            <button class="btn-form btn" type="submit">Subscribe</button>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" id="agree" required>
                            <label for="agree">
                                By subscribing, you agree to our
                                <a href="<?php echo get_permalink(get_page_by_path('privacy-policy')); ?>" target="_blank">Privacy Policy</a>
                            </label>
                        </div>
                        <input type="hidden" name="with_quest" value="<?php echo esc_attr($with_quest); ?>">
                    </div>
                </form>
                <div class="form-message" id="form-message" style="display: none;"></div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
?>