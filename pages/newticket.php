<?php
if (!isset($INCLUDED)) {
    require($_SERVER["DOCUMENT_ROOT"]."/index.php");
    die();
}
?>
<div class="modal fade" id="newTicketModal" tabindex="-1" aria-labelledby="newTicketModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="newTicketModalLabel">A jegy adatok leadása sikeres volt!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
    </div>
    <div class="modal-body">
        <p>Ha minden jól alakul, akkor varázsmanóink pillanatokon belül a levelesládádba szállítanak egy QR kódot, amivel majd azonosítani tudod magad!</p>
        <p>Nem találod? <i>Előfordul.</i><br>
        Elsősorban nézd meg a levélszemét mappát, illetve ellenőrizd, hogy a manóink jól hallották-e a címedet:<br><strong><span id="givenAddress"></span></strong></p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">A levelek közé</button>
    </div>
    </div>
</div>
</div>
<div class="container-fluid">
    <h1 class="mt-3 mb-4">Jegy adatok megadása</h1>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" onchange="updateValidation($('#name'))" id="name" placeholder="Gyula" required spellcheck="false">
        <label for="name">Név</label>
        <div class="invalid-feedback">
            Kötelező mező!
        </div>
    </div>
    <div class="form-floating mb-3">
        <input type="email" class="form-control" oninput="$('#givenAddress').text($('#email').val())" onchange="updateValidation($('#email'))" id="email" placeholder="name@newTicket.com" required spellcheck="false">
        <label for="email">E-mail cím</label>
        <div class="invalid-feedback" id="invalidformat">
            Egy érvényes e-mail címet adj meg!
        </div>
        <div class="invalid-feedback" id="alreadytaken" style="display: none;">
            Már foglalt!
        </div>
    </div>
    <div class="row col-sm-4 mt-4 mx-auto">
        <button type="button" onclick="submit()" class="btn btn-primary">Mentés</button>
        <div class="d-flex justify-content-center">
            <div class="spinner-border" id="loading" style="display: none;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</div>
<script src="/js/newticket.js"></script>