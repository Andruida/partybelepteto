<?php
if (!isset($INCLUDED)) {
    require($_SERVER["DOCUMENT_ROOT"]."/index.php");
    die();
}
?>
<div class="container-fluid">
    <h1 class="mt-3 mb-4">Jegy beolvasása</h1>
    <div class="mb-3" id="reader"></div>
    <div class="alert alert-danger" id="invalid-alert" style="display: none;">Nem regisztrált QR kód!</div>
    <div class="alert alert-success" id="already-entered-alert" style="display: none;">Már érvényesített jegy!</div>
    <div class="alert alert-warning" id="not-entered-alert" style="display: none;">Ez a jegy <strong>még nincs</strong> érvényesítve!</div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control forminput" <?= (isset($_SESSION["user_id"])) ? "" : "disabled " ?>onchange="updateValidation($('#name'))" id="name" placeholder="Gyula" required spellcheck="false">
        <label for="name">Beolvasott név</label>
        <div class="invalid-feedback">
            Kötelező mező!
        </div>
    </div>
    <div class="form-floating mb-3">
        <input type="email" class="form-control forminput" <?= (isset($_SESSION["user_id"])) ? "" : "disabled " ?>onchange="updateValidation($('#email'))" id="email" placeholder="name@newTicket.com" required spellcheck="false">
        <label for="email">Beolvasott e-mail cím</label>
        <div class="invalid-feedback" id="invalidformat">
            Egy érvényes e-mail címet adj meg!
        </div>
    </div>
    <div class="vstack gap-2 col-sm-4 mt-4 mx-auto">
        <?php if (isset($_SESSION["user_id"])) { ?>
        <button type="button" onclick="submit()" class="btn btn-primary">Érvényesítés</button>
        <?php } ?>
        <button type="button" onclick="cancel()" class="btn btn-outline-secondary">Újat!</button>
        <div class="d-flex justify-content-center">
            <div class="spinner-border" id="loading" style="display: none;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</div>
<script src="/js/scan.js"></script>
<?php if (!empty($_GET["t"])) { ?> 
<script>
    onScanSuccess("<?= $_SERVER["REQUEST_URI"] ?>", {})
</script>
<?php } ?> 