<?php
require_once '../php_require/session.php';
if (isset($_SESSION['status'])) {
    header('location: /chat/members_chat.php');
}
?>

<head>
    <title>Inscription</title>
</head>

<body>

    <?php
    require_once '../php_require/navbar.php';
    ?>

    <main class="registration-main">
        <h1>Inscription</h1>
        <form action="registration.php" method="POST">
            <div>
                <label for="pseudo">Pseudo : *</label> <input type="text" id="pseudo" name="pseudo" minlength="2"
                    maxlength="30" title="Entre 2 et 30 caractères" autocomplete="username" require_onced>
            </div>
            <div>
                <label for="password">Mot de passe : *</label> <input type="password" id="password" name="password"
                    title="Au moins 4 caractères" minlength="4" autocomplete="new-password" require_onced>
            </div>
            <div>
                <label for="email">Adresse email :</label> <input type="email" id="email" name="email"
                    pattern="[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})"
                    title="L'adresse mail doit être au format 'exemple@exemple.ex'.">
            </div>
            <div>
                * : Champs obligatoires
            </div>
            <input type="submit" id="submit" value="Envoyer">
        </form>
        <?php

        if (isset($_POST['pseudo']) && isset($_POST['password'])) {

            if (strlen($_POST['password']) >= 4 && trim(strlen($_POST['pseudo'])) <= 30) {
                $pseudo = htmlspecialchars($_POST['pseudo']);

                $email = "";
                if (isset($_POST['email'])) {
                    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        $email = $_POST['email'];
                    }
                }

                $findAccountByPseudoRequest = $bdd->query("SELECT user_pseudo FROM accounts WHERE user_pseudo = '$pseudo'");
                $foundAccountByPseudo = $findAccountByPseudoRequest->fetch();
                if ($foundAccountByPseudo) {
                    echo '<p>Pseudo déjà utilisé</p>';
                    $isRegistrationValid = false;
                } else {
                    if ($email != "") {
                        $findAccountByEmailRequest = $bdd->query("SELECT user_email FROM accounts WHERE user_email = '$email'");
                        $foundAccountByEmail = $findAccountByEmailRequest->fetch();
                        if ($foundAccountByEmail) {
                            echo '<p>Adresse mail déjà utilisée</p>';
                            $isRegistrationValid = false;
                        } else {
                            $isRegistrationValid = true;
                        }
                    } else {
                        $isRegistrationValid = true;
                    }
                }
                if ($isRegistrationValid) {
                    $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $req3 = $bdd->prepare('INSERT INTO accounts(user_pseudo,  user_password, user_email, user_ip) VALUES(:user_pseudo, :user_password, :user_email, :user_ip)');
                    $req3->execute(array(
                        'user_pseudo' => $pseudo,
                        'user_password' => $hash,
                        'user_email' => $email,
                        'user_ip' => $ip
                    ));

                    $req_del_visitor = $bdd->query("DELETE FROM visitors WHERE visitor_ip = '$ip' ");
                    $req_ren_author_msg_visitor = $bdd->query("UPDATE messages_public_chat SET message_author = '$pseudo' WHERE message_author = '" . $_SESSION['pseudo'] . "' ");

                    header('location: /account/login.php?src=registration');
                }

            } else {
                echo "<p>Veuillez remplir correctement le formulaire</p>";
            }
        }

        ?>
    </main>
    <?php
    require_once '../php_require/footer.php';
    ?>
</body>

</html>