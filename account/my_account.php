<?php
require('../php_require/session.php');
if (!isset($_SESSION['status'])) {
    echo '<script>window.location.href="/account/login.php?src=not_allowed";</script>';
}
?>

<head>
    <title>Mon compte</title>
</head>

<body>

    <?php
    require('../php_require/navbar.php');
    ?>

    <main class="registration-main">
        <h1>Mon compte</h1>
        <hr>
        <!-- --------------------------------------------------------------------------------------- -->
        <!-- -------------------------------CHANGEMENT PSEUDO -------------------------------- -->
        <!-- --------------------------------------------------------------------------------------- -->
        <form action="my_account.php" method="POST">
            <h2>Modifier mon pseudo</h2>
            <div>
                <label for="password">Mot de passe : </label> <input type="password" id="password" name="password"
                    required>
            </div>
            <div>
                <label for="new-pseudo">Nouveau pseudo : </label> <input type="text" id="new-pseudo" name="new-pseudo"
                    minlength="3" maxlength="30" required>
            </div>
            <input type="submit" name="pseudo-change" value="Envoyer">

            <?php
            if (isset($_POST['pseudo-change'])) {
                if (isset($_POST['password']) && isset($_POST['new-pseudo'])) {
                    $password = $_POST['password'];
                    $pseudo = $_POST['new-pseudo'];

                    $req = $bdd->query("SELECT user_password FROM accounts WHERE user_pseudo = '" . $_SESSION["pseudo"] . "' ");
                    $response = $req->fetch();
                    if (password_verify($password, $response['user_password'])) {

                        $req = $bdd->query("SELECT user_pseudo FROM accounts WHERE user_pseudo = '$pseudo'");
                        $verif = $req->fetch();
                        if (!$verif) {
                            $req2 = $bdd->query("UPDATE accounts SET user_pseudo = '$pseudo' WHERE user_pseudo = '" . $_SESSION["pseudo"] . "' ");
                            $req_ren_author_msg_public = $bdd->query("UPDATE messages_public_chat SET message_author = '$pseudo' WHERE message_author = '" . $_SESSION['pseudo'] . "' ");
                            $req_ren_author_msg_members = $bdd->query("UPDATE messages_members_chat SET message_author = '$pseudo' WHERE message_author = '" . $_SESSION['pseudo'] . "' ");
                            echo "<p>Pseudo changé</p>";
                            $_SESSION['pseudo'] = $pseudo;
                        } else {
                            echo '<p>Pseudo déjà utilisé</p>';
                        }
                    } else {
                        echo "<p>Mot de passe incorrect</p>";
                    }
                } else {
                    echo "<p>Veuillez remplir tout les champs</p>";
                }
            }
            ?>

        </form>

        <hr>
        <!-- --------------------------------------------------------------------------------------- -->
        <!-- -------------------------------CHANGEMENT MOT DE PASSE -------------------------------- -->
        <!-- --------------------------------------------------------------------------------------- -->
        <form action="my_account.php" method="POST">
            <h2>Modifier mon mot de passe</h2>
            <div>
                <label for="password">Mot de passe actuel : </label> <input type="password" id="password"
                    name="password" required>
            </div>
            <div>
                <label for="password1">Nouveau mot de passe : </label> <input type="password" id="password1"
                    name="password1" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    title="Au moins 8 caractères dont au moins un chiffre, une minuscule et une majuscule" minlength="8"
                    required>
            </div>
            <div>
                <label for="password2">Confirmer le mot de passe : </label> <input type="password" id="password2"
                    name="password2" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    title="Au moins 8 caractères dont au moins un chiffre, une minuscule et une majuscule" minlength="8"
                    required>
            </div>
            <div>
                <label class="auto-width" for="no-verif-password">Désactiver l'obligation de mot de passe fort :
                    <input type="checkbox" id="no-verif-password" name="no-verif-password"></label>
            </div>
            <input type="submit" name="mdp-change" value="Envoyer">

            <?php
            if (isset($_POST['mdp-change'])) {
                if (isset($_POST['password']) && isset($_POST['password1']) && isset($_POST['password2'])) {
                    if (strlen($_POST['password1']) >= 8) {
                        $password = $_POST['password'];
                        $password1 = $_POST['password1'];
                        $password2 = $_POST['password2'];

                        if ($password1 == $password2) {

                            $req = $bdd->query("SELECT user_password FROM accounts WHERE user_pseudo = '" . $_SESSION["pseudo"] . "' ");
                            $response = $req->fetch();

                            if (password_verify($password, $response['user_password'])) {
                                $hash = password_hash($password1, PASSWORD_BCRYPT);
                                $req2 = $bdd->query("UPDATE accounts SET user_password = '$hash' WHERE user_pseudo = '" . $_SESSION["pseudo"] . "' ");
                                echo "<p>Mot de passe changé</p>";
                            } else {
                                echo "<p>Mot de passe incorrect</p>";
                            }
                        } else {
                            echo "<p>Les mots de passes ne correspondents pas</p>";
                        }
                    } else {
                        echo "<p>Veuillez remplir tout les champs</p>";
                    }
                } else {
                    echo "<p>Veuillez remplir tout les champs</p>";
                }
            }
            ?>

        </form>

        <hr>
        <!-- -------------------------------------------------------------------------------------------- -->
        <!-- ---------------------------------CHANGEMENT D'ADRESSE EMAIL--------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------- -->
        <form action="my_account.php" method="POST">
            <h2>Modifier mon adresse email</h2>
            <div>
                <label for="password">Mot de passe : </label> <input type="password" id="password" name="password"
                    required>
            </div>
            <div>
                <label for="new-email">Nouvelle adresse email : </label> <input type="email" id="new-email"
                    name="new-email"
                    pattern="[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})"
                    title="L'adresse mail doit être au format 'exemple@exemple.ex'." required>
            </div>
            <input type="submit" name="email-change" value="Envoyer">

            <?php
            if (isset($_POST['email-change'])) {
                if (isset($_POST['password']) && isset($_POST['new-email'])) {
                    if (filter_var($_POST['new-email'], FILTER_VALIDATE_EMAIL)) {
                        $email = $_POST['new-email'];

                        $req = $bdd->query("SELECT user_password FROM accounts WHERE user_pseudo = '" . $_SESSION['pseudo'] . "' ");
                        $response = $req->fetch();

                        if (password_verify($_POST['password'], $response['user_password'])) {
                            $req2 = $bdd->query("UPDATE accounts SET user_email = '$email' WHERE user_pseudo = '" . $_SESSION['pseudo'] . "' ");
                            echo "<p>Adresse email changée</p>";
                        } else {
                            echo "<p>Mot de passe incorrect</p>";
                        }
                    } else {
                        echo "<p>Adresse email invalide</p>";
                    }
                } else {
                    echo "<p>Veuillez remplir tout les champs</p>";
                }
            }
            ?>

        </form>
        <hr>
        <!-- --------------------------------------------------------------------------------------- -->
        <!-- -------------------------------CHANGEMENT AVATAR--------------------------------------- -->
        <!-- --------------------------------------------------------------------------------------- -->
        <form enctype="multipart/form-data" action="my_account.php" method="post">
            <h2>Modifier mon avatar</h2>

            <?php
            if (isset($_POST['avatar-change'])) {
                if (isset($_FILES['new-avatar'])) {
                    $dossier = 'avatars/';
                    $extension = strrchr($_FILES['new-avatar']['name'], '.');
                    $fichier = $_SESSION['pseudo'] . $extension;
                    $file_size = filesize($_FILES['new-avatar']['tmp_name']);
                    $url = "/account/" . $dossier . $fichier;

                    if ($extension == ".png" || $extension == ".gif") {
                        if ($file_size < 5000000) {
                            if (move_uploaded_file($_FILES['new-avatar']['tmp_name'], $dossier . $fichier)) {
                                $error_avatar = '<p>Avatar changé</p>';

                                $req = $bdd->query("UPDATE accounts SET user_avatar = '$url' WHERE user_pseudo = '" . $_SESSION['pseudo'] . "' ");
                            } else {
                                $error_avatar = '<p>Echec de l\'envoie du fichier</p>';
                            }
                        } else {
                            $error_avatar = '<p>Image trop lourde</p>';
                        }
                    } else {
                        $error_avatar = '<p>Veuillez selectionner un fichier au format "png ou gif"</p>';
                    }
                }
            }
            ?>

            <?php
            $req = $bdd->query("SELECT user_avatar FROM accounts WHERE user_pseudo = '" . $_SESSION['pseudo'] . "' ");
            $response = $req->fetch();
            if ($response['user_avatar']) {
                echo "<div><img class='avatar' src='" . $response['user_avatar'] . "'></div>";
            }
            ?>

            <div>
                <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
                <label for="new-avatar">Importer une image (.png, .gif) de 1Mo maximum : </label>
                <input type="file" accept="image/png, image/gif" name="new-avatar" id="new-avatar">
            </div>
            <input type="submit" name="avatar-change" value="Envoyer">
            <?php
            if (isset($error_avatar)) {
                echo $error_avatar;
            }
            ?>

        </form>
        <hr>
        <!-- --------------------------------------------------------------------------------------- -->
        <!-- ---------------------------------SUPPRESSION DE COMPTE -------------------------------- -->
        <!-- --------------------------------------------------------------------------------------- -->
        <form action="my_account.php" method="POST">
            <h2>Supprimer mon compte</h2>
            <div>
                <label for="password">Mot de passe : </label> <input type="password" id="password" name="password"
                    required>
            </div>
            <div>
                <label class="auto-width" for="password-checkbox">Supprimer mon compte : <input type="checkbox"
                        id="password-checkbox" name="password-checkbox" required></label>
            </div>
            <div id="password-confirm" style="display: none;">
                <label for="password-confirm">Entrez "supprimer" : </label> <input type="text" id="password-confirm"
                    name="password-confirm" required>
            </div>
            <input type="submit" name="account-del" value="Envoyer">

            <?php
            if (isset($_POST['account-del'])) {
                if (!empty($_POST['password']) && isset($_POST['password-checkbox']) && isset($_POST['password-confirm'])) {
                    if ($_POST['password-confirm'] == "supprimer") {

                        $password = $_POST['password'];

                        $req = $bdd->query("SELECT user_password FROM accounts WHERE user_pseudo = '" . $_SESSION["pseudo"] . "' ");
                        $response = $req->fetch();

                        if (password_verify($password, $response['user_password'])) {

                            $req2 = $bdd->query("DELETE FROM accounts WHERE user_pseudo = '" . $_SESSION["pseudo"] . "'  ");
                            $req_ren_author_msg = $bdd->query("UPDATE messages_public_chat SET message_author = 'Compte supprimé' WHERE message_author = '" . $_SESSION["pseudo"] . "' ");
                            $req_ren_author_msg2 = $bdd->query("UPDATE messages_members_chat SET message_author = 'Compte supprimé' WHERE message_author = '" . $_SESSION["pseudo"] . "' ");

                            session_destroy();
                            echo '<script>window.location.href="/account/login.php?src=deleted-account";</script>';
                        } else {
                            echo "<p>Mot de passe incorrect</p>";
                        }
                    } else {
                        echo "<p>Veuillez confirmer que vous souhaitez supprimer votre compte</p>";
                    }
                } else {
                    echo "<p>Veuillez remplir tout les champs</p>";
                }
            }
            ?>

        </form>
        <hr>

    </main>
    <?php
    require('../php_require/footer.php');
    ?>
</body>

<script src="/js/jquery.js"></script>
<script src="/js/script.js"></script>

</html>