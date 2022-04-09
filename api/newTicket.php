<?php

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    die();
}

require_once($_SERVER["DOCUMENT_ROOT"] .'/classloader.php');

$conn = new Connection();

function checkEmailAvailability($email) {
    global $conn;
    $query = $conn->prepare("SELECT `id` FROM `tickets` WHERE `email` = :EMAIL LIMIT 1 ");
    $query->bindValue(":EMAIL", $email);
    $query->execute();
    return ($query->rowCount() === 0);
}

if (empty($_POST["email"]) || preg_match(
    "/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/",
    $_POST["email"]
) !== 1 || !checkEmailAvailability($_POST["email"])) {
    http_response_code(400);
    die("Hibás vagy foglalt email cím!");
}
$_POST["email"] = trim($_POST["email"]);

if (empty($_POST["name"])) {
    http_response_code(400);
    die("Hiányzó név!");
}
$_POST["name"] = trim($_POST["name"]);

$token = base64_encode(random_bytes(6));

$query = $conn -> prepare("INSERT INTO `tickets`(`email`, `name`, `qrcode`) VALUES (:EMAIL, :NAME, :TOKEN)");
$query->bindParam(":EMAIL", $_POST["email"]);
$query->bindParam(":NAME", $_POST["name"]);
$query->bindParam(":TOKEN", $token);
$query->execute();

$filename = tempnam(sys_get_temp_dir(), "QRCode");
unlink($filename);
$filename .= ".png";
file_put_contents($filename, QR::create($token));
$name = $_POST["name"];

$success = Mailer::mail($_POST["email"], "Megérkezett előfoglalt jegyed", <<<MAILEND

<h3>Kedves $name!</h3>

<p>Ím a kódod, amivel jegyvásárlásodat véglegesíteni tudod!</p>

MAILEND, $filename);

unlink($filename);

if ($success) {
    http_response_code(200);
    die();
} else {
    http_response_code(500);
    die();
}
?>