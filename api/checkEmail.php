<?php

require_once($_SERVER["DOCUMENT_ROOT"] .'/classloader.php');

if (empty($_GET["email"])) {
    http_response_code(400);
    die();
}

$conn = new Connection();

$query = $conn->prepare("SELECT `id` FROM `tickets` WHERE `email` = :EMAIL LIMIT 1 ");
$query->bindValue(":EMAIL", $_GET["email"]);
$query->execute();
header("Content-Type: application/json");
echo json_encode(array(
    "exists" => ($query->rowCount() > 0)
));
?>