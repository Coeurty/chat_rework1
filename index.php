<?php
if (isset($_GET['src'])) {
    if ($_GET['src'] == "members_chat") {
        header('location: /chat/members_chat.php#spawn');
    }
    if ($_GET['src'] == "public_chat") {
        header('location: /chat/public_chat.php#spawn');
    }
} else {
    header('location: /account/login.php');
}