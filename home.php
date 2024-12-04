<?php
require('php_require/session.php');
?>

<head>
    <title>Accueil</title>
</head>

<body>

    <?php
    require('php_require/navbar.php');
    ?>

    <div id="home">

        <div>
            <p>(Le contenu de cette page est temporaire)</p>
            <h2>Bienvenue</h2>
            <p>Vous êtes ici sur un site de discussion actuellement en construction, qui évoluera surement par la suite.</p>
            <p>Développé dans un langage que j'apprends sur mon temps libre (PHP) à côté d'une formation que je suis en
                même temps,
                j'essai de le faire évoluer assez régulièrement<br>
                (voir le page "<a class="link1" target="_blank" href="/changelog.php">Changelog</a>" en bas de page pour
                voir son
                avancée)<br>
                Ci-après une liste de ce que vous pouvez faire ici:</p>
            <ul>
                <li>Utiliser le chat public sous un pseudo aléatoire</li>
                <li>Créer un compte, concernant cela :</li>
                <ul>
                    <li>L'adresse email est optionnelle car non utilisée mais le sera plus tard pour restaurer le
                        mot
                        de passe par exemple
                    </li>
                    <li>Vous pourrez utiliser un chat restreint aux inscrits</li>
                    <li>Vous pourrez changer de pseudo, de mot de passe, d'adresse email et d'avatar,
                        ainsi que supprimer votre compte</li>
                </ul>
            </ul>
            <p>Je ne m'attarde pour le moment pas sur l'esthétique car là n'est ni mon objectif ni mon fort, bien
                que j'essai de le rendre présentable.<br>
                Mon objectif étant de m'améliorer dans les langages utilisés côté serveur pour m'y spécialiser plus
                tard.<br>
                Vous pouvez rapporter les bugs ou failles que vous trouvez afin que je puisse les corriger et ainsi apprendre.
                Je vais mettre un place un bugtracker mais en attendant vous pouvez utiliser les canaux de
                discussions</p>
                <p>Merci de votre attention et bonne visite !</p>
                

        </div>

    </div>

    <?php
    require('php_require/footer.php');
    ?>

</body>

<script src="/js/jquery.js"></script>
<script src="/js/script.js"></script>

</html>