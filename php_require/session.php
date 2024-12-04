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

require('bdd.php');

try {
    $bdd = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);
} catch (PDOException $e) {
    echo "Erreur!: " . $e->getMessage() . "<br/>";
    die();
}

if (!isset($_SESSION['pseudo'])) {
    $req = $bdd->query("SELECT * FROM visitors WHERE visitor_ip = '$ip'");
    $verif = $req->fetch();
    if ($verif) {
        $_SESSION['pseudo'] = $verif['visitor_pseudo'];
    } else {
        $_SESSION['pseudo'] = "visiteur" . rand(1, 100000);
        $req2 = $bdd->query("INSERT INTO visitors(visitor_pseudo, visitor_ip) VALUES('" . $_SESSION['pseudo'] . "', '$ip')");
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link id="style" rel="stylesheet" href="<?= $_SESSION['cssChoice'] ?>">
</head>