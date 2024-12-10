<?php
session_start();

function getIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
$ip = getIp();

require_once __DIR__ . '/../bdd.php';

$bdd = new PDO("mysql:host=$host_name; port=$port; dbname=$database;", $user_name, $password);

if (!isset($_SESSION['pseudo'])) {
    $findVisitorByIp = $bdd->query("SELECT * FROM visitors WHERE ip = '$ip'");
    $knownVisitor = $findVisitorByIp->fetch();
    if ($knownVisitor) {
        $_SESSION['pseudo'] = $knownVisitor['pseudo'];
    } else {
        $_SESSION['pseudo'] = "visiteur" . rand(1, 100000);
        $bdd->query("INSERT INTO visitors(pseudo, ip) VALUES('" . $_SESSION['pseudo'] . "', '$ip')");
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link id="style" rel="stylesheet" href="../assets/css/style.css">
    <script defer src="../assets/js/script.js"></script>
</head>