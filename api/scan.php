<?php
if (!in_array($_SERVER["REQUEST_METHOD"], ["POST", "GET"])) {
    http_response_code(405);
    die();
}


require_once($_SERVER["DOCUMENT_ROOT"] .'/classloader.php');
session_start();

if (!isset($_SESSION["user_id"])) {
    http_response_code(403);
    die();
}

$conn = new Connection();

if (!empty($_GET["qr"])) {
    $query = $conn -> prepare("SELECT `email`, `name`, `entered` FROM `tickets` WHERE `qrcode` = :QR");
    $query->bindValue(":QR", $_GET["qr"]);
    $query->execute();
    if ($query->rowCount() <= 0) {
        http_response_code(404);
        die();
    }
    $result = $query->fetch(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($result);
    die();
}

#######################################################
// POST side
#######################################################
use chillerlan\QRCode\{QROptions,QRCode};

function checkEmailAvailability($email) {
    global $conn;
    $query = $conn->prepare("SELECT `id` FROM `tickets` WHERE `email` = :EMAIL LIMIT 1 ");
    $query->bindValue(":EMAIL", $email);
    $query->execute();
    return ($query->rowCount() === 0);
}

function checkEntered($email) {
    global $conn;
    $query = $conn->prepare("SELECT `entered` FROM `tickets` WHERE `email` = :EMAIL LIMIT 1 ");
    $query->bindValue(":EMAIL", $email);
    $query->execute();
    if ($query->rowCount() <= 0) {
        return false;
    } else {
        return $query->fetch(PDO::FETCH_ASSOC)["entered"];
    }
}


if (empty($_POST["email"]) || preg_match(
    "/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/",
    $_POST["email"]
) !== 1) {
    http_response_code(400);
    die("Hibás vagy foglalt email cím!");
}
$_POST["email"] = trim($_POST["email"]);

if (empty($_POST["name"])) {
    http_response_code(400);
    die("Hiányzó név!");
}
$_POST["name"] = trim($_POST["name"]);

if (checkEmailAvailability($_POST["email"])) {

    $token = base64_encode(random_bytes(6));

    $query = $conn -> prepare("INSERT INTO `tickets`(`email`, `name`, `qrcode`, `entered`) VALUES (:EMAIL, :NAME, :TOKEN, CURRENT_TIMESTAMP)");
    $query->bindParam(":EMAIL", $_POST["email"]);
    $query->bindParam(":NAME", $_POST["name"]);
    $query->bindParam(":TOKEN", $token);
    $query->execute();

    $qroptions = new QROptions([
        "imageBase64" => false,
        "imageTransparent" => false,
        "scale" => 20,
        // "eccLevel" => QRCode::ECC_M
    ]);
    $qrcode = new QRCode($qroptions);
    $filename = tempnam(sys_get_temp_dir(), "QRCode");
    unlink($filename);
    $filename .= ".png";
    file_put_contents($filename, $qrcode->render($token));
    $name = $_POST["name"];

    $success = Mailer::mail($_POST["email"], "Megérkezett vásárolt jegyed", <<<MAILEND

    <h3>Kedves $name!</h3>

    <p>Az alábbi kód felmutatásával tudod igazolni, hogy már kifizetted a jegyed!</p>

    MAILEND, $filename);

    unlink($filename);
} else if (checkEntered($_POST["email"]) === null) {
    $query = $conn -> prepare("UPDATE `tickets` SET `name` = :NAME, `entered` = CURRENT_TIMESTAMP WHERE `email` = :EMAIL");
    $query->bindParam(":EMAIL", $_POST["email"]);
    $query->bindParam(":NAME", $_POST["name"]);
    $query->execute();

    $success = Mailer::mail($_POST["email"], "Érvényesítetted a jegyed", <<<MAILEND

    <h3>Kedves $name!</h3>

    <p>Sikeresen kifizetted előzőleg lefoglalt jegyedet! Az előző levélben kapott QR kód felmutatásával tudod igazolni, hogy van jegyed.</p>

    MAILEND);
}


?>