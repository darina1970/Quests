<?php
/* Template Name: Cryptex */
get_header();
?>

<h2>Выберите игру</h2>

<select id="gameSelect">
    <option value="">-- Выберите --</option>
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

<br><br>
<button id="showCryptex">Показать криптекс</button>

<div id="cryptexArea" style="margin-top:30px; display:none;">

    <h3>Введите код:</h3>

    <div id="slots" style="display:flex; gap:10px; margin-bottom:20px;"></div>

    <button id="checkCode">Проверить код</button>

    <div id="output" style="margin-top:20px;"></div>

</div>

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
            const input = document.createElement('input');
            input.maxLength = 1;
            input.style.width = "40px";
            input.style.textAlign = "center";
            slots.appendChild(input);
        }

        document.getElementById('cryptexArea').style.display = "block";
        document.getElementById('output').innerHTML = "";

    });
});

document.getElementById('checkCode').addEventListener('click', function() {
    const inputs = document.querySelectorAll('#slots input');
    let user = "";
    inputs.forEach(input => user += input.value.toUpperCase());

    if (user === correctCode) {
        document.getElementById('output').innerHTML =
            "<h3>Код верный!</h3>" + window.successContent;
    } else {
        document.getElementById('output').innerHTML =
            "<p style='color:red;'>Неверный код</p>";
    }
});
</script>

<?php get_footer(); ?>