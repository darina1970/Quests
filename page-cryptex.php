<?php
/* Template Name: Cryptex */
get_header();

$theme_uri = get_stylesheet_directory_uri();
$cryptex_line = $theme_uri . '/assets/images/cryptex-line.png';
$cryptex_photo = $theme_uri . '/assets/images/cryptex-photo-removebg-preview.png';
?>

<main class="cryptex-page">
    <section class="cryptex-section">
        <div class="container-cryptex">
            <div class="game-select">
                <label for="game">Choose your quest</label>
                <div class="select-wrapper">
                    <select id="gameSelect">
                        <option value="">-- Quest Name --</option>
                        <?php
                        $games = get_posts([
                            'post_type' => 'cryptex_game',
                            'posts_per_page' => -1
                        ]);

                        foreach ($games as $g) {
                            echo '<option value="'.$g->ID.'">'.$g->post_title.'</option>';
                        }
                        ?>
                    </select>
                </div>
                <button class="btn btn-form" id="showCryptex">Show cryptex</button>
            </div>

            <div id="cryptexArea" style="margin-top:30px; display:none;">

                <div class="chars" id="slots"></div>

                    <div class="cryptex_wrap">
                    <div class="cryptex" style="background-image: url('<?php echo esc_url( $cryptex_line ); ?>');">
                        <div id="lft"
                        style="background-image: url('<?php echo esc_url( $cryptex_photo ); ?>');">
                        </div>
                        <div id="rgt"
                        style="background-image: url('<?php echo esc_url( $cryptex_photo ); ?>'); transform: scaleX(-1);">
                        </div>

                        <!-- Вынесла колесико -->
                        <div id="cryptexWheels"></div>
                    </div>
                </div>


                <button id="checkCode" class="btn btn-form">Check your code</button>


                <div id="output" style="margin-top:20px;"></div>

            </div>
        </div>
    </section>


    
</main>

<?php get_footer(); ?>