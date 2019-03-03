<?php
session_start();

if(
    isset($_SESSION["login"]) &&
    isset($_POST["project_id"])
) {
    if($_SESSION["permissions"]["manage_project"]) {
        require_once __DIR__ . '/../utils.php';
        require_once __DIR__ . '/database.php';
    
        $project_id = $_POST["project_id"];
        $to_database = array();
        $db = new Database();
    
        foreach($_FILES as $key => $value) {
            $filename = generateRandomString(10) . "_" . $value["name"];
            while(file_exists(__DIR__ . '/../files/' . $filename)) {
                $filename = generateRandomString(10) . "_" . $value["name"];
            }

            $target = __DIR__ . '/../files/' . $filename;
            move_uploaded_file( $_FILES[$key]['tmp_name'], $target);
            
            $name = str_replace("_", " ", $key);
            array_push($to_database, [
                "name" => $name,
                "url" => "/files/" . $filename
            ]);
        }
        $db->add_upload_files($project_id, $to_database);
    
        $response["success"] = true;
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