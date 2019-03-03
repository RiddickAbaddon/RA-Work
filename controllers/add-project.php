<?php
session_start();
$this->error = true;
if($_SESSION["permissions"]["manage_project"]) {
    $config = require_once $this->get_config_path();
    $db = new Database();
    $this->error = false;

    $this->priorities = $db->get_priorities();
    $this->groups_list = $db->get_all_groups();

    $this->allocation = $_SESSION["permissions"]["allocation"];
    if($this->allocation) {
        $this->users_list = $db->get_all_users();
    }
}