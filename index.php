<?php
session_start();
$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
$request = $uri_parts[0];

require_once __DIR__ . '/api/database.php';
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/view_generator.php';

update_permissions();

switch ($request) {
    case '/': {
        if(isset($_SESSION["login"])) {
            $view->load_view('panel', 'Indeks projektów');
        } else {
            $view->load_view('login', 'Logowanie');
        }
        break;
    }
    case '/project': {
        if(isset($_GET["id"])) {
            if(isset($_SESSION["login"])) {
                $view->load_view('project', "Projekt");
            } else {
                $view->load_view('onlylogged', 'Brak uprawnień');
            }
        } else {
            $view->load_view('404', 'Błąd 404');
        }
        break;
    }
    case '/group': {
        if(isset($_GET["id"])) {
            if(isset($_SESSION["login"])) {
                $view->load_view('group', "Grupa");
            } else {
                $view->load_view('onlylogged', 'Brak uprawnień');
            }
        } else {
            $view->load_view('404', 'Błąd 404');
        }
        break;
    }
    case '/profile': {
        if(isset($_GET["id"])) {
            if(isset($_SESSION["login"])) {
                $view->load_view('profile', "Profil");
            } else {
                $view->load_view('onlylogged', 'Brak uprawnień');
            }
        } else {
            $view->load_view('404', 'Błąd 404');
        }
        break;
    }
    case '/change_email': {
        if(isset($_GET["code"])) {
            $view->load_view('change_email', "Zmiana hasła");
        } else {
            $view->load_view('404', 'Błąd 404');
        }
        break;
    }
    case '/manage-users': {
        if(isset($_SESSION["login"])) {
            $view->load_view('manage_users', 'Zarządzanie użytkownikami');
        } else {
            $view->load_view('onlylogged', 'Brak uprawnień');
        }
        break;
    }
    case '/generate-password': {
        $view->load_view('generate_password', 'Generator hasła');
        break;
    }
    case '/register': {
        if(isset($_GET["code"]) && isset($_GET["id"])) {
            $view->load_view('register', "Rejestracja");
        } else {
            $view->load_view('404', 'Błąd 404');
        }
        break;
    }
    case '/edit-project': {
        
        if(isset($_GET["id"])) {
            if(isset($_SESSION["login"])) {
                $view->load_view('edit_project', "Edytuj projekt");
            } else {
                $view->load_view('onlylogged', 'Brak uprawnień');
            }
        } else {
            $view->load_view('404', 'Błąd 404');
        }
        break;
    }
    case '/add-project': {
        if(isset($_SESSION["login"])) {
            $view->load_view('add_project', "Dodaj projekt");
        } else {
            $view->load_view('onlylogged', 'Brak uprawnień');
        }
        break;
    }
    case '/help': {
        $view->load_view('help', "Pomoc");
        break;
    }
    case '/reset-password': {
        if(isset($_GET["code"]) && isset($_GET["id"])) {
            $view->load_view('reset_password_step2', "Resetowanie hasła");
        } else {
            $view->load_view('reset_password_step1', "Resetowanie hasła");
        }
        break;
    }
    default: {
        $view->load_view('404', 'Błąd 404');
        break;
    }
}
