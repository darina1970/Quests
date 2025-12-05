<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>

<body>
    <header id="header" class="header <?php echo is_front_page() ? 'main-pg' : ''; ?>">
        <div class="header__wrapper container">

            <?php if (is_page('cryptex')) : ?>
                <div class="logo cryptex__logo">
                    <?php
                    if (function_exists('the_custom_logo') && has_custom_logo()) {
                        $custom_logo_id = get_theme_mod('custom_logo');
                        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                        if ($logo) {
                            echo '<img src="' . esc_url($logo[0]) . '" alt="logo">';
                        }
                    } else {
                        echo '<img src="' . get_template_directory_uri() . '/assets/logo.svg" alt="logo">';
                    }
                    ?>
                </div>

            <?php else : ?>

                <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
                    <?php
                    if (function_exists('the_custom_logo') && has_custom_logo()) {
                        the_custom_logo();
                    } else {
                        echo '<img src="' . get_template_directory_uri() . '/assets/logo.svg" alt="logo">';
                    }
                    ?>
                </a>
                <nav class="header__nav">
                    <?php if (is_front_page()) : ?>
                        <ul class="header__nav-list text-black text-uppercase list-unstyled">
                            <li class="nav-list__item"><a href="#products">Our Products</a></li>
                            <li class="nav-list__item"><a href="#reviews">Reviews</a></li>
                            <li class="nav-list__item"><a href="<?php echo site_url('/archive/'); ?>">Blog</a></li>
                            <li class="nav-list__item"><a href="#faq">FAQ</a></li>
                            <li class="nav-list__item"><a href="#form">Present</a></li>
                        </ul>
                    <?php elseif (is_archive()) : ?>
                        <ul class="header__nav-list text-black text-uppercase list-unstyled">
                            <li class="nav-list__item"><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                        </ul>
                    <?php elseif (is_singular('product') || is_singular('post')) : ?>
                        <ul class="header__nav-list text-black text-uppercase list-unstyled">
                            <li class="nav-list__item"><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                            <li class="nav-list__item"><a href="<?php echo site_url('/archive/'); ?>">Blog</a></li>
                        </ul>
                    <?php else : ?>
                        <ul class="header__nav-list text-black text-uppercase list-unstyled">
                            <li class="nav-list__item"><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                        </ul>
                    <?php endif; ?>
                </nav>
                <div class="header__icons">
                    <a href="<?php echo wc_get_cart_url(); ?>" class="header__cart">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/cart.svg" alt="Cart">
                        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                    </a>
                    <?php if (is_front_page()) : ?>
                        <button class="header__burger" aria-label="menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </header>