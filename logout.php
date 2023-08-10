<?php
session_start();
include('config.php');

$sql = 'UPDATE login SET online = 0 WHERE username = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['username']]);
$data = $stmt->fetch();
session_destroy();
session_unset();
header('Location: login.php');
exit();