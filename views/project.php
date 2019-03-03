<?php
    $this->add_controller('project');
    $this->add_js('inc/smoth-scrollbar/smooth-scrollbar.js');
    $this->add_js('inc/smoth-scrollbar/plugins/overscroll.js');
    $this->add_js('js/scrollbar.js');
    $this->add_css('inc/fontello/css/fontello.css');
?>
<div class="body">
    <div class="container">

        <?php if(!$this->project_error) { ?>

        <div class="title-bar">
            <?= $this->project_data["name"] ?>
        </div>
        <div class="row">
            <div class="col-50">
                <div class="stat-title">
                    Informacje
                </div>
                <?php foreach($this->project_stats as $stat) { ?>
                    <div class="stat-row">
                        <div><?= $stat["key"] ?></div>
                        <div <?php if($stat["key"] == "Priorytet") echo $this->priority_color; ?>>
                            <?php if($stat["key"] == "Tylko dla grupy") echo '<a href="group?id=' . $this->project_data["only_group"] . '" target="_blank">' ?>
                            <?= $stat["value"] ?>
                            <?php if($stat["key"] == "Tylko dla grupy") echo '</a>' ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="stat-list">
                    <div class="title"><?= ($this->project_members ? 'Nad projektem pracują:' : 'Nad tym projektem nikt jeszcze nie pracuje') ?></div>
                    <?php if($this->project_members) { ?>
                    <ul>
                        <?php
                            foreach($this->project_members as $member) {
                                echo '<li>' . $member["login"] . '</li>';
                            }
                        ?>
                    </ul>
                    <?php } ?>
                </div>
            </div>
            <div class="col-50">
                <div class="stat-title">
                    Załączniki
                </div>
                <?php foreach($this->project_attachments as $attachment) { ?>
                    <a class="stat-link" href="<?= $attachment["url"] ?>" target="<?= (int)$attachment["external"] == 1 ? "_blank" : "_self" ?>"<?= (int)$attachment["external"] == 1 ? "" : " download" ?>>
                        <div><?= $attachment["name"] ?></div>
                        <div class="icon"><i class="icon-link-ext"></i></div>
                    </a>
                <?php } ?>
            </div>
        </div>
        <div style="height: 40px;"></div>
        <div class="title-bar-small">
            Opis
        </div>
        <div class="project-description">
            <?= $this->project_data["description"] ?>
        </div>
        
        <?php } else { ?>
        <div class="title-bar">
            Nie można wyświetlić tego projektu
        </div>
        <p>
            Nie znaleziono takiego projektu lub nie masz do niego uprawnień.
        </p>    
        <?php } ?>

    </div>
</div>