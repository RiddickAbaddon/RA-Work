<?php
session_start();

if(
    isset($_GET["loaded_projects"]) &&
    isset($_GET["projects_per_query"]) &&
    isset($_GET["filter"]) &&
    isset($_GET["sort"]) &&
    isset($_GET["direction"]) &&
    isset($_GET["phrase"]) &&
    isset($_SESSION["login"])
) {
    $err = false;
    $user_id = $_SESSION["user_id"];
    if(isset($_GET["as_profile"])) {
        $user_id = $_GET["as_profile"];
    }
    if(!$err) {
        require_once __DIR__ . '/database.php';

        require_once __DIR__ . '/../utils.php';
        update_permissions();

        $db = new Database();
        $config = include __DIR__ . '/../config.php';
        $filter = $config["filter"][(int)$_GET["filter"]];
        if($_GET["filter"] == 5) {
            $filter .= $user_id;
        }
        $args = [
            "offset" => $_GET["loaded_projects"],
            "limit" => $_GET["projects_per_query"],
            "filter" => $filter,
            "sort" => $config["sort"][(int)$_GET["sort"]],
            "direction" => $_GET["direction"],
            "phrase" => $_GET["phrase"],
            "user_id" => $user_id
        ];
        $db_response = $db->get_project_list($args);
        if($db_response) {
            $result = [
                "data" => $db_response["data"],
                "end" => $db_response["end"]
                // ,"query" => $db_response["query"]
            ];
            echo json_encode($result);
        } else {
            $result = ["data" => []];
            echo json_encode($result);
        }
    }
} else {
    $result = ["data" => []];
    echo json_encode($result);
}