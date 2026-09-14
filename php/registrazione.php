<?php
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
  
  exit("Accesso non valido.");
} else {
  $dbconn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")
    or die("Connessione fallita: " . pg_last_error());
}


$email = $_POST["inputEmail"];
$q1 = "SELECT * FROM utente WHERE email=$1";
$result = pg_query_params($dbconn, $q1, array($email));

if (pg_fetch_array($result, null, PGSQL_ASSOC)) {
    exit(json_encode(["success" => false, "message" => "Email già registrata!"]));
} else {
    $nome = $_POST["inputName"];
    $cognome = $_POST["inputSurname"];
    $password = $_POST["inputPassword"];

    $q2 = "INSERT INTO utente(email, nome, cognome, password_hash) VALUES($1, $2, $3, $4)";
    $data = pg_query_params($dbconn, $q2, array($email, $nome, $cognome, $password));

    if ($data) {
        echo json_encode(["success" => true, "message" => "Registrazione completata!", "redirect" => "../login.html"]);
    } else {
        echo json_encode(["success" => false, "message" => "Errore nella registrazione."]);
    }
}

pg_close($dbconn);
?>
