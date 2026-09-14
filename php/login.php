<?php
header("Content-Type: application/json");
session_start();

$dbconn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password");

$email = trim($_POST["inputEmail"]);
$password = trim($_POST["inputPassword"]);

if (empty($email) || empty($password)) {
    echo "Errore: tutti i campi sono obbligatori.";
    exit();
}

$query = "SELECT password_hash, nome FROM utente WHERE email = '$email'";
$result = pg_query($dbconn, $query);

if ($row = pg_fetch_assoc($result)) {
    if ($password === $row["password_hash"]) {
        $_SESSION["user_email"] = $email;
        $_SESSION["user_nome"] = $row["nome"];
        echo json_encode(["success" => true, "message" => "Login riuscito!", "redirect" => "../subjects.html"]);
        exit();
    } else {
        echo json_encode(["success" => false, "message" => "password errata!"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "email non trovata!"]);
}

pg_close($dbconn);
?>