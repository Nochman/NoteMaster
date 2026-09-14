<?php
session_start();
$email = $_SESSION["user_email"] ?? null;

if (!$email) {
    echo "Utente non loggato.";
    exit();
}

$conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password");

$subject = $_POST['subject'];
$title = $_POST['title'];
$body = $_POST['body'];
$email = $_SESSION["user_email"] ?? null;

$sql = "INSERT INTO notes (subject, title, body, email) VALUES ('$subject', '$title', '$body', '$email')";
$result = pg_query($conn, $sql);

if ($result) {
    echo 'ok';
} else {
    echo 'errore query';
}
?>