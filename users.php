<?php
session_start();
include('config.php');

$lastOnline = time();

$sql = 'UPDATE login SET online = ? WHERE username = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$lastOnline, $_SESSION['username']]);

$sql = 'SELECT username, name, picture FROM login WHERE online > (? - 100)';
$stmt = $pdo->prepare($sql);
$stmt->execute([$lastOnline]);
$data = $stmt->fetchAll();
?>
<h2>Online uživatelé:</h2>
<style>
   #me {
       position: absolute;
       bottom: 0;
       border-top: black 1px solid;
       text-align: center;
       width: 100%;
       height: 50px;
   }

   .center-name {
       font-size: 16px;
       font-weight: 600;
       position: relative;
       bottom: 17px;
       margin-left: 5px;
   }
   .user-name{
       display:none;
       font-size: 12px;
       font-weight: normal;
       position: relative;
       bottom: 18px;
       margin-left: 5px;
       color: #292929;
   }
    .another-users:hover .user-name{
       display: inline;
       
    }
   .another-users {
       margin-left: 10px;
       height: 50px;
   }

</style>
<?php
$myUser = '';
$name = '';
foreach($data as $user) {
    if($user['name']!='')
        $name = $user['name'];
    else
        $name = $user['username'];
    if($_SESSION['username'] != $user['username']) {
        echo('<p type="button" class="another-users" onclick="insertUser(\''.$user['username'].'\')"><img width="50" height="50" src="'.$user['picture'].'"><span class="center-name">'.htmlspecialchars($name).'</span> <span class="user-name">'.htmlspecialchars($user['username']).'</span></p>');
    }
    else {
        $myUser = $user;
    }
}
if($myUser['name']!='')
    $name = $myUser['name'];
else
    $name = $myUser['username'];
echo('<div id="me"><img width="50" height="50" src="'.$myUser['picture'].'"><span class="center-name">'.htmlspecialchars($name).'</span></div>');
?>