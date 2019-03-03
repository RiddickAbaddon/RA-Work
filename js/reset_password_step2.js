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
    var pass = $('input#password').val();
    var pass1 = $('input#password2').val();

    if(pass != '' && pass1 != '') {
        if(canSend) {
            // Password validation
            if(pass == '') {
                messageObject.text('Wpisz hasło');
                return;
            }
            if(pass1 == '') {
                messageObject.text('Powtórz hasło');
                return;
            }

            if(pass != pass1) {
                messageObject.text('Hasło w obu polach musi być takie samo');
                return;
            }
            if(pass.length < 8) {
                messageObject.text('Hasło musi zawierać minimum 8 znaków');
                return;
            }
            if(pass.length > 64) {
                messageObject.text('Hasło musi zawierać maksymalnie 64 znaków');
                return;
            }
            if(!pass.match(/[a-z]/)) {
                messageObject.text('Hasło musi zawierać przynajmniej jedną małą literę');
                return;
            }
            if(!pass.match(/[A-Z]/)) {
                messageObject.text('Hasło musi zawierać przynajmniej jedną dużą literę');
                return;
            }
            if(!pass.match(/\d+/)) {
                messageObject.text('Hasło musi zawierać przynajmniej jedną cyfrę');
                return;
            }
            if(!pass.match(/.[!,@,#,$,%,^,&,*,?,_,~]/)) {
                messageObject.text('Hasło musi zawierać przynajmniej jeden znak specjalny');
                return;
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
                    pass: pass,
                    pass1: pass1,
                    code: code,
                    profile: profile_id
                }
            })
            .done(function(response) {
                if(response.check) {
                    window.location.href = "/api/logout.php";
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