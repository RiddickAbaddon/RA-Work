<?php
    $this->add_controller('register');
    $this->add_js('inc/smoth-scrollbar/smooth-scrollbar.js');
    $this->add_js('inc/smoth-scrollbar/plugins/overscroll.js');
    $this->add_js('js/scrollbar.js');
    $this->add_js('js/register.js');
?>

<div class="body">
    <div class="container">
        <div class="title-bar">
            Rejestracja
        </div>
        <div class="little-form">
            <h3>
                Uzupełnij dane swojego konta
            </h3>
            <div class="form-container">
                <form id="loginForm" action="api/register.php" method="POST">
                    <div class="input-row input-white">
                        <label for="login">Login</label>
                        <input id="login" name="login">
                    </div>
                    <div class="input-row input-white">
                        <label for="password">Hasło</label>
                        <input id="password" type="password" name="password">
                    </div>
                    <div class="input-row input-white">
                        <label for="password2">Powtórz hasło</label>
                        <input id="password2" type="password" name="password2">
                    </div>
                    <div style="height: 16px"></div>
                    <button id="submit" class="button-white-big w-100" type="submit">Załóż konto<span>&raquo;</span></button>
                </form>
            </div>
        </div>
        <div class="message-container">
            <div id="message"></div>
        </div>
    </div>
</div>