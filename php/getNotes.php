<?php
header('Content-Type: application/json');

$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=password");

$subject = $_GET['subject'] ?? '';

$query = "SELECT title, body FROM notes WHERE subject = '$subject'";
$result = pg_query($dbconn, $query);

$notes = pg_fetch_all($result) ?: [];

echo json_encode($notes);

pg_close($dbconn);
?>
