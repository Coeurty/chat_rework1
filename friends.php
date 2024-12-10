<?php
require_once 'php_require/session.php';
if (!isset($_SESSION['status'])) {
    header('location: /account/login.php?src=not_allowed');
}
?>

<head>
    <title>Amis</title>
</head>

<body>
    <?php
    require_once 'php_require/navbar.php';
    ?>

    <main>
        <h1>Amis</h1>
        <h2>Demandes d'amis en attentes</h2>
        <?php
        if (isset($_POST['requester-pseudo']) && (isset($_POST['accept-friend']) || isset($_POST['refuse-friend']))) {

            $checkRequest = $bdd->prepare(
                "SELECT R.requester, R.receiver
                        FROM relations AS R
                        JOIN accounts AS A
                        ON A.user_id = R.requester
                        WHERE R.receiver = :receiver_id
                          AND A.pseudo = :requester_pseudo
                          AND R.is_accepted = 0;"
            );
            $checkRequest->execute([
                'receiver_id' => $_SESSION['id'],
                'requester_pseudo' => $_POST['requester-pseudo']
            ]);

            $friendRequest = $checkRequest->fetch();

            if ($friendRequest) {
                $requesterId = $friendRequest['requester'];
                $receiverId = $friendRequest['receiver'];

                if (isset($_POST['accept-friend'])) {
                    $updateRequest = $bdd->prepare(
                        "UPDATE relations
                                    SET is_accepted = 1
                                    WHERE requester = :requester_id AND receiver = :receiver_id;"
                    );
                    $updateRequest->execute([
                        'requester_id' => $requesterId,
                        'receiver_id' => $receiverId
                    ]);
                } elseif (isset($_POST['refuse-friend'])) {
                    $deleteRequest = $bdd->prepare(
                        "DELETE FROM relations
                                WHERE requester = :requester_id AND receiver = :receiver_id;"
                    );
                    $deleteRequest->execute([
                        'requester_id' => $requesterId,
                        'receiver_id' => $receiverId
                    ]);
                }
            }
        }

        $getPendingFriendRequests = $bdd->prepare(
            "SELECT A.pseudo
                    FROM relations as R
                    JOIN accounts as A
                    ON A.user_id = R.requester
                    where R.receiver = :receiver_id AND R.is_accepted = 0;"
        );
        $getPendingFriendRequests->execute(["receiver_id" => $_SESSION["id"]]);
        $pendingFriends = $getPendingFriendRequests->fetchAll();
        if ($pendingFriends) {
            echo "<ul>";
            foreach ($pendingFriends as $pendingFriend) {
                $pendingFriendPseudo = $pendingFriend['pseudo'];
                ?>
                <form method="POST">
                    <li style="display: flex;">
                        <div style="margin-right: 2rem;"><?= $pendingFriendPseudo ?></div>
                        <input type="hidden" name="requester-pseudo" value="<?= $pendingFriendPseudo ?>">
                        <div>
                            <input type="submit" name="accept-friend" value="Accepter">
                        </div>
                        <div>
                            <input type="submit" name="refuse-friend" value="Refuser">
                        </div>
                    </li>
                </form>
                <?php
            }
            echo "</ul>";
        } else {
            echo "<p>Aucune demandes d'amis en attentes.</p>";
        }
        ?>

        <h2>Ajouter un ami</h2>
        <form method="POST">
            <div>
                <label for="newFriendPseudo">Pseudo : </label> <input type="text" id="newFriendPseudo"
                    name="newFriendPseudo" require_onced>
            </div>
            <input type="submit" name="new-friend" value="Envoyer">
        </form>

        <?php
        if (isset($_POST['new-friend']) && isset($_POST['newFriendPseudo'])) {
            $findAccountByPseudo = $bdd->prepare('SELECT user_id FROM accounts WHERE pseudo = :pseudo');
            $findAccountByPseudo->execute(['pseudo' => $_POST['newFriendPseudo']]);
            $foundAccount = $findAccountByPseudo->fetch();

            if (!$foundAccount) {
                echo '<p style="color: red;">Aucune compte trouvé avec ce pseudo</p>';
            } else {
                // TODO: empêcher les demandes d'amis vers soi-même et les doublons
                $createRelation = $bdd->prepare('INSERT INTO relations(requester, receiver) VALUES(:requester_id, :receiver_id)');
                $createRelation->execute(
                    array(
                        'requester_id' => $_SESSION['id'],
                        'receiver_id' => $foundAccount['user_id']
                    ),
                );

                echo '<p style="color: lightseagreen;">Invitation envoyé</p>';
            }
        }
        ?>
    </main>
    <?php
    require_once 'php_require/footer.php';
    ?>
</body>

</html>