<header>
    <nav>
        <label for="checkboxMenu"><img id="btnOpenMenu" src="/assets/images/icons/hamburgerMenuOpen.png"
                alt="Ouvrir le menu hamburger"></label>
        <input type="checkbox" id="checkboxMenu">
        <ul>
            <li><a href="/chat/public_chat.php#spawn">Chat public</a></li>

            <?php
            require_once 'db_queries.php';
            if (isset($_SESSION["status"])) {
                $friendRequestNumber = getFriendRequestNumber($bdd, $_SESSION['id']);
                $friendRequestNumber = $friendRequestNumber == 0 ? "" : "($friendRequestNumber)";
            }

            if (isset($_SESSION["status"])) {
                ?>
                <li><a href='/chat/members_chat.php#spawn'>Chat des membres</a></li>
                <?php
                if (isset($_SESSION["status"])) {
                    echo "<li><a href='/friends.php'>Amis $friendRequestNumber</a></li>";
                }
                ?>
                <li><a href='/profil.php'>Mon profil</a></li>
                <li><a href='/account/my_account.php'>Mon compte</a></li>
                <li><a href='/account/logout.php'>Déconnexion</a></li>
                <?php
            } else {
                ?>
                <li><a href='/account/login.php'>Connexion</a></li>
                <li><a href='/account/registration.php'>Inscription</a></li>
                <?php
            }
            ?>
            <li>
                <label for="checkboxMenu">
                    <img id="btnCloseMenu" src="/assets/images/icons/hamburgerMenuClose.png" alt="Fermer le menu hamburger">
                </label>
            </li>
        </ul>
    </nav>
</header>