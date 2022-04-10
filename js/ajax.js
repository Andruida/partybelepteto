function validateEmail(email) {
    return String(email)
        .toLowerCase()
        .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
};

function removeParam(parameter) {
    var url, origUrl;
    url = origUrl = document.location.href;
    var urlparts = url.split('?');

    if (urlparts.length >= 2) {
        var urlBase = urlparts.shift();
        var queryString = urlparts.join("?");

        var prefix = encodeURIComponent(parameter) + '=';
        var pars = queryString.split(/[&;]/g);
        for (var i = pars.length; i-- > 0;)
            if (pars[i].lastIndexOf(prefix, 0) !== -1)
                pars.splice(i, 1);
        if (pars.length > 0) {
            url = urlBase + '?' + pars.join('&');
        } else {
            url = urlBase;
        }
        if (url != origUrl) {
            window.history.pushState('', document.title, url); // added this line to push the new url directly to url bar .
        }
    }
    return url;
}

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

function getEmailCode(email) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/api/checkEmail.php",
            data: {email},
            method: "GET",
            success: function(response) {
                resolve(response.qrcode)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown)
            }
        })
    })
}