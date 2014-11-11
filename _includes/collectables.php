<script>
function DoAlert(message)
{
	alert(message); // this is the message in ""
}

</script>
<?php include_once('/Connections/gamesconn.php'); 
 	  include_once('/Connections/customersconn.php'); 

	if (!isset($_SESSION))
	{
		session_start(); //Iniciamos la Sesion o la Continuamos
	}
	
	$var_Interes = 'A';
	$var_Mundos = 'All';
	$var_Alcance = 'A';
	$var_Usuario = '';

	$muestra_detalle = false;
	if (isset($_SESSION['usuario']))
	{
		if ($_SESSION['usuario'] <> 'usuario'){
			$muestra_detalle = true;
			$var_Usuario = $_SESSION['usuario'];
		}
		else{
			$muestra_detalle = false;
			$var_Usuario = '';
		}
	}

	if (isset($_GET['lst_games'])) {
		$var_Mundos = $_GET['lst_games'];
	}

	if (isset($_GET['lst_interes'])) {
		$var_Interes = $_GET['lst_interes'];
	}
	
	if (isset($_GET['chk_misofertas'])){
		if ($_GET['chk_misofertas'] == 1){
			$var_Alcance = 'M';
		}else{
			$var_Alcance = 'A';
		}
	}

/****************************  Collectables Handling - Start ***********************************/
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rs_collectables = 30;
$pageNum_rs_collectables = 0;
if (isset($_GET['pageNum_rs_collectables'])) {
  $pageNum_rs_collectables = $_GET['pageNum_rs_collectables'];
}
$startRow_rs_collectables = $pageNum_rs_collectables * $maxRows_rs_collectables;

if (isset($_GET['lst_games']) && isset($_GET['lst_interes']) && isset($_GET['chk_misofertas'])) {
	$var_Mundos = $_GET['lst_games'];
	$var_Interes = $_GET['lst_interes'];
	if ($_GET['chk_misofertas'])
	{
		$var_Alcance = 'M';
	}else{
		$var_Alcance = 'A';
	}
}

	$query_rs_collectables = 
		"Call games.GetOffers(0, 0, '" . $var_Mundos . "', '" . $var_Interes . "', '" . $var_Alcance . "', '" . $var_Usuario . "', 0)";
		/*echo '<script>DoAlert("' . $query_rs_collectables . '")</script>';*/
	if ($rs_collectables = mysqli_query($gamesconn_mysqli, $query_rs_collectables))
	{
		if (isset($_GET['totalRows_rs_collectables'])) {
		  $totalRows_rs_collectables = $_GET['totalRows_rs_collectables'];
		} else {
		
		  $totalRows_rs_collectables = $rs_collectables->num_rows; 
		}
	}
	$totalPages_rs_collectables = ceil($totalRows_rs_collectables/$maxRows_rs_collectables)-1;
	$rs_collectables->close();
	$gamesconn_mysqli->next_result();
	$query_rs_collectables = 
		"Call games.GetOffers(" .$startRow_rs_collectables .", " . $maxRows_rs_collectables . ", '" 
							    . $var_Mundos . "', '" . $var_Interes . "', '" . $var_Alcance . "', '" . $var_Usuario . "', 0)";

	if ($rs_collectables = mysqli_query($gamesconn_mysqli, $query_rs_collectables))
	{
		$row_rs_collectables = mysqli_fetch_row($rs_collectables);
	}else{
		print $gamesconn_mysqli->error."<br />";
	}

$queryString_rs_collectables = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
	if (stristr($param, "pageNum_rs_collectables") == false && 
		stristr($param, "totalRows_rs_collectables") == false) {
	  array_push($newParams, $param);
	}
  }
  if (count($newParams) != 0) {
	$queryString_rs_collectables = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_collectables = sprintf("&totalRows_rs_collectables=%d%s", $totalRows_rs_collectables, $queryString_rs_collectables);
/****************************  Collectables Handling - End ***********************************/
/*This part is for the Games List box only*/
//mysql_select_db($database_customersconn, $customersconn);
$query_rs_games = "select gm_Name as Etiqueta, gm_Name from games.gm_Head Order by gm_Name";
$rs_games = mysqli_query($customersconn_mysqli, $query_rs_games) or die(mysqli_error());
$row_rs_games = mysqli_fetch_row($rs_games);
$totalRows_rs_games = mysqli_num_rows($rs_games);

?>
<a name="filtro"></a>
<div id="header" class="pagetitle">
Mercado Virtual
</div>
<div id="thepane" style="	height: 750px;
                            width: 1090px;
                            margin-top: 2px;
                            margin-left:2px;
                            border-top-width: 2px;
                            border-right-width: 1px;
                            border-bottom-width: 2px;
                            border-left-width: 3px;
                            border-top-style: solid;
                            border-right-style: solid;
                            border-bottom-style: solid;
                            border-left-style: solid;
                            border-top-color:#F90;
                            border-right-color: #F90;
                            border-bottom-color: #F90;
                            border-left-color: #F90;
    ">
<?php if ($var_Usuario != '') { ?>
 <a href="market.php?OfferId=0" style="position:absolute; top:1px; left:800px;">
 <img src="images/buttons/btn_crear_normal.png" onmouseout="this.src='images/buttons/btn_crear_normal.png'" 
                                                onmouseover="this.src='images/buttons/btn_crear_hover.png'" 
            alt="Crear" style="margin-top:55px" width="240" height="60" border="0" />
 </a>
 <?php } ?>
<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="get">
    <div> 
    <label class="datalabel" style="font-size: 24px; color:#FC0">Mundos
        <select name="lst_games" size="1" class="data" 
        onchange="form.action = window.location;this.form.submit();"> <!--"location.href='market.php?lst_games='+this.value+'#filtro'"-->
          <option class="drop" value="All" <?php if ($var_Mundos == 'All'){ ?> selected="selected" <?php } ?>>Todos</option>
          	<?php
			do {  
			?>
			<option class="drop" value="<?php echo $row_rs_games[0]?>" 
			<?php if ($var_Mundos == $row_rs_games[0]){ ?> selected="selected" <?php } ?>><?php echo $row_rs_games[0]?>
            </option>
			<?php
			} while ($row_rs_games = mysqli_fetch_row($rs_games));
			  $rows = mysqli_num_rows($rs_games);
			  if($rows > 0) {
				  mysqli_data_seek($rs_games, 0);
				  $row_rs_games = mysqli_fetch_row($rs_games);
			  }
			?>
      </select>
     </label>
     <label class="datalabel" style="font-size: 24px; color:#FC0">Interés
     	<select name="lst_interes" size="1" class="data" 
        onchange="form.action = window.location;this.form.submit();"> <!--"location.href='market.php?lst_interes='+this.value+'#filtro'"-->
     	  <option class="drop" value="A" <?php if ($var_Interes == 'A'){ ?> selected="selected" <?php } ?>>Todos</option>
     	  <option class="drop" value="B" <?php if ($var_Interes == 'B'){ ?> selected="selected" <?php } ?>>Compras</option>
     	  <option class="drop" value="S" <?php if ($var_Interes == 'S'){ ?> selected="selected" <?php } ?>>Ventas</option>
     	</select>
     </label>  	&nbsp;	&nbsp;	&nbsp; 	&nbsp; 	&nbsp;	&nbsp;
     <?php if ($muestra_detalle) { ?>
     <label id="lbl_misofertas" class="datalabel" style="font-size: 24px; color:#FC0; text-align:right">Mis Ofertas 
     <!-- Squared ONE -->
	 <p style="position:absolute; top:40px; left:650px" class="squaredOne" >
     <input name="chk_misofertas" type="checkbox" id="squaredOne" value="None" 
     		onchange="location.href='market.php?chk_misofertas='+this.value+'#filtro'" 
	 					<?php if ($var_Alcance == 'M'){ ?> checked="checked" <?php } ?>/>
	<label for="squaredOne"></label>
     </p>
     </label>

     <?php } ?>
  </div>
<br /><br />
<?php 
	if ($pageNum_rs_collectables > 0) { // Show if not first page ?>
	<a href="<?php printf("%s?pageNum_rs_collectables=%d%s", $currentPage, 0, $queryString_rs_collectables); ?>">
				<img src="images/buttons/first.png" alt="Primera" width="32" height="32" border="0" >
	</a> 
	<a href="<?php printf("%s?pageNum_rs_collectables=%d%s", $currentPage, max(0, $pageNum_rs_collectables - 1), $queryString_rs_collectables); ?>">
  			<img src="images/buttons/previous.png" alt="Anterior" width="32" height="32" border="0" >
	</a>
<?php } // Show if not first page ?>
  
<?php if ($pageNum_rs_collectables < $totalPages_rs_collectables) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_rs_collectables=%d%s", $currentPage, min($totalPages_rs_collectables, $pageNum_rs_collectables + 1), $queryString_rs_collectables); ?>">
  			<img src="images/buttons/next.png" alt="Siguiente" width="32" height="32" border="0" >
  </a>
  <a href="<?php printf("%s?pageNum_rs_collectables=%d%s", $currentPage, $totalPages_rs_collectables, $queryString_rs_collectables); ?>">
  			<img src="images/buttons/last.png" alt="Última" width="32" height="32" border="0" >
  </a>
<?php }// Show if not last page ?>

  <table border="0" style="margin-left:auto; margin-right:auto; table-layout:fixed; overflow:hidden" >
  <tr class="tableheaders">
    <th>Mundo</th>
    <th>Artículo</th>
    <th>Precio</th>
    <th>Acción</th>
    <th>Propietario</th>
    <th>Num.</th>
  </tr>
  <?php do { ?>
    <tr class="data">
      <td style="width:100px; font-weight:bold"><?php echo $row_rs_collectables[0]; ?></td>
      <td style="width:400px; font-weight:bold"><?php echo $row_rs_collectables[1]; ?></td>
      <td style="width:200px; font-weight:bold">
		<em style="color:#F60"><?php echo $row_rs_collectables[2] ?></em> /
								<em style="color:#CCC"><?php echo $row_rs_collectables[3] ?></em> /
								<em style="color:#FF3"><?php echo $row_rs_collectables[4] ?></em> /
	  				 			<em style="color:#060"><?php echo $row_rs_collectables[5] ?></em>
      </td>
      <td style="width:80px; font-weight:bold"><?php echo $row_rs_collectables[8] ?></td>
      <td style="width:120px; font-weight:bold"><?php echo $row_rs_collectables[7] ?></td>
      <td style="width:80px; font-weight:bold; text-align:center; "> 
      			<a style="font-family: Verdana, Geneva, sans-serif; font-style:normal; color: #CCC;" 
                   href="market.php?OfferId=<?php echo $row_rs_collectables[9] ?>">
						<?php echo $row_rs_collectables[9]; ?> 
                 </a>
                 </td>
    </tr>
    <?php } while ($row_rs_collectables = mysqli_fetch_row($rs_collectables)); ?>
</table>
</form>
<?php
/*	$rs_collectables->close();
	$gamesconn_mysqli->next_result();*/
mysqli_free_result($rs_collectables);

mysqli_free_result($rs_games);


?>
</div>