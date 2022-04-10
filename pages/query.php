<?php
if (!isset($INCLUDED)) {
    require($_SERVER["DOCUMENT_ROOT"]."/index.php");
    die();
}
?>
<div class="container-fluid">
    <h1 class="mt-3 mb-4">Jegy keresése</h1>
    <div class="form-floating mb-3">
        <input type="email" class="form-control forminput" onchange="updateValidation($('#email'))" id="email" placeholder="name@newTicket.com" required spellcheck="false">
        <label for="email">E-mail cím</label>
        <div class="invalid-feedback">
            Egy érvényes e-mail címet adj meg!
        </div>
    </div>
    <div class="row col-sm-4 mt-4 mx-auto">
        <button type="button" onclick="submit()" class="btn btn-primary">Keresés</button>
        <div class="d-flex justify-content-center">
            <div class="spinner-border" id="loading" style="display: none;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <div class="alert alert-danger mt-4" id="error-alert" style="display: none;">Ezzel az e-mail címmel nincs jegy regisztrálva!</div>
</div>
<script src="/js/query.js"></script>