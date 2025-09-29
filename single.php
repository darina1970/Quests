<?php
get_header();
?>

<main>
    <section class="article section-common" id="article">
        <div class="article__wrapper container">
            <div class="blog-article__image">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/monogram.png" alt="monogram">
            </div>

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="article__main">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="article__main-image">
                                <?php the_post_thumbnail('blog-large'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="blog__item-descr">
                            <h3 class="blog__item-title"><?php the_title(); ?></h3>
                            <p class="blog__item-date"><?php echo get_the_date('F j, Y'); ?></p>
                            <div class="blog__item-text">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>

                    <a href=<?php echo site_url('/archive/'); ?> class="blog__link">
                        <span>BACK TO BLOG</span>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/learn-more-arrow.svg" alt="arrow right">
                    </a>
            <?php endwhile;
            endif; ?>
        </div>
    </section>

    <?php get_footer(); ?>


</main>

<?php
get_footer();
?>