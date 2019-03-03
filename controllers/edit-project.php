<?php
session_start();
$this->error = true;
if($_SESSION["permissions"]["manage_project"]) {
    $config = require_once $this->get_config_path();
    $db = new Database();
    
    $project_data = $db->get_project($_GET["id"], $_SESSION["user_id"]);
    if($project_data["search"] && $project_data["data"]["end_date"] == NULL) {
        $this->error = false;
        $this->priorities = $db->get_priorities();
        $this->groups_list = $db->get_all_groups();
    
        $this->allocation = $_SESSION["permissions"]["allocation"];
        if($this->allocation) {
            $this->users_list = $db->get_all_users();
        }
        
        $this->project_data = $project_data["data"];
        $this->project_attachments = $project_data["attachments"];
        $this->project_members = $db->get_project_members($_GET["id"]);
        $this->add_js_var('old_attachments', json_encode($this->project_attachments));
        $this->add_js_var('edited_id', $_GET['id']);
    }
    
    // pre($this->project_data);
}