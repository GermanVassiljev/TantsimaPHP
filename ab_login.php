<?php // include('login.php'); ?>
<?php
require_once ('connect_tans.php');

/*CREATE TABLE kasutajad (
    id int PRIMARY KEY AUTO_INCREMENT,
    kasutaja varchar(10),
    parool varchar(250)
)*/
session_start();
if (isset($_SESSION['tuvastamine'])) {
    header('Location: admin.php');
    exit();
}
global $yhendus;
//kontrollime kas väljad on täidetud
if (!empty($_POST['login']) && !empty($_POST['pass'])) {
    //eemaldame kasutaja sisestusest kahtlase pahna
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));
    //SIIA UUS KONTROLL
    $sool = 'taiestisuvalinetekst';
    $kryp = crypt($pass, $sool);
    //kontrollime kas andmebaasis on selline kasutaja ja parool
    //$paring = "SELECT * FROM kasutajad WHERE kasutaja='$login' AND parool='$kryp'";
    //$valjund = mysqli_query($yhendus, $paring);
    //kui on, siis loome sessiooni ja suuname
    $kask=$yhendus->prepare("SELECT kasutaja, onAdmin, koduleht FROM kasutajad WHERE kasutaja=? AND parool=?");
    $kask->bind_param("ss",$login,$kryp);
    $kask->bind_result($nimi, $onAdmin, $koduleht);
    $kask->execute();

    if($kask->fetch()){

    //if (mysqli_num_rows($valjund)==1) {
        $_SESSION['tuvastamine'] = 'misiganes';
        $_SESSION['kasutaja']=$nimi;
        $_SESSION['onAdmin']=$onAdmin;
        if(isset($koduleht) && $onAdmin==1){
            header("Location: $koduleht");
        }
        else{
            header('Location: parool.php');
            exit();
        }
    }
    else {
        echo "kasutaja $login või parool $kryp on vale";
    }
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>TARpv21 tantsud</title>
    <link rel="stylesheet" href="styletants.css">
</head>
<body>
    <h1>Login</h1>
    <form action="" method="post">
        Login: <input type="text" name="login"><br>
        Password: <input type="password" name="pass"><br>
        <input type="submit" value="Logi sisse">
    </form>
</body>
<?php

?>

