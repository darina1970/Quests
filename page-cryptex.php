<?php
/* Template Name: Cryptex */
get_header();
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
                <button class="btn btn-form" id="showCryptex">Показать криптекс</button>
            </div>

            <div id="cryptexArea" style="margin-top:30px; display:none;">

                <div class="chars" id="slots"></div>

                <div class="cryptex_wrap">
                    <div class="cryptex" id="cryptexWheels">
                        <div id="lft">&nbsp;</div>
                        <div id="rgt">&nbsp;</div>
                    </div>
                </div>


                <button id="checkCode" class="btn btn-form">Check your code</button>


                <div id="output" style="margin-top:20px;"></div>

            </div>
        </div>
    </section>


    
</main>

<script>
let correctCode = "";
let codeLength = 0;

document.getElementById('showCryptex').addEventListener('click', function() {
    const id = document.getElementById('gameSelect').value;
    if (!id) return alert("Выберите игру");

    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=load_game&game_id=' + id
    })
    .then(res => res.json())
    .then(data => {

        if (!data.success) return;

        correctCode = data.data.code;
        codeLength = data.data.length;
        window.successContent = data.data.content;

        // Слот под каждую букву
        const slots = document.getElementById('slots');
        slots.innerHTML = "";
        for (let i = 0; i < codeLength; i++) {
            const slot = document.createElement('div');
            slot.classList.add('char-slot');
            slot.innerHTML = `<b id="slot${i}">−</b>`
            slots.appendChild(slot);
        }

        // Генерация криптекса
        const wheels = document.getElementById('cryptexWheels');
        wheels.innerHTML = "";

        const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        for (let i = 0; i < codeLength; i++) {

            const wheel = document.createElement('div');
            wheel.classList.add('c');
            wheel.id = i;

            alphabet.split('').forEach(letter => {
                const a = document.createElement('a');
                a.href = "#!";
                a.textContent = letter;

                a.addEventListener('click', () => {
                    document.getElementById("slot" + i).textContent = letter;
                });

                wheel.appendChild(a);
            });

            wheels.appendChild(wheel);
        }

        document.getElementById('cryptexArea').style.display = "block";
        document.getElementById('output').innerHTML = "";

    });
});

document.getElementById('checkCode').addEventListener('click', function() {
    let user = "";
    for (let i = 0; i < codeLength; i++) {
        const slot = document.getElementById('slot' + i);
        user += slot.textContent.toUpperCase();
    }

    if (user === correctCode.toUpperCase()) {
        document.getElementById('output').innerHTML =
            '<div class="cryptex-box cryptex-box--success"><p>Код верный!</p>' +
            window.successContent +
            '</div>';
    } else {
        document.getElementById('output').innerHTML =
            '<div class="cryptex-box cryptex-box--error"><p>Неверный код</p></div>';
    }
});
</script>

<?php get_footer(); ?>