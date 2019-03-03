<?php
    $this->add_controller('manage_users');
    $this->add_js('inc/smoth-scrollbar/smooth-scrollbar.js');
    $this->add_js('inc/smoth-scrollbar/plugins/overscroll.js');
    $this->add_js('js/scrollbar.js');
    $this->add_js('js/utils.js');
    $this->add_css('inc/fontello/css/fontello.css');
    if($this->manage_users || $this->add_reader) {
        $this->add_js('js/manage_users.js');
    }
?>

<div class="body">
    <div class="container">
    
        <?php if(!$this->page_error) { ?>

        <div class="title-bar">
            Zarządzanie użytkownikami
        </div>
        <div class="row">
            <div class="col-50">
                
                <?php if($this->manage_users) { ?>

                <div class="stat-title">
                    Użytkownicy
                </div>
                <?php foreach($this->users_list as $user) { ?>
                    <div class="stat-options">
                        <div class="button-container">
                            <a class="title" href="profile?id=<?= $user["id"] ?>" target="_blank"><div><?= $user["login"] != '' ? $user["login"] : 'Niezarejestrowany (' . $user["email"] . ')'?><?= $user["block"] ? ' <i class="icon-lock"></i>': '' ?></div></a>
                            <button class="button" data-user-id="<?= $user["id"] ?>" onclick="open_user_options(this)"><i class="icon-edit"></i></button>
                        </div>
        
                        <div class="options-container" data-user-id="<?= $user["id"] ?>">
                            <div class="options" data-permissions="<?= $user['id'] ?>" data-permissions-default="<?= $user["permissions"] ?>">
                                <?php if($this->my_id != $user["id"]) { ?>

                                <div>Uprawnienia</div>
                                <div style="height:10px"></div>
                                <?php foreach($this->permissions_list as $permission) { ?> 
                                <label class="input-container">
                                    <input type="radio" name="permissions-<?= $user["id"] ?>" value="<?= $permission["level"] ?>"<?= ($permission["level"] == $user["permissions"]) ? " checked" : "" ?>>
                                    <div class="checkmark-radio"></div>
                                    <div class="title"><?= $permission["name"]; ?><?= ($permission["level"] == $user["permissions"]) ? " (aktualnie)" : "" ?></div>
                                </label>
                                <?php } ?>

                                <div style="height:10px"></div>
                                <button class="button-danger-small action-button" onclick="delete_account(<?= $user['id'] ?>)">Usuń konto <i class="icon-trash-empty"></i></button>
                                <?php if($user["block"] == 1) {
                                    echo '<button class="button-primary-small action-button" onclick="lock_account(' . $user['id'] . ')">Odblokuj konto <i class="icon-lock-open"></i></button>';
                                } else {
                                    echo '<button class="button-primary-small action-button" onclick="lock_account(' . $user['id'] . ')">Zablokuj konto <i class="icon-lock"></i></button>';
                                }
                                ?>
                                <button class="button-primary-small action-button" onclick="save(<?= $user['id'] ?>)">Zapisz <i class="icon-floppy"></i></button>
                                
                                <?php } else {
                                    echo '<div>Nie możesz edytować swojego konta z tego poziomu</div>';
                                }
                                ?>

                                <div class="message" data-message-id="update-user-<?= $user["id"] ?>"></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="stat-options">
                    <div class="button-container">
                        <div class="title no-hover">Dodaj nowego użytkownika</div>
                        <button class="button" id="button-add-user" onclick="show_add_user()"><i class="icon-user-plus"></i></button>
                    </div>
                    <div class="options-container" id="add-new-user">
                        <div class="options">
                            <div>Uprawnienia</div>
                            <div style="height:10px"></div>
                            <?php foreach($this->permissions_list as $permission) { ?> 
                            <label class="input-container">
                                <input type="radio" name="permissions-new-user" value="<?= $permission["level"] ?>"<?= ($permission["level"] == 1) ? " checked" : "" ?>>
                                <div class="checkmark-radio"></div>
                                <div class="title"><?= $permission["name"]; ?></div>
                            </label>
                            <?php } ?>
                            
                            <div class="input-row input-blue-small">
                                <label for="new_user_email">Wpisz email</label>
                                <input id="new_user_email" name="new_user_email">
                            </div>
                
                            <button onclick="add_user()" class="button-primary-small action-button">Dodaj <i class="icon-user-plus"></i></button>
                            
                            <div class="message" data-message-id="add-user"></div>
                        </div>
                    </div>
                </div>
                <?php
                } 
                if($this->add_reader) {
                ?>

                <div class="stat-title">
                    Dodaj użytkownika
                </div>

                <div class="input-row input-blue-small">
                    <label for="new_user_email">Wpisz email</label>
                    <input id="new_user_email" name="new_user_email">
                </div>
    
                <button onclick="add_reader()" class="button-primary-small action-button">Dodaj <i class="icon-user-plus"></i></button>
                
                <div class="message" data-message-id="add-reader"></div>

                <?php } ?>
            </div>
            <div class="col-50">

                <?php if($this->manage_groups) { ?>

                <div class="stat-title">
                    Grupy
                </div>
                    <?php foreach($this->groups_list as $group) { ?>
                        <div class="stat-options">
                            <div class="button-container">
                                <a class="title" href="group?id=<?= $group["id"] ?>" target="_blank"><div><?= $group["name"] ?></div></a>
                                <button class="button" data-group-id="<?= $group["id"] ?>" onclick="open_group_options(this)"><i class="icon-edit"></i></button>
                            </div>
                            <div class="options-container" data-group-id="<?= $group["id"] ?>">
                                <div class="options">
                                    <?php
                                    $current_users = array();
                                    $other_users = array();
                                    foreach($this->users_list as $user) {
                                        $test = false;
                                        foreach($group["users"] as $group_user) {
                                            if($group_user["id"] == $user["id"]) {
                                                $test = true;
                                                break;
                                            }
                                        }
                                        if($test) {
                                            array_push($current_users, $user);
                                        } else {
                                            array_push($other_users, $user);
                                        }
                                    }
                                    ?>
                                    <div class="input-row input-blue-small active">
                                        <label for="group-name-<?= $group["id"] ?>">Zmień nazwę</label>
                                        <input id="group-name-<?= $group["id"] ?>" name="group-name" value="<?= $group["name"] ?>" data-old-name="<?= $group["name"] ?>">
                                    </div>
                                    <?php if($current_users) { ?>
                                        <div>Członkowie:</div>
                                        <div style="height:10px"></div>
                                        <?php foreach($current_users as $user) { ?>
                                        <label class="input-container">
                                            <input type="checkbox" name="group-user" data-input-group-id="<?= $group["id"] ?>" value="<?= $user["id"];?>" checked>
                                            <div class="checkmark-checkbox"></div>
                                            <div class="title"><?= $user["login"]; ?></div>
                                        </label>
                                        <?php } ?>
                                        <div style="height:10px"></div>
                                    <?php } ?>

                                    <div>Dodaj:</div>
                                    <div style="height:10px"></div>
                                    <?php
                                    foreach($other_users as $user) {
                                        if($user["login"]) {    
                                    ?>
                                    <label class="input-container">
                                        <input type="checkbox" name="group-user" data-input-group-id="<?= $group["id"] ?>" value="<?= $user["id"];?>">
                                        <div class="checkmark-checkbox"></div>
                                        <div class="title"><?= $user["login"]; ?></div>
                                    </label>
                                    <?php }} ?>

                                    <div style="height:10px"></div>
                                    <button class="button-danger-small action-button" onclick="delete_group(<?= $group['id'] ?>)">Usuń grupę <i class="icon-trash-empty"></i></button>
                                    <button class="button-primary-small action-button" onclick="save_group(<?= $group['id'] ?>)">Zapisz <i class="icon-floppy"></i></button>
                                    <div class="message" data-message-id="update-group-<?= $group["id"] ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="stat-options">
                        <div class="button-container">
                            <div class="title no-hover">Dodaj nową grupę</div>
                            <button class="button" id="button-add-group" onclick="show_add_group()"><i class="icon-user-plus"></i></button>
                        </div>
                        <div class="options-container" id="add-new-group">
                            <div class="options">
                                <div class="input-row input-blue-small">
                                    <label for="new_group_name">Wpisz nazwę</label>
                                    <input id="new_group_name" name="new_group_name">
                                </div>
                    
                                <button onclick="add_group()" class="button-primary-small action-button">Dodaj <i class="icon-user-plus"></i></button>
                                <div class="message" data-message-id="add-group"></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        
        <?php } else { ?>

        Nie masz uprawnień do wyświetlania tej zawartości.
            
        <?php } ?>
    </div>
</div>
