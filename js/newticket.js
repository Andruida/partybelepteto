async function updateValidation($obj) {
    if (!($obj instanceof jQuery)) {
        $obj = $($obj)
    }

    var valid;
    if ($obj.prop("type") == "email") {
        var formatValid = validateEmail($obj.val())
        valid = (Boolean(formatValid) && await checkEmailAvailability($obj.val()))
        if (formatValid && !valid) {
            $obj.parent().find("#invalidformat").hide()
            $obj.parent().find("#alreadytaken").show()
        } else if (valid) {
            $obj.parent().find("#invalidformat").hide()
            $obj.parent().find("#alreadytaken").hide()
        } else {
            $obj.parent().find("#invalidformat").show()
            $obj.parent().find("#alreadytaken").hide()
        }
    } else if ($obj.prop("type") == "text") {
        valid = (typeof $obj.val() == 'string' && $obj.val().length > 0)
        console.log(typeof $obj.val(), $obj.val().length)
    } else if ($obj.prop("type") == "select-one") {
        if ($obj.prop("id") == "group") {
            valid = true;
        } else {
            valid = ($obj.val().length > 0)
        }
    }

    $obj.removeClass("is-"+(valid?"in":"")+"valid")
    $obj.addClass("is-"+(valid?"":"in")+"valid")

    return valid;
}

async function submit() {
    var valids = await Promise.all($(".forminput").toArray().map(updateValidation))
    if (!valids.every(function(k){return k})) {
        return false;
    }
    $("#loading").show()
    $.ajax({
        url: "/api/newTicket.php",
        data: {
            name: $("#name").val(), 
            email: $("#email").val(),
            grade: $("#grade").val(),
            class: $("#class").val(),
            group: $("#group").val(),
        },
        method: "POST",
        success: function(response) {
            $("#newTicketModal").modal("show")
            $("#loading").hide()
            $(".forminput")
                .removeClass("is-valid")
                .removeClass("is-invalid")
                .val("")
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error(textStatus, errorThrown)
            $("#loading").hide()
        }
    })
}