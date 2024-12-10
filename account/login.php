<?php
require_once '../php_require/session.php';
if (isset($_SESSION['status'])) {
    header('location: /chat/members_chat.php');
}
?>

<head>
    <title>Connexion</title>
</head>

<body>

    <?php
    require_once '../php_require/navbar.php';
    ?>

    <main class="registration-main">
        <h1>Connexion</h1>
        <form action="login.php" method="POST">
            <div>
                <label for="pseudo">Pseudo : </label>
                <input type="text" id="pseudo" name="pseudo" autocomplete="username" require_onced>
            </div>
            <div>
                <label for="password">Mot de passe : </label>
                <input type="password" id="password" name="password" autocomplete="current-password" require_onced>
            </div>
            <input type="submit" name="login_submit" id="login_submit" value="Envoyer">
        </form>

        <?php
        if (isset($_POST['login_submit'])) {
            if (isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['password']) && !empty($_POST['password'])) {

                $pseudo = $_POST['pseudo'];
                $password = $_POST['password'];

                $findAccountByPseudoRequest = $bdd->query("SELECT * FROM accounts WHERE pseudo = '$pseudo'");
                $foundAccount = $findAccountByPseudoRequest->fetch();

                if ($foundAccount) {
                    if (password_verify($password, $foundAccount['password'])) {

                        $req_del_visitor = $bdd->query("DELETE FROM visitors WHERE ip = '$ip' ");

                        $_SESSION['id'] = $foundAccount['user_id'];
                        $_SESSION['status'] = $foundAccount['status'];
                        $_SESSION['pseudo'] = $foundAccount['pseudo'];
                        header('location: /chat/members_chat.php#spawn');
                    } else {
                        echo '<p>Identifiant ou mot de passe incorrect</p>';
                    }
                } else {
                    echo '<p>Identifiant ou mot de passe incorrect</p>';
                }
            } else {
                echo '<p>Saisissez un identifiant et un mot de passe</p>';
            }
        }
        if (isset($_GET['src'])) {
            if ($_GET['src'] == "registration") {
                echo "<p>Compte créé, vous pouvez vous connecter</p>";
            }
            if ($_GET['src'] == "deleted-account") {
                echo "<p>Compte supprimé</p>";
            }
            if ($_GET['src'] == "not_allowed") {
                echo "<p>Veuillez vous connecter pour accéder à cette page</p>";
            }
        }
        ?>

    </main>

    <?php
    require_once '../php_require/footer.php';
    ?>
</body>

</html>