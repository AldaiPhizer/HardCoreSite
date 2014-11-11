<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_customersconn = "localhost";
$database_customersconn = "customers";
$username_customersconn = "root";
$password_customersconn = "Aldarius.02";

$customersconn_mysqli = new mysqli($hostname_customersconn, $username_customersconn, $password_customersconn, $database_customersconn);
mysqli_query($customersconn_mysqli, "SET NAMES 'utf8'");
/*
$customersconn = mysql_pconnect($hostname_customersconn, $username_customersconn, $password_customersconn) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMES 'utf8'");
*/
?>