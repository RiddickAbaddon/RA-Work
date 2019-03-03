<?php
session_start();

if(isset($_POST["login"]) && isset($_POST["password"]) && isset($_POST["code"])) {
    if($_POST["login"] != '' && $_POST["password"] != '') {
        require_once __DIR__ . '/database.php';
        $db = new Database();
        $result = $db->check_login($_POST["login"], $_POST["password"]);
        
        if($result["check"]) {
            $data = $db->check_temp_code('1', $result["id"], $_POST["code"]);
            if($data) {
                $db->delete_temp_code('1', $result["id"]);
                $db->update_user_email($data, $result["id"]);

                $_SESSION["login"] = $result["login"];
                $_SESSION["user_id"] = $result["id"];
                $_SESSION["user_email"] = $data;
                $_SESSION["permissions"] = $result["permissions"];
                $response["success"] = true;
                echo json_encode($response);
            } else {
                $response["success"] = false;
                $response["message"] = "Nie znaleziono instrukcji zmiany adresu email dla tego konta";
                echo json_encode($response);
            }

        } else {
            $response["success"] = false;
            $response["message"] = $result["err_message"];
            echo json_encode($response);
        }
    } else {
        $response["success"] = false;
        $response["message"] = "Podaj login i hasło";
        echo json_encode($response);
    }
} else {
    $response["success"] = false;
    $response["message"] = "Podaj login i hasło";
    echo json_encode($response);
}