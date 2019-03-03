var attachments = new Array();
var attachments_files = new Array();

function show_add_attachment() {
    var collapse = $('#add-attachment');
    var button = $('#button-add-attachment');
    var collapse_height = $('#add-attachment .options').outerHeight();
    if(collapse.hasClass('active')) {
        collapse.removeClass('active');
        collapse.css('height', '0');
        button.html('<i class="icon-doc-add"></i>');
    } else {
        collapse.addClass('active');
        collapse.css('height', collapse_height+"px");
        button.html('<i class="icon-cancel"></i>');
    }
}

$('#attachment-file ').on('change', function(e) {
    var label = $('#input-file label');
    var labelVal = $('#input-file label').html();
    var fileName = e.target.value.split( '\\' ).pop();

    if( fileName )
        label.html('<i class="icon-upload-cloud"></i> ' + fileName);
    else
        label.html(labelVal);
});

function add_attachment() {
    var attachment_name = $('input[name="attachment_name"]').val();
    var attachment_url = $('input[name="url"]').val();
    var attachment_file = $('input[name="file"]').prop("files");
    var message_object = $('.message[data-message-id="add-attachment"]');
    if(attachment_name == "") {
        message_object.text("Wpisz nazwę załącznika");
        return;
    }
    if(attachment_name.length < 3) {
        message_object.text("Nazwa załącznika powinna zawierać minimum 3 znaki");
        return;
    }
    if(attachment_url == "" && attachment_file.length === 0) {
        message_object.text("Wpisz adres url załącznika lub wybierz plik z dysku");
        return;
    }
    if(attachment_url != "" && attachment_file.length > 0) {
        message_object.text("Nie możesz dodać pliku i linku do załącznika pod jedną nazwą. Usuń link lub plik");
        return;
    }
    
    var index = attachments.findIndex(x => x.name == attachment_name);
    var index_file = attachments_files.findIndex(x => x.name == attachment_name);
    if(index != -1 || index_file != -1) {
        message_object.text("Już istnieje załącznik o takiej nazwie");
        return;
    }

    message_object.text("");
    if(attachment_url !== "") {
        var attachment = {
            name: attachment_name,
            url: attachment_url
        }
        attachments.push(attachment);
    }
    else if(attachment_file.length) {
        var attachment = {
            name: attachment_name,
            file: attachment_file[0]
        }
        attachments_files.push(attachment);
    }
    
    var subname = (attachment.url ? attachment.url : attachment.file.name);
    $('#attachments').append(`
    <div class="stat-options" data-attachment-name="${attachment.name}">
        <div class="button-container">
            <div class="title no-hover">${attachment.name} (${subname})</div>
            <button class="button" onclick="delete_attachment('${attachment.name}')"><i class="icon-cancel"></i></button>
        </div>
    </div>
    `);
    $('input[name="attachment_name"]').val('');
    $('input[name="url"]').val('');
    cancel_attachment();
    $('input[name="attachment_name"]').blur();
    $('input[name="url"]').blur();
}
function cancel_attachment() {
    $('input[name="file"]').val('');
    $('label[for="attachment-file"]').html('<i class="icon-upload-cloud"></i> Wybierz plik');
}
function delete_attachment(name) {
    var index = attachments.findIndex(x => x.name == name);
    var index_file = attachments_files.findIndex(x => x.name == name);
    if(index != -1) {
        attachments.splice(index, 1);
        $('.stat-options[data-attachment-name="'+name+'"]').remove();
    }
    else if(index_file != -1) {
        attachments_files.splice(index_file, 1);
        $('.stat-options[data-attachment-name="'+name+'"]').remove();
    }
}
var can_send = true;
function send_project() {
    var project_name = $('input#name').val();
    var project_type = $('input#type').val();
    var client = $('input#client').val();
    var settled = $('input[name="settled"]:checked').val();
    var priority = $('input[name="priority"]:checked').val();
    var onlygroup = $('input[name="onlygroup"]:checked').val();
    var allocate = new Array();
    $('input[name="allocate"]:checked').each(function() {
        allocate.push($(this).val());
    });
    var description = $('#description_ifr').contents().find("body").html();

    var message_object = $('.message[data-message-id="base-info"]');

    if(project_name == "") {
        message_object.text('Wpisz nazwę projektu');
        return;
    }
    if(project_name.length < 3) {
        message_object.text('Nazwa projektu musi mieć conajmniej 3 znaki');
        return;
    }
    if(project_type == "") {
        message_object.text('Wpisz typ projektu');
        return;
    }
    if(project_type.length < 3) {
        message_object.text('Typ projektu musi zawierać conajmniej 3 znaki');
        return;
    }
    if(client == "") {
        message_object.text('Wpisz nazwę klienta');
        return;
    }
    if(client.length < 3) {
        message_object.text('Nazwa projektu musi zawierać conajmniej 3 znaki');
        return;
    }
    message_object.text('');

    if(can_send) {
        can_send = false;

        $.ajax({
            url:        'api/add_project.php',
            method:     'POST',
            dataType:   "json", 
            data:       {
                name: project_name,
                type: project_type,
                client: client,
                attachments: attachments,
                settled: settled,
                priority: priority,
                onlygroup: onlygroup,
                allocate: allocate,
                description: description
            }
        })
        .done(function(response) {
            if(response.success) {
                if(attachments_files.length != 0) {
                    send_files(response.project_id,
                    function() {
                        window.parent.reset_content();
                        window.parent.cancel_project();
                    },
                    function() {
                        console.error('Błąd wysyłania plików');
                        message_object.text('Nie udało się przesłać plików');
                    });
                } else {
                    window.parent.last_selected.type = 'project';
                    window.parent.last_selected.id = response.project_id;
                    window.parent.reset_content();
                    window.parent.cancel_project();
                }
            } else {
                message_object.text(response.message);
                console.warn(response.message);
            }
            can_send = true;
        })
        .fail(function( jqXHR, textStatus ) {
            console.error("Połączenie nie powiodło się: " + textStatus);
            can_send = true;
        });
    }
}

function send_files(project_id, callback_success, callback_error) {
    var progressHandling = function(event) {
        var percent = 0;
        var position = event.loaded || event.position;
        var total = event.total;
        var progress_bar_id = "#upload-files";
        if (event.lengthComputable) {
            percent = Math.ceil(position / total * 100);
        }
        $(progress_bar_id + " .bar").css("width", + percent + "%");
        $(progress_bar_id + " .title").text('Wysyłanie plików: ' + percent + "%");
    }
    
    var formData = new FormData();

    attachments_files.forEach(function(attachment) {
        formData.append(attachment.name, attachment.file, attachment.file.name);
    });
    formData.append("upload_file", true);
    formData.append("project_id", project_id);
    $('#upload-files').removeClass('hide');
    $.ajax({
        type: "POST",
        url: "api/upload_files.php",
        xhr: function () {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                myXhr.upload.addEventListener('progress', progressHandling, false);
            }
            return myXhr;
        },
        async: true,
        data: formData,
        cache: false,
        contentType: false,
        dataType: "json", 
        processData: false,
        timeout: 60000
    })
    .done(function(response) {
        if(response.success) {
            $('#upload-files .title').text('Pliki wysłane');
            callback_success();
        } else {
            callback_error();
        }
    })
    .fail(function(jqXHR, textStatus) {
        console.error("Przesyłanie plików nie powiodło się: " + textStatus);
        callback_error();
    });
}