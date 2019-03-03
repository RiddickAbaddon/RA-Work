<?php
$response = array();
if(
    isset($_POST["pass"]) &&
    isset($_POST["pass1"]) &&
    isset($_POST["code"]) &&
    isset($_POST["profile"])
) {
    require_once __DIR__ . '/database.php';
    $db = new Database();

    $pass = $_POST["pass"];
    $pass1 = $_POST["pass1"];
    $code = $_POST["code"];
    $profile = $_POST["profile"];
    $err = false;
    if(
        $pass != '' &&
        $pass1 != ''
    ) {
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
            $data = $db->check_temp_code('3', $profile, $code);
            if(!$data) {
                $err = true;
                $response["check"] = false;
                $response["message"] = 'Nie znaleziono akcji resetowania hasła dla twojego konta';
            }
        }
        if(!$err) {
            $db->update_user_password($pass, $profile);
            $db->delete_temp_code(3, $profile);
            $response["check"] = true;
        }
    } else {
        $response["check"] = false;
        $response["message"] = "Nie podano wszystkich argumentów";
    }
    
} else {
    $response["check"] = false;
    $response["message"] = "Nie podano wszystkich argumentów";
}
echo json_encode($response);