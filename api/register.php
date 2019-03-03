<?php
session_start();
$response = array();
require_once __DIR__ . '/database.php';
$db = new Database();
if(
    isset($_POST["name"]) &&
    isset($_POST["pass"]) &&
    isset($_POST["pass1"]) &&
    isset($_POST["code"]) &&
    isset($_POST["profile"])
) {
    $name = $_POST["name"];
    $pass = $_POST["pass"];
    $pass1 = $_POST["pass1"];
    $code = $_POST["code"];
    $profile = $_POST["profile"];

    $err = false;

    if(
        $name != '' &&
        $pass != '' &&
        $pass1 != '' &&
        $profile != '' &&
        !$err
    ) {
        // Name validation
        if(strlen($name) < 3 && !$err) {
            $err = true;
            $response["check"] = false;
            $response["message"] = 'Nazwa użytkownika musi zawierać conajmniej 3 znaki';
        }
        if(strlen($name) > 32 && !$err) {
            $err = true;
            $response["check"] = false;
            $response["message"] = 'Nazwa użytkownika musi zawierać maksymalnie 32 znaki';
        }
        if(!$err) {
            if($db->is_user_name($name)) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Już istnieje użytkownik o takiej nazwie';
            }
        }

        // Password validation
        if($pass != $pass1) {
            $err = true;
            $response["check"] = false;
            $response["message"] = 'Hasło w obu polach musi być takie samo';
        }
        if(strlen($pass) < 8 && !$err) {
            $err = true;
            $response["check"] = false;
            $response["message"] = 'Hasło musi zawierać minimum 8 znaków';
        }
        if(strlen($pass) > 64 && !$err) {
            $err = true;
            $response["check"] = false;
            $response["message"] = 'Hasło musi zawierać maksymnalnie 64 znaków';
        }
        if(strlen($pass) > 124 && !$err) {
            $err = true;
            $response["check"] = false;
            $response["message"] = 'Hasło jest za długie';
        }
        if(!preg_match('/[a-z]/', $pass) && !$err) {
            $err = true;
            $response["check"] = false;
            $response["message"] = 'Hasło musi zawierać przynajmniej jedną małą literę';
        }
        if(!preg_match('/[A-Z]/', $pass) && !$err) {
            $err = true;
            $response["check"] = false;
            $response["message"] = 'Hasło musi zawierać przynajmniej jedną dużą literę';
        }
        if(!preg_match('/[0-9]/', $pass) && !$err) {
            $err = true;
            $response["check"] = false;
            $response["message"] = 'Hasło musi zawierać przynajmniej jedną cyfrę';
        }
        if(!preg_match('/[!@#\$%\^&\*\?_~]/', $pass) && !$err) {
            $err = true;
            $response["check"] = false;
            $response["message"] = 'Hasło musi zawierać przynajmniej jeden znak specjalny';
        }
        if(!$err) {
            $data = $db->check_temp_code('2', $profile, $code);
            if(!$data) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Nie znaleziono akcji rejestracji dla twojego konta';
            }
        }
        if(!$err) {
            $db->update_user_login($name, $profile);
            $db->update_user_password($pass, $profile);
            $db->delete_temp_code(2, $profile);
            $response["check"] = true;
        }
        
    } else {
        $response["check"] = false;
    }
} else {
    $response["check"] = false;
}
echo json_encode($response);