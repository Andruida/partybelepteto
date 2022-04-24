<?php

require_once($_SERVER["DOCUMENT_ROOT"] .'/classloader.php');

if (!Config::enabled()) {
    http_response_code(403);
    die("Disabled");
}

if (empty($_GET["email"])) {
    http_response_code(400);
    die();
}

$conn = new Connection();

$query = $conn->prepare("SELECT `id`, `qrcode` FROM `tickets` WHERE `email` = :EMAIL LIMIT 1 ");
$query->bindValue(":EMAIL", $_GET["email"]);
$query->execute();
header("Content-Type: application/json");
$data = array(
    "exists" => ($query->rowCount() > 0),
    "qrcode" => null
);
if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $data["qrcode"] = $row["qrcode"];
}
echo json_encode($data);
?>