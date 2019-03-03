$('.input-row input').focusin(function() {
    $(this).parent().addClass('active');  
}).focusout(function() {
    if($(this).val() == '') {
        $(this).parent().removeClass('active');  
    }
});

function popup( args = {
    message: "Wiadomość nie została ustawiona",
    cancel_button: "Anuluj",
    ok_button: "Ok",
    cancel_action: null,
    ok_action: null
}) {
    var template = `
    <div class="popup show">
        <div class="popup-container">
            <div class="message-box">
                ${args.message}
            </div>
            <div class="options">
                ` + (args.cancel_button ? `<button class="button-secondary-big" id="popup-cancel">${args.cancel_button}</button>` : '') + ` 
                <button class="button-primary-big" id="popup-ok">${args.ok_button}</button>
            </div>
        </div>
    </div>
    `;
    $('#popup-area').html(template);
    $('#popup-cancel').click(function() {
        $('.popup').removeClass('show');
        setTimeout(function() {
            $('.popup').addClass('hide');
            setTimeout(function() {
                $('.popup').remove();
                if(args.cancel_action) {
                    args.cancel_action();
                }
            }, 290);
        }, 100);
    });
    $('#popup-ok').click(function() {
        $('.popup').removeClass('show');
        setTimeout(function() {
            $('.popup').addClass('hide');
            setTimeout(function() {
                $('.popup').remove();
                if(args.ok_action) {
                    args.ok_action();
                }
            }, 290);
        }, 100);
    });
}

function fallbackCopyTextToClipboard(text) {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    var response = false;
    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log('Fallback: Copying text command was ' + msg);
      response = true;
    } catch (err) {
      console.error('Fallback: Oops, unable to copy', err);
    }
  
    document.body.removeChild(textArea);
    return response;
  }

function copyTextToClipboard(text) {
    if (!navigator.clipboard) {
        return fallbackCopyTextToClipboard(text);
    }
    navigator.clipboard.writeText(text).then(function() {
        console.log('Async: Copying to clipboard was successful!');
        return true;
    }, function(err) {
        console.error('Async: Could not copy text: ', err);
        return false;
    });
}