<?php
session_start();
include('config.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $allowed = array('png', 'jpg');
    $filename = $_FILES['userfile']['name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if(!in_array($ext, $allowed) &&($_FILES['userfile']['name']!='')) {
        echo('<p>Špatný formát obrázku!<br>Povolené formáty: jpg, png');
    }
    else {
        if(($_FILES['userfile']['name']!='')) {
            $tmpFile = $_FILES['userfile']['tmp_name'];
            $newFile = './img/profiles/' . $_SESSION['username'] . '/' . time() . '.jpg';
            if (!is_dir("./img/profiles/" . $_SESSION["username"] . "/")) {
                mkdir("./img/profiles/" . $_SESSION["username"] . "/");
            }
            $result = move_uploaded_file($tmpFile, $newFile);
            if($_POST['nickname']!='') {
                $sql = 'UPDATE login SET name = ?, picture = ? WHERE username = ?';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_POST[nickname], $newFile, $_SESSION[username]]);
                $_SESSION['name'] = $_POST['nickname'];
            }
            else {
                $sql = 'UPDATE login SET picture = ? WHERE username = ?';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$newFile, $_SESSION[username]]);
            }
        }
        else {
            $sql = 'UPDATE login SET name = ? WHERE username = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$_POST[nickname], $_SESSION[username]]);
            $_SESSION['name'] = $_POST['nickname'];
        }
        header('Location: index.php');
        exit();
    }
}