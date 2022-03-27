<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">A jegy adatok leadása sikeres volt!</h5>
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
        <input type="text" class="form-control" onchange="newTicket_UpdateValidation($('#name'))" id="name" placeholder="Gyula" required spellcheck="false">
        <label for="name">Név</label>
        <div class="invalid-feedback">
            Kötelező mező!
        </div>
    </div>
    <div class="form-floating mb-3">
        <input type="email" class="form-control" oninput="$('#givenAddress').text($('#email').val())" onchange="newTicket_UpdateValidation($('#email'))" id="email" placeholder="name@example.com" required spellcheck="false">
        <label for="email">E-mail cím</label>
        <div class="invalid-feedback" id="invalidformat">
            Egy érvényes e-mail címet adj meg!
        </div>
        <div class="invalid-feedback" id="alreadytaken" style="display: none;">
            Már foglalt!
        </div>
    </div>
    <div class="row col-sm-4 mt-4 mx-auto">
        <button type="button" class="btn btn-primary">Mentés</button>
    </div>
</div>