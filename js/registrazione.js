document.getElementById("registrazioneForm").addEventListener("submit", function(event) {
    event.preventDefault(); 

let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let nome = document.getElementById("nome").value;
    let cognome = document.getElementById("cognome").value;

    fetch("php/registrazione.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ inputEmail: email, inputPassword: password, inputName: nome, inputSurname: cognome })
    })
    .then(response => response.json())
    .then(data => {
        let messageDiv = document.getElementById("message");
        messageDiv.innerHTML = data.message;
        messageDiv.style.color = data.success ? "green" : "red";

        if (data.success && data.redirect) {
            setTimeout(() => window.location.href = data.redirect, 1000);
        }

    })

});
