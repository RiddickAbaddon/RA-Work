<?php
    $this->add_controller('change_email');
    $this->add_js('inc/smoth-scrollbar/smooth-scrollbar.js');
    $this->add_js('inc/smoth-scrollbar/plugins/overscroll.js');
    $this->add_js('js/scrollbar.js');
    $this->add_js('js/change_email.js');
?>

<div class="body">
    <div class="container">
        <div class="title-bar">
            Zmiana adresu e-mail
        </div>
        <div class="little-form">
            <h3>
                Po zalogowaniu, twój adres email zostanie zmieniony.
            </h3>
            <div class="form-container">
                <form id="loginForm" action="api/change_email.php" method="POST">
                    <div class="input-row input-white">
                        <label for="login">Login</label>
                        <input id="login" name="login">
                    </div>
                    <div class="input-row input-white">
                        <label for="password">Hasło</label>
                        <input id="password" type="password" name="password">
                    </div>
                    <div style="height: 16px"></div>
                    <button id="submit" class="button-secondary-big w-100" type="submit">Zaloguj<span>&raquo;</span></button>
                    
                </form>
            </div>
        </div>
        <div class="message-container">
            <div id="message"></div>
        </div>
    </div>
</div>