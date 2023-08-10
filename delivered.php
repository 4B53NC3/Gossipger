<?php
session_start();
include('config.php');

if(!empty($_SESSION['username'])) {
    $sql = 'SELECT * FROM messages WHERE ((id > (SELECT MAX(id) - 50 FROM messages)) AND (recipient = ? OR recipient = ? OR user = ?)) ORDER BY time';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['username'], '', $_SESSION['id']]);
    $data = $stmt->fetchAll();
    
    function getMax($array)
    {
        $max = 0;
        foreach ($array as $k => $v) {
            $max = max(array($max, $v['ID']));
        }
        return $max;
    }

    /*  MESSAGE ECHO FUNCTIONS  */
    function MyMessage($message, $recipient, $messageHTM) {
        echo('<p class="me tooltip"><span style="color: #44A9B7">' .htmlspecialchars($recipient).' </span>' . $messageHTM . '<span class="span span-right">('
            . date('d. m. H:i:s', $message['time']) . ')</span></p><br>
        ');
    }
    function MyMessageLast($message, $last_id, $recipient,$messageHTM) {
        echo('<input type="hidden" id="last" value="' . $last_id . '"></input><p class="me tooltip" id="last-message"><span style="color: #44A9B7">'.$recipient.' </span>' . $messageHTM . '<span class="span span-right">('
            . date('d. m. H:i:s', $message['time']) . ')</span></p><br>');
    }
    function AnotherMessage($message, $user, $recipientStyle,$messageHTM) {
        echo('
            <p class="another tooltip"><span class="span span-left"><span '.$recipientStyle.'>(' . date('d. m. H:i:s', $message['time']) . ') 
                <b>' . htmlspecialchars($user) . ':</b> </span></span>
                ' . $messageHTM . '
                </p>'
        );
    }
    function AnotherMessageLast($message, $user, $last_id, $recipientStyle,$messageHTM) {
        echo('
            <input type="hidden" id="last" value="' . $last_id . '"></input><p class="another tooltip" id="last-message"><span '.$recipientStyle.'><span class="span span-left">
                    (' . date('d. m. H:i:s', $message['time']) . ')
                <b>' . htmlspecialchars($user) . ':</b> </span></span>
                ' . $messageHTM . '</p>
            ');
    }


    $i = 1;
    $count = count($data);
    $last_id = getMax($data);
    $lastIsMy = false;

    echo('
<style>
    .me {text-align: right; position: absolute; right: 0; width: 500px; word-wrap: break-word}
    .another {text-align: left; width: 500px; word-wrap: break-word}
    /*.tooltip {position: relative}
    .span {visibility: hidden;width: 120px;background-color: #fff;
                    color: black;text-align: center;padding: 5px 0;
                    border-radius: 6px;position: absolute;z-index: 1}
    .tooltip .span-left {left: 10%;top:-5px}
    .tooltip .span-right {right: 10%;top:-5px}
    .tooltip:hover span {visibility: visible}*/
</style>
');

    foreach ($data as $message) {
            $messageHTML = htmlspecialchars($message['message']);
            $messageHTML = str_replace('[br]', '<br>', $messageHTML);
            $sql = 'SELECT username, name FROM login WHERE ID = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$message['user']]);
            $userDB = $stmt->fetch();

            $user = $userDB['name'] != '' ? $userDB['name'] : $userDB['username'];



            if ($userDB['username'] != $_SESSION['username']) {
                $recipientStyle = ($message['recipient']=='') ? '':' style="color:#FF4A42"';
                if ($i == $count) {
                    AnotherMessageLast($message, $user, $last_id, $recipientStyle, $messageHTML);
                    $lastIsMy = false;
                } else {
                    AnotherMessage($message, $user, $recipientStyle, $messageHTML);
                }
            }
            else {
                $recipient = ($message['recipient']=='') ? '':'@'.$message['recipient'];
                if ($i == $count) {
                    MyMessageLast($message, $last_id, $recipient, $messageHTML);
                    $lastIsMy = true;
                } else
                    MyMessage($message, $recipient, $messageHTML);
            }
        $i++;
    }

    if ($_GET['last'] != $last_id && !empty($_GET['last']) && !$lastIsMy) {
        echo('
    <script>audio.play();</script>
    ');
    }
}
else {
    header('Location: login.php');
}