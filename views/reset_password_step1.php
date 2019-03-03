<?php
    $this->add_js('inc/smoth-scrollbar/smooth-scrollbar.js');
    $this->add_js('inc/smoth-scrollbar/plugins/overscroll.js');
    $this->add_js('js/scrollbar.js');
    $this->add_js('js/reset_password_step1.js');
?>

<div class="body">
    <div class="container">
        <div class="title-bar">
            Resetowanie hasła
        </div>
        <div class="little-form">
            <h3>
                Wpisz swój adres e-mail
            </h3>
            <div class="form-container">
                <form id="loginForm" action="api/reset_password.php" method="POST">
                    <div class="input-row input-white">
                        <label for="email">Email</label>
                        <input id="email" name="email">
                    </div>
                    <div style="height: 16px"></div>
                    <button id="submit" class="button-white-big w-100" type="submit">Resetuj hasło<span>&raquo;</span></button>
                </form>
            </div>
            
        </div>
        <div class="message-container">
            <div id="message"></div>
        </div>
    </div>
</div>