<?php
$kasutaja='tarpv21vassiljev';//d113377_german | tarpv21vassiljev
$server='localhost';//d113377.mysql.zonevs.eu | localhost
$andmebaas='tarpv21vassiljev';//d113377_baas | tarpv21vassiljev
$salasyna='12345';//htijrn9trhibnmdfohbn | 12345
//teeme käsk mis ühendab andmebaasiga
$yhendus=new mysqli($server,$kasutaja,$salasyna,$andmebaas);
$yhendus->set_charset('UTF8');
?>
