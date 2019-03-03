function open_user_options(object) {
    var button = $(object);
    var id = button.attr('data-user-id');
    var options_container = $('.options-container[data-user-id="'+id+'"]');
    var all_options_container = $('.options-container[data-user-id]');
    var all_buttons = $('button[data-user-id]');
    var options = $('.options-container[data-user-id="'+id+'"] .options');
    
    if(options_container.hasClass('active')) {
        options_container.removeClass('active');
        options_container.css('height', '0');
        button.html('<i class="icon-edit"></i>');
    } else {
        all_options_container.removeClass('active');
        all_options_container.css('height', '0');
        all_buttons.html('<i class="icon-edit"></i>');
  
        $('#add-new-user').removeClass('active');
        $('#add-new-user').css('height', '0');
        $('#button-add-user').html('<i class="icon-user-plus"></i>');

        options_container.addClass('active');
        options_container.css('height', options.outerHeight()+"px");
        button.html('<i class="icon-cancel"></i>');
    }
}
function show_add_user() {
    var collapse = $('#add-new-user');
    var button = $('#button-add-user');
    var collapse_height = $('#add-new-user .options').outerHeight();
    if(collapse.hasClass('active')) {
        collapse.removeClass('active');
        collapse.css('height', '0');
        button.html('<i class="icon-user-plus"></i>');
    } else {
        $('.options-container[data-user-id]').removeClass('active');
        $('.options-container[data-user-id]').css('height', '0');
        $('button[data-user-id]').html('<i class="icon-edit"></i>');

        collapse.addClass('active');
        collapse.css('height', collapse_height+"px");
        button.html('<i class="icon-cancel"></i>');
    }
}

var canSend = true;
function delete_account(id) {
    var messageObject = $('.message[data-message-id="update-user-'+id+'"]');
    if(canSend) {
        popup({
            message: "Czy na pewno chcesz usunąć to konto?",
            cancel_button: "Nie",
            ok_button: "Tak",
            cancel_action: function() {},
            ok_action: function() {
                canSend = false;
                messageObject.html('');
                $('.action-button').addClass('disabled');
                $.ajax({
                    url:        'api/manage_users.php',
                    method:     'POST',
                    dataType:   "json", 
                    data:       {
                        action: 'delete',
                        user:   id
                    }
                })
                .done(function(response) {
                    if(response.success) {
                        location.reload();
                    } else {
                        messageObject.html(response.message);
                    }
                    canSend = true;
                    $('.action-button').removeClass('disabled');
                })
                .fail(function( jqXHR, textStatus ) {
                    console.error("Połączenie nie powiodło się: " + textStatus);
                    canSend = true;
                    $('.action-button').removeClass('disabled');
                });
            }
        });
    }
}
function add_user() {
    var messageObject = $('.message[data-message-id="add-user"]');
    var email = $('input[name="new_user_email"]').val();
    var permissions = $('input[name="permissions-new-user"]:checked').val();
    if(canSend) {
        // Email validation
        if(email != '') {
            if(!email.match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/)) {
                messageObject.text('Wpisz poprawny adres email');
                return;
            }
            if(email.length > 64) {
                messageObject.text('Adres email może zawierać maksymalnie 64 znaków');
                return;
            }
        }

        canSend = false;
        messageObject.html('');
        $('.action-button').addClass('disabled');
        $.ajax({
            url:        'api/manage_users.php',
            method:     'POST',
            dataType:   "json", 
            data:       {
                action: 'add_user',
                email:   email,
                permissions: permissions
            }
        })
        .done(function(response) {
            if(response.success) {
                location.reload();
            } else {
                messageObject.html(response.message);
            }
            canSend = true;
            $('.action-button').removeClass('disabled');
        })
        .fail(function( jqXHR, textStatus ) {
            console.error("Połączenie nie powiodło się: " + textStatus);
            canSend = true;
            $('.action-button').removeClass('disabled');
        });
    }
}
function add_reader() {
    var messageObject = $('.message[data-message-id="add-reader"]');
    var email = $('input[name="new_user_email"]').val();
    if(canSend) {
        // Email validation
        if(email != '') {
            if(!email.match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/)) {
                messageObject.text('Wpisz poprawny adres email');
                return;
            }
            if(email.length > 64) {
                messageObject.text('Adres email może zawierać maksymalnie 64 znaków');
                return;
            }
        }

        canSend = false;
        messageObject.html('');
        $('.action-button').addClass('disabled');
        $.ajax({
            url:        'api/manage_users.php',
            method:     'POST',
            dataType:   "json", 
            data:       {
                action: 'add_reader',
                email:   email
            }
        })
        .done(function(response) {
            if(response.success) {
                location.reload();
            } else {
                messageObject.html(response.message);
            }
            canSend = true;
            $('.action-button').removeClass('disabled');
        })
        .fail(function( jqXHR, textStatus ) {
            console.error("Połączenie nie powiodło się: " + textStatus);
            canSend = true;
            $('.action-button').removeClass('disabled');
        });
    }
}
function save(id) {
    var messageObject = $('.message[data-message-id="update-user-'+id+'"]');
    var user_permissions = $('div[data-permissions="'+id+'"] label input:checked').val();
    var permissions_default = $('div[data-permissions="'+id+'"]').attr('data-permissions-default');
    if(canSend && user_permissions != permissions_default) {
        canSend = false;
        messageObject.html('');
        $('.action-button').addClass('disabled');
        $.ajax({
            url:        'api/manage_users.php',
            method:     'POST',
            dataType:   "json", 
            data:       {
                action: 'change_permissions',
                permissions:   user_permissions,
                user: id
            }
        })
        .done(function(response) {
            if(response.success) {
                location.reload();
            } else {
                messageObject.html(response.message);
            }
            canSend = true;
            $('.action-button').removeClass('disabled');
        })
        .fail(function( jqXHR, textStatus ) {
            console.error("Połączenie nie powiodło się: " + textStatus);
            canSend = true;
            $('.action-button').removeClass('disabled');
        });
    }
}
function lock_account(id) {
    var messageObject = $('.message[data-message-id="update-user-'+id+'"]');
    if(canSend) {
        canSend = false;
        messageObject.html('');
        $('.action-button').addClass('disabled');
        $.ajax({
            url:        'api/manage_users.php',
            method:     'POST',
            dataType:   "json", 
            data:       {
                action: 'lock_account',
                user: id
            }
        })
        .done(function(response) {
            if(response.success) {
                location.reload();
            } else {
                messageObject.html(response.message);
            }
            canSend = true;
            $('.action-button').removeClass('disabled');
        })
        .fail(function( jqXHR, textStatus ) {
            console.error("Połączenie nie powiodło się: " + textStatus);
            canSend = true;
            $('.action-button').removeClass('disabled');
        });
    }
}
function open_group_options(object) {
    var button = $(object);
    var id = button.attr('data-group-id');
    var options_container = $('.options-container[data-group-id="'+id+'"]');
    var all_options_container = $('.options-container[data-group-id]');
    var all_buttons = $('button[data-group-id]');
    var options = $('.options-container[data-group-id="'+id+'"] .options');
    
    if(options_container.hasClass('active')) {
        options_container.removeClass('active');
        options_container.css('height', '0');
        button.html('<i class="icon-edit"></i>');
    } else {
        all_options_container.removeClass('active');
        all_options_container.css('height', '0');
        all_buttons.html('<i class="icon-edit"></i>');

        $('#add-new-group').removeClass('active');
        $('#add-new-group').css('height', '0');
        $('#button-add-group').html('<i class="icon-user-plus"></i>');

        options_container.addClass('active');
        options_container.css('height', options.outerHeight()+"px");
        button.html('<i class="icon-cancel"></i>');
    }
}
function show_add_group() {
    var collapse = $('#add-new-group');
    var button = $('#button-add-group');
    var collapse_height = $('#add-new-group .options').outerHeight();
    if(collapse.hasClass('active')) {
        collapse.removeClass('active');
        collapse.css('height', '0');
        button.html('<i class="icon-user-plus"></i>');
    } else {
        $('.options-container[data-group-id]').removeClass('active');
        $('.options-container[data-group-id]').css('height', '0');
        $('button[data-group-id]').html('<i class="icon-edit"></i>');

        collapse.addClass('active');
        collapse.css('height', collapse_height+"px");
        button.html('<i class="icon-cancel"></i>');
    }
}
function delete_group(id) {
    var messageObject = $('.message[data-message-id="update-group-'+id+'"]');
    if(canSend) {
        popup({
            message: "Czy na pewno chcesz usunąć tą grupę? Wszystkie przydzielone do niej projekty staną się dostępne dla wszystkich",
            cancel_button: "Nie",
            ok_button: "Tak",
            cancel_action: function() {},
            ok_action: function() {
                canSend = false;
                messageObject.html('');
                $('.action-button').addClass('disabled');
                $.ajax({
                    url:        'api/manage_users.php',
                    method:     'POST',
                    dataType:   "json", 
                    data:       {
                        action: 'delete_group',
                        group:   id
                    }
                })
                .done(function(response) {
                    if(response.success) {
                        location.reload();
                    } else {
                        messageObject.html(response.message);
                    }
                    canSend = true;
                    $('.action-button').removeClass('disabled');
                })
                .fail(function( jqXHR, textStatus ) {
                    console.error("Połączenie nie powiodło się: " + textStatus);
                    canSend = true;
                    $('.action-button').removeClass('disabled');
                });
            }
        });
    }
}
function save_group(id) {    
    var messageObject = $('.message[data-message-id="update-group-'+id+'"]');
    var add_users = new Array();
    var delete_users = new Array();
    var name = $('#group-name-'+id).val();
    var old_name = $('#group-name-'+id).attr('data-old-name');
    $('input[data-input-group-id="'+id+'"]').each(function() {
        if($(this).is(':checked')) {
            add_users.push($(this).val());
        } else {
            delete_users.push($(this).val());
        }
    });

    if(canSend) {
        if(name != old_name) {
            // Name valid
            if(name.length < 3) {
                messageObject.text('Nazwa grupy musi zawierać conajmniej 3 znaki');
                return;
            }
            if(name.length > 32) {
                messageObject.text('Nazwa grupy musi zawierać maksymalnie 32 znaki');
                return;
            }
        }

        canSend = false;
        messageObject.html('');
        $('.action-button').addClass('disabled');
        $.ajax({
            url:        'api/manage_users.php',
            method:     'POST',
            dataType:   "json", 
            data:       {
                action: 'update_group',
                add_users: add_users,
                delete_users: delete_users,
                group: id,
                name: name
            }
        })
        .done(function(response) {
            if(response.success) {
                location.reload();
            } else {
                messageObject.html(response.message);
            }
            canSend = true;
            $('.action-button').removeClass('disabled');
        })
        .fail(function( jqXHR, textStatus ) {
            console.error("Połączenie nie powiodło się: " + textStatus);
            canSend = true;
            $('.action-button').removeClass('disabled');
        });
    }
}
function add_group() {
    var messageObject = $('.message[data-message-id="add-group"]');
    var name = $('#new_group_name').val();
    if(name.length < 3) {
        messageObject.text('Nazwa grupy musi zawierać conajmniej 3 znaki');
        return;
    }
    if(name.length > 32) {
        messageObject.text('Nazwa grupy musi zawierać maksymalnie 32 znaki');
        return;
    }
    messageObject.text('');
    if(canSend) {
        canSend = false;
        messageObject.html('');
        $('.action-button').addClass('disabled');
        $.ajax({
            url:        'api/manage_users.php',
            method:     'POST',
            dataType:   "json", 
            data:       {
                action: 'add_group',
                name:   name
            }
        })
        .done(function(response) {
            if(response.success) {
                location.reload();
            } else {
                messageObject.html(response.message);
            }
            canSend = true;
            $('.action-button').removeClass('disabled');
        })
        .fail(function( jqXHR, textStatus ) {
            console.error("Połączenie nie powiodło się: " + textStatus);
            canSend = true;
            $('.action-button').removeClass('disabled');
        });
    }
}