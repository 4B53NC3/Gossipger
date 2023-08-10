<?php
session_start();
include ('config.php');


if(isset($_POST['submit'])) {
    $sql = 'SELECT password, ID, name, admin FROM login WHERE username = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['username']]);
    $data = $stmt->fetch();

    if(password_verify($_POST['password'], $data['password'])) {
        $_SESSION['id'] = $data['ID'];
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['name'] = $data['name'];
        if($data['admin'] == 1)
        $_SESSION['admin'] = true;
        else
            $_SESSION['admin'] = false;
        $sql = 'UPDATE login SET online = ? WHERE username = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([time(), $_SESSION['username']]);
        header('Location: index.php');
        exit();
    }
    else {
        $err = 'Neplatné uživatelské údaje!';
    }
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
    <title>Přihlášení Gossipger</title>
</head>
<body>
    <header>
        <h1>Gossipger</h1>
        <h2>Přihlášení</h2>
    </header>
    <section>
        <form method="post">
            <label for="username">Přihlašovací jméno:</label>
            <input type="text" name="username" id="username" placeholder="Přihlašovací jméno" required>
            <label for="password">Heslo:</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" name="submit" value="Přihlásit se">
        </form><br>
        <a id="registration" href="registration.php">Registrace</a>
        <?php

        if(isset($err)) {
            echo('<p style="color: red">'.$err.'</p>');
        }

        ?>
    </section>
</body>
</html>
