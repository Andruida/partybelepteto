<?php
if (!isset($INCLUDED)) {
    require($_SERVER["DOCUMENT_ROOT"] . "/index.php");
    die();
}
?>
<img style="width: 100%;" src="data:image/png;base64,<?= base64_encode(QR::create("tesztelek")) ?>">