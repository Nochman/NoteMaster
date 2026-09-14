function caricaAppunti() {
    let appuntiContainer = document.getElementById("appunti-container");
    let appuntiDiv = document.getElementById("appunti");

    if (appuntiContainer.style.display === "block") {
        appuntiContainer.style.display = "none";
        return;
    }

    appuntiContainer.style.display = "none";
    appuntiDiv.innerHTML = '';

    fetch("php/miei_appunti.php")
        .then(response => response.text())
        .then(data => {
            data = data.trim(); 

            if (!data) {
                appuntiDiv.innerHTML = '<p style="color: red; font-size: 50px; text-align: center;">Non hai nessun appunto</p>';
            } else {
                appuntiDiv.innerHTML = data;
            }

            appuntiContainer.style.display = "block";
        })
        .catch(error => console.error("Errore nel caricamento degli appunti:", error));
}


function eliminaAppunto(id) {
    if (!confirm("Sei sicuro di voler eliminare questa nota?")) {
        return;
    }

    fetch("php/miei_appunti.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `delete_id=${id}`
    })
    .then(response => response.json()) 
    .then(data => {
        if (data.success) {
            let rigaNota = document.querySelector(`tr[data-id="${id}"]`);
            if (rigaNota) {
                rigaNota.remove();
            }
            alert("Nota eliminata con successo!");
        } else {
            alert("Errore durante l'eliminazione!");
        }
    })
    .catch(error => console.error("Errore nella richiesta:", error));
}


function modificaAppunto(id) {
    let titleElement = document.getElementById(`title-${id}`);
    let bodyElement = document.getElementById(`body-${id}`);

    let nuovoTitolo = titleElement.value.trim();
    let nuovoContenuto = bodyElement.value.trim();

    let titoloOriginale = titleElement.getAttribute("data-original");
    let contenutoOriginale = bodyElement.getAttribute("data-original");

    if (nuovoTitolo === titoloOriginale && nuovoContenuto === contenutoOriginale) {
        alert("Non è stata fatta nessuna modifica.");
        return;
    }

    if (!confirm("Sei sicuro di voler modificare questa nota?")) {
        return;
    }

    fetch("php/miei_appunti.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `edit_id=${id}&title=${encodeURIComponent(nuovoTitolo)}&body=${encodeURIComponent(nuovoContenuto)}`
    })
    .then(response => response.text())
    .then(() => {
        alert("Nota modificata con successo!"); 
        aggiornaNota(id, nuovoTitolo, nuovoContenuto);

        titleElement.setAttribute("data-original", nuovoTitolo);
        bodyElement.setAttribute("data-original", nuovoContenuto);
    })
    .catch(error => console.error("Errore nella modifica:", error));
}

function aggiornaNota(id, nuovoTitolo, nuovoContenuto) {
    document.getElementById(`title-${id}`).value = nuovoTitolo;
    document.getElementById(`body-${id}`).value = nuovoContenuto;
}


