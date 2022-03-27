$(document).on("ready", function() {
    //
})

function validateEmail(email) {
    return String(email)
        .toLowerCase()
        .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
};

function checkEmailAvailability(email) {
    console.log(email)
    return true
}



function newTicket_UpdateValidation($obj) {
    var valid;
    if ($obj.prop("type") == "email") {
        var formatValid = validateEmail($obj.val())
        valid = (formatValid && checkEmailAvailability($obj.val()))
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
}