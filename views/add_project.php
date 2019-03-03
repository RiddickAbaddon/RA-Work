<?php
    $this->add_controller('add-project');
    $this->add_css('inc/fontello/css/fontello.css');
    $this->add_js('inc/smoth-scrollbar/smooth-scrollbar.js');
    $this->add_js('inc/smoth-scrollbar/plugins/overscroll.js');
    $this->add_js('js/scrollbar.js');
    $this->add_js('js/add_project.js');
    $this->add_js('inc/tinymce/js/tinymce/tinymce.min.js', true);
?>

<script>
tinymce.init({
    selector:'#description',
    height: 500
});
</script>
<div class="body">
    <div class="container">
        <?php if(!$this->error) { ?>
        <div class="title-bar">
            Dodaj projekt
        </div>
        <div class="row">
            <div class="col-50">
                <div class="stat-title">
                    Informacje podstawowe
                </div>
                <div class="input-row input-blue-small">
                    <label for="name">Nazwa projektu</label>
                    <input id="name" name="name">
                </div>
                <div class="input-row input-blue-small">
                    <label for="type">Typ projektu</label>
                    <input id="type" name="type">
                </div>
                <div class="input-row input-blue-small">
                    <label for="client">Klient</label>
                    <input id="client" name="client">
                </div>
                <div class="message" data-message-id="base-info"></div>
                <div style="height: 20px"></div>
                <div class="stat-title">
                    Załączniki:
                </div>
                <div id="attachments"></div>
                <div class="stat-options">
                    <div class="button-container">
                        <div class="title no-hover">Dodaj załącznik</div>
                        <button class="button" id="button-add-attachment" onclick="show_add_attachment()"><i class="icon-doc-add"></i></button>
                    </div>
                    <div class="options-container" id="add-attachment">
                        <div class="options">
                            <div class="input-row input-blue-small">
                                <label for="attachment_name">Nazwa</label>
                                <input id="attachment_name" name="attachment_name">
                            </div>
                            <div class="input-row input-blue-small">
                                <label for="url">Wpisz adres url</label>
                                <input id="url" name="url">
                            </div>
                            <div>Lub</div>
                            <div class="input-row input-file" id="input-file">
                                <input type="file" id="attachment-file" name="file" class="inputfile">
                                <label for="attachment-file"><i class="icon-upload-cloud"></i> Wybierz plik</label>
                                <button class="button-danger-small" onclick="cancel_attachment()"><i class="icon-cancel"></i></button>
                            </div>
                            
                            <button onclick="add_attachment()" class="button-primary-small action-button">Dodaj <i class="icon-doc-add"></i></button>
                            <div class="message" data-message-id="add-attachment"></div>
                        </div>
                    </div>
                </div>
                <div style="height: 20px"></div>
                <div class="progress-bar hide" id="upload-files">
                    <div class="title">0%</div>
                    <div class="bar"></div>
                </div>

            </div>
            <div class="col-50">
                <div class="stat-title">
                    Informacje dodatkowe
                </div>
                <div style="height: 20px;"></div>
                <div>Rozliczony:</div>
                <div style="height: 10px;"></div>
                <label class="input-container">
                    <input type="radio" name="settled" value="0" checked>
                    <div class="checkmark-radio"></div>
                    <div class="title no-hover">Nie</div>
                </label>
                <label class="input-container">
                    <input type="radio" name="settled" value="1">
                    <div class="checkmark-radio"></div>
                    <div class="title no-hover">Tak</div>
                </label>

                <div style="height: 20px;"></div>
                <div>Priorytet:</div>
                <div style="height: 10px;"></div>
                <label class="input-container">
                    <input type="radio" name="priority" value="0" checked>
                    <div class="checkmark-radio"></div>
                    <div class="title no-hover">&FilledSmallSquare; Zwykły</div>
                </label>
                <?php foreach($this->priorities as $level => $prio) { ?>
                <label class="input-container">
                    <input type="radio" name="priority" value="<?= $level ?>">
                    <div class="checkmark-radio"></div>
                    <div class="title no-hover"><span style="color:<?= $prio["color"]?>">&FilledSmallSquare;</span> <?= $prio["name"]?></div>
                </label>
                <?php } ?>

                <div style="height: 20px;"></div>
                <div>Dostępne tylko dla:</div>
                <div style="height: 10px;"></div>
                <label class="input-container">
                    <input type="radio" name="onlygroup" value="0" checked>
                    <div class="checkmark-radio"></div>
                    <div class="title no-hover">Dostępne dla wszystkich</div>
                </label>
                <?php foreach($this->groups_list as $group) { ?>
                <label class="input-container">
                    <input type="radio" name="onlygroup" value="<?= $group["id"] ?>">
                    <div class="checkmark-radio"></div>
                    <div class="title no-hover"><?= $group["name"] ?></div>
                </label>
                <?php } ?>
                <?php 
                if($this->allocation) {
                ?>
                <div style="height: 20px;"></div>
                <div>Przydziel projekt:</div>
                <div style="height: 10px;"></div>
                <?php
                    foreach($this->users_list as $user) {
                        if($user["login"]) {
                ?>
                <label class="input-container">
                    <input type="checkbox" name="allocate" value="<?= $user["id"] ?>">
                    <div class="checkmark-checkbox"></div>
                    <div class="title no-hover"><?= $user["login"] ?></div>
                </label>
                <?php
                        }
                    }
                }
                ?>
            </div>
        </div>
        <div style="height: 40px;"></div>
        <div class="title-bar-small">
            Opis
        </div>
        <textarea id="description"></textarea>
        <?php
        } else {
        ?>
        Nie masz uprawnień do tworzenia projektów.
        <?php } ?>
    </div>
</div>
