<script>
function DoAlert(message)
{
	alert(message); // this is the message in ""
}

</script>
<?php 
	include_once('/Connections/gamesconn.php'); 
 	include_once('/Connections/customersconn.php'); 

	if (!isset($_SESSION))
	{
		session_start(); //Iniciamos la Sesion o la Continuamos
	}
	
	$var_Usuario = '';
	$var_NumUsuario = 0;
	$var_PlayerName = '';

	$var_Mundos = 'All';
	$var_GameServer = 'All';
	$var_GameName = '';
	
	$var_ContestName = '';	
	$var_LongDescription = ''; 
	$var_StartDate = ''; 
	$var_EndDate = ''; 
	$var_StartLevel = ''; 
	$var_EndLevel = ''; 
	$var_BaseInscriptionCost = ''; 
	$var_ExtendedInscriptionCost = '';
	$var_MaxPlayers = '';
	$var_MinPlayers = '';


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

	if (isset($_GET['Servers'])){
		if($_GET['Servers'] != ''){
			$var_GameServer = $_GET['Servers'];
		}
	}

	if (isset($_GET['PlayerName'])){
		if($_GET['PlayerName'] != ''){
			$var_PlayerName = $_GET['PlayerName'];
		}
	}
	
	if (isset($_GET['ContestName'])){
		if($_GET['ContestName'] != ''){
			$var_ContestName = $_GET['ContestName'];
		}
	}
	
	if (isset($_GET['LongDescription'])){
		if($_GET['LongDescription'] != ''){
			$var_LongDescription = $_GET['LongDescription'];
		}
	}

	if (isset($_GET['StartDate'])){
		if($_GET['StartDate'] != ''){
			$var_StartDate = $_GET['StartDate'];
		}
	}

	if (isset($_GET['EndDate'])){
		if($_GET['EndDate'] != ''){
			$var_EndDate = $_GET['EndDate'];
		}
	}

	if (isset($_GET['StartLevel'])){
		if($_GET['StartLevel'] != ''){
			$var_StartLevel = $_GET['StartLevel'];
		}
	}

	if (isset($_GET['EndLevel'])){
		if($_GET['EndLevel'] != ''){
			$var_EndLevel = $_GET['EndLevel'];
			if ($var_EndLevel == ''){
				$var_EndLevel = 'Ilimitado';
			}
		}
	}

	if (isset($_GET['BaseInscriptionCost'])){
		if($_GET['BaseInscriptionCost'] != ''){
			$var_BaseInscriptionCost = $_GET['BaseInscriptionCost'];
		}
	}

	if (isset($_GET['ExtendedInscriptionCost'])){
		if($_GET['ExtendedInscriptionCost'] != ''){
			$var_ExtendedInscriptionCost = $_GET['ExtendedInscriptionCost'];
		}
	}

	if (isset($_GET['MaxPlayers'])){
		if($_GET['MaxPlayers'] != ''){
			$var_MaxPlayers = $_GET['MaxPlayers'];
		}
	}

	if (isset($_GET['MinPlayers'])){
		if($_GET['MinPlayers'] != ''){
			$var_MinPlayers = $_GET['MaxPlayers'];
		}
	}



/*************** This part is for the User Number only ***********************/
	//mysql_select_db($database_customersconn, $customersconn);
	$query_rs_cusnum = "Select cu_number 
						  From customers.cu_head
						 Where cu_Aka = '". $var_Usuario . "';";
	$rs_cusnum = mysqli_query($customersconn_mysqli, $query_rs_cusnum) or die(mysqli_error());
	$row_rs_cusnum = mysqli_fetch_row($rs_cusnum);
	
	$var_NumUsuario = $row_rs_cusnum[0];
	
/*************************************  Subscribe the contest - Start ********************************/
	if (isset($_GET['GoFor_It'])){
		$query_rs_create = "Call games.SetContestPlayers('C', '" 
												  . $var_Usuario . "', '" 
												  . $var_GameServer . "', '"
												  . $var_PlayerName . "', '"
												  . $var_ContestName . "');";
		/*echo '<script>DoAlert("' . $query_rs_create . '")</script>'; */	
		if ($rs_create = mysqli_query($gamesconn_mysqli, $query_rs_create))
		{
			$row_rs_create = mysqli_fetch_row($rs_create);
			$var_Retorno = $row_rs_create['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_create->close();
			$gamesconn_mysqli->next_result();
			$var_Priority = 0;
			echo '<META http-equiv="Refresh" Content="0; contests.php?#filtro"';
		}else{
			$var_Retorno =  $gamesconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>';
			echo '<META http-equiv="Refresh" Content="0; account.php"';
		}
		/*$gamesconn_mysqli->next_result();*/
	}
/************************************* Subscribe the contest - End ********************************/
/**************************************** This part is for the Games List box only ************/
	//mysql_select_db($database_customersconn, $customersconn);
	$query_rs_games = "select gm_Name as Etiqueta, gm_Name from games.gm_Head Order by gm_Name";
	$rs_games = mysqli_query($customersconn_mysqli, $query_rs_games) or die(mysqli_error());
	$row_rs_games = mysqli_fetch_row($rs_games);
	$totalRows_rs_games = mysqli_num_rows($rs_games);
/************************************** This part is for the Servers list box only *************/
	//mysql_select_db($database_gamesconn, $gamesconn);
	if ($var_Mundos != 'All'){
		$query_rs_server = "Select sv_Name from gm_servers Where sv_GameName = '" . $var_Mundos . "' Order by sv_Name;";
	}else{
		$query_rs_server = "Select sv_Name from gm_servers Order by sv_Name;";
	}
	
	$rs_server = mysqli_query($gamesconn_mysqli, $query_rs_server) or die(mysqli_error());
	$row_rs_server = mysqli_fetch_row($rs_server);
	$totalRows_rs_server = mysqli_num_rows($rs_server);
	//$gamesconn_mysqli->next_result();
/**************************************** This part is for the Players List box only ************/
	if ($var_GameServer != 'All' && $var_Usuario != '') {
		//mysql_select_db($database_gamesconn, $gamesconn);
		$query_rs_players = "select ch_Name From games.gm_characters Where ch_ServerName = '" . $var_GameServer . "' "
							. " And ch_CustomerNumber = ". $var_NumUsuario . ";";
/*	echo '<script>DoAlert("' . $query_rs_players . '")</script>';*/

		$rs_players = mysqli_query($gamesconn_mysqli, $query_rs_players) or die(mysqli_error());
		$row_rs_players = mysqli_fetch_row($rs_players);
		$totalRows_rs_players = mysqli_num_rows($rs_players);
		
	}
/****************************  Contests Handling - Start ***********************************/
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rs_contests = 7;
$pageNum_rs_contests = 0;
if (isset($_GET['pageNum_rs_contests'])) {
  $pageNum_rs_contests = $_GET['pageNum_rs_contests'];
}
$startRow_rs_contests = $pageNum_rs_contests * $maxRows_rs_contests;

	$query_rs_contests = 
		"Call games.GetContests(0, 0, '" . $var_Mundos . "', '" . $var_GameServer . "')";
		/*echo '<script>DoAlert("' . $query_rs_contests . '")</script>';*/
	if ($rs_contests = mysqli_query($gamesconn_mysqli, $query_rs_contests))
	{
		if (isset($_GET['totalRows_rs_contests'])) {
		  $totalRows_rs_contests = $_GET['totalRows_rs_contests'];
		} else {
		
		  $totalRows_rs_contests = $rs_contests->num_rows; 
		}
	}
	$totalPages_rs_contests = ceil($totalRows_rs_contests/$maxRows_rs_contests)-1;
	$rs_contests->close();
	$gamesconn_mysqli->next_result();
	$query_rs_contests = 
		"Call games.GetContests(" .$startRow_rs_contests .", " . $maxRows_rs_contests . ", '" 
							    . $var_Mundos . "', '" . $var_GameServer . "')";
		
	if ($rs_contests = mysqli_query($gamesconn_mysqli, $query_rs_contests))
	{
		$row_rs_contests = mysqli_fetch_row($rs_contests);
	}else{
		print $gamesconn_mysqli->error."<br />";
	}

$queryString_rs_contests = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
	if (stristr($param, "pageNum_rs_contests") == false && 
		stristr($param, "totalRows_rs_contests") == false) {
	  array_push($newParams, $param);
	}
  }
  if (count($newParams) != 0) {
	$queryString_rs_contests = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_contests = sprintf("&totalRows_rs_contests=%d%s", $totalRows_rs_contests, $queryString_rs_contests);
/****************************  Contests Handling - End ***********************************/


?>

<div id="header" class="pagetitle" style="color:#FC0">
Concursos
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
                            border-top-color:#006;
                            border-right-color: #006;
                            border-bottom-color: #006;
                            border-left-color: #006;
">
<a name="filtro"></a>
<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="get">
    <div> &nbsp;&nbsp;&nbsp; 
    <label class="datalabel" style="font-size: 24px; color:#FC0">Mundos
        <select name="lst_games" size="1" class="anydata" 
        onchange="form.action = window.location;this.form.submit();"> <!--"location.href='contests.php?lst_games='+this.value+'#filtro'"-->
          <option class="anydrop" value="All" <?php if ($var_Mundos == 'All'){ ?> selected="selected" <?php } ?>>Todos</option>
          	<?php
			do {  
			?>
			<option class="anydrop" value="<?php echo $row_rs_games[0]?>" 
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
     <label class="datalabel" style="font-size: 24px; color:#FC0">Servidor
        <select name="Servers" size="1" class="anydata" 
        onchange="form.action = window.location;this.form.submit();">  <!--"location.href='contests.php?lst_games=<?php/* echo $var_Mundos ?>&Servers='+this.value+'#filtro'"-->
          <option class="anydrop" value="" <?php if ($var_GameServer == ''){ ?> selected="selected" <?php } ?>>Todos</option>
            <?php
            do {  
            ?>
            <option class="anydrop" value="<?php echo $row_rs_server[0]?>" 
            <?php if ($var_GameServer == $row_rs_server[0]){ ?> 
                        selected="selected" <?php } ?> > <?php echo $row_rs_server[0]?>
            </option>
            <?php
            } while ($row_rs_server = mysqli_fetch_row($rs_server));
              $rows = mysqli_num_rows($rs_server);
              if($rows > 0) {
                  mysqli_data_seek($rs_server, 0);
                  $row_rs_server = mysqli_fetch_row($rs_server);
              }
            ?>
        </select>
     </label>

	</div>
<br /><br />
<?php 
	if ($pageNum_rs_contests > 0) { // Show if not first page ?>
	<a href="<?php printf("%s?pageNum_rs_contests=%d%s", $currentPage, 0, $queryString_rs_contests); ?>">
				<img src="images/buttons/first.png" alt="Primera" width="32" height="32" border="0" >
	</a> 
	<a href="<?php printf("%s?pageNum_rs_contests=%d%s", $currentPage, max(0, $pageNum_rs_contests - 1), $queryString_rs_contests); ?>">
  			<img src="images/buttons/previous.png" alt="Anterior" width="32" height="32" border="0" >
	</a>
<?php } // Show if not first page ?>
  
<?php if ($pageNum_rs_contests < $totalPages_rs_contests) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_rs_contests=%d%s", $currentPage, min($totalPages_rs_contests, $pageNum_rs_contests + 1), $queryString_rs_contests); ?>">
  			<img src="images/buttons/next.png" alt="Siguiente" width="32" height="32" border="0" >
  </a>
  <a href="<?php printf("%s?pageNum_rs_contests=%d%s", $currentPage, $totalPages_rs_contests, $queryString_rs_contests); ?>">
  			<img src="images/buttons/last.png" alt="Última" width="32" height="32" border="0" >
  </a>
<?php }// Show if not last page ?>

  <table border="0" style="margin-left:auto; margin-right:auto; table-layout:fixed; overflow:hidden" >
  <tr style="font-size:30px;">
    <th>Concurso</th>
    <th>Mundo</th>
    <th>Servidor</th>
    <th>Reglas</th>
  </tr>
  <?php do { ?>
    <tr class="anydata">
      <td style="width:300px; font-weight:bold">
      			<a style="font-family: Verdana, Geneva, sans-serif; font-style:normal; color: #CCC;" 
                   href="contests.php?lst_games=<?php echo $row_rs_contests[1] 
				   					?>&Servers=<?php echo $row_rs_contests[2]  
									?>&ContestName=<?php echo $row_rs_contests[0] 
									?>&LongDescription=<?php echo $row_rs_contests[3] . '. ' . $row_rs_contests[4] 
									?>&StartDate=<?php echo $row_rs_contests[5] 
									?>&EndDate=<?php echo $row_rs_contests[6] 
									?>&StartLevel=<?php echo $row_rs_contests[7] 
									?>&EndLevel=<?php echo $row_rs_contests[8] 
									?>&BaseInscriptionCost=<?php echo $row_rs_contests[9] 
									?>&ExtendedInscriptionCost=<?php echo $row_rs_contests[10] 
									?>&MaxPlayers=<?php echo $row_rs_contests[11] 
									?>&MinPlayers=<?php echo $row_rs_contests[12] 
									?>#detalle">
	  						<?php echo $row_rs_contests[0]; ?>
                </a></td>
      <td style="width:150px; font-weight:bold"><?php echo $row_rs_contests[1]; ?></td>
      <td style="width:150px; font-weight:bold"><?php echo $row_rs_contests[2] ?></td>
      <td style="width:400px; font-weight:bold"><?php echo $row_rs_contests[3] ?></td>
    </tr>
    <?php } while ($row_rs_contests = mysqli_fetch_row($rs_contests)); ?>
</table>
</form>
<?php
	$rs_contests->close();
	$gamesconn_mysqli->next_result();
/*mysqli_free_result($rs_contests);*/
mysqli_free_result($rs_games);

?>
    <?php if ($var_ContestName != '') { //Hemos seleccionado un concurso
    
    ?>
    <a name="detalle"></a>
    <form id="SubscribeContest" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
       <input name="ContestName" type="hidden" value="<?php echo $var_ContestName ?>" />
       <input name="lst_games" type="hidden" value="<?php echo $var_Mundos ?>" />
       <input name="Servers" type="hidden" value="<?php echo $var_GameServer ?>" />
       <input name="PlayerName" type="hidden" value="<?php echo $var_PlayerName ?>" />
       <p style="position:absolute; width:1050px; left:40px; top:500px;"  >
       <label class="datalabel" style="font-size: 16px; color:#FC0">Concurso</label>
       <label class="datalabel" style="font-size: 16px; color:#FFF"><?php echo $var_ContestName ?></label>
       <br />
       <label class="datalabel" style="font-size: 16px; color:#FC0">Descripción</label>
       <label class="datalabel" style="font-size: 16px; color:#FFF"><?php echo $var_LongDescription ?></label>
       <br />
       <label class="datalabel" style="font-size: 16px; color:#FC0">Inicia</label>
       <label class="datalabel" style="font-size: 16px; color:#FFF"><?php echo $var_StartDate ?></label>
       <br />
       <label class="datalabel" style="font-size: 16px; color:#FC0">Finaliza</label>
       <label class="datalabel" style="font-size: 16px; color:#FFF"><?php echo $var_EndDate ?></label>
       <br />
       <label class="datalabel" style="font-size: 16px; color:#FC0">Nivel Requerido</label>
       <label class="datalabel" style="font-size: 16px; color:#FFF"><?php echo $var_StartLevel ?></label>
       <br />
       <label class="datalabel" style="font-size: 16px; color:#FC0">Nivel Tope</label>
       <label class="datalabel" style="font-size: 16px; color:#FFF"><?php echo $var_EndLevel ?></label>
       <br />
       <label class="datalabel" style="font-size: 16px; color:#FC0">Valor Inscripción - Oro</label>
       <label class="datalabel" style="font-size: 16px; color:#FFF"><?php echo $var_BaseInscriptionCost ?></label>
       <br />
       <label class="datalabel" style="font-size: 16px; color:#FC0">Valor Inscripción - Bronce</label>
       <label class="datalabel" style="font-size: 16px; color:#FFF"><?php echo $var_ExtendedInscriptionCost ?></label>
       <br />
       <label class="datalabel" style="font-size: 16px; color:#FC0">Tope Jugadores</label>
       <label class="datalabel" style="font-size: 16px; color:#FFF"><?php echo $var_MaxPlayers ?></label>
       <br />
       <label class="datalabel" style="font-size: 16px; color:#FC0">Mínimo de Jugadores</label>
       <label class="datalabel" style="font-size: 16px; color:#FFF"><?php echo $var_MinPlayers ?></label>

      </p>
       <p style=" position:absolute; left:400px; top:650px;">
       <?php if ($var_Usuario != '') { ?>
        <label class="datalabel" style="font-size: 24px; color:#FC0">Jugador
            <select name="PlayerName" size="1" class="anydata" 
            onchange="location.href='contests.php?lst_games=<?php echo $var_Mundos
												?>&Servers=<?php echo $var_GameServer
												?>&ContestName=<?php echo $var_ContestName 
												?>&LongDescription=<?php echo $var_LongDescription
												?>&StartDate=<?php echo $var_StartDate 
												?>&EndDate=<?php echo $var_EndDate
												?>&StartLevel=<?php echo $var_StartLevel 
												?>&EndLevel=<?php echo $var_EndLevel
												?>&BaseInscriptionCost=<?php echo $var_BaseInscriptionCost
												?>&ExtendedInscriptionCost=<?php echo $var_ExtendedInscriptionCost
												?>&MaxPlayers=<?php echo $var_MaxPlayers
												?>&MinPlayers=<?php echo $var_MinPlayers
												?>&PlayerName='+this.value+'#detalle'">
              <option class="anydrop" value="All" <?php if ($var_PlayerName == ''){ ?> selected="selected" <?php } ?>>Selecciona un jugador</option>
                <?php
                do {  
                ?>
                <option class="anydrop" value="<?php echo $row_rs_players[0]?>" 
                <?php if ($var_PlayerName == $row_rs_players[0]){ ?> selected="selected" <?php } ?>><?php echo $row_rs_players[0]?>
                </option>
                <?php
                } while ($row_rs_players = mysqli_fetch_row($rs_players));
                  $rows = mysqli_num_rows($rs_players);
                  if($rows > 0) {
                      mysqli_data_seek($rs_players, 0);
                      $row_rs_players = mysqli_fetch_row($rs_players);
                  }
                ?>
         	 </select>
         </label>
         <?php } ?>
      </p>   
	<?php if ($var_PlayerName != '') { ?>
        <p style=" position:absolute; left:600px; top:700px;">
            <input  type="submit" name="GoFor_It"  value="" class="botoninscribir"/> <!-- Es Uno no "ele"-->
        </p>    
    <?php } ?>
   </form>
	<script  type="text/javascript">
        var frmvalidator = new Validator("SubscribeContest");
        frmvalidator.addValidation("PlayerName","req", "Selecciona un jugador");
    </script>
    <?php } ?>
</div>