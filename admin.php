<?php

//sessiooni algus
 session_start();
 if (!isset($_SESSION['tuvastamine'])) {
     header('Location: ab_login.php');
     exit();
 }
require_once ('connect_tans.php');
echo "<script>
        function onFormSubmission(e){
            return confirm('do you want to delete Y/N');
        }

        var frm = document.getElementById('frm');
        frm.addEventListener('submit', onFormSubmission);
    </script>";
global $yhendus;
//lisamine punktide
if (isset($_REQUEST['punkt0'])){
    $kask=$yhendus->prepare('UPDATE tantsud SET punktid=0 WHERE id=?');
    $kask->bind_param("s", $_REQUEST['punkt0']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//kustuta kommentaari
if (isset($_REQUEST['kustutaK'])){
    $kask=$yhendus->prepare("UPDATE tantsud SET kommentaarid=' ' WHERE id=?");
    $kask->bind_param("i", $_REQUEST['kustutaK']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//kustuta kõik
if (isset($_REQUEST['kustuta'])&& on){

    $kask=$yhendus->prepare("DELETE FROM tantsud WHERE id=?");
    $kask->bind_param("i", $_REQUEST['kustuta']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//peitmine
if (isset($_REQUEST['peida'])){
    $kask=$yhendus->prepare("UPDATE tantsud SET avalik=0 WHERE id=?");
    $kask->bind_param("i", $_REQUEST['peida']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//näitamine
if (isset($_REQUEST['naita'])){
    $kask=$yhendus->prepare("UPDATE tantsud SET avalik=1 WHERE id=?");
    $kask->bind_param("i", $_REQUEST['naita']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
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

<header>
    <div>
        <?php
        //session_start();
        echo $_SESSION['kasutaja'];
        ?> on sisse logitud
    <form action="logout.php" method="post">
        <input type="submit" value="Logi välja" name="logout">
    </form>
    </div>
    <h1>Tantsud TARpv21</h1>
    <h2>Administraator leht</h2>
    <nav>
        <a href="tantsudPunktid.php" id="kasutaja">Kasutaja leht</a>
        <a href="admin.php" id="kasutaja">Admin leht</a>
    </nav>
</header>
<!-- Otsi paar -->
<div>
    <form action="?">
        <input type="text" placeholder="otsi paar" name="otsi">
        <input type="submit" value="Otsi">
    </form>
</div>

<table>
    <tr>
        <th>
            Kustuta nupp
        </th>
        <th>
            Tantsupaar
        </th>
        <th>
            Punktid<br>Punktid nulliks
        </th>
        <th>
            Kommentaarid
        </th>
        <th>
            Kustuta kommentaarid
        </th>
        <th>
            Avalikustamine staatus
        </th>
        <th>
            Avalikustamine päev
        </th>
        <th>
            Pilt paar
        </th>
    </tr>
    <?php
    global $yhendus;
    // Otsi paar
    if (!empty($_REQUEST['otsi'])) {
        $kask = $yhendus->prepare('SELECT id, tantsupaar, punktid, kommentaarid, avaliku_paev, avalik, pilt FROM tantsud WHERE tantsupaar=?');
        $kask->bind_param("s", $_REQUEST['otsi']);
        $kask->bind_result($id, $tantsupaar, $punktid, $kommentaarid, $apaev, $avalik, $pilt);
    }
    else {
        $kask = $yhendus->prepare('SELECT id, tantsupaar, punktid, kommentaarid, avaliku_paev, avalik, pilt FROM tantsud');
        $kask->bind_result($id, $tantsupaar, $punktid, $kommentaarid, $apaev, $avalik, $pilt);
    }
    $kask->execute();
    while($kask->fetch()) {
        echo "<tr>";
        echo "<td><a href='?kustuta=$id' onclick='onFormSubmission()' style='color: red'>Kustuta kõik: </a></td>";
        echo "<td>" . $tantsupaar . "</td>";
        echo "<td>" . $punktid . "<br><a href='?punkt0=$id'>nulliks</a></td>";
        echo "<td>" . $kommentaarid . "</td>";
        echo "<td><a href='?kustutaK=$id' style='color: red'>Kustuta</a></td>";
        $tekst='Näita';
        if ($avalik==1){
            $tekst='Peida';
        }
        echo "<td>              
                <a href='?naita=$id'>Näita</a><br>
                <a href='?peida=$id'>Peida</a><br>
                See on $tekst
</td>";
        echo "<td>$apaev</td>";
        echo "<td><img src=$pilt alt='pilt' width='200px'> </td>";
        echo "</tr>";
    }
    ?>