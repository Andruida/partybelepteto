function updateValidation($obj) {
    var valid;
    if ($obj.prop("type") == "email") {
        valid = validateEmail($obj.val())
    }

    $obj.removeClass("is-"+(valid?"in":"")+"valid")
    $obj.addClass("is-"+(valid?"":"in")+"valid")

    return valid;
}

async function submit() {
    if (!updateValidation($("#email"))) {
        return false;
    }
    $("#loading").show()
    var qrcode = await getEmailCode($("#email").val());
    if (qrcode != null) {
        window.location.href = "/scan?t="+encodeURIComponent(qrcode);
        $("#error-alert").hide()
    } else {
        $("#error-alert").show()
    }
    $("#loading").hide()
}