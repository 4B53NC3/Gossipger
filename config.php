<?php
$host = 'localhost';
$db   = 'gossipger';
$user = 'gossipger4fancz';
$pass = '4ReaNZnM1r8U';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new \PDO($dsn, $user, $pass, $opt);