<?php
  include "config.php";
  $db = new mysqli($servername, $username, $password, $dbname);
  $db->set_charset("utf8");
  $db->query("SET time_zone = \"+11:00\"");
?>
