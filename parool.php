<?php
$parool = 'german';
$sool = 'taiestisuvalinetekst';
$kryp = crypt($parool, $sool);
echo $kryp;
?>