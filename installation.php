<?php
include("inc/bdd.php");
require('inc/config.php');

if (isset($_GET['ok'])) {
    echo "<h3>The installation is finish. You need to delete the file  \"installation.php\"  now !!! for safety (check if it work before that :)</h3>";
    exit;
}
if ($connexion->errorCode() == 0) {
    $connexion->query("
    CREATE TABLE `shortener` (
      `short` char(".URL_SIZE.") NOT NULL,
      `url` varchar(700) NOT NULL,
      `comment` char(30) DEFAULT NULL,
      `views` int(11) DEFAULT NULL,
      `username` varchar(25) DEFAULT NULL,
      `date` datetime NOT NULL,
      PRIMARY KEY (`short`)
      );

    CREATE TABLE `users` (
      `username` varchar(25) NOT NULL,
      `password` char(128) NOT NULL,
      `email` varchar(255) NOT NULL,
      `token` char(15) NOT NULL,
      `admin` tinyint(1) NOT NULL DEFAULT '0'
      );
    ");
    header('Location: installation.php?ok');
}
else {
    echo "SQL ERROR   ".$connexion->errorCode();
    exit;
}


?>
