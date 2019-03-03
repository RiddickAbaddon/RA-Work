<?php
session_start();

$db = new Database();

$this->group = $db->get_group($_GET["id"], $_SESSION["user_id"], $_SESSION["permissions"]["level"]);
$this->priorities = $db->get_priorities();
