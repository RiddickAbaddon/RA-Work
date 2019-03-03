<?php
$password = $_GET["p"];


echo password_hash($password, PASSWORD_DEFAULT);