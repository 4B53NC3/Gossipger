<?php
session_start();

if(!isset($_SESSION['username'])) {
    header('Location: login.php');
}
?>

<!doctype html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <link href="img/logo.png">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Gossipger</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function settings() {
            let dropdown = document.getElementById("dropdown-content");
            if (dropdown.style.display == "none")
                dropdown.style.display = "block";
            else
                dropdown.style.display = "none";
        }
        
        function showEmoji() {
            let content = document.getElementById("emoji-content");
            if (content.style.display == "none")
                content.style.display = "block";
            else
                content.style.display = "none";
        }

        function addEmoji(emoji) {
            $("#message").val($("#message").val() + emoji.value);
        }

        function insertUser(user) {
            $("#message").val("/msg "+user+ " ");
            $("#message").focus();
            
        }
        
        let isVisible = true;
        let observer = new IntersectionObserver(function (entries) {
            if (entries[0].isIntersecting === true) {
                if (entries[0].intersectionRatio > 0.1)
                    isVisible = true;
            } else
                isVisible = false;

        }, {threshold: [0, 0.1]});

        function helpButton() {
            $.post("send.php",
                {
                    "message": "/help"
                }
            );
            refreshMessages();
        }

        function send() {
            <?php
            if(!$_SESSION['admin']) {
                echo('
            if(!IsSending){
                IsSending = true;
                $.post("send.php",
                    {
                        "message": $("#message").val()
                    },
                    function (data) {
                        $("#message").val("");
                        IsSending = false;
                    }
                );
                $("#gossip-section").scrollTop($("#gossip-section").height());
                refreshMessages();
            }
            ');
            }
            else {
                echo('
            if($("#message").val()!="/admin") {
            if(!IsSending){
                IsSending = true;
                $.post("send.php",
                    {
                        "message": $("#message").val()
                    },
                    function (data) {
                        $("#message").val("");
                        IsSending = false;
                    }
                );
                $("#gossip-section").scrollTop($("#gossip-section").height());
                refreshMessages();
            }
            }
            else {
            $.post("admin.php",
                {
                    "message": $("#message").val()
                },
                function (data) {
                    $("#gossip-section").html(data);
                }
            );
            }
            ');
            }
            ?>
        }

        function basicRefreshMessages() {
            $.get("delivered.php",{
                "last":$("#last").val()
            }, function (data) {
                $("#gossip-section").html(data);
            });
            if (isVisible)
                $("#gossip-section").scrollTop($("#gossip-section").height());
        }

        function refreshMessages() {
            
            if($("#message").val() != "/admin") {
                //observer.observe(document.querySelector("#last-message"));
                observer.observe(document.getElementById("last-message"));
                basicRefreshMessages();
            }
        }

        function refreshUsers() {
            $.get("users.php", function (data) {
                $("#users-section").html(data);
            });
        }

        let audio = document.createElement('audio');
        let IsSending = false;

        window.onload = function() {
            audio.src='sounds/alert.mp3';
            refreshUsers();
            setTimeout("basicRefreshMessages();",2800);
            setInterval(refreshMessages, 3000);
            setInterval(refreshUsers, 30000);
        }
    </script>
</head>
<body>
<header class="borders">
    <h1>GOSSIPGER</h1>
            <button id="settings" onclick="settings()">NASTAVENÍ</button>
            <div id="dropdown-content">
                <form method="post" id="form-set" action="settings.php" enctype="multipart/form-data">
                    <label for="nickname">Přezdívka:</label><br>
                    <input id="nickname" name="nickname" type="text"><br>
                    <label for="password-change">Změna hesla:</label><br>
                    <input type="password" id="password-change" name="password-change"><br><br>
                    <label for="profile-pic">Profilovka:</label><br>
                    <input type="file" name="userfile" id="profile-pic" accept="image/*"><br><br>
                   
                    <button type="submit">Uložit změny</button>
                </form>
                <button type="button" onclick="helpButton()">O aplikaci</button><br>
                <a href= "logout.php">Odhlásit se</a>
            </div>
</header>
<main>
    <section class="borders" id="users-section">
        <h2>Online uživatelé:</h2>
    </section>
    <section class="borders" id="gossip-section">
        <div id="background-div"> <img src="img/logo-background.png">
        </div>
    </section>
    <section class="borders" id="message-field">
        <div id="message-field2">
            <form action="javascript:send(null)" autocomplete="off">
                <input id="message" type="text" name="message" placeholder="Zpráva">
                <button type="submit" id="send">Odeslat</button>
                <button type="button" id="emoji-btn" onclick="showEmoji()">&#x1F600</button>
            </form>
            <div id="emoji-content">
                <?php
                for($i = 128512; $i<128592;$i++) {
                    echo('
                            <button onclick="addEmoji(this)" class="emoji" value="&#'.$i.'">&#'.$i.'</button>
                        ');
                }
                for($i = 128147; $i<128158;$i++) {
                    echo('
                            <button onclick="addEmoji(this)" class="emoji" value="&#'.$i.'">&#'.$i.'</button>
                        ');
                }
                ?>
            </div>
        </div>
    </section>
</main>
</body>
</html>
