<?php
function add_log($text) {
    $text = '[' . date('H:i:s') . '] ' . $text;

    $file = __DIR__ . '/logs/' . date('d-m-Y') . '__log.txt';
    if (file_exists($file)) {
        $fh = fopen($file, 'a');
        fwrite($fh, $text."\n");
    } else {
        $fh = fopen($file, 'w');
        fwrite($fh, $text."\n");
    }
    fclose($fh);

    $config = require(__DIR__ . "/config.php");
    $log_dir = scandir(__DIR__ . '/logs');
    $log_dir_size = sizeof($config["logs_limit"]);
    if($log_dir_size > ($config["logs_limit"] + 3)) {
        for($i = 3; $i < $log_dir_size - ($max_logs - 1); $i++) {
            unlink(__DIR__ . '/logs/' . $log_dir[$i]);
        }
    }
}

function pre($object) {
    echo '<pre>';
    print_r($object);
    echo '</pre>';
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function update_permissions() {
    if(isset($_SESSION["user_id"])) {
        $db = new Database();
        $permissions = $db->check_permissions($_SESSION["user_id"]);
        $_SESSION["permissions"] = $permissions;
        $block = $db->is_block($_SESSION["user_id"]);
        if($block) {
            session_destroy();
        }
    }
}