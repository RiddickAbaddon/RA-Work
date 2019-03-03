<?php
session_start();
$response = array();
if(isset($_POST["action"])) {
    $class = require_once __DIR__ . '/database.php';
    $db = new Database();
    
    require_once __DIR__ . '/../utils.php';
    update_permissions();
    
    switch($_POST["action"]) {
        case "delete": {
            if($_SESSION["permissions"]["manage_users"]) {
                if(isset($_POST["user"])) {
                    if($_POST["user"] != $_SESSION["user_id"]) {
                        $db->delete_user($_POST["user"]);
                        $response["success"] = true;
                        echo json_encode($response);
                    } else {
                        $response["success"] = false;
                        $response["message"] = 'Nie możesz usunąć swojego konta z tego miejsca';
                        echo json_encode($response);
                    }
                } else {
                    $response["success"] = false;
                    $response["message"] = 'Nie przekazano wszystkich argumentów';
                    echo json_encode($response);
                }
            } else {
                $response["success"] = false;
                $response["message"] = 'Nie masz wystarczających uprawnień aby wykonać tą akcje';
                echo json_encode($response);
            }
            break;
        }
        case "delete_my_account": {
            if($_POST["user"] == $_SESSION["user_id"]) {
                $db->delete_user($_POST["user"]);
                session_destroy();
                $response["success"] = true;
                echo json_encode($response);
            } else {
                $response["success"] = false;
                $response["message"] = 'Przekazano błędny argument: ' . $_POST["user"] . ' == ' . $_SESSION["user_id"];
                echo json_encode($response);
                pre($_SESSION);
            }
            break;
        }
        case "add_user": {
            if($_SESSION["permissions"]["manage_users"]) {
                if(isset($_POST["permissions"])) {
                    add_user($_POST["permissions"], $db);
                } else {
                    $response["success"] = false;
                    $response["message"] = 'Nie przekazano wszystkich argumentów';
                    echo json_encode($response);
                }
            } else {
                $response["success"] = false;
                $response["message"] = 'Nie masz wystarczających uprawnień aby wykonać tą akcje';
                echo json_encode($response);
            }
            break;
        }
        case "add_reader": {
            if($_SESSION["permissions"]["add_reader"]) {
                add_user(1, $db);
            } else {
                $response["success"] = false;
                $response["message"] = 'Nie masz wystarczających uprawnień aby wykonać tą akcje';
                echo json_encode($response);
            }
            break;
        }
        case "change_permissions": {
            if($_SESSION["permissions"]["manage_users"]) {
                if(isset($_POST["user"]) && isset($_POST["permissions"])) {
                    if($_POST["user"] != $_SESSION["user_id"]) {
                        $permissions = $db->get_permissions();
                        $permission_levels = array();
                        foreach($permissions as $perm) {
                            array_push($permission_levels, $perm["level"]);
                        }

                        if(in_array($_POST["permissions"], $permission_levels)) {
                            $db->update_user_permissions($_POST["user"], $_POST["permissions"]);
                            $response["success"] = true;
                            echo json_encode($response);
                        } else {
                            $response["success"] = false;
                            $response["message"] = 'Niewłaściwa wartość uprawnień';
                            echo json_encode($response);
                        }
                    } else {
                        $response["success"] = false;
                        $response["message"] = 'Nie możesz zmienić uprawnień swojego konta';
                        echo json_encode($response);
                    }
                } else {
                    $response["success"] = false;
                    $response["message"] = 'Nie przekazano wszystkich argumentów';
                    echo json_encode($response);
                }
            } else {
                $response["success"] = false;
                $response["message"] = 'Nie masz wystarczających uprawnień aby wykonać tą akcje';
                echo json_encode($response);
            }
            break;
        }
        case "lock_account": {
            if($_SESSION["permissions"]["manage_users"]) {
                if(isset($_POST["user"])) {
                    $is_lock = $db->is_lock_account($_POST["user"]);
                    if($is_lock) {
                        $db->lock_account($_POST["user"], 0);
                    } else {
                        $db->lock_account($_POST["user"], 1);
                    }
                    $response["success"] = true;
                    echo json_encode($response);
                } else {
                    $response["success"] = false;
                    $response["message"] = 'Nie przekazano wszystkich argumentów';
                    echo json_encode($response);
                }
            } else {
                $response["success"] = false;
                $response["message"] = 'Nie masz wystarczających uprawnień aby wykonać tą akcje';
                echo json_encode($response);
            }
            break;
        }
        case "delete_group": {
            if($_SESSION["permissions"]["manage_groups"]) {
                if(isset($_POST["group"])) {
                    $db->delete_group($_POST["group"]);
                    $response["success"] = true;
                    echo json_encode($response);
                } else {
                    $response["success"] = false;
                    $response["message"] = 'Nie przekazano wszystkich argumentów';
                    echo json_encode($response);
                }
            } else {
                $response["success"] = false;
                $response["message"] = 'Nie masz wystarczających uprawnień aby wykonać tą akcje';
                echo json_encode($response);
            }
            break;
        }
        case "update_group": {
            if($_SESSION["permissions"]["manage_groups"]) {
                if(isset($_POST["group"]) && isset($_POST["name"])) {
                    $add = array();
                    $delete = array();
                    if(isset($_POST["add_users"])) {
                        $add = $_POST["add_users"];
                    }
                    if(isset($_POST["delete_users"])) {
                        $delete = $_POST["delete_users"];
                    }
                    $group_id = $_POST["group"];
                    $old_name = $db->get_group_name($group_id);
                    $name = $_POST["name"];

                    $err = false;

                    if($name != $old_name) {
                        if(strlen($name) < 3) {
                            $err = true;
                            $response["success"] = false;
                            $response["message"] = 'Nazwa grupy musi zawierać conajmniej 3 znaki';
                            echo json_encode($response);
                        }
                        if(strlen($name) > 32 && !$err) {
                            $err = true;
                            $response["success"] = false;
                            $response["message"] = 'Nazwa grupy musi zawierać maksymalnie 32 znaki';
                            echo json_encode($response);
                        }
                        if(!$err) {
                            if($db->is_group($name)) {
                                $err = true;
                                $response["success"] = false;
                                $response["message"] = 'Już istnieje grupa o takiej nazwie';
                                echo json_encode($response);
                            }
                        }
                    } else {
                        $name = null;
                    }
                    if(!$err) {
                        $db->update_group($_POST["group"], $add, $delete, $name);
                        $response["success"] = true;
                        echo json_encode($response);
                    }
                    
                } else {
                    $response["success"] = false;
                    $response["message"] = 'Nie przekazano wszystkich argumentów';
                    echo json_encode($response);
                }
            } else {
                $response["success"] = false;
                $response["message"] = 'Nie masz wystarczających uprawnień aby wykonać tą akcje';
                echo json_encode($response);
            }
            break;
        }
        case "add_group": {
            if($_SESSION["permissions"]["manage_groups"]) {
                if(isset($_POST["name"])) {
                    $name = $_POST["name"];
                    $err = false;
                    if(strlen($name) < 3 && !$err) {
                        $response["success"] = false;
                        $response["message"] = 'Nazwa grupy musi zawierać conajmniej 3 znaki';
                        echo json_encode($response);
                    }
                    if(strlen($name) > 32 && !$err) {
                        $response["success"] = false;
                        $response["message"] = 'Nazwa grupy musi zawierać maksymalnie 32 znaki';
                        echo json_encode($response);
                    }
                    if(!$err) {
                        if(!$db->is_group($name)) {
                            $db->add_group($name);
                            $response["success"] = true;
                            echo json_encode($response);
                        } else {
                            $response["success"] = false;
                            $response["message"] = 'Już istnieje grupa o takiej nazwie';
                            echo json_encode($response);
                        }
                    }
                } else {
                    $response["success"] = false;
                    $response["message"] = 'Nie przekazano wszystkich argumentów';
                    echo json_encode($response);
                }
            } else {
                $response["success"] = false;
                $response["message"] = 'Nie masz wystarczających uprawnień aby wykonać tą akcje';
                echo json_encode($response);
            }
            break;
        }
    }
} else {
    $response["success"] = false;
    $response["message"] = 'Nie wybrano akcji';
    echo json_encode($response);
}

function add_user($permissions, $db) {
    if(isset($_POST["email"])) {
        $email = $_POST["email"];
        $err = false;

        if ($email == '') {
            $err = true;
            $response["success"] = false;
            $response["message"] = 'Wpisz poprawny adres email';
        }
        
        if(strlen($email) > 64 && !$err) {
            $err = true;
            $response["success"] = false;
            $response["message"] = 'Adres email może zawierać maksymalnie 64 znaków';
        } 
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !$err) {
            $err = true;
            $response["success"] = false;
            $response["message"] = 'Wpisz poprawny adres email';
        }
        if(!$err) {
            if($db->is_user_email($email)) {
                $err = true;
                $response["success"] = false;
                $response["message"] = 'Już istnieje użytkownik z takim adresem email';
            }
        }
        
        if(!$err) {
            if(!checkdnsrr(array_pop(explode("@",$email)),"MX")) {
                $err = true;
                $response["success"] = false;
                $response["message"] = 'Taki email nie istnieje';
            }
        }

        if(!$err) {
            include __DIR__ . '/email.php';
            $db->init_user($email, $permissions);
            $id_new_user = $db->get_user_id_by_email($email);
            $code = $db->add_temp_code(2, $id_new_user, $email);
            $message = '
<p>Stwórz nowe konto w RA Work. Poniżej znajduje się link aktywacyjny:</p>
<a href="http://work.marcin-kalinowski.pl/register?code=' . $code . '&id=' . $id_new_user . '">http://work.marcin-kalinowski.pl/register?code=' . $code . '&id=' . $id_new_user . '</a>
            ';
            $mail = new send_email($email, $email, "RA Work | Rejestracja", $message);
            if(!($mail->send())) {
                add_log('[ERROR] Nie udało się wysłać maila. Błąd: ' . $mail->getEmailError());
                exit('Nie udało się wysłać wiadomości e-mail.');
            }
            $response["success"] = true;
        }
        echo json_encode($response);


    } else {
        $response["success"] = false;
        $response["message"] = 'Nie przekazano wszystkich argumentów';
        echo json_encode($response);
    }
}