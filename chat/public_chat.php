<?php
require_once '../php_require/session.php';
if (!function_exists('apcu_enabled') || !apcu_enabled()) {
    header('Location: /account/login.php');
    exit();
}
?>

<head>
    <title>Chat public</title>
</head>

<body>
    <?php
    // TODO: apcu utilise la ram => limiter taille des messages, nb messages stockés, nb messages envoyables
    function getPublicChatMessages(): array
    {
        $messages = apcu_fetch("publicChatMessages") ?: [];
        usort($messages, function ($a, $b) {
            return strtotime($a["date"]) - strtotime($b["date"]);
        });
        return array_reverse($messages);
    }

    function addMessage($newMessage): void
    {
        $messages = getPublicChatMessages();
        $messages[] = $newMessage;
        apcu_store("publicChatMessages", $messages);
    }

    if (isset($_SESSION['pseudo']) && !empty($_SESSION['pseudo']) && isset($_POST['message']) && !empty($_POST['message'])) {
        $newMessage = [
            'author' => $_SESSION['pseudo'],
            'content' => htmlspecialchars($_POST['message']),
            'date' => date("Y-m-d H:i:s")
        ];
        addMessage($newMessage);
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
            foreach (getPublicChatMessages() as $response_message) {
                $findAccount = $bdd->query("SELECT * FROM accounts WHERE pseudo = '" . $response_message['author'] . "' ");
                $foundAccount = $findAccount->fetch();

                $date = date('d/m/Y \à H\hi', strtotime($response_message['date']));
                $message = str_replace(" ", "&nbsp;", $response_message['content']);

                $avatar = "";
                if ($foundAccount && $foundAccount['avatar']) {
                    $avatar = "<img class='mini-avatar' src='" . $foundAccount['avatar'] . "'>";
                }

                $userMessageClass = "";
                if (isset($_SESSION['pseudo']) && $_SESSION['pseudo'] == $response_message['author']) {
                    $userMessageClass = "user-message";
                }
                ?>

                <div class="<?= $userMessageClass ?>">
                    <p>
                        <?= $date ?>
                    </p>
                    <div class="img-pseudo">
                        <div><?= $avatar ?></div>
                        <h4><?= $response_message['author'] ?></h4>
                    </div>
                    <p><?= nl2br($message) ?></p>
                </div>

                <?php
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