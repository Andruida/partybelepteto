<?php
if (!isset($INCLUDED)) {
    require($_SERVER["DOCUMENT_ROOT"]."/index.php");
    die();
}
?>
<div class="container-fluid">
    <h1 class="mt-3 mb-4">Bejelentkezés</h1>
    <div class="form-floating mb-3">
        <input type="email" class="form-control" id="email" placeholder="name@newTicket.com" required spellcheck="false">
        <label for="email">E-mail cím</label>
        <div class="invalid-feedback">
            Vagy nem létezik ilyen címmel fiók
        </div>
    </div>
    <div class="form-floating mb-3">
        <input type="password" class="form-control" id="password" placeholder="Gyula" required spellcheck="false">
        <label for="password">Jelszó</label>
        <div class="invalid-feedback">
            Vagy téves jelszót adtál meg!
        </div>
    </div>
    <div class="row col-sm-4 mt-4 mx-auto">
        <button type="button" onclick="submit()" class="btn btn-primary">Bejelentkezés</button>
        <div class="d-flex justify-content-center">
            <div class="spinner-border" id="loading" style="display: none;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</div>
<script src="/js/login.js"></script>