var html5QrcodeScanner;

$(document).ready(function() {
    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: 250, videoConstraints: { facingMode: "environment" }});

    console.log("hello")
    html5QrcodeScanner.render(onScanSuccess);
})


function handleQR(decodedResult) {
    $("#loading").show()
    $.ajax({
        url:"/api/scan.php",
        method: "GET",
        data: {qr: decodedResult},
        success: function(response) {
            console.log(response)
            $("#name").val(response.name)
            $("#email").val(response.email)
            $("#grade").val(response.grade)
            $("#class").val(response.class)
            $("#group").val(response.hostel_group)
            $("#loading").hide()
            $(".alert").hide()
            $("#submitBtn").prop("disabled", false)
            if (response.entered) {
                $("#already-entered-alert").show()
                $("#submitBtn").prop("disabled", true)
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

var currentQR;

function onScanSuccess(decodedText, decodedThing) {
    // Handle on success condition with the decoded text or result.
    console.log(`Scan result: ${decodedText}`, decodedThing);
    const result = decodedText.match(/.*t=([^&]*)/)
    var decodedResult;
    if (result) {
        decodedResult = decodeURIComponent(result[1])
    } else {
        decodedResult = decodedText
    }

    currentQR = decodedResult;

    try {
        html5QrcodeScanner.pause(true)
    } catch (error) {}
    handleQR(decodedResult)
}

function cancel() {
    $(".forminput").val("")
    $("#loading").hide()
    $(".alert").hide()
    $(".forminput").removeClass("is-valid").removeClass(".is-invalid")
    $("#submitBtn").prop("disabled", false)
    removeParam("t")
    try {
        html5QrcodeScanner.resume()
    } catch (error) { }
    
}

function updateValidation($obj) {
    if (!($obj instanceof jQuery)) {
        $obj = $($obj)
    }

    var valid;
    if ($obj.prop("type") == "email") {
        valid = validateEmail($obj.val())
    } else if ($obj.prop("type") == "text") {
        valid = (typeof $obj.val() == 'string' && $obj.val().length > 0)
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

function submit() {
    if (!$(".forminput").toArray().every(updateValidation)) {
        return false;
    }
    if (!updateValidation($("#email")) || !updateValidation($("#name"))) {
        return false;
    }
    $("#loading").show()
    $.ajax({
        url: "/api/scan.php",
        method: "POST",
        data: {
            name: $("#name").val(), 
            email: $("#email").val(),
            grade: $("#grade").val(),
            class: $("#class").val(),
            group: $("#group").val(),
        },
        success: function() {
            if (currentQR) {
                handleQR(currentQR)
            } else {
                cancel()
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error(textStatus, errorThrown)
            $("#loading").hide()
        }
    })
}