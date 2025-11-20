let correctCode = "";
let codeLength = 0;

document.getElementById('showCryptex').addEventListener('click', function() {
    const id = document.getElementById('gameSelect').value;
    if (!id) return alert("Выберите игру");

    fetch(cryptexParams.ajax_url, {
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
        window.errorContent = data.data.error_text;

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
            '<div class="cryptex-box cryptex-box--success">' +
            window.successContent +
            '</div>';
    } else {
        document.getElementById('output').innerHTML =
            '<div class="cryptex-box cryptex-box--error">' +
            window.errorContent + 
            '</div>';
    }
});