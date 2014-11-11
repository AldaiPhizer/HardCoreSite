<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_worldconn = "localhost";
$database_worldconn = "world";
$username_worldconn = "auriga_gamer";
$password_worldconn = "BenHur.1959";
$worldconn_mysqli = new mysqli($hostname_worldconn, $username_worldconn, $password_worldconn, 'world');
mysqli_query($worldconn_mysqli, "SET NAMES 'utf8'");
/*
$worldconn = mysql_pconnect($hostname_worldconn, $username_worldconn, $password_worldconn) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_query("SET NAMES 'utf8'");
*/
?>