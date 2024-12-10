<?php
require_once '../php_require/session.php';
?>

<head>
    <title>Chat public</title>
</head>

<body>

    <?php
    if (isset($_SESSION['pseudo']) && !empty($_SESSION['pseudo']) && isset($_POST['message']) && !empty($_POST['message'])) {

        $req = $bdd->prepare('INSERT INTO messages_public_chat(message_author, message_content, message_date, message_ip) VALUES(:message_author, :message_content, :message_date, :message_ip)');
        $req->execute(array(
            'message_author' => $_SESSION['pseudo'],
            'message_content' => htmlspecialchars($_POST['message']),
            'message_date' => date("Y-m-d H:i:s"),
            'message_ip' => $ip
        ));

        header('location: /index.php?src=public_chat');
    }
    ?>

    <?php
    require_once '../php_require/navbar.php';
    ?>

    <main>
        <h1>Chat public</h1>
        <section id="chat">

            <?php
            if (!isset($_POST['lmm_counter'])) {
                $lmm_counter = 1;
            } else {
                $lmm_counter = $_POST['lmm_counter'] + 1;
            }
            $nbr_msg_to_load = intval($lmm_counter) * 20;

            $req_message = $bdd->query("SELECT * FROM messages_public_chat ORDER BY ID DESC LIMIT " . $nbr_msg_to_load);

            $message_load_counter = $req_message->rowCount();

            $more_msg = true;
            if ($message_load_counter < $nbr_msg_to_load) {
                $more_msg = false;
            };

            while ($response_message = $req_message->fetch()) {

                $req_account = $bdd->query("SELECT * FROM accounts WHERE user_pseudo = '" . $response_message['message_author'] . "' ");
                $response_account = $req_account->fetch();

                $date = date('d/m/Y \à H\hi', strtotime($response_message['message_date']));
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
                    <p>
                        <?= $date ?>
                    </p>
                    <div class="img-pseudo">
                        <div><?= $avatar ?></div>
                        <h4><?= $response_message['message_author'] ?></h4>
                    </div>
                    <p><?= nl2br($message) ?></p>
                </div>

                <?php
            }

            if ($more_msg) {
                ?>
                <form action="public_chat.php#spawn" method="POST">
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

        <form method="POST">
            <div>
                Connecté en tant que <strong><?= $_SESSION['pseudo']; ?></strong>
            </div>
            <?php
            if (!isset($_SESSION['status'])) {
                echo '<a class="link1" href="/account/registration.php">Créez un compte</a> pour obtenir un pseudo personnalisé';
            }
            ?>
            <div>
                <textarea name="message" id="message" rows="3"
                    placeholder='Envoyer un message... ("ENTRER" pour envoyer, "MAJ" + "ENTRER" pour retourner à la ligne)'></textarea>
            </div>
            <button id="chatRefreshBtn">Actualiser</button>
        </form>

    </main>
    <?php
    require_once '../php_require/footer.php';
    ?>
</body>

</html>