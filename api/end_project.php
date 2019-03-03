<?php
session_start();
$response = array();
if(
    isset($_POST["id"]) &&
    isset($_SESSION["login"])
) {
    if($_SESSION["permissions"]["manage_project"]) {
        require_once __DIR__ . '/database.php';
        $db = new Database();

        if($db->end_project($_POST["id"])) {
            $response["success"] = true;
            echo json_encode($response);
        } else {
            $response["success"] = false;
            echo json_encode($response);
        }
    } else {
        $response["success"] = false;
        echo json_encode($response);
    }
} else {
    $response["success"] = false;
    echo json_encode($response);
}