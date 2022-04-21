<?php
if (!isset($INCLUDED)) {
    require($_SERVER["DOCUMENT_ROOT"] . "/index.php");
    die();
}

$conn = new Connection();

if (isset($_GET["onlyentered"])) {
    $query = $conn->query("SELECT * FROM `tickets` WHERE `entered` IS NOT NULL ORDER BY `name` ASC");
} else {
    $query = $conn->query("SELECT * FROM `tickets` ORDER BY `name` ASC");
}
$csv = '"Név";"Osztály";"Csoport";"Bement"'."\n";


ob_start();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            if ($row["qrcode"] == "tesztelek") {
                continue;
            }
            $csv .= '"'.$row["name"].'";"'.$row["grade"].'.'.$row["class"].'";"'.$row["hostel_group"].'"';
            if ($row["entered"]) {
                $csv .= ';"'.date("H:i",strtotime($row["entered"])).'"';
            } else {
                $csv .= ';""';
            }
            $csv .= "\n";
        ?>
        <tr class="<?= ($row["entered"])? "table-success" : "" ?>">
            <td><?= htmlspecialchars($row["name"]) ?></td>
            <td><?= htmlspecialchars($row["grade"]).".".htmlspecialchars($row["class"]) ?></td>
            <td><?= htmlspecialchars($row["hostel_group"]) ?></td>
            <td><?= ($row["entered"])? (htmlspecialchars(date("H:i",strtotime($row["entered"])))) : "" ?></td>
        </tr>
        <?php } 

$html = ob_get_clean();
?>
<nav class="navbar nav-pills navbar-light mt-3 ">
        <a class="nav-link<?= (isset($_GET["onlyentered"])) ? " active" : "" ?>" href="?onlyentered">Akik bementek</a>
        <a class="nav-link<?= (!isset($_GET["onlyentered"])) ? " active" : "" ?>" href="/table">Összes</a>
</nav>
<div class="text-center mb-4">
    <a download="jegyek<?= isset($_GET["onlyentered"]) ? "_ervenyes" : "_osszes" ?>.csv" href="data:text/csv;charset=utf-8,<?= rawurlencode($csv) ?>">
        <button class="btn btn-success">Letöltés CSV-ként</button>
    </a>
</div>
<table class="table table-sm table-striped table-hover table-responsive">
    <thead>
        <tr>
            <th scope="col">Név</th>
            <th scope="col">Osztály</th>
            <th scope="col">Csoport</th>
            <th scope="col">Bement</th>
        </tr>
    </thead>
    <tbody>
        <?= $html ?>
    </tbody>
</table>