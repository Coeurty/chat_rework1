<?php
require_once '../php_require/session.php';
if (!isset($_SESSION['status'])) {
    header('location: /account/login.php?src=not_allowed');
}
?>

<head>
    <title>Mon compte</title>
</head>

<body>

    <?php
    require_once '../php_require/navbar.php';
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
                    require_onced>
            </div>
            <div>
                <label for="new-pseudo">Nouveau pseudo : </label> <input type="text" id="new-pseudo" name="new-pseudo"
                    minlength="3" maxlength="30" require_onced>
            </div>
            <input type="submit" name="pseudo-change" value="Envoyer">

            <?php
            if (isset($_POST['pseudo-change'])) {
                if (isset($_POST['password']) && isset($_POST['new-pseudo'])) {
                    $password = $_POST['password'];
                    $pseudo = $_POST['new-pseudo'];

                    $req = $bdd->query("SELECT password FROM accounts WHERE pseudo = '" . $_SESSION["pseudo"] . "' ");
                    $response = $req->fetch();
                    if (password_verify($password, $response['password'])) {

                        $req = $bdd->query("SELECT pseudo FROM accounts WHERE pseudo = '$pseudo'");
                        $verif = $req->fetch();
                        if (!$verif) {
                            $req2 = $bdd->query("UPDATE accounts SET pseudo = '$pseudo' WHERE pseudo = '" . $_SESSION["pseudo"] . "' ");
                            $req_ren_author_msg_members = $bdd->query("UPDATE messages_members_chat SET author = '$pseudo' WHERE author = '" . $_SESSION['pseudo'] . "' ");
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
                    name="password" require_onced>
            </div>
            <div>
                <label for="newPassword">Nouveau mot de passe : </label> <input type="password" id="newPassword"
                    title="Au moins 4 caractères" minlength="4" require_onced>
            </div>
            <input type="submit" name="mdp-change" value="Envoyer">

            <?php
            if (isset($_POST['mdp-change'])) {
                if (isset($_POST['password']) && isset($_POST['newPassword'])) {
                    if (strlen($_POST['newPassword']) >= 4) {
                        $password = $_POST['password'];
                        $newPassword = $_POST['newPassword'];

                        $req = $bdd->query("SELECT password FROM accounts WHERE pseudo = '" . $_SESSION["pseudo"] . "' ");
                        $response = $req->fetch();

                        if (password_verify($password, $response['password'])) {
                            $hash = password_hash($newPassword, PASSWORD_BCRYPT);
                            $req2 = $bdd->query("UPDATE accounts SET password = '$hash' WHERE pseudo = '" . $_SESSION["pseudo"] . "' ");
                            echo "<p>Mot de passe changé</p>";
                        } else {
                            echo "<p>Mot de passe incorrect</p>";
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
                    require_onced>
            </div>
            <div>
                <label for="new-email">Nouvelle adresse email : </label> <input type="email" id="new-email"
                    name="new-email"
                    pattern="[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})"
                    title="L'adresse mail doit être au format 'exemple@exemple.ex'." require_onced>
            </div>
            <input type="submit" name="email-change" value="Envoyer">

            <?php
            if (isset($_POST['email-change'])) {
                if (isset($_POST['password']) && isset($_POST['new-email'])) {
                    if (filter_var($_POST['new-email'], FILTER_VALIDATE_EMAIL)) {
                        $email = $_POST['new-email'];

                        $req = $bdd->query("SELECT password FROM accounts WHERE pseudo = '" . $_SESSION['pseudo'] . "' ");
                        $response = $req->fetch();

                        if (password_verify($_POST['password'], $response['password'])) {
                            $req2 = $bdd->query("UPDATE accounts SET email = '$email' WHERE pseudo = '" . $_SESSION['pseudo'] . "' ");
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

                    $allowedExtension = [".png", ".gif"];
                    if (in_array($extension, $allowedExtension)) {
                        if ($file_size <= 1_000_000) {
                            if (move_uploaded_file($_FILES['new-avatar']['tmp_name'], $dossier . $fichier)) {
                                $error_avatar = '<p>Avatar changé</p>';

                                $req = $bdd->query("UPDATE accounts SET avatar = '$url' WHERE pseudo = '" . $_SESSION['pseudo'] . "' ");
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
            $req = $bdd->query("SELECT avatar FROM accounts WHERE pseudo = '" . $_SESSION['pseudo'] . "' ");
            $response = $req->fetch();
            if ($response['avatar']) {
                echo "<div><img class='avatar' src='" . $response['avatar'] . "'></div>";
            }
            ?>

            <div>
                <label for="avatarFileInput">Importer une image (.png, .gif) de 1Mo maximum : </label>
                <input type="file" name="new-avatar" id="avatarFileInput" require_onced>
                <p id="fileError" style="color: red; display: none;">Le fichier est trop lourd.</p>
            </div>
            <input type="submit" name="avatar-change" id="avatarSubmitButton" value="Envoyer" disabled>
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
                <label for="password">Mot de passe : </label>
                <input type="password" id="password" name="password" require_onced>
            </div>
            <div>
                <label class="auto-width" for="deletionConfirmationCheckbox">
                    Supprimer mon compte : </label>
                <input type="checkbox" id="deletionConfirmationCheckbox" name="deletionConfirmation" require_onced>

            </div>
            <div id="deletionConfirmationInput" style="display: none;">
                <label for="password-confirm">Entrez "supprimer" : </label> <input type="text" id="password-confirm"
                    name="password-confirm" require_onced>
            </div>
            <input type="submit" name="account-del" value="Envoyer">

            <?php
            if (isset($_POST['account-del'])) {
                if (!empty($_POST['password']) && isset($_POST['deletionConfirmation']) && isset($_POST['password-confirm'])) {
                    if ($_POST['password-confirm'] == "supprimer") {

                        $password = $_POST['password'];

                        $req = $bdd->query("SELECT password FROM accounts WHERE pseudo = '" . $_SESSION["pseudo"] . "' ");
                        $response = $req->fetch();

                        if (password_verify($password, $response['password'])) {

                            $req2 = $bdd->query("DELETE FROM accounts WHERE pseudo = '" . $_SESSION["pseudo"] . "'  ");

                            $req_ren_author_msg2 = $bdd->query("UPDATE messages_members_chat SET author = 'Compte supprimé' WHERE author = '" . $_SESSION["pseudo"] . "' ");

                            session_destroy();
                            header('location: /account/login.php?src=deleted-account');
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
    require_once '../php_require/footer.php';
    ?>
</body>

</html>