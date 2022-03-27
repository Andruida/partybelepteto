<?php
// Set a global constant, so scripts can not be called directly from browser
$INCLUDED = true;
require_once($_SERVER["DOCUMENT_ROOT"] . '/classloader.php');

$queryMarker = strpos($_SERVER["REQUEST_URI"], "?");
$URI = ($queryMarker) ? substr($_SERVER["REQUEST_URI"], 0, $queryMarker) : $_SERVER["REQUEST_URI"];
if (strlen($URI) != 1 && strlen($URI) - 1 == strrpos($URI, "/")) {
    $URI = substr($URI, 0, strlen($URI) - 1); // Get rid of trailing slash
}


session_start();


$pageMap = [
    "/" => "newticket",
    "/login" => "login"
];

$curpage = "newticket";
if (isset($pageMap[$URI])) {
    $curpage = $pageMap[$URI];
}

$required_page_file = $_SERVER["DOCUMENT_ROOT"] . "/pages/" . $curpage . ".php";

?>

<!doctype html>
<html lang="hu">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Beléptető</title>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light my-3 ">
            <button class="navbar-toggler ms-auto " type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse nav-pills mt-2" id="navbarSupportedContent">
                <a class="nav-link<?= ($curpage == "newticket") ? " active" : "" ?> me-auto" aria-current="page" href="/">Jegy adatok megadása</a>
                <a class="nav-link<?= ($curpage == "login") ? " active" : "" ?>" href="/login">Jegyeladóknak</a>
            </div>
        </nav>
        <hr>
        <div class="mx-auto" style="max-width: 600px;">
            <?php require($required_page_file); ?>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="/js/html5-qrcode.min.js"></script>
    <script src="/js/ajax.js"></script>
</body>

</html>