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
                    <a href="#" class="footer__socials-icon">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/linkedin.svg' ); ?>" alt="linkedin">
                    </a>
                    <a href="#" class="footer__socials-icon">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/instagram.svg' ); ?>" alt="instagram">
                    </a>
                    <a href="#" class="footer__socials-icon">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/whatsapp.svg' ); ?>" alt="whatsapp">
                    </a>
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