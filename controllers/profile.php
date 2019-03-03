<?php
session_start();
if($_GET["id"] == $_SESSION["user_id"] || $_SESSION["permissions"]["manage_users"] == 1) {
    $config = include $this->get_config_path();
    $db = new Database();

    $this->can_show = true;
    
    if($_GET["id"] == $_SESSION["user_id"] || $_SESSION["permissions"]["level"] == 99) {
        $this->can_edit = true;
    } else {
        $this->can_edit = false;
    }
    if($_SESSION["permissions"]["level"] == 99) {
        $this->is_root = true;
    } else {
        $this->is_root = false;
    }

    $user = $db->get_user($_GET["id"]);
    if($user) {
        $this->user_name = $user["login"];
        $this->email = $user["email"];

        $this->user = $user;
        $this->account_type = $user["permissions"]["name"];
        $this->permissions = array();
        foreach($config["permission_names"] as $key => $value) {
            if($user["permissions"][$key] == 1) {
                array_push($this->permissions, $value);
            }
        }
        $this->groups = $db->get_user_groups($user["id"]);
        
        $priorities = json_encode($db->get_priorities());
        $this->add_js_var('priorities', $priorities);
        $this->add_js_var('profile_id', $user["id"]);
    } else {
        $this->can_show = false;
    }
} else {
    $this->can_show = false;
    $this->can_edit = false;
}