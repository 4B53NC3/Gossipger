<?php
//INSERT INTO `login` (`ID`, `username`, `password`, `name`, `picture`) VALUES (NULL, 'ruzekja', '1903', 'Honzíček Růžek', '/img/profile.jpg');
session_start();
include('config.php');

if(isset($_POST['submit'])) {
    $sql = 'SELECT COUNT(*)
            FROM login
            WHERE username = ?
            LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['username']]);
    $data = $stmt->fetch();


    if($data['COUNT(*)'] == 0) {
        if($_POST['password'] == $_POST['second-password']) {
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql = 'INSERT INTO login (ID, username, password, name, online, picture, admin)
                    VALUES (NULL, ?, ?, "", ?, "img/profile.png", 0)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$_POST['username'], $pass, time()]);
            $data = $stmt->fetch();
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['name'] = '';
            $_SESSION['admin'] = false;
            header('Location: index.php');
            exit();
        }
        else
            $err = 'Hesla se neshodují!';
    }
    else
        $err = 'Uživatelské jméno je obsazeno.';
}
?>
<!doctype html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="registration_modification.css">
    <title>Registrace - Gossipger</title>
</head>
<body>
    <header>
        <h1>Gossipger</h1>
        <h2>Registrace</h2>
    </header>
    <section>
        <form method="post">
            <label for="username">Uživatelské jméno:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Heslo:</label>
            <input type="password" name="password" id="password" required>
            <label for="second-password">Heslo znovu:</label>
            <input type="password" name="second-password" id="second-password" required>
            <input type="submit" name="submit" value="Zaregistrovat">
        </form>
        <?php

        if(isset($err)) {
            echo('<p style="color: red">'.$err.'</p>');
        }

        ?>
    </section>
</body>
</html>
