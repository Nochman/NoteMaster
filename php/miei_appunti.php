<?php
header('Content-Type: application/json');
session_start();

$conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password");
if (!$conn) {
    die("Errore di connessione: ");
}

$email = $_SESSION["user_email"];
$nome = $_SESSION["user_nome"] ?? 'ospite';

// elimina nota
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {
    $delete_id = $_POST["delete_id"];
    $query_delete = "DELETE FROM notes WHERE id = $1 AND email = $2";
    $result_delete = pg_query_params($conn, $query_delete, array($delete_id, $email));

    if ($result_delete) {
        echo json_encode(["success" => true]); 
    } else {
        echo json_encode(["success" => false, "error" => pg_last_error()]);
    }
    exit; 
}


// modifica nota
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_id"])) {
    $edit_id = $_POST["edit_id"];
    $title = trim($_POST["title"]);
    $body = trim($_POST["body"]);

    if ($title !== "" && $body !== "") {
        $query_update = "UPDATE notes SET title = $1, body = $2 WHERE id = $3 AND email = $4";
        $result_update = pg_query_params($conn, $query_update, array($title, $body, $edit_id, $email));

        echo $result_update
            ? "Nota modificata con successo!"
            : "Errore nella modifica: " . pg_last_error();
    } else {
        echo "Tutti i campi devono essere compilati per modificare.";
    }
}

// Recupera tutte le note dell'utente
$query = "SELECT id, subject, title, body, created_at FROM notes WHERE email = $1 ORDER BY created_at DESC";
$result = pg_query_params($conn, $query, array($email));

// Se non ci sono appunti, mostra la scritta
if (pg_num_rows($result) === 0) {
    echo "<p style='color: red; font-size: 40px; text-align: center;'>Non hai nessuna nota</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Le tue note</title>
    <link rel="stylesheet" href="../css/miei_appunti.css"/>
    <script>
        function confermaEliminazione(id) {
            if (confirm('Sei sicuro di voler eliminare questa nota?')) {
                document.getElementById('deleteForm' + id).submit();
            }
        }
    </script>
</head>
<body>
    <h2>Le tue note</h2>

    <table>
        <thead>
            <tr>
                <th>Materia</th>
                <th>Titolo</th>
                <th>Contenuto</th>
                <th>Creato il</th>
                <th>Elimina</th>
                <th>Modifica</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = pg_fetch_assoc($result)): ?>
                <tr>
                    <tr data-id="<?= $row['id'] ?>"> 
                    <td><?= $row['subject'] ?></td>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['body'] ?></td>
                    <td><?= substr($row['created_at'], 0, 10) ?></td>
                    <td>
                        <button class="elimina" onclick="eliminaAppunto(<?= $row['id'] ?>)">X</button>
                    </td>
                    <td>
                        <label for="title-<?= $row['id'] ?>"><strong>Titolo</strong></label><br>
                        <textarea id="title-<?= $row['id'] ?>" data-original="<?= $row['title'] ?>"><?= $row['title'] ?></textarea>
                        <br>
                        <label for="body-<?= $row['id'] ?>"><strong>Contenuto</strong></label><br>
                        <textarea id="body-<?= $row['id'] ?>" data-original="<?= $row['body'] ?>" style="resize: both; min-width: 100px; min-height: 50px;"><?= $row['body'] ?></textarea>
                        <br>
                         <button class="modifica" onclick="modificaAppunto(<?= $row['id'] ?>)">M</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php pg_close($conn); ?>
