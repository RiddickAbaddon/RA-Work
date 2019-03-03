var form;
var canSend = true;
window.onload = function() {
    form = $('#loginForm');
    form.on("submit", processForm);
}

function processForm(e) {
    console.log('Działa');
    if (e.preventDefault) e.preventDefault();
    var action = form.attr('action');
    var method = form.attr('method');
    var messageObject = $('#message');
    var button = $('#submit');
    var email = $('input#email').val();

    if(email != '') {
        if(canSend) {
            // Email validation
            if(email != '') {
                if(!email.match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/)) {
                    messageObject.text('Wpisz poprawny adres email');
                    return;
                }
            }

            messageObject.text('');

            button.addClass('disabled');
            button.html('Resetowanie...');
            canSend = false;
            $.ajax({
                url:        action,
                method:     method,
                dataType:   "json", 
                data:       {
                    email: email
                }
            })
            .done(function(response) {
                if(response.check) {
                    button.html('Wysłano');
                    messageObject.html('Sprawdź swoją pocztę email aby ustawić nowe hasło')
                } else {
                    messageObject.html(response.message);
                    button.removeClass('disabled');
                    button.html('Resetuj hasło<span>&raquo;</span>');
                }
                canSend = true;
            })
            .fail(function( jqXHR, textStatus ) {
                console.error("Połączenie nie powiodło się: " + textStatus);
                
                button.removeClass('disabled');
                button.html('Resetuj hasło<span>&raquo;</span>');
                canSend = true;
            });
        }
        
    } else {
        messageObject.text("Podaj adres e-mail");
    }

    return false;
}