<?php
session_start();
include('config.php');

if(!empty($_SESSION['username'])) {
    if ($_POST['message']) {
        $message = trim($_POST['message']);
        if (!empty($message)) {
            if(mb_strpos($message, '/msg ') === 0) {
                $message = str_replace('/msg ', '', $message);
                $cutMessage = explode(' ', $message);
                $recipient = $cutMessage[0];
                $cutMessage[0] = '';
                $message = implode(' ', $cutMessage);
                $message = trim($message);
                if(!empty($message)) {
                    $sql = 'INSERT INTO messages (user, message, time, recipient) VALUES (?, ?, ?, ?)';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$_SESSION['id'], $message, time(), $recipient]);
                }
            }
            else if($message == '/help') {
                $sql = 'INSERT INTO messages (user, message, time, recipient) VALUES (?,?,?,?)';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['26', '/msg <user> <message> - pošle soukromou zprávu uživateli[br]
                /help - zobrazí nápovědu[br]
                Autoři: Jiří Richter, Simona Richterová, Jan Růžek, Lucie Vacková',time(), $_SESSION['username']]);
            }
            else {
                    $sql = 'INSERT INTO messages (user, message, time) VALUES (?, ?, ?)';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$_SESSION['id'], $_POST['message'], time()]);
            }
        }
    }
}
else {
    header('Location: login.php');
}