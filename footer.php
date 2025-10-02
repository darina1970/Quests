<footer class="footer">
    <div class="footer__wrapper container">
        <div class="logo">
            <a href="<?php echo esc_url( home_url('/') ); ?>">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/logo.svg' ); ?>" alt="logo" />
            </a>
        </div>
        <div class="footer__info">
            <nav class="footer__nav">
                <?php
                if ( is_front_page() ) : ?>
                    <ul class="footer__nav-list">
                        <li class="nav-list__item"><a href="#products">Our Products</a></li>
                        <li class="nav-list__item"><a href="#reviews">Reviews</a></li>
                        <li class="nav-list__item">
                            <a href="<?php echo esc_url( get_permalink( get_option('page_for_posts') ) ); ?>">Blog</a>
                        </li>
                        <li class="nav-list__item"><a href="#faq">FAQ</a></li>
                    </ul>
                <?php elseif (is_singular('product') || is_singular('post')) : ?>
                    <ul class="footer__nav-list">
                        <li class="nav-list__item"><a href="<?php echo esc_url( home_url('/') ); ?>">Home</a></li>
                        <li class="nav-list__item"><a href="<?php echo esc_url( get_permalink(get_option('page_for_posts')) ); ?>">Blog</a></li>
                    </ul>
                <?php else : ?>
                    <ul class="footer__nav-list">
                        <li class="nav-list__item"><a href="<?php echo esc_url( home_url('/') ); ?>">Home</a></li>
                    </ul>
                <?php endif; ?>
            </nav>
            <div class="footer__socials">
                <p class="footer__socials-text">PURCHASE ASSISTANCE</p>
                <div class="footer__socials-wrapper">
                    <?php
                    $socials = new WP_Query([
                        'post_type' => 'social_link',
                        'posts_per_page' => -1,
                        'orderby' => 'menu_order',
                        'order' => 'ASC'
                    ]);

                    if ($socials->have_posts()) :
                        while ($socials->have_posts()) : $socials->the_post();
                            $url  = get_field('social_url');
                            $icon = get_the_post_thumbnail_url(get_the_ID(), 'full');
                            if ($url && $icon) : ?>
                                <a href="<?php echo esc_url($url); ?>" class="footer__socials-icon" target="_blank" rel="noopener">
                                    <img src="<?php echo esc_url($icon); ?>" alt="<?php the_title(); ?>">
                                </a>
                            <?php endif;
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </div>
        </div>
        <div class="footer__arrow">
            <a href="#header">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/scroll-up.svg' ); ?>" alt="scroll up" />
            </a>
        </div>
    </div>
</footer>

<?php
wp_footer(); 
?>

</body>

</html>