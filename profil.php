<?php
require_once 'php_require/session.php';
if (!isset($_SESSION['status'])) {
    header('location: /account/login.php?src=not_allowed');
}

require_once 'php_require/db_queries.php';
?>

<?php
if (isset($_POST["remove_friend"]) && isset($_POST["pseudo"])) {
    removeFriend($bdd, $_SESSION["id"], $_POST["pseudo"]);
}

if (isset($_POST["add_friend"]) && isset($_POST["pseudo"])) {
    createFriendRequest($bdd, $_SESSION["id"], $_POST["pseudo"]);
}
?>

<?php
if (isset($_GET['pseudo']) && !empty($_GET['pseudo'])) {
    $pseudo = $_GET['pseudo'];
} else {
    $pseudo = $_SESSION["pseudo"];
}

$findAccountByPseudo = $bdd->prepare(
    "SELECT pseudo, avatar 
            FROM accounts
            WHERE pseudo = :pseudo"
);
$findAccountByPseudo->execute(["pseudo" => $pseudo]);
$foundAccount = $findAccountByPseudo->fetch();

if (!$foundAccount) {
    $titlePage = "Profil introuvable";
} else {
    $titlePage = "Profil de $pseudo";
}

?>

<head>
    <title><?= $titlePage ?></title>
</head>

<body>
    <?php
    require_once 'php_require/navbar.php';
    ?>
    <main>
        <?php
        if (!$foundAccount) {
            ?>
            <p style="color: red;">Utilisateur introuvable</p>
            <?php
        } else {
            ?>
            <h1>
                <?= $pseudo ?>
            </h1>
            <?php
            if ($foundAccount['avatar']) {
                echo "<div><img class='avatar' src='" . $foundAccount["avatar"] . "'></div>";
            }
            if ($foundAccount["pseudo"] !== $_SESSION["pseudo"]) {
                ?>
                <form method="post">
                    <input type="hidden" name="pseudo" value="<?= $pseudo ?>">
                    <?php
                    if (isFriendByPseudo($bdd, $_SESSION["id"], $pseudo)) {
                        echo '<button type="submit" name="remove_friend">Retirer de mes amis</button>';
                    } else {
                        echo '<button type="submit" name="add_friend">Envoyer une demande d\'ami</button>';
                    }
                    ?>
                </form>
                <?php
            }
        }
        ?>
    </main>

    <?php
    require_once 'php_require/footer.php';
    ?>
</body>

</html>