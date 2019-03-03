<?php
session_start();
if(
    isset($_POST["id"]) &&
    isset($_POST["name"]) &&
    isset($_POST["type"]) &&
    isset($_POST["client"]) &&
    isset($_POST["settled"]) &&
    isset($_POST["priority"]) &&
    isset($_POST["onlygroup"]) &&
    isset($_SESSION["login"])
) {
    if($_SESSION["permissions"]["manage_project"]) {
        require_once __DIR__ . '/database.php';
        $db = new Database();

        $project_id = $_POST["id"];
        $name = $_POST["name"];
        $type = $_POST["type"];
        $client = $_POST["client"];
        $attachment = (isset($_POST["attachments"]) ? $_POST["attachments"] : null);
        $delete_old_attachments = (isset($_POST["delete_old_attachments"]) ? $_POST["delete_old_attachments"] : null);
        $settled = $_POST["settled"];
        $priority = $_POST["priority"];
        $onlygroup = $_POST["onlygroup"];
        $allocate = (isset($_POST["allocate"]) ? $_POST["allocate"] : []);
        $description = (isset($_POST["description"]) ? $_POST["description"] : "");

        $response = array();
        $err = false;
        if(strlen($name) < 3) {
            $err = true;
            $response["success"] = false;
            $response["message"] = "Nazwa projektu musi zawierać conajmniej 3 znaki";
        }
        if(!$err) {
            if($db->is_project_name($name, $project_id)) {
                $err = true;
                $response["success"] = false;
                $response["message"] = "Już istnieje projekt o takiej nazwie";    
            }
        }
        if(strlen($type) < 3 && !$err) {
            $err = true;
            $response["success"] = false;
            $response["message"] = "Typ projektu musi zawierać conajmniej 3 znaki";
        }
        if(strlen($client) < 3 && !$err) {
            $err = true;
            $response["success"] = false;
            $response["message"] = "Nazwa klienta musi zawierać conajmniej 3 znaki";
        }
        if(!($settled == 0 || $settled == 1)) {
            $err = true;
            $response["success"] = false;
            $response["message"] = "Nie właściwa wartość: rozliczony";
        }
        if(!$err && $priority != 0) {
            $priorities = $db->get_priorities();
            $priorities_id = array();
            foreach($priorities as $key => $value) {
                array_push($priorities_id, $key);
            }
            if(!in_array($priority, $priorities_id)) {
                $err = true;
                $response["success"] = false;
                $response["message"] = "Nie właściwa wartość: priorytet";    
            }
        }
        if(!$err && $onlygroup != 0) {
            $groups = $db->get_all_groups();
            $groups_id = array();
            foreach($groups as $group) {
                array_push($groups_id, $group["id"]);
            }
            if(!in_array($onlygroup, $groups_id)) {
                $err = true;
                $response["success"] = false;
                $response["message"] = "Nie właściwa wartość: przydzielone do grupy";    
            }
        }
        if(!$err && sizeof($allocate) > 0) {
            $users = $db->get_all_users();
            $users_id = array();
            foreach($users as $user) {
                array_push($users_id, $user["id"]);
            }
            foreach($allocate as $allocate_user) {
                if(!in_array($allocate_user, $users_id)) {
                    $err = true;
                    $response["success"] = false;
                    $response["message"] = "Nie właściwa wartość: przydzielone do użytkowników";    
                    break;
                }
            }
        }
        if(!$err) {
            $intro = strip_tags($description);
            $intro = substr($intro, 0, 255);

            $args = array(
                "id" => $project_id,
                "name" => $name,
                "client" => $client,
                "type" => $type,
                "settled" => $settled,
                "description" => $description,
                "intro" => $intro,
                "priority" => $priority,
                "onlygroup" => $onlygroup,
                "allocate" => $allocate,
                "attachments" => $attachment,
                "delete_old_attachments" => $delete_old_attachments
            );
            $db->edit_project($args);
            // pre($attachment);
            $response["success"] = true;
        }

        echo json_encode($response);
    } else {
        $response["success"] = false;
        $response["message"] = "Nie masz wystarczających uprawnień do wykonania tej akcji";
        echo json_encode($response);
    }
} else {
    $response["success"] = false;
    $response["message"] = "Nie przesłano wszystkich argumentów";
    echo json_encode($response);
}