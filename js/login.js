document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); 

    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    fetch("php/login.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ inputEmail: email, inputPassword: password })
    })
    .then(response => response.json())
    .then(data => {
        let messageDiv = document.getElementById("div-msg");
        messageDiv.innerHTML = data.message;
        messageDiv.style.color = data.success ? "green" : "red";

        if (data.success && data.redirect) {
            setTimeout(() => window.location.href = data.redirect, 1000);
        }

    })
    //.catch(console.error("Errore AJAX:", error));
});
