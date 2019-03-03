<?php
$config = include __DIR__ . '/../config.php';
$config = $config["database"];

require_once __DIR__ . '/../utils.php';

try {
    $connect = new PDO(
        "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8",
        $config['db_user'],
        $config['db_password'],
        [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
    $connect->exec("set names utf8");
} catch(PDOException $error) {
    add_log('[Error] Błąd łączenia się z bazą danych. '. $error);
    exit('Błąd łączenia z bazą danych');
}

return $connect;