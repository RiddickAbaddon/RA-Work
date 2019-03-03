<?php
    $this->add_controller('panel');
    $this->add_js('inc/smoth-scrollbar/smooth-scrollbar.js');
    $this->add_js('inc/smoth-scrollbar/plugins/overscroll.js');
    $this->add_js('js/content-loader.js');
    $this->add_css('inc/fontello/css/animation.css');
    $this->add_css('inc/fontello/css/fontello.css');
?>
<div class="wrapper">
    <div class="mobile-bar">
        <div class="list-icon" onclick="show_menu(1, this)">
            <div></div>
            <div></div>
            <div></div>
        </div>
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
        <div class="dots-icon" onclick="show_menu(2, this)">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div class="panel-wrapper">
        <div class="cancel-area" onclick="show_menu(0)"></div>
        <div class="list-panel">
            <div class="header">
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
                <div class="search-container">
                    <div class="input-row input-white search-interract">
                        <label for="search">Szukaj...</label>
                        <input id="search" type="search" name="search">
                    </div>
                </div>
                <div class="search-options">
                    <button class="search-option" data-target-name="filter" onclick="show_list(this)"><i class="icon-filter"></i> <span>Filtrowanie</span></button>
                    <button class="search-option" data-target-name="sort" onclick="show_list(this)"><i class="icon-sort-alt-down"></i> <span>Sortowanie</span></button>
                    <button class="search-option-direction search-interract desc" onclick="change_search_direction(this)"><i class="icon-down-open"></i></button>
                </div>
            </div>
            <div class="list-container">
                <div class="list"></div>
                <div class="search-option-list" data-list-name="filter">
                    <div class="content scrollbar">
                        <label class="input-container search-interract">
                            <input type="radio" name="filter" value="0">
                            <div class="checkmark-radio"></div>
                            <div class="title">Wszystkie</div>
                        </label>
                        <label class="input-container search-interract">
                            <input type="radio" name="filter" value="1" checked>
                            <div class="checkmark-radio"></div>
                            <div class="title">Aktualne</div>
                        </label>
                        <label class="input-container search-interract">
                            <input type="radio" name="filter" value="2">
                            <div class="checkmark-radio"></div>
                            <div class="title">Ukończone</div>
                        </label>
                        <label class="input-container search-interract">
                            <input type="radio" name="filter" value="3">
                            <div class="checkmark-radio"></div>
                            <div class="title">O wyższym priorytecie</div>
                        </label>
                        <label class="input-container search-interract">
                            <input type="radio" name="filter" value="4">
                            <div class="checkmark-radio"></div>
                            <div class="title">Nie rozliczone</div>
                        </label>
                        <label class="input-container search-interract">
                            <input type="radio" name="filter" value="5">
                            <div class="checkmark-radio"></div>
                            <div class="title">Moje projekty</div>
                        </label>
                    </div>
                </div>
                <div class="search-option-list" data-list-name="sort">
                    <div class="content scrollbar">
                        <label class="input-container search-interract">
                            <input type="radio" name="sort" value="0" checked>
                            <div class="checkmark-radio"></div>
                            <div class="title">Po dacie utworzenia</div>
                        </label>
                        <label class="input-container search-interract">
                            <input type="radio" name="sort" value="1">
                            <div class="checkmark-radio"></div>
                            <div class="title">Po dacie ukończenia</div>
                        </label>
                        <label class="input-container search-interract">
                            <input type="radio" name="sort" value="2">
                            <div class="checkmark-radio"></div>
                            <div class="title">Po nazwie</div>
                        </label>
                        <label class="input-container search-interract">
                            <input type="radio" name="sort" value="3">
                            <div class="checkmark-radio"></div>
                            <div class="title">Po priorytecie</div>
                        </label>
                        <label class="input-container search-interract">
                            <input type="radio" name="sort" value="4">
                            <div class="checkmark-radio"></div>
                            <div class="title">Po typie projektu</div>
                        </label>
                        <label class="input-container search-interract">
                            <input type="radio" name="sort" value="5">
                            <div class="checkmark-radio"></div>
                            <div class="title">Po nazwie klienta</div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div id="content">
            <div class="loading-text">Wczytywanie <i class="icon-spin4"></i></div>
        </div>
        <div class="options-panel">
            <div class="list2">
                <div class="top-options">
                    <button class="option" onclick="show_profile()" data-choose-element="profile">
                        <i class="icon-user-circle-o"></i>
                        <div class="tooltip">Profil</div>
                    </button>
                    <?php if($this->manage_users) { ?>
                    <button class="option" onclick="show_page(this)" data-choose-element="manage-users">
                        <i class="icon-users"></i>
                        <div class="tooltip">Zarządzanie użytkownikami</div>
                    </button>
                    <?php } ?>
                    <div class="separator"></div>
                    <?php if($this->manage_project) { ?>
                    <button class="option project" onclick="edit_project()" data-choose-element="edit-project">
                        <i class="icon-edit"></i>
                        <div class="tooltip">Edytuj projekt</div>
                    </button>
                    <button class="option project" onclick="save_project()" data-choose-element="save-project">
                        <i class="icon-floppy"></i>
                        <div class="tooltip">Zapisz projekt</div>
                    </button>
                    <button class="option project" onclick="cancel_project()" data-choose-element="cancel-project">
                        <i class="icon-cancel"></i>
                        <div class="tooltip">Anuluj</div>
                    </button>
                    <button class="option project" onclick="end_project()" data-choose-element="end-project">
                        <i class="icon-check"></i>
                        <div class="tooltip">Zakończ projekt</div>
                    </button>
                    <button class="option project show" onclick="add_project()" data-choose-element="add-project">
                        <i class="icon-doc-add"></i>
                        <div class="tooltip">Dodaj projekt</div>
                    </button>
                    <button class="option project" onclick="delete_project()" data-choose-element="delete-project">
                        <i class="icon-trash-empty"></i>
                        <div class="tooltip">Usuń projekt</div>
                    </button>
                    <?php } ?>
                    <button class="option project" onclick="copy_link_project()" data-choose-element="copy-link-project">
                        <i class="icon-export"></i>
                        <div class="tooltip">Kopiuj link</div>
                    </button>
                </div>
                <div class="bottom-options">
                    <div class="separator"></div>
                    <button class="option" onclick="show_page(this)" data-choose-element="help">
                        <i class="icon-help-circled"></i>
                        <div class="tooltip">Pomoc</div>
                    </button>
                    <a href="api/logout.php" class="option">
                        <i class="icon-logout"></i>
                        <div class="tooltip">Wyloguj</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>