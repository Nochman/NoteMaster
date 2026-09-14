<?php

$conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password");

if (!$conn) {
    die("Errore di connessione: ");
}

// recupera il messaggio dal form
$feedback = trim($_POST["message"] ?? '');

if ($feedback === '') {
    echo "Inserire un feedback";
    exit();
}

// inserisce nella tabella "feedback"
$query = "INSERT INTO feedback (feedback) VALUES ('$feedback')";
$result = pg_query($conn, $query);

if ($result) {
    echo "Grazie per il tuo feedback!";
} else {
    echo "Errore durante l'invio: ";
}

pg_close($conn);
?>
