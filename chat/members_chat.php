<?php
require('../php_require/session.php');
if (!isset($_SESSION['status'])) {
    echo '<script>window.location.href="/account/login.php?src=not_allowed";</script>';
}
?>

<head>
    <title>Chat des membres</title>
</head>

<body>

    <?php
    if (isset($_SESSION['pseudo']) && !empty($_POST['message'])) {

        $req = $bdd->prepare('INSERT INTO messages_members_chat(message_author, message_content, message_date, message_ip) VALUES(:message_author, :message_content, :message_date, :message_ip)');
        $req->execute(array(
            'message_author' => $_SESSION['pseudo'],
            'message_content' => htmlspecialchars($_POST['message']),
            'message_date' => date("Y-m-d H:i:s"),
            'message_ip' => $ip
        ));

        echo '<script>window.location.href="/index.php?src=members_chat";</script>';
    }
    ?>

    <?php
    require('../php_require/navbar.php');
    ?>

    <main>
        <h1>Chat des membres</h1>
        <section id="chat">

            <?php
            if (!isset($_POST['lmm_counter'])) {
                $lmm_counter = 1;
            } else {
                $lmm_counter = $_POST['lmm_counter'] + 1;
            }
            $nbr_msg_to_load = $lmm_counter * 20;

            $req_message = $bdd->query("SELECT * FROM messages_members_chat ORDER BY ID DESC LIMIT 0," . $nbr_msg_to_load . " ");

            $message_load_counter = $req_message->rowCount();

            $more_msg = true;
            if ($message_load_counter < $nbr_msg_to_load) {
                $more_msg = false;
            };

            while ($response_message = $req_message->fetch()) {

                $req_account = $bdd->query("SELECT * FROM accounts WHERE user_pseudo = '" . $response_message['message_author'] . "' ");
                $response_account = $req_account->fetch();

                // setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
                // $date = strftime('%A %e %B à %H h %M', strtotime($response_message['message_date']));
                $date = date('d/m/Y \à H\hi', strtotime($response_message['message_date']));
                // $message = $response_message['message_content']);
                $message = str_replace(" ", "&nbsp;", $response_message['message_content']);

                $avatar = "";
                if ($response_account['user_avatar']) {
                    $avatar = "<img class='mini-avatar' src='" . $response_account['user_avatar'] . "'>";
                }

                $addId = "";
                if (isset($_SESSION['pseudo'])) {
                    if ($response_message['message_author'] == $_SESSION['pseudo']) {
                        $addId = 'id="mine"';
                    }
                }
            ?>

            <div <?= $addId ?>>
                <p><?= $date ?></p>
                <div class="img-pseudo">
                    <div><?= $avatar ?></div>
                    <h4><?= $response_message['message_author'] ?></h4>
                </div>
                <p>
                    <?= nl2br($message) ?>
                </p>
            </div>

            <?php
            }

            if ($more_msg) {
            ?>
            <form action="members_chat.php" method="POST">
                <label for="load-more-msg">
                    <h4 class="centered underline-hover" style="cursor: pointer;">Charger plus de message</h4>
                </label>
                <input type="submit" id="load-more-msg" style="display: none;" name="lmm_counter"
                    value="<?= $lmm_counter ?>">
            </form>
            <?php
            } else {
                echo "<h4 class='centered'>Il n'y a plus de message à charger</h4>";
            }
            ?>

        </section>
        <hr id="spawn">

        <?php
        if (isset($_SESSION['status'])) {
        ?>
        <form action="members_chat.php#spawn" method="POST">
            <div>
                Connecté en tant que <strong><?= $_SESSION['pseudo']; ?></strong>
            </div>
            <div>
                <label for="message">Message :</label><br>
                <textarea name="message" id="message" rows="10"></textarea>
            </div>
            <input type="submit" id="submit" name="message_submit" value="Envoyer">
            <button id="refreshBTN">Actualiser</button>
        </form>
        <?php
        } else {
            echo '<a class="link1" href="/account/login.php">Connectez-vous</a> pour envoyer des messages';
        }
        ?>

    </main>
    <?php
    require('../php_require/footer.php');
    ?>
</body>

<script src="/js/jquery.js"></script>
<script src="/js/script.js"></script>

</html>