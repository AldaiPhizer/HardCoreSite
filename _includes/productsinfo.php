<script>
function DoAlert(message)
{
	alert(message); // this is the message in ""
}

</script>

<?php require_once('/Connections/gamesconn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

//mysql_select_db($database_gamesconn, $gamesconn);
$query_rs_games = "SELECT gm_Name FROM gm_head WHERE gm_DateCeased is null ORDER BY gm_head.gm_DateCreated;";
$rs_games = mysqli_query($gamesconn_mysqli, $query_rs_games);
$row_rs_games = mysqli_fetch_row($rs_games);
$totalRows_rs_games = mysqli_num_rows($rs_games);
	

$i=1;
?>
<?php do { 
		
?>
    <a href="<?php echo $row_rs_games[0]; ?>.php">
    	<img style="padding:2px;" src="images/banners/<?php echo $row_rs_games[0]; ?>.jpg" >
    </a>
  <?php 
    } while ($row_rs_games = mysqli_fetch_row($rs_games)); ?>

<?php
mysqli_free_result($rs_games);
?>