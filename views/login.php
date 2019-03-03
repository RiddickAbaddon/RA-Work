<?php
    $this->add_controller('login');
    $this->add_js('js/login.js');
    $this->add_css('inc/fontello/css/fontello.css');
?>
<div class="wrapper">
    <div class="login-wrapper">
        <div class="col-50 big-title">
            <div></div>
            <div class="logo">
                <div class="hide-container">
                    <img src="img/logo.png">
                </div>
                <div class="separator"></div>
                <div class="hide-container">
                    <div class="title-container">
                        <div class="title"><?= $this->project_name; ?></div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <?= $this->footer_text; ?>
            </div>
            <div class="to-login" onclick="to_login()">
                <div>Zaloguj się</div>
                <div class="icon"><i class="icon-down-open"></i></div>
            </div>
        </div>
        <div class="col-50 login-container">
            <div></div>
            <div class="form-container">
                <form id="loginForm" action="api/login.php" method="POST">
                    <div class="input-row input-blue">
                        <label for="login">Login</label>
                        <input id="login" name="login">
                    </div>
                    <div class="input-row input-blue">
                        <label for="password">Hasło</label>
                        <input id="password" type="password" name="password">
                    </div>
                    <div style="height: 16px"></div>
                    <button id="submit" class="button-primary-big w-100" type="submit">Zaloguj<span>&raquo;</span></button>
                    <div class="password-link">
                        <a class="password-link" href="reset-password">Zapomniałem hasła</a>
                    </div>
                    <div class="message-container">
                        <div id="message"></div>
                    </div>
                </form>
            </div>
            <div class="author">
                <?= $this->footer2_text; ?>
            </div>
        </div>
    </div>
</div>