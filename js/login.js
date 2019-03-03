var form;
var canSend = true;
window.onload = function() {
    form = $('#loginForm');
    form.on("submit", processForm);
}

function processForm(e) {
    if (e.preventDefault) e.preventDefault();
    var action = form.attr('action');
    var method = form.attr('method');
    var login = $('input[name="login"]').val();
    var password = $('input[name="password"]').val();
    var messageObject = $('#message');
    var button = $('#submit');

    if(login != '' && password != '') {
        
        if(canSend) {
            canSend = false;
            button.addClass('disabled');
            button.html('Logowanie...');
            $.ajax({
                url:        action,
                method:     method,
                dataType:   "json", 
                data:       {
                    login: login,
                    password: password
                }
            })
            .done(function(response) {
                if(response.success) {
                    location.reload();
                } else {
                    messageObject.html(response.message);
                    button.removeClass('disabled');
                    button.html('Zaloguj<span>&raquo;</span>');
                    canSend = true;
                }
            })
            .fail(function( jqXHR, textStatus ) {
                console.error("Połączenie nie powiodło się: " + textStatus);
                
                button.removeClass('disabled');
                button.html('Zaloguj<span>&raquo;</span>');
                canSend = true;
            });
        }
        
    } else {
        messageObject.html("Podaj login i hasło");
    }
    

    login = null;
    password = null;

    return false;
}

function to_login() {
    $('.login-wrapper').animate({
        scrollTop: $(".login-container").offset().top
    }, 600);
}