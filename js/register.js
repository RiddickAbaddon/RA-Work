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
    var messageObject = $('#message');
    var button = $('#submit');
    var name = $('input#login').val();
    var pass = $('input#password').val();
    var pass1 = $('input#password2').val();

    if(name != '' && pass != '' && pass1 != '') {
        if(canSend) {

            // Name validation
            if(name == '') {
                $('#message').text('Wpisz nazwę swojego konta');
                return;
            }
            if(name.length < 3) {
                $('#message').text('Nazwa użytkownika musi zawierać conajmniej 3 znaki');
                return;
            }
            if(name.length > 32) {
                $('#message').text('Nazwa użytkownika musi zawierać maksymalnie 32 znaki');
                return;
            }

            // Password validation
            if(pass == '') {
                $('#message').text('Wpisz hasło');
                return;
            }
            if(pass1 == '') {
                $('#message').text('Powtórz hasło');
                return;
            }

            if(pass != pass1) {
                $('#message').text('Hasło w obu polach musi być takie samo');
                return;
            }
            if(pass.length < 8) {
                $('#message').text('Hasło musi zawierać minimum 8 znaków');
                return;
            }
            if(pass.length > 64) {
                $('#message').text('Hasło musi zawierać maksymalnie 64 znaków');
                return;
            }
            if(!pass.match(/[a-z]/)) {
                $('#message').text('Hasło musi zawierać przynajmniej jedną małą literę');
                return;
            }
            if(!pass.match(/[A-Z]/)) {
                $('#message').text('Hasło musi zawierać przynajmniej jedną dużą literę');
                return;
            }
            if(!pass.match(/\d+/)) {
                $('#message').text('Hasło musi zawierać przynajmniej jedną cyfrę');
                return;
            }
            if(!pass.match(/.[!,@,#,$,%,^,&,*,?,_,~]/)) {
                $('#message').text('Hasło musi zawierać przynajmniej jeden znak specjalny');
                return;
            }

            $('#message').text('');

            button.addClass('disabled');
            button.html('Rejestrowanie...');
            data = {
                name: name,
                pass: pass,
                pass1: pass1,
                code: code,
                profile: profile_id
            };
            console.log(data);
            canSend = false;
            $.ajax({
                url:        action,
                method:     method,
                dataType:   "json", 
                data:       {
                    name: name,
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
                    button.html('Zarejestruj się<span>&raquo;</span>');
                }
                canSend = true;
            })
            .fail(function( jqXHR, textStatus ) {
                console.error("Połączenie nie powiodło się: " + textStatus);
                
                button.removeClass('disabled');
                button.html('Zarejestruj się<span>&raquo;</span>');
                canSend = true;
            });
        }
        
    } else {
        messageObject.html("Podaj login i dwukrotnie hasło");
    }
    

    login = null;
    password = null;

    return false;
}