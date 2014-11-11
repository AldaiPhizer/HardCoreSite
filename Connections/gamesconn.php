<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_gamesconn = "localhost";
$database_gamesconn = "games";
$username_gamesconn = "auriga_gamer";
$password_gamesconn = "BenHur.1959";

$gamesconn_mysqli = new mysqli($hostname_gamesconn, $username_gamesconn, $password_gamesconn, $database_gamesconn);
mysqli_query($gamesconn_mysqli, "SET NAMES 'utf8'");

/*$gamesconn = mysql_pconnect($hostname_gamesconn, $username_gamesconn, $password_gamesconn) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_query("SET NAMES 'utf8'");*/
?>