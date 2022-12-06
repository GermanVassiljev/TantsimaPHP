<?php
require_once('connect_tans.php');
//sessiooni algus
session_start();
if (isset($_SESSION['tuvastamine'])) {
    header('Location: login.php');
    exit();
}

function isAdmin(){
    return isset($_SESSION['onAdmin'])&&$_SESSION['onAdmin'];
}

global $yhendus;
//Uue tantsupaari lisamine
if (!empty($_REQUEST['paarnimi']) && !empty($_REQUEST['pilt']) && isAdmin()){
    $kask=$yhendus->prepare("INSERT INTO tantsud (tantsupaar, pilt, avaliku_paev) VALUES (?, ?, now())");
    $kask->bind_param("ss",$_REQUEST['paarnimi'],$_REQUEST['pilt']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//kommentaaride lisamine
if (isset($_REQUEST['uuskomment'])){
    if (!empty($_REQUEST['komment'])){
        $kask=$yhendus->prepare('UPDATE tantsud SET kommentaarid=CONCAT(kommentaarid, ?) WHERE id=?');
        $komment2=$_REQUEST['komment']. "\n";
        $kask->bind_param("si", $komment2, $_REQUEST['uuskomment']);
        $kask->execute();
        header("Location: $_SERVER[PHP_SELF]");
    }

}

//lisamine punktide
if (isset($_REQUEST['punkt'])){
    $kask=$yhendus->prepare('UPDATE tantsud SET punktid=punktid+1 WHERE id=?');
    $kask->bind_param("s", $_REQUEST['punkt']);
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
        <?=$_SESSION['kasutaja']?> on sisse logitud
        <form action="logout.php" method="post">
            <input type="submit" value="Logi välja" name="logout">
        </form>
    </div>
    <h1>Tantsud TARpv21</h1>
    <h2>Kasutaja leht</h2>
    <nav>
        <a href="tantsudPunktid.php" id="kasutaja">Kasutaja leht</a>
        <a href="admin.php" id="kasutaja">Admin leht</a>
    </nav>
</header>

<table>
    <?php
    if (isset($_REQUEST['tants'])){
        $kask=$yhendus->prepare('SELECT id, tantsupaar, punktid, kommentaarid, pilt FROM tantsud WHERE avalik=0 ORDER BY tantsupaar ASC ');
        $kask->bind_result($id, $tantsupaar, $punktid, $kommentaarid, $pilt);
    }
    elseif (isset($_REQUEST['punktid'])){
        $kask=$yhendus->prepare('SELECT id, tantsupaar, punktid, kommentaarid, pilt FROM tantsud WHERE avalik=0 ORDER BY punktid desc ');
        $kask->bind_result($id, $tantsupaar, $punktid, $kommentaarid, $pilt);
    }
    else {
        $kask = $yhendus->prepare('SELECT id, tantsupaar, punktid, kommentaarid, pilt FROM tantsud WHERE avalik=0');
        $kask->bind_result($id, $tantsupaar, $punktid, $kommentaarid, $pilt);
    }
    ?>
    <tr>
        <th>
            <?php
            echo "<a href='?tants=$tantsupaar' id='tha'>Tantsupaar</a>";
            ?>
        </th>
        <th>
            <?php
            echo "<a href='?punktid=$punktid' id='tha'>Punktid</a>";
            ?>

        </th>
        <th>
            Haldus
        </th>
        <th>
            Kommentaarid
        </th>
        <th>
            Sisesta kommentaarid
        </th>
        <th>
            Pilt paar
        </th>
    </tr>
    <?php

    $kask->execute();
    while($kask->fetch()){
        echo "<tr>";
        echo "<td>".$tantsupaar."</td>";
        echo "<td>".$punktid."</td>";
        echo "<td><a href='?punkt=$id'>Lisa 1 punkt</a> </td>";
        $kommentaarid=nl2br(htmlspecialchars($kommentaarid));
        echo "<td>".$kommentaarid."</td>";
        echo "<td>
<form action='?'>
<input type='hidden' value='$id' name='uuskomment'>
<input type='text' name='komment'>
<input type='submit' value='Ok'>
</form>

</td>";
        echo "<td><img src='$pilt' alt='pilt' width='200px'></td>";
        echo "</tr>";
    }
    ?>
</table>
<?php if (isAdmin()){?>
<div>
    <h2>Uue tantsupaari lisamine</h2>
    <form action="?" id="lisa">
        <input type="text" placeholder="Tantsupaar nimed" name="paarnimi">
        <textarea name="pilt" placeholder="Sisesta pilt paar"></textarea>
        <input type="submit" value="Ok">
    </form>
</div>
<?php } ?>
</body>
</html>
<!-- TantsudTahtega projekti ülesanded:
1. admin.php - lisa otsing +
2. kasutaja leht - Tabel näitab tantsupaaride pildid ja saab lisada pilti. +
3. Sorteerimine tabelis: kui vajutada pealkirja peale, siis andmed sorteeritakse. +
4. Oma ülesanne: lisa punktid summ ja summ paarid