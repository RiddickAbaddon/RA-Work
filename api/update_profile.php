<?php
session_start();
$response = array();
require_once __DIR__ . '/database.php';

require_once __DIR__ . '/../utils.php';
update_permissions();

$db = new Database();
if(
    isset($_POST["name"]) &&
    isset($_POST["email"]) &&
    isset($_POST["pass1"]) &&
    isset($_POST["pass2"]) &&
    isset($_POST["pass3"]) &&
    isset($_SESSION["login"]) &&
    isset($_POST["user"])
) {
    $access = true;
    if($_POST["user"] != $_SESSION["user_id"] && $_SESSION["permissions"]["level"] != 99) {
        $access = false;
    }
    if($access) {
        $user_id = $_POST["user"];
        $cur_user = $db->get_user($user_id);
        $cur_name = $cur_user["login"];
        $cur_email = $cur_user["email"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $pass1 = $_POST["pass1"];
        $pass2 = $_POST["pass2"];
        $pass3 = $_POST["pass3"];
    
        $err = false;
        $change_name = false;
        $change_email = false;
        $change_password = false;
    
        // Valid name
        if($name != '' && !$err) {
            if($name == $cur_name && !$err) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Nowa nazwa użytkownika musi się różnić od starej';
            }
            if(strlen($name) < 3 && !$err) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Nazwa użytkownika musi zawierać conajmniej 3 znaki';
            }
            if(strlen($name) > 32 && !$err) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Nazwa użytkownika musi zawierać Maksymalnie 32 znaki';
            }
            if(!$err) {
                if($db->is_user_name($name)) {
                    $err = true;
                    $response["check"] = false;
                    $response["message"] = 'Już istnieje użytkownik o takiej nazwie';
                }
            }
            
            if(!$err) {
                $change_name = true;
            }
        }
    
        // Valid email
        if($email != '' && !$err) {
            if(strlen($email) > 64 && !$err) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Adres email może zawierać maksymalnie 64 znaków';
            } 
            if($email == $cur_email && !$err) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Nowy adres email musi się różnić od starego';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !$err) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Wpisz poprawny adres email';
            }
            if(!$err) {
                if($db->is_user_email($email)) {
                    $err = true;
                    $response["check"] = false;
                    $response["message"] = 'Już istnieje użytkownik z takim adresem email';
                }
            }
            
            if(!$err) {
                if(!checkdnsrr(array_pop(explode("@",$email)),"MX")) {
                    $err = true;
                    $response["check"] = false;
                    $response["message"] = 'Taki email nie istnieje';
                }
            }
    
            if(!$err) {
                $change_email = true;
            }
        }
    
        // Valid password
        if($pass1 != '' && $pass2 != '' && $pass3 != '' && !$err) {
            if($pass1 == $pass2) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Nowe hasło musi się różnić od starego';
            }
            if($pass2 != $pass3) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Nowe hasło w obu polach musi być takie samo';
            }
            if(strlen($pass2) < 8 && !$err) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Nowe hasło musi zawierać minimum 8 znaków';
            }
            if(strlen($pass2) > 64 && !$err) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Nowe hasło musi zawierać maksymalnie 64 znaków';
            }
            if(!preg_match('/[a-z]/', $pass2) && !$err) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Hasło musi zawierać przynajmniej jedną małą literę';
            }
            if(!preg_match('/[A-Z]/', $pass2) && !$err) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Hasło musi zawierać przynajmniej jedną dużą literę';
            }
            if(!preg_match('/[0-9]/', $pass2) && !$err) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Hasło musi zawierać przynajmniej jedną cyfrę';
            }
            if(!preg_match('/[!@#\$%\^&\*\?_~]/', $pass2) && !$err) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Hasło musi zawierać przynajmniej jeden znak specjalny';
            }
            if(!$err) {
                if(!$db->check_password($pass1, $user_id)) {
                    $err = true;
                    $response["check"] = false;
                    $response["message"] = 'Stare hasło jest nieprawidłowe';
                }
            }
    
            
            if(!$err) {
                $change_password = true;
            }
        }
    
        if(!$err) {
            $response["update"] = array();
            
            if($change_name) {
                $db->update_user_login($name, $user_id);
                $_SESSION["login"] = $name;
                array_push($response["update"], "name");
            }
            if($change_password) {
                $db->update_user_password($pass2, $user_id);
                array_push($response["update"], "password");
            }
            if($change_email) {
                include __DIR__ . '/email.php';
    
                $code = $db->add_temp_code(1, $user_id, $email);
                $message = '
<p>Jeśli chcesz zmienić adres e-mail do którego jest podłączone twoje konto kliknij w poniższy link i zaloguj się.</p>
<a href="http://work.marcin-kalinowski.pl/change_email?code=' . $code . '">http://work.marcin-kalinowski.pl/change_email?code=' . $code . '</a>
                ';
                $mail = new send_email($cur_name, $email, "RA Work | Zmiana adresu email", $message);
                if(!($mail->send())) {
                    add_log('[ERROR] Nie udało się wysłać maila. Błąd: ' . $mail->getEmailError());
                    exit('Nie udało się wysłać wiadomości e-mail.');
                }
                array_push($response["update"], "email");
            }
            
            $response["check"] = true;
        } else {
            $response["check"] = false;

        }
    } else {
        $response["check"] = false;
    }
    
} else {
    $response["check"] = false;
}
echo json_encode($response);