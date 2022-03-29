function checkEmailAvailability(email) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/api/checkEmail.php",
            data: {email},
            method: "GET",
            success: function(response) {
                resolve(!response.exists)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown)
            }
        })
    })
}



async function updateValidation($obj) {
    var valid;
    if ($obj.prop("type") == "email") {
        var formatValid = validateEmail($obj.val())
        valid = (formatValid && await checkEmailAvailability($obj.val()))
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
    }

    $obj.removeClass("is-"+(valid?"in":"")+"valid")
    $obj.addClass("is-"+(valid?"":"in")+"valid")

    return valid;
}

async function submit() {
    var a = await updateValidation($("#email"))
    var b = await updateValidation($("#name"))
    if (!a || !b) {
        return false;
    }
    $("#loading").show()
    $.ajax({
        url: "/api/newTicket.php",
        data: {
            name: $("#name").val(), 
            email: $("#email").val()
        },
        method: "POST",
        success: function(response) {
            $("#newTicketModal").modal("show")
            $("#loading").hide()
            updateValidation($("#email"))
            updateValidation($("#name"))
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error(textStatus, errorThrown)
            $("#loading").hide()
        }
    })
}