<?php
    $this->add_controller('profile');
    $this->add_js('inc/smoth-scrollbar/smooth-scrollbar.js');
    $this->add_js('inc/smoth-scrollbar/plugins/overscroll.js');
    $this->add_js('js/scrollbar.js');
    $this->add_css('inc/fontello/css/fontello.css');

    if($this->can_show) {
        $this->add_js('js/profile.js');
?>
<div class="body">
    <div class="container">
        <div class="title-bar">
            Profil
        </div>
        <div class="row">
            <div class="col-50">
                <div class="stat-title">
                    Informacje
                </div>
                <div id="show">
                    <div class="stat-row">
                        <div>Nazwa użytkownika</div>
                        <div class="id-current-name"><?= $this->user_name ?></div>
                    </div>
                    <div class="stat-row">
                        <div>Email</div>
                        <div class="id-current-email"><?= $this->email ?></div>
                    </div>
                    <div class="stat-row">
                        <div>Typ konta</div>
                        <div><?= $this->account_type ?></div>
                    </div>
                    <div class="stat-list">
                        <?php if($this->permissions) { ?>
                        <div class="title">Uprawnienia:</div>
                        <?php } else { ?>
                        <div class="title">Nie posiadasz dodatkowych uprawnień</div>
                        <?php } ?>
                            <?php
                            if($this->permissions) {
                                echo '<ul>';
                                foreach($this->permissions as $perm) {
                                    echo '<li>' . $perm . '</li>';
                                }
                                echo '</ul>';
                            }
                            ?>
                    </div>
                    <div class="stat-list">
                        <div class="title"><?= ($this->groups ? 'Przynależność do grup:' : 'Nie należysz do żadnej grupy') ?></div>
                        <?php if($this->groups) { ?>
                        <ul>
                            <?php
                                foreach($this->groups as $group) {
                                    echo '<li><a href="group?id=' . $group["id"] . '" target="_blank">' . $group["name"] . '</a></li>';
                                }
                            ?>
                        </ul>
                        <?php } ?>
                    </div>

                    
                    <?php
                    if($this->can_edit) {
                    ?>

                    <div style="height:50px;"></div>
                    <div class="row">
                        <?php if(!$this->is_root) { ?>
                        <div class="col-50">
                            <button class="button-danger-big w-100" id="edit_button" onclick="delete_my_account()">Usuń konto<span><i class="icon-trash-empty"></i></span></button>
                        </div>
                        <?php } ?>
                        <div class="col-50">
                            <button class="button-primary-big w-100" id="edit_button" onclick="change_edit_mode()">Edytuj profil<span><i class="icon-edit"></i></span></button>
                        </div>
                    </div>
                    
                    <div class="message" data-message-id="show"></div>
                    
                    <?php
                    }
                    ?>
                </div>
                <?php

                if($this->can_edit) {
                ?>
                <div class="d-none" id="edit">
                    <div class="input-row input-blue-small active">
                        <label for="user_name">Nazwa użytkownika</label>
                        <input id="user_name" name="user_name" value="<?= $this->user_name ?>">
                    </div>
                    <div class="input-row input-blue-small active">
                        <label for="email">Email</label>
                        <input id="email" name="email" value="<?= $this->email ?>">
                    </div>
                    <div style="height: 20px"></div>
                    <div class="input-row input-blue-small">
                        <label for="pass1">Obecne hasło</label>
                        <input id="pass1" type="password" name="pass1">
                    </div>
                    <div class="input-row input-blue-small">
                        <label for="pass2">Nowe hasło</label>
                        <input id="pass2" type="password" name="pass2">
                    </div>
                    <div class="input-row input-blue-small">
                        <label for="pass3">Powtórz nowe hasło</label>
                        <input id="pass3" type="password" name="pass3">
                        <div class="bottom-text">Nowe hasło musi zawierać conajmniej 8 znaków, przynajmniej jedną małą i jedną dużą literę, jedną cyfrę i jeden znak specjalny</div>
                    </div>
                    <div style="height:20px;"></div>
                    <div class="message" data-message-id="edit"></div>
                    <div style="height:20px;"></div>
                    <div class="row">
                        <div class="col-50">
                            <button class="button button-primary-big w-100" onclick="change_edit_mode('send')">Zapisz<span><i class="icon-floppy"></i></span></button>
                        </div>
                        <div class="col-50">
                            <button class="button button-secondary-big w-100" onclick="change_edit_mode()">Anuluj<span><i class="icon-cancel"></i></span></button>
                        </div>
                    </div>
                </div>
                
                <?php
                }
                ?>
            </div>
            <div class="col-50">
                <div class="stat-title">
                    Przydzielone projekty
                </div>
                <div id="profile_projects">

                </div>
            </div>
        </div>
    </div>
</div>
<?php
} else {
?>

<div class="body">
    <div class="container">
        <div class="title-bar">
            Brak uprawnień
        </div>
        <p>
            Nie masz wystarczających uprawnień do wyświetlenia tej zawartości
        </p>
    </div>
</div>


<?php
}
?>