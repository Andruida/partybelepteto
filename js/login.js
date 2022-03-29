    // $obj.removeClass("is-"+(valid?"in":"")+"valid")
    // $obj.addClass("is-"+(valid?"":"in")+"valid")

function submit() {
    $("#password").removeClass("is-valid").removeClass("is-invalid")
    $("#email").removeClass("is-valid").removeClass("is-invalid")
    $("#loading").show()
    $.ajax({
        url: "/api/login.php",
        data: {
            password: $("#password").val(), 
            email: $("#email").val()
        },
        method: "POST",
        success: function(response) {
            $("#loading").hide()
            window.location.href = "/scan"
        },
        error: function(jqXHR, textStatus, errorThrown) {
            $("#loading").hide()
            
            if (jqXHR.status == 401) {
                $("#email").addClass("is-invalid")
                $("#password").addClass("is-invalid")
            } else {
                console.error(errorThrown)
            }
        }
    })
}