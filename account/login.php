<?php
require('../php_require/session.php');
if (isset($_SESSION['status'])) {
    echo '<script>window.location.href="/index.php";</script>';
}
?>

<head>
    <title>Connexion</title>
</head>

<body>

    <?php
    require('../php_require/navbar.php');
    ?>

    <main class="registration-main">
        <h1>Connexion</h1>
        <form action="login.php" method="POST">
            <div>
                <label for="pseudo">Pseudo : </label> <input type="text" id="pseudo" name="pseudo">
            </div>
            <div>
                <label for="password">Mot de passe : </label> <input type="password" id="password" name="password">
            </div>
            <!-- <div class="g-recaptcha" data-sitekey="6LdFIq4ZAAAAAArO_eQGyw6WB2SFxyWmbA1kOEma"></div> -->
            <input type="submit" name="login_submit" id="login_submit" value="Envoyer">
            <!-- pseudo = adminadmin
            mdp = mdpMDP0% -->
        </form>

        <?php
        if (isset($_POST['login_submit'])) {
            // if (isset($_POST['g-recaptcha-response'])) {
            //     $captcha = $_POST['g-recaptcha-response'];
            // }
            // if (!$captcha) {
            //     echo '<p>Veuillez vérifier le captcha</p>';
            //     exit;
            // }
            // $secretKey = "6LdFIq4ZAAAAANawhSp6Jx-ROm7DBk6C0z78bm2u";
            // $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
            // $response = file_get_contents($url);
            // $responseKeys = json_decode($response, true);
            // if ($responseKeys["success"]) {
            if (isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['password']) && !empty($_POST['password'])) {

                $pseudo = $_POST['pseudo'];
                $password = $_POST['password'];

                if ($pseudo == 'adminadmin' && $password == 'mdpMDP0%') {
                    echo '<script>window.location.href="https://rickrolled.fr/rickroll.mp4";</script>';
                }

                $req = $bdd->query("SELECT * FROM accounts WHERE user_pseudo = '$pseudo'");
                $response = $req->fetch();

                if ($response) {
                    if (password_verify($password, $response['user_password'])) {

                        $req_del_visitor = $bdd->query("DELETE FROM visitors WHERE visitor_ip = '$ip' ");
                        $req_ren_author_msg_visitor = $bdd->query("UPDATE messages_public_chat SET message_author = '" . $response['user_pseudo'] . "' WHERE message_author = '" . $_SESSION['pseudo'] . "' ");

                        $_SESSION['status'] = $response['user_status'];
                        $_SESSION['pseudo'] = $response['user_pseudo'];
                        echo '<script>window.location.href="/chat/members_chat.php#spawn";</script>';
                    } else {
                        echo '<p>Identifiant ou mot de passe incorrect</p>';
                    }
                } else {
                    echo '<p>Identifiant ou mot de passe incorrect</p>';
                }
            } else {
                echo '<p>Saisissez un identifiant et un mot de passe</p>';
            }
            // } else {
            //     echo '<p>Vous êtes un bot</p>';
            // }
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
    require('../php_require/footer.php');
    ?>

</body>
<script src="/js/jquery.js"></script>
<script src="/js/script.js"></script>

</html>