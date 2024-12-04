<?php
require('../php_require/session.php');
if (isset($_SESSION['status'])) {
    echo '<script>window.location.href="/index.php";</script>';
}
?>

<head>
    <title>Inscription</title>
</head>

<body>

    <?php
    require('../php_require/navbar.php');
    ?>

    <main class="registration-main">
        <h1>Inscription</h1>
        <form action="registration.php" method="POST">
            <div>
                <label for="pseudo">Pseudo : *</label> <input type="text" id="pseudo" name="pseudo" minlength="3"
                    maxlength="30" required>
            </div>
            <div>
                <label for="password1">Mot de passe : *</label> <input type="password" id="password1" name="password1"
                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    title="Au moins 8 caractères dont au moins un chiffre, une minuscule et une majuscule" minlength="8"
                    required>
            </div>
            <div>
                <label for="password2">Confirmer le mot de passe : *</label> <input type="password" id="password2"
                    name="password2" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    title="Au moins 8 caractères dont au moins un chiffre, une minuscule et une majuscule" minlength="8"
                    required>
            </div>
            <div>
                <label class="auto-width" for="no-verif-password">Désactiver l'obligation de mot de passe fort :
                    <input type="checkbox" id="no-verif-password" name="no-verif-password"></label>
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

        if (isset($_POST['pseudo']) && isset($_POST['password1']) && isset($_POST['password2'])) {

            if (strlen($_POST['password1']) >= 8 && strlen($_POST['pseudo']) <= 30) {
                if ($_POST['password1'] == $_POST['password2']) {
                    $pseudo = htmlspecialchars($_POST['pseudo']);

                    $email = "";
                    if (isset($_POST['email'])) {
                        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                            $email = $_POST['email'];
                        }
                    }

                    $req = $bdd->query("SELECT user_pseudo FROM accounts WHERE user_pseudo = '$pseudo'"); // Verification dispo pseudo
                    $verif = $req->fetch();
                    if ($verif) {
                        echo '<p>Pseudo déjà utilisé</p>';
                        $valid_registration = false;
                    } else {
                        if ($email != "") {
                            $req2 = $bdd->query("SELECT user_email FROM accounts WHERE user_email = '$email'"); // Verification dispo email
                            $verif2 = $req2->fetch();
                            if ($verif2) {
                                echo '<p>Adresse mail déjà utilisée</p>';
                                $valid_registration = false;
                            } else {
                                $valid_registration = true;
                            }
                        } else {
                            $valid_registration = true;
                        }
                    }
                    if ($valid_registration) {
                        $hash = password_hash($_POST['password1'], PASSWORD_BCRYPT);
                        $req3 = $bdd->prepare('INSERT INTO accounts(user_pseudo,  user_password, user_email, user_ip) VALUES(:user_pseudo, :user_password, :user_email, :user_ip)');
                        $req3->execute(array(
                            'user_pseudo' => $pseudo,
                            'user_password' => $hash,
                            'user_email' => $email,
                            // 'user_date_registration' => date("Y-m-d H:i:s"),
                            'user_ip' => $ip
                        ));

                        $req_del_visitor = $bdd->query("DELETE FROM visitors WHERE visitor_ip = '$ip' ");
                        $req_ren_author_msg_visitor = $bdd->query("UPDATE messages_public_chat SET message_author = '$pseudo' WHERE message_author = '" . $_SESSION['pseudo'] . "' ");

                        echo '<script>window.location.href="/account/login.php?src=registration";</script>';
                    }
                } else {
                    echo "<p>Les mots de passe ne correspondent pas</p>";
                }
            } else {
                echo "<p>Veuillez remplir correctement le formulaire</p>";
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