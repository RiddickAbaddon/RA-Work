<?php
    $this->add_controller('group');
    $this->add_js('inc/smoth-scrollbar/smooth-scrollbar.js');
    $this->add_js('inc/smoth-scrollbar/plugins/overscroll.js');
    $this->add_js('js/scrollbar.js');
    $this->add_css('inc/fontello/css/fontello.css');
?>
<div class="body">
    <div class="container">
        <?php if($this->group) { ?>
        <div class="title-bar">
            <?= $this->group["name"] ?>
        </div>
        <div class="row">
            <div class="col-50">
                <div class="stat-title">
                    Członkowie
                </div>
                <?php foreach($this->group["users"] as $user) { ?>
                    <div class="stat-row">
                        <div><?= $user["login"] ?></div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-50">
                <div class="stat-title">
                    Projekty
                </div>
                
                <?php
                foreach($this->group["projects"] as $project) {
                    $priority = '';
                    if($project["priority"] != 0) {
                        $priority = ' style="border-color:' . $this->priorities[$project["priority"]]["color"] . '"';
                    }
                ?>
                    <a class="stat-link" href="/project?id=<?= $project["id"]?>" target="_blank" <?= $priority ?>>
                        <div><?= $project["name"] ?></div>
                        <div class="icon"><i class="icon-link-ext"></i></div>
                    </a>
                <?php } ?>
            </div>
        </div>
        <?php } else { ?>
            <div class="title-bar">
                Nie można wyświetlić tej grupy
            </div>
            <p>
                Nie znaleziono takiej grupy lub nie masz do niej uprawnień.
            </p>
        <?php } ?>
    </div>
</div>