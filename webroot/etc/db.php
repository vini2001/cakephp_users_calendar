<?php
  $servername = "classmatch.com.br";
  $username = "classmat_vinny";
  $password = "vinny2020";
  $dbname = "classmat_cake";
  $db = new mysqli($servername, $username, $password, $dbname);
  $db->set_charset("utf8");
  $db->query("SET time_zone = \"+00:00\"");
?>
