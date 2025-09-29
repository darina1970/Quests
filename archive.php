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
    <section class="blog-article__main section-common" id="blogArticleMain">
        <div class="blog-article__wrapper container">
            <div class="blog-article__image">
                <img src="./assets/icons/monogram.png" alt="monogram">
            </div>
            <h2 class="text-align">Our stories & Mysteries</h2>
            <article class="blog__item blog-article__item">
                <div class="blog__item-content">
                    <a href="article.html" class="blog__item-image-main">
                        <img src="./assets/images/card-8.webp" alt="article">
                    </a>
                    <div class="blog__item-descr">
                        <a href="article.html">
                            <h3 class="blog__item-title">Deciphering an Ancient Map</h3>
                        </a>
                        <p class="blog__item-date">August 28, 2025</p>
                        <p class="blog__item-text">Faded ink, torn edges, and cryptic symbols… The map seems to
                            whisper
                            secrets of places long forgotten. Every mark invites you to look closer, to connect
                            clues
                            and uncover what lies beyond the obvious.</p>
                    </div>
                </div>
            </article>
            <div class="blog-article__image">
                <img src="./assets/icons/monogram-down.png" alt="monogram">
            </div>
        </div>
    </section>
    <section class="blog-articles__sub section-common" id="blogArticles">
        <div class="blog__wrapper container">
            <div class="blog__items">
                <article class="blog__item">
                    <div class="blog__item-content">
                        <a href="article.html" class="blog__item-image">
                            <img src="./assets/images/card-8.webp" alt="article">
                        </a>
                        <a href="article.html">
                            <h3 class="blog__item-title">How to host a home quest party</h3>
                        </a>
                        <p class="blog__item-date">August 28, 2025</p>
                        <p class="blog__item-text">Bring the thrill of adventure right into your living room!
                            Hosting a
                            home quest party is a
                            fun and creative way to gather friends and family, solve puzzles together, and turn an
                            ordinary evening into an unforgettable mystery.</p>
                    </div>
                    <a href="article.html" class="blog__link">
                        <span>READ MORE</span>
                        <img src="./assets/icons/learn-more-arrow.svg" alt="arrow right">
                    </a>
                </article>
                <article class="blog__item">
                    <div class="blog__item-content">
                        <a href="article.html" class="blog__item-image">
                            <img src="./assets/images/card-8.webp" alt="article">
                        </a>
                        <a href="article.html">
                            <h3 class="blog__item-title">How to host a home quest party</h3>
                        </a>
                        <p class="blog__item-date">August 28, 2025</p>
                        <p class="blog__item-text">Bring the thrill of adventure right into your living room!
                            Hosting a
                            home quest party is a
                            fun and creative way to gather friends and family, solve puzzles together, and turn an
                            ordinary evening into an unforgettable mystery.</p>
                    </div>
                    <a href="article.html" class="blog__link">
                        <span>READ MORE</span>
                        <img src="./assets/icons/learn-more-arrow.svg" alt="arrow right">
                    </a>
                </article>
                <article class="blog__item">
                    <div class="blog__item-content">
                        <a href="article.html" class="blog__item-image">
                            <img src="./assets/images/card-8.webp" alt="article">
                        </a>
                        <a href="article.html">
                            <h3 class="blog__item-title">How to host a home quest party</h3>
                        </a>
                        <p class="blog__item-date">August 28, 2025</p>
                        <p class="blog__item-text">Bring the thrill of adventure right into your living room!
                            Hosting a
                            home quest party is a
                            fun and creative way to gather friends and family, solve puzzles together, and turn an
                            ordinary evening into an unforgettable mystery.</p>
                    </div>
                    <a href="article.html" class="blog__link">
                        <span>READ MORE</span>
                        <img src="./assets/icons/learn-more-arrow.svg" alt="arrow right">
                    </a>
                </article>
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

                <?php if ( get_field('show_form_image') ) :
                $image = get_field('form_image');
                if( $image ) : ?>
                    <img class="form__image" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                <?php endif; endif; ?>

                <div class="form__text">
                    <?php if ( get_field('form_title') ) : ?>
                    <h2 class="text-align"><?php the_field('form_title'); ?></h2>
                    <?php endif; ?>

                    <?php if ( get_field('form_subtitle') ) : ?>
                    <h3 class="text-align"><?php the_field('form_subtitle'); ?></h3>
                    <?php endif; ?>

                    <?php if ( get_field('show_form_paragraph') && get_field('form_paragraph') ) : ?>
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
                            <a href="<?php echo get_permalink( get_page_by_path('privacy-policy') ); ?>" target="_blank">Privacy Policy</a>
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