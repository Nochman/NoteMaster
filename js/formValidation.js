function validaForm() {
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let nome = document.getElementById("nome").value;
    let cognome = document.getElementById("cognome").value;

    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let nomeCognomeRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ]+$/; 

    let messaggioErrore = ""; 

    if (!emailRegex.test(email) && messaggioErrore === "") {
        messaggioErrore = "Inserisci un'email valida.";
    }
    if (password.length < 6 && messaggioErrore === "") {
        messaggioErrore = "La password deve avere almeno 6 caratteri.";
    }
    if (!nomeCognomeRegex.test(nome) && messaggioErrore === "") {
        messaggioErrore = "Il nome può contenere solo lettere.";
    }
    if (!nomeCognomeRegex.test(cognome) && messaggioErrore === "") {
        messaggioErrore = "Il cognome può contenere solo lettere.";
    }

    if (messaggioErrore !== "") {
        mostraErrore(messaggioErrore);
        return false;
    }

    return true;
}

let ultimoErroreMostrato = ""; 

function mostraErrore(messaggio) {
    if (messaggio === ultimoErroreMostrato) return; 
    ultimoErroreMostrato = messaggio; 
    setTimeout(() => { ultimoErroreMostrato = ""; }, 500); 
    alert(messaggio);
}

