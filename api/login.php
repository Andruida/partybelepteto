<?php

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    die();
}

if (empty($_POST["password"]) || empty($_POST["email"])) {
    http_response_code(400);
    die();
}

require_once($_SERVER["DOCUMENT_ROOT"] .'/classloader.php');
$conn = new Connection();

$query = $conn -> prepare("SELECT `id`,`password` FROM `users` WHERE `email` = :EMAIL");
$query->bindValue(":EMAIL", $_POST["email"]);
$query->execute();

if ($query->rowCount() <= 0) {
    http_response_code(401);
    die();
}
$result = $query->fetch(PDO::FETCH_ASSOC);
if (password_verify($_POST["password"], $result["password"])) {
    session_start();
    $_SESSION["user_id"] = $result["id"];
    $_SESSION["user_email"] = $_POST["email"];
} else {
    http_response_code(401);
    die();
}


?>