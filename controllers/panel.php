<?php
session_start();
$config = require $this->get_config_path();
$db = new Database();
$this->project_name = $config["project_name"];

$priorities = json_encode($db->get_priorities());
$this->manage_users = false;
$this->manage_project = $_SESSION["permissions"]["manage_project"];
if(
    $_SESSION["permissions"]["add_reader"] ||
    $_SESSION["permissions"]["manage_users"] ||
    $_SESSION["permissions"]["manage_groups"]
) {
    $this->manage_users = true;
} 
$this->add_js_var('projects_per_query', $config["projects_per_query"]);
$this->add_js_var('priorities', $priorities);
$this->add_js_var('user_id', $_SESSION["user_id"]);