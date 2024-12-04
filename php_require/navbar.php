<header>
    <nav>
        <label for="checkboxMenu"><img id="btnOpenMenu" src="/images/icons/hamburgerMenuOpen.png"
                alt="Ouvrir le menu hamburger"></label>
        <input type="checkbox" id="checkboxMenu">
        <ul>
            <li><a href="/home.php">Accueil</a></li>
            <li><a href="/chat/public_chat.php#spawn">Chat public</a></li>
            <?php
            if (isset($_SESSION['status'])) {
                echo "<li><a href='/chat/members_chat.php#spawn'>Chat des membres</a></li>";
                echo "<li><a href='/account/my_account.php'>Mon compte</a></li>";
                echo "<li><a href='/account/logout.php'>Déconnexion</a></li>";
            } else {
                echo "<li><a href='/account/login.php'>Connexion</a></li>";
                echo "<li><a href='/account/registration.php'>Inscription</a></li>";
            }
            ?>
            <li><label for="checkboxMenu"><img id="btnCloseMenu" src="/images/icons/hamburgerMenuClose.png"
                        alt="Fermer le menu hamburger"></label></li>
        </ul>
    </nav>
</header>