<?php

$db_host = 'localhost';
$db_user = 'system';
$db_password = 'system';
$db_name = 'login_setup';

try {
    $db = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOEXCEPTION $e) {
    echo $e->getMessage();
}