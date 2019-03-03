<?php
session_start();
$this->page_error = false;
$this->add_reader = false;
$this->manage_users = false;
$this->manage_groups = false;
$this->isroot = false;

$this->users_list = array();
$this->groups_list = array();
$this->permissions_list = array();
$this->my_id = $_SESSION["user_id"];
if(
    $_SESSION["permissions"]["manage_users"] ||
    $_SESSION["permissions"]["manage_groups"] ||
    $_SESSION["permissions"]["add_reader"]
) {
    $config = include $this->get_config_path();
    $db = new Database();

    if($_SESSION["permissions"]["manage_users"]) {
        $this->manage_users = true;
        $this->users_list = $db->get_all_users();
        $this->permissions_list = $db->get_permissions();
    }
    else if($_SESSION["permissions"]["add_reader"]) {
        $this->add_reader = true;
    }

    if($_SESSION["permissions"]["manage_groups"]) {
        $this->groups_list = $db->get_all_groups();
        $this->manage_groups = true;
    }
    if($_SESSION["permissions"]["level"] == 99) {
        $this->isroot = true;
    }

} else {
    $this->page_error = true;
}