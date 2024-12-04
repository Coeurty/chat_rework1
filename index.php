<?php
if (isset($_GET['src'])) {
    if ($_GET['src'] == "members_chat") {
        echo '<script>window.location.href="./chat/members_chat.php#spawn";</script>';
    }
    if ($_GET['src'] == "public_chat") {
        echo '<script>window.location.href="./chat/public_chat.php#spawn";</script>';
    }
} else {
    echo '<script>window.location.href="./home.php";</script>';
}