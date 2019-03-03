<?php
session_start();

if(isset($_POST["login"]) && isset($_POST["password"])) {
    if($_POST["login"] != '' && $_POST["password"] != '') {
        require_once __DIR__ . '/database.php';
        $db = new Database();
        $result = $db->check_login($_POST["login"], $_POST["password"]);
        
        if($result["check"]) {
            $_SESSION["login"] = $result["login"];
            $_SESSION["user_id"] = $result["id"];
            $_SESSION["user_email"] = $result["email"];
            $_SESSION["permissions"] = $result["permissions"];
            $response["success"] = true;
            echo json_encode($response);
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