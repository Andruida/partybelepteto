var html5QrcodeScanner;

$(document).ready(function() {
    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: 250, videoConstraints: { facingMode: "environment" }});

    console.log("hello")
    html5QrcodeScanner.render(onScanSuccess);
})


function onScanSuccess(decodedText, decodedResult) {
    // Handle on success condition with the decoded text or result.
    console.log(`Scan result: ${decodedText}`, decodedResult);
    const result = decodedText.match(/.*t=([^&]*)/)
    var decodedResult;
    if (result) {
        decodedResult = decodeURIComponent(result[1])
    } else {
        decodedResult = decodedText
    }

    try {
        html5QrcodeScanner.pause(true)
    } catch (error) {}
    $("#loading").show()
    $.ajax({
        url:"/api/scan.php",
        method: "GET",
        data: {qr: decodedResult},
        success: function(response) {
            console.log(response)
            $("#name").val(response.name)
            $("#email").val(response.email)
            $("#loading").hide()
            $(".alert").hide()
            if (response.entered) {
                $("#already-entered-alert").show()
            } else {
                $("#not-entered-alert").show()
            }
        },
        error: function(jqXHR) {
            $("#loading").hide()
            if (jqXHR.status == 404) {
                $(".alert").hide()
                $("#invalid-alert").show()
            }
        }
    })
}

function cancel() {
    $(".forminput").val("")
    $("#loading").hide()
    $(".alert").hide()
    $(".forminput").removeClass("is-valid").removeClass(".is-invalid")
    try {
        html5QrcodeScanner.resume()
    } catch (error) { }
    
}

function updateValidation($obj) {
    var valid;
    if ($obj.prop("type") == "email") {
        valid = validateEmail($obj.val())
    } else if ($obj.prop("type") == "text") {
        valid = (typeof $obj.val() == 'string' && $obj.val().length > 0)
    }

    $obj.removeClass("is-"+(valid?"in":"")+"valid")
    $obj.addClass("is-"+(valid?"":"in")+"valid")

    return valid;
}

function submit() {
    if (!updateValidation($("#email")) || !updateValidation($("#name"))) {
        return false;
    }
    $("#loading").show()
    $.ajax({
        url: "/api/scan.php",
        method: "POST",
        data: {
            name: $("#name").val(), 
            email: $("#email").val()
        },
        success: function() {
            cancel()
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error(textStatus, errorThrown)
            $("#loading").hide()
        }
    })
}