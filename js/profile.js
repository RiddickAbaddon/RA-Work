
$.ajax({
    url:        'api/getprojects.php?as_profile='+profile_id,
    method:     'GET',
    dataType:   "json", 
    data:       {
        loaded_projects: 0,
        projects_per_query: 99,
        filter: 5,
        sort: 0,
        direction: 1,
        phrase: ''
    }
})
.done(function(response) {
    if(response.data) {
        response.data.forEach(function(data) {
            var color = data.priority == 0 ? '' : ' style="border-bottom-color:' + priorities[data["priority"]]["color"] + ';"';

            $('#profile_projects').append(`
            <a class="stat-link"${color} href="project?id=${data.id}" target="_blank">
                <div>${data.name}</div>
                <div class="icon"><i class="icon-link-ext"></i></div>
            </a>
            `);
        });
    }
})
.fail(function( jqXHR, textStatus ) {
    console.error("Połączenie nie powiodło się: " + textStatus);
});


var edit_mode = "show";
var can_change_edit_mode = true;
var can_update_profile = true;
var can_delete_account = true;
var message_object_profile = $('.message[data-message-id="show"]');
var message_object_edit = $('.message[data-message-id="edit"]');

function change_edit_mode(action) {
    if(can_change_edit_mode) {
        if(edit_mode == "show") {
            show_mode('edit');
        } else {
            if(action == "send") {
                if(can_update_profile) {
                    var cur_name = $('.id-current-name').first().text();
                    var cur_email = $('.id-current-email').first().text();
                    var name = $('input#user_name').val();
                    var email = $('input#email').val();
                    var pass1 = $('input#pass1').val();
                    var pass2 = $('input#pass2').val();
                    var pass3 = $('input#pass3').val();

                    if(name == '' && email == '' && pass1 == '' && pass2 == '' && pass3 == '') {
                        message_object_edit.text('');
                        show_mode('show');
                        return;
                    }

                    // Name validation
                    if(name == cur_name) {
                        name = '';
                    } 
                    if(name != '') {
                        if(name.length < 3) {
                            message_object_edit.text('Nazwa użytkownika musi zawierać conajmniej 3 znaki');
                            return;
                        }
                        if(name.length > 32) {
                            message_object_edit.text('Nazwa użytkownika musi zawierać maksymalnie 32 znaki');
                            return;
                        }
                    }

                    // Email validation
                    if(email == cur_email) {
                        email = '';
                    }
                    if(email != '') {
                        if(!email.match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/)) {
                            message_object_edit.text('Wpisz poprawny adres email');
                            return;
                        }
                        if(email.length > 64) {
                            message_object_edit.text('Adres email może zawierać maksymalnie 64 znaków');
                            return;
                        }
                    }

                    // Password validation
                    if(name == '', email == '') {
                        if(pass1 != '' || pass2 != '' || pass3 != '') {
                            if(pass1 == '' || pass2 == '' || pass3 == '') {
                                message_object_edit.text('Jeśli chcesz zmienić hasło wpisz stare hasło i dwukrotnie nowe');
                                return;
                            }
                        }
                    }

                    if(pass1 != '' && pass2 != '' && pass3 != '') {
                        if(pass2 != pass3) {
                            message_object_edit.text('Nowe hasło w obu polach musi być takie samo');
                            return;
                        }
                        if(pass2.length < 8) {
                            message_object_edit.text('Nowe hasło musi zawierać minimum 8 znaków');
                            return;
                        }
                        if(pass2.length > 64) {
                            message_object_edit.text('Nowe hasło musi zawierać maksymalnie 64 znaków');
                            return;
                        }
                        if(!pass2.match(/[a-z]/)) {
                            message_object_edit.text('Hasło musi zawierać przynajmniej jedną małą literę');
                            return;
                        }
                        if(!pass2.match(/[A-Z]/)) {
                            message_object_edit.text('Hasło musi zawierać przynajmniej jedną dużą literę');
                            return;
                        }
                        if(!pass2.match(/\d+/)) {
                            message_object_edit.text('Hasło musi zawierać przynajmniej jedną cyfrę');
                            return;
                        }
                        if(!pass2.match(/.[!,@,#,$,%,^,&,*,?,_,~]/)) {
                            message_object_edit.text('Hasło musi zawierać przynajmniej jeden znak specjalny');
                            return;
                        }
                        if(pass2 == pass1) {
                            message_object_edit.text('Nowe hasło musi się różnić od starego');
                            return;
                        }
                    }

                    message_object_edit.text('');
                    can_update_profile = false;
                    $('.button').addClass('disabled');
                    $.ajax({
                        url:        'api/update_profile.php',
                        method:     'POST',
                        dataType:   "json", 
                        data:       {
                            name: name,
                            email: email,
                            pass1: pass1,
                            pass2: pass2,
                            pass3: pass3,
                            user: profile_id
                        }
                    })
                    .done(function(response) {
                        if(response.check) {
                            console.log(response);
                            if(response.update.indexOf('name') !== -1) {
                                $('.id-current-name').text(name);
                            }
                            if(response.update.indexOf('email') !== -1) {
                                message_object_profile.text('Na podany adres email został wysłany link potwierdzający zmianę adresu e-mail. Wejdź w niego aby dokończyć zmianę adresu email.');
                            }

                            $('#edit input').not('#email').not('#user_name').val('');
                            $('#edit input').blur();
                            show_mode('show');
                        } else {
                            if(response.message) {
                                message_object_edit.text(response.message);
                            }
                        }
                        $('.button').removeClass('disabled');
                        can_update_profile = true;
                    })
                    .fail(function( jqXHR, textStatus ) {
                        console.error("Połączenie nie powiodło się: " + textStatus);
                        can_update_profile = true;
                        $('.button').removeClass('disabled');
                    });
                }
            } else {
                show_mode('show');
            }
        }
    }
}

function show_mode(mode) {
    can_change_edit_mode = false;
    if(mode == "edit") {
        $('#show').addClass('fadeout');
        setTimeout(function() {
            $('#show').addClass('d-none');
            $('#show').removeClass('fadeout');
            $('#edit').removeClass('d-none');
            $('#edit').addClass('fadein');
            setTimeout(function() {
                $('#edit').removeClass('fadein');
                edit_mode = "edit";
                can_change_edit_mode = true;
            },290);
        },290);
    }
    else if(mode == "show") {
        $('#edit').addClass('fadeout');
        setTimeout(function() {
            $('#edit').addClass('d-none');
            $('#edit').removeClass('fadeout');
            $('#show').removeClass('d-none');
            $('#show').addClass('fadein');
            setTimeout(function() {
                $('#show').removeClass('fadein');
                edit_mode = "show";
                can_change_edit_mode = true;
            },290);
        },290);
    }
}

function delete_my_account() {
    if(can_delete_account) {
        popup({
            message: "Czy na pewno chcesz usunąć to konto?",
            cancel_button: "Nie",
            ok_button: "Tak",
            cancel_action: function() {},
            ok_action: function() {
                can_delete_account = false;
                $('.button').addClass('disabled');
                $.ajax({
                    url:        'api/manage_users.php',
                    method:     'POST',
                    dataType:   "json", 
                    data:       {
                        action: 'delete_my_account',
                        user:   profile_id
                    }
                })
                .done(function(response) {
                    if(response.success) {
                        window.parent.location.reload();
                    } else {
                        console.error('Nie udało się usunąć konta. Powód: '+response.message);
                    }
                    can_delete_account = true;
                    $('.button').removeClass('disabled');
                })
                .fail(function( jqXHR, textStatus ) {
                    console.error("Połączenie nie powiodło się: " + textStatus);
                    can_delete_account = true;
                    $('.button').removeClass('disabled');
                });
            }
        });
    }
}
