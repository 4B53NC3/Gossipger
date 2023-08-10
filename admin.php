<?php
session_start();
include('config.php');
if($_SESSION['admin']) {
    if(isset($_POST['delete'])) {
        $sql = 'DELETE from messages WHERE ID < (SELECT MAX(ID) - 100 FROM messages)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        header('Location: index.php');
    }
    if(isset($_POST['new-admin'])) {
        $sql = 'UPDATE login SET admin = 1 WHERE username = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['new-admin']]);
        header('Location: index.php');
    }
}
else {
    header('Location: index.php');
}

?>
<style>
    #admin-section {
        margin: 50px;
    }
</style>
<div id="admin-section">
    <form action="admin.php" method="post">
        <input type="hidden" name="delete" value="delete">
    <input type="submit" value="Smazat staré zprávy">
    </form>
    <form action="admin.php" method="post">
        <label for="new-admin">Nový admin:</label>
        <input type="text" id="new-admin" name="new-admin">
        <input type="submit" value="Nastavit">
    </form>
    <button onclick='$("#message").val("");basicRefreshMessages()'>Ukončit</button>
</div>