<?php
require('./php_require/session.php');
?>

<head>
    <title>Changelog</title>
    <style>
    section {
        display: flex;
        flex-direction: column-reverse;
    }

    h1 {
        text-align: center;
    }

    main li {
        margin: 0.5em 0;
    }
    </style>
</head>

<body>
    <?php
    require('./php_require/navbar.php');
    ?>

    <main>
        <h1>Changelog</h1>

        <section>
            <div>
                <h2>22/08/2020</h2>
                <ul>
                    <li>Création du site</li>
                    <li>Contenu de base :</li>
                    <ul>
                        <li>Page d'accueil = Chat public accessible sans compte en indiquant seulement un pseudo à chaque message</li>
                        <li>Page de connexion</li>
                        <li>Page d'inscription</li>
                    </ul>
                </ul>
            </div>

            <div>
                <h2>23/08/2020</h2>
                <ul>
                    <li>Mise en ligne du site</li>
                    <li>Page d'accueil :</li>
                    <ul>
                        <li>Il faut maintenant un compte pour envoyer des messages</li>
                        <li>Modification du style des messages selon s'il est de l'utilisateur connecté ou non
                        </li>
                        <li>Ajout de la date et l'heure d'envoi sur les messages</li>
                        <li>Ajout d'un bouton actualiser</li>
                        <li>Correction de divers bug et failles</li>
                    </ul>
                </ul>
            </div>

            <div>
                <h2>25/08/2020</h2>
                <ul>
                    <li>Ensemble du site maintenant responsive</li>
                    <li>Ajout de la page "Mon compte" pour les utilisateurs connectés</li>
                    <ul>
                        <li>Ajout de la possibilité de modifier le mot de passe</li>
                    </ul>
                    <li>Correction d'un problème d'affichage sur mobile lorsque de trop longue chaine de caractères
                        (sans
                        espaces) sont
                        présentes dans le chat</li>
                </ul>
            </div>

            <div>
                <h2>26/08/2020</h2>
                <ul>
                    <li>Page "Mon compte"</li>
                    <ul>
                        <li>Ajout de la possibilité de supprimer le compte</li>
                    </ul>
                    <li>Page d'inscription' et "Mon compte"</li>
                    <ul>
                        <li>
                            Ajout de la vérification de mot de passe fort
                        </li>
                    </ul>
                </ul>
            </div>

            <div>
                <h2>30/08/2020</h2>
                <ul>
                    <li>Page d'inscription et "Mon compte"</li>
                    <ul>
                        <li>Ajout de la possibilité de désactiver la vérification de mot de passe fort (temporaire)</li>
                        <li>Ajout de la possibilité d'ajouter ou modifier une adresse email et vérification du format
                        </li>
                    </ul>
                </ul>
            </div>

            <div>
                <h2>31/08/2020</h2>
                <ul>
                    <li>Page "Mon compte"</li>
                    <ul>
                        <li>Ajout de la possibilité d'ajouter une image d'avatar</li>
                    </ul>
                    <li>Page d'accueil</li>
                    <ul>
                        <li>Ajout de l'image d'avatar sur les messages</li>
                    </ul>
                    <li>Améliorations diverses</li>
                </ul>
            </div>

            <div>
                <h2>01/09/2020</h2>
                <ul>
                    <li>Page "Mon compte"</li>
                    <ul>
                        <li>Ajout de l'extension ".gif" pour l'image d'avatar.</li>
                    </ul>
                    <li>Correctifs divers</li>
                </ul>
            </div>

            <div>
                <h2>02/09/2020</h2>
                <ul>
                    <li>Ajout d'un nouveau chat sur la page d'accueil = Chat public</li>
                    <li>Déplacement du précédant chat de la page d'accueil sur une page uniquement accessible aux
                        membres =
                        Chat
                        des membres</li>
                    <li>Ajout de l'attribution d'un pseudo temporaire pour les visiteurs (utilisateurs non connectés)
                        pour
                        le
                        chat public
                    </li>
                    <li>Les chats n'affichent plus que les 20 derniers messages</li>
                    <li>Ajout d'un lien "Charger plus de message" qui a chaque clic ajoutera les 20 messages précédants
                    </li>
                </ul>
            </div>

            <div>
                <h2>03/09/2020</h2>
                <ul>
                    <li>Pages de chat</li>
                    <ul>
                        <li>Amélioration de "Charger plus de message" pour qu'il change lorsqu'il n'y a plus de
                            message à charger</li>
                    </ul>
                    <li>Ajout du footer</li>
                    <li>Ajout de la page "Changelog"</li>
                </ul>
            </div>

            <div>
                <h2>04/09/2020</h2>
                <ul>
                    <li>Pages de chat</li>
                    <ul>
                        <li>Les messages voient leur nom d'auteur changer en "Compte supprimé" quand leur posteur
                            supprime son compte</li>
                    </ul>
                    <li>Modification du système d'attribution de pseudo d'invité (à revoir)</li>
                    <ul>
                        <li>Les pseudo temporaires sont maintenant liés à la connexion du visiteur</li>
                        <li>Les messages postés par un visiteur verront leur nom d'auteur modifié à la connexion ou à
                            l'inscription du posteur</li>
                    </ul>
                </ul>
            </div>

            <div>
                <h2>06/09/2020</h2>
                <ul>
                    <li>La page d'acceuil contient maintenant un message de bienvenue, l'ancien chat public a été
                        déplacée sur une autre page</li>
                </ul>
            </div>

            <div>
                <h2>13/09/2020</h2>
                <ul>
                    <li>Correction de bug</li>
                    <li>Page "Mon compte"</li>
                    <ul>
                        <li>Ajout de la possiblité de changer de pseudo</li>
                    </ul>
                </ul>
            </div>
        </section>

    </main>
    <?php
    require('./php_require/footer.php');
    ?>
</body>

</html>