<script>
function DoAlert(message)
{
	alert(message); // this is the message in ""
}
</script>

<script type="text/javascript">

	function ConvertMoney(option)
	{
		/******************************** User Money Section *********************************************/
		if (option=='GtoS' &&
			document.getElementById('NewUserGold').value >= 1){
				document.getElementById('NewUserGold').value = document.getElementById('NewUserGold').value - 1;
				document.getElementById('NewUserSilver').value = parseInt(document.getElementById('NewUserSilver').value) + 10;
		}
		
		if (option=='StoG' &&
			document.getElementById('NewUserSilver').value >= 10){
				document.getElementById('NewUserSilver').value = document.getElementById('NewUserSilver').value - 10;
				document.getElementById('NewUserGold').value = parseInt(document.getElementById('NewUserGold').value) + 1;
		}
		
		if (option=='StoB' &&
			document.getElementById('NewUserSilver').value >= 1){
				document.getElementById('NewUserSilver').value = document.getElementById('NewUserSilver').value - 1;
				document.getElementById('NewUserBronze').value = parseInt(document.getElementById('NewUserBronze').value) + 100;
		}

		if (option=='BtoS' &&
			document.getElementById('NewUserBronze').value >= 100){
				document.getElementById('NewUserBronze').value = document.getElementById('NewUserBronze').value - 100;
				document.getElementById('NewUserSilver').value = parseInt(document.getElementById('NewUserSilver').value) + 1;
		}
		
		/**************************************** Players Money Sectio *********************************************/
		if (option=='PGtoS' &&
			document.getElementById('NewPlayerGold').value >= 1){
				document.getElementById('NewPlayerGold').value = document.getElementById('NewPlayerGold').value - 1;
				document.getElementById('NewPlayerSilver').value = parseInt(document.getElementById('NewPlayerSilver').value) + 10;
		}
		
		if (option=='PStoG' &&
			document.getElementById('NewPlayerSilver').value >= 10){
				document.getElementById('NewPlayerSilver').value = document.getElementById('NewPlayerSilver').value - 10;
				document.getElementById('NewPlayerGold').value = parseInt(document.getElementById('NewPlayerGold').value) + 1;
		}
		
		if (option=='PStoB' &&
			document.getElementById('NewPlayerSilver').value >= 1){
				document.getElementById('NewPlayerSilver').value = document.getElementById('NewPlayerSilver').value - 1;
				document.getElementById('NewPlayerBronze').value = parseInt(document.getElementById('NewPlayerBronze').value) + 100;
		}

		if (option=='PBtoS' &&
			document.getElementById('NewPlayerBronze').value >= 100){
				document.getElementById('NewPlayerBronze').value = document.getElementById('NewPlayerBronze').value - 100;
				document.getElementById('NewPlayerSilver').value = parseInt(document.getElementById('NewPlayerSilver').value) + 1;
		}
		
	}
/*********************************************** User - Player Money Exchange ***********************************/
	function MoneyExchange(operation,coin){
		
		if (coin=='Gold'){
			if (operation == 'Add' && document.getElementById('NewUserGold').value >= 1){
				document.getElementById('NewPlayerGold').value = parseInt(document.getElementById('NewPlayerGold').value) + 1;
				document.getElementById('NewUserGold').value = document.getElementById('NewUserGold').value - 1;
			}
			if (operation == 'Sub' && document.getElementById('NewPlayerGold').value >= 1){
				document.getElementById('NewUserGold').value = parseInt(document.getElementById('NewUserGold').value) + 1;
				document.getElementById('NewPlayerGold').value = document.getElementById('NewPlayerGold').value - 1;
			}
		}
		
		if (coin=='Silver'){
			if (operation == 'Add' && document.getElementById('NewUserSilver').value >= 10){
				document.getElementById('NewPlayerSilver').value = parseInt(document.getElementById('NewPlayerSilver').value) + 10;
				document.getElementById('NewUserSilver').value = document.getElementById('NewUserSilver').value - 10;
			}
			if (operation == 'Sub' && document.getElementById('NewPlayerSilver').value >= 10){
				document.getElementById('NewUserSilver').value = parseInt(document.getElementById('NewUserSilver').value) + 10;
				document.getElementById('NewPlayerSilver').value = document.getElementById('NewPlayerSilver').value - 10;
			}
		}
		
		if (coin=='Bronze'){
			if (operation == 'Add' && document.getElementById('NewUserBronze').value >= 100){
				document.getElementById('NewPlayerBronze').value = parseInt(document.getElementById('NewPlayerBronze').value) + 100;
				document.getElementById('NewUserBronze').value = document.getElementById('NewUserBronze').value - 100;
			}
			if (operation == 'Sub' && document.getElementById('NewPlayerBronze').value >= 100){
				document.getElementById('NewUserBronze').value = parseInt(document.getElementById('NewUserBronze').value) + 100;
				document.getElementById('NewPlayerBronze').value = document.getElementById('NewPlayerBronze').value - 100;
			}
		}
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
	$var_Mundos = 'All';
	
	$var_UserGold	= 0;
	$var_UserSilver = 0;
	$var_UserBronze = 0;
	$var_UserUSD = 0.00;

	$var_NewUserGold	= 0;
	$var_NewUserSilver 	= 0;
	$var_NewUserBronze 	= 0;
	$var_NewUserUSD		= 0.00;

	$var_PlayerName		= '';
	$var_PlayerServer	= '';
	$var_PlayerGold 	= 0;
	$var_PlayerSilver	= 0;
	$var_PlayerBronze	= 0;
	
	$var_NewPlayerGold 		= 0;
	$var_NewPlayerSilver	= 0;
	$var_NewPlayerBronze	= 0;
	
	$var_albegra = ''; // '+' or '-'
	$var_leeDB = true;
	$var_Retorno = '';
	
	if (isset($_SESSION['usuario']))
	{
		if ($_SESSION['usuario'] <> 'usuario'){
			$var_Usuario = $_SESSION['usuario'];
		}
		else{
			$var_Usuario = '';
		}
	}

	if (isset($_GET['PlayerName'])){
		$var_PlayerName		= $_GET['PlayerName'];
	}

	if (isset($_GET['PlayerServer'])){
		$var_PlayerServer	= $_GET['PlayerServer'];
	}

	if (isset($_GET['PlayerGold']) && $_GET['PlayerGold']!=''){
		$var_PlayerGold 	= $_GET['PlayerGold'];
		$var_NewPlayerGold 	= $_GET['PlayerGold'];
	}
	
	if (isset($_GET['PlayerSilver']) && $_GET['PlayerSilver']!=''){
		$var_PlayerSilver	= $_GET['PlayerSilver'];
		$var_NewPlayerSilver	= $_GET['PlayerSilver'];
	}
	
	if (isset($_GET['PlayerBronze']) && $_GET['PlayerBronze']!=''){
		$var_PlayerBronze	= $_GET['PlayerBronze'];
		$var_NewPlayerBronze	= $_GET['PlayerBronze'];
	}

/*This part is for the User Number only*/
	//mysql_select_db($database_customersconn, $customersconn);
	$query_rs_cusnum = "Select cu_number 
						  From customers.cu_head
						 Where cu_Aka = '". $var_Usuario . "';";
	$rs_cusnum = mysqli_query($customersconn_mysqli, $query_rs_cusnum) or die(mysqli_error());
	$row_rs_cusnum = mysqli_fetch_row($rs_cusnum);
	
	$var_NumUsuario = $row_rs_cusnum[0];

/*This part is for the Games List box only*/
	//mysql_select_db($database_customersconn, $customersconn);
	$query_rs_games = "select gm_Name as Etiqueta, gm_Name from games.gm_Head Order by gm_Name";
	$rs_games = mysqli_query($customersconn_mysqli, $query_rs_games) or die(mysqli_error());
	$row_rs_games = mysqli_fetch_row($rs_games);
	$totalRows_rs_games = mysqli_num_rows($rs_games);

/****************************************  Validation - Start **********************************/	
	if ($var_leeDB)
	{
		/*This part is for the User Validation only*/
		//mysql_select_db($database_customersconn, $customersconn);
		$query_rs_user = "select 
							cu_Aka, 
							cu_Bronze, 
							cu_Silver, 
							cu_Gold, 
							IfNull((select my_Amount from customers.cu_money where my_CustomerId = A.cu_Number and my_Currency = 'USD'),0) As Dollars 
							  from customers.cu_head A
							 where cu_Aka = '" . $var_Usuario . "';";
		$rs_user = mysqli_query($customersconn_mysqli, $query_rs_user) or die(mysqli_error());
		$row_rs_user = mysqli_fetch_row($rs_user);
		$totalRows_rs_user = mysqli_num_rows($rs_user);
		if ($totalRows_rs_user > 0){
			$_SESSION['usr_Bronze'] = $row_rs_user[1];
			$_SESSION['usr_Silver'] = $row_rs_user[2];
			$_SESSION['usr_Gold'] = $row_rs_user[3];
			$_SESSION['usr_USD'] = $row_rs_user[4];
			
			$var_UserBronze = $_SESSION['usr_Bronze'];
			$var_UserSilver = $_SESSION['usr_Silver'];
			$var_UserGold = $_SESSION['usr_Gold'];
			$var_UserUSD = $_SESSION['usr_USD'];
			
			$var_NewUserBronze = $var_UserBronze;
			$var_NewUserSilver = $var_UserSilver;
			$var_NewUserGold = $var_UserGold;
			$var_NewUserUSD = $var_UserUSD;
		}else{
			echo '<script>DoAlert("No se obtuvieron los datos")</script>';
		}
		mysqli_free_result($rs_user);
	}
/****************************************  Validation - End **********************************/	
	
	if (isset($_GET['NewUserGold'])){
		$var_NewUserGold 	= $_GET['NewUserGold'];
	}	
	if (isset($_GET['NewUserSilver'])){
		$var_NewUserSilver 	= $_GET['NewUserSilver'];
	}
	if (isset($_GET['NewUserBronze'])){
		$var_NewUserBronze 	= $_GET['NewUserBronze'];
	}
	if (isset($_GET['NewUserUSD'])){
		$var_NewUserUSD 	= $_GET['NewUserUSD'];
	}
	
	if (isset($_GET['NewPlayerGold'])){
		$var_NewPlayerGold 		= $_GET['NewPlayerGold'];
	}	
	if (isset($_GET['NewPlayerSilver'])){
		$var_NewPlayerSilver 	= $_GET['NewPlayerSilver'];
	}
	if (isset($_GET['NewPlayerBronze'])){
		$var_NewPlayerBronze 	= $_GET['NewPlayerBronze'];
	}

/****************************  Players Handling - Start ***********************************/
		$currentPage = $_SERVER["PHP_SELF"];
		
		$maxRows_rs_worlds = 5;
		$pageNum_rs_worlds = 0;
		if (isset($_GET['pageNum_rs_worlds'])) {
		  $pageNum_rs_worlds = $_GET['pageNum_rs_worlds'];
		}
		$startRow_rs_worlds = $pageNum_rs_worlds * $maxRows_rs_worlds;
			$query_rs_worlds = 
				"Call games.GetWorlds(0, 0, '" . $var_Usuario . "')";
				/*echo '<script>DoAlert("' . $query_rs_worlds . '")</script>';*/
			if ($rs_worlds = mysqli_query($gamesconn_mysqli, $query_rs_worlds))
			{
				if (isset($_GET['totalRows_rs_worlds'])) {
				  $totalRows_rs_worlds = $_GET['totalRows_rs_worlds'];
				} else {
				
				  $totalRows_rs_worlds = $rs_worlds->num_rows; 
				}
			}
			$totalPages_rs_worlds = ceil($totalRows_rs_worlds/$maxRows_rs_worlds)-1;
			$rs_worlds->close();
			$gamesconn_mysqli->next_result();
			$query_rs_worlds = 
			"Call games.GetWorlds(" .$startRow_rs_worlds .", " . $maxRows_rs_worlds . ", '" . $var_Usuario . "')";
		
			if ($rs_worlds = mysqli_query($gamesconn_mysqli, $query_rs_worlds))
			{
				$row_rs_worlds = mysqli_fetch_row($rs_worlds);
			}else{
				print $gamesconn_mysqli->error."<br />";
			}
		
		$queryString_rs_worlds = "";
		if (!empty($_SERVER['QUERY_STRING'])) {
		  $params = explode("&", $_SERVER['QUERY_STRING']);
		  $newParams = array();
		  foreach ($params as $param) {
			if (stristr($param, "pageNum_rs_worlds") == false && 
				stristr($param, "totalRows_rs_worlds") == false) {
			  array_push($newParams, $param);
			}
		  }
		  if (count($newParams) != 0) {
			$queryString_rs_worlds = "&" . htmlentities(implode("&", $newParams));
		  }
		}
		$queryString_rs_worlds = sprintf("&totalRows_rs_worlds=%d%s", $totalRows_rs_worlds, $queryString_rs_worlds);
/****************************  Players Handling - End ***********************************/

/*************************************  Update the Money - Start ********************************/
	if (isset($_GET['Procesar'])){
		$query_rs_update = "Call customers.SetMoney('U', '"
												  . $var_Usuario . "', '" 
												  . $var_PlayerName . "', '"
												  . $var_PlayerServer . "', "
												  . $var_NewUserGold . ", "
												  . $var_NewUserSilver . ", "
												  . $var_NewUserBronze . ", "
												  . $var_NewPlayerGold . ", "
												  . $var_NewPlayerSilver . ", "
												  . $var_NewPlayerBronze . " );";
		/*echo '<script>DoAlert("' . $query_rs_update . '")</script>';*/ 	
		if ($rs_update = mysqli_query($customersconn_mysqli, $query_rs_update))
		{
			$row_rs_update = mysqli_fetch_row($rs_update);
			$var_Retorno = $row_rs_update['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_update->close();
			$customersconn_mysqli->next_result();
			$var_Priority = 0;
			echo '<META http-equiv="Refresh" Content="0; account.php?admoney"';
		}else{
			$var_Retorno =  $customersconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>';
			echo '<META http-equiv="Refresh" Content="0; account.php"';
		}
	}
/*************************************  Update the Money - End ********************************/

?>
<div id="moneyhandel" style="background:url(images/backgrounds/WhiteStorm_big.png); width:1120px; height:825px" >
<a href="account.php" style="position:absolute">
	<img src="images/buttons/img_Volver.png"/>
</a>
<div id="header" class="pagetitle">
Administra tu Dinero <?php echo  $var_Usuario	?> 
</div>
<form id="user-form" name="user-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="get">
<div id="upperpane" style="	height: 530px;
                            width: 1092px;
                            margin-top: 2px;
                            margin-left:10px;
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


<div>
	 <input name="admoney" type="hidden" value="" />
     <input name="UserGold" type="hidden" value="<?php echo $var_UserGold ?>" />
     <input name="UserSilver" type="hidden" value="<?php echo $var_UserSilver ?>" />
     <input name="UserBronze" type="hidden" value="<?php echo $var_UserBronze ?>" />
     <input name="UserUSD" type="hidden" value="<?php echo $var_UserUSD ?>" />
     <input name="PlayerName" type="hidden" value="<?php echo $var_PlayerName ?>" />
     <input name="PlayerServer" type="hidden" value="<?php echo $var_PlayerServer ?>" />
     <label style=" width:1000px; 
     				height:60px; position:absolute; color:#FC0; margin-left:60px;
     				font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif">Distribuye tu dinero en distintos tipos de moneda o entre tu y tus personajes.  Joder! ¿Que más se puede pedir?</label>
     </br>
     <p style="margin-left:190px;">
     <input  type="submit" name="AddGold"  value="" class="botonaddgold"/> &nbsp; &nbsp;
     <input  type="submit" name="AddPaymentMethod"  value="" class="botonpayforms"/>
     </br>
     </p>
</div>     
    <table border="0" style="margin-left:120px; table-layout:fixed; overflow:hidden" >
	    <tr>
            <td width="75px" class="datalabel"  style="font-size:24px; font-weight:bolder; " >Oro: </td>
             <td width="75px" class="datalabel" style="font-size:24px; font-weight:bolder; "><?php echo $var_UserGold ?> </td>
            <td width="20px" class="datalabel" style="font-size:20px; text-align:center;">--></td>
            <td width="100px" style="color:#960; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >
                 <input name="NewUserGold" id="NewUserGold" type="text" readonly="readonly" size="8" value="<?php echo $var_NewUserGold ?>" 
                                 style="color:#FC0; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
             </td>
            <td width="180px" >
            	<input  type="button" name="usr_GoldToSilver"  value="" class="botontosilver" onclick="ConvertMoney('GtoS');"/>
            </td>
		</tr>
	    <tr>
            <td width="75px" class="datalabel"  style="font-size:24px; font-weight:bolder; " >Plata: </td>
            <td width="75px" class="datalabel"  style="font-size:24px; font-weight:bolder; "><?php echo $var_UserSilver ?></td>
            <td width="20px" class="datalabel" style="font-size:20px; text-align:center;">--></td>
            <td width="100px" style="color:#666; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >
				<input name="NewUserSilver" id="NewUserSilver" type="text" readonly="readonly" size="8" value="<?php echo $var_NewUserSilver ?>"
                            style="color:#CCC; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
			</td>
            <td width="180px" >
            	<input  type="button" name="usr_SilverToGold"  value="" class="botontogold" onclick="ConvertMoney('StoG');"/>
            </td>
            <td width="230px" >
            	<input  type="button" name="usr_SilverToBronze"  value="" class="botontobronze" onclick="ConvertMoney('StoB');"/>
            </td>
		</tr>
	    <tr>
            <td width="75px"  class="datalabel"  style="font-size:24px; font-weight:bolder; " >Bronce: </td>
            <td width="75px"  class="datalabel"  style="font-size:24px; font-weight:bolder; " ><?php echo $var_UserBronze ?></td>
            <td width="20px" class="datalabel" style="font-size:20px; text-align:center;">--></td>
            <td width="100px" style="color:#C60; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >
				<input name="NewUserBronze" id="NewUserBronze" type="text" readonly="readonly" size="8" value="<?php echo $var_NewUserBronze ?>"
                       style="color:#C60; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
			</td>
            <td width="180px" >
            	<input  type="button" name="usr_BronzeToSilver"  value="" class="botontosilver" onclick="ConvertMoney('BtoS');"/>
            </td>
		</tr>
	    <tr>
            <td width="75px" class="datalabel"  style="font-size:24px; font-weight:bolder; " >USD: </td>
            <td width="75px" class="datalabel"  style="font-size:24px; font-weight:bolder; " ><?php echo $var_UserUSD ?></td>
            <td width="20px" class="datalabel" style="font-size:20px; text-align:center;">--></td>
            <td width="100px" style="color:#363; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >
				<input name="NewUserUSD" id="NewUserUSD" type="text" readonly="readonly" size="8" value="<?php echo $var_NewUserUSD ?>"
                       style="color:#390; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
							
			</td>
            <td width="180px" >
            	<input  type="submit" name="usr_DollarsToCash"  value="" class="botoncobrar"/>
            </td>
		</tr>
	</table>
	<p style="position:absolute; margin-left:420px; ">
		<input  type="submit" name="Procesar"  value="" class="botonentrar"/>
	</p>
<br /><br /><br />
	<?php 
        if ($pageNum_rs_worlds > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rs_worlds=%d%s", $currentPage, 0, $queryString_rs_worlds); ?>">
                    <img src="images/buttons/first.png" alt="Primera" width="32" height="32" border="0" >
        </a> 
        <a href="<?php printf("%s?pageNum_rs_worlds=%d%s", $currentPage, max(0, $pageNum_rs_worlds - 1),
																					$queryString_rs_worlds); ?>">
                <img src="images/buttons/previous.png" alt="Anterior" width="32" height="32" border="0" >
        </a>
    <?php } // Show if not first page ?>
      
    <?php if ($pageNum_rs_worlds < $totalPages_rs_worlds) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rs_worlds=%d%s", $currentPage, min($totalPages_rs_worlds, $pageNum_rs_worlds + 1), $queryString_rs_worlds); ?>">
                <img src="images/buttons/next.png" alt="Siguiente" width="32" height="32" border="0" >
      </a>
      <a href="<?php printf("%s?pageNum_rs_worlds=%d%s", $currentPage, $totalPages_rs_worlds, $queryString_rs_worlds); ?>">
                <img src="images/buttons/last.png" alt="Última" width="32" height="32" border="0" >
      </a>
    <?php }// Show if not last page ?>
    <table border="0" style="margin-left:auto; margin-right:auto; table-layout:fixed; overflow:hidden" >
      <tr class="tableheaders">
        <th>Personaje</th>
        <th>Servidor</th>
        <th>Dinero</th>
        <th style="display:none;">Add type</th>
      </tr>
      <?php do { ?>
        <tr class="data">
            <td style="width:300px; font-weight:bold; text-align:center; "> 
            <?php if ($var_PlayerName == '') { ?>
                <a style="font-family: Verdana, Geneva, sans-serif; font-style:normal; color: #CCC;" 
                   href="account.php?admoney=''&PlayerName=<?php echo $row_rs_worlds[1] 
				   								?>&PlayerServer=<?php echo $row_rs_worlds[2]
												?>&PlayerGold=<?php echo $row_rs_worlds[4]
												?>&PlayerSilver=<?php echo $row_rs_worlds[5]
												?>&PlayerBronze=<?php echo $row_rs_worlds[6]
												?>#detalle">
                        <?php echo $row_rs_worlds[1]; ?> 
                 </a>
             <?php }else { echo $row_rs_worlds[1]; } ?>
            </td>
			<td style="width:300px; font-weight:bold"><?php echo $row_rs_worlds[2]; ?></td>
            <td style="width:350px; font-weight:bold; color:#FFF ">
                                    <em style="color:#F60"><?php echo $row_rs_worlds[6] ?></em> /
                                    <em style="color:#CCC"><?php echo $row_rs_worlds[5] ?></em> /
                                    <em style="color:#FF3"><?php echo $row_rs_worlds[4] ?></em>
            </td>
        </tr>
        <?php } while ($row_rs_worlds = mysqli_fetch_row($rs_worlds)); ?>
    </table>
<?php 
	$rs_worlds->close();
	if($customersconn_mysqli->more_results()){
		$customersconn_mysqli->next_result();
	}
?>



</div>	<!--upperpane-->

<div id="bottonpane" style="height: 220px;
                            width: 1092px;
                            margin-top: 2px;
                            margin-left:10px;
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
<a name="detalle"></a>
<?php if ($var_PlayerName != '') { //We're going to add update some players money

?>
	    <p style="position:absolute; left:75px; top:560px;"  >
        <br />
        <label style="color:#FC0; 
        			font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 24px;"><?php echo $var_PlayerName ?></label>
        <br /><br />
    	</p>
    <table border="0" style="margin-left:auto; margin-right:auto; margin-top:30px; table-layout:fixed; overflow:hidden" >
	    <tr>
            <td width="100px" class="datalabel"  style="font-size:24px; font-weight:bolder; " >Oro: </td>
            <td width="60px" class="datalabel"  style="font-size:24px; font-weight:bolder; text-align:right; " >
				<?php echo $var_PlayerGold ?>
            </td>
			<td width="20px" class="datalabel"  style="font-size:24px; font-weight:bolder; text-align:center; "> --></td>
            <td width="110px" >
                <input name="NewPlayerGold" id="NewPlayerGold" type="text" readonly="readonly"  size="8" value="<?php echo $var_NewPlayerGold ?>" 
                    style="color:#FC0; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
            </td>
            <td width="100px" >
            	<input  type="button" name="ply_MoreGold"  title="Dale oro a tu personaje"
                		class="botonmas"  onclick="MoneyExchange('Add','Gold');"/> &nbsp;
				<input  type="button" name="ply_LessGold"  title="Toma oro de tu personaje"
                		class="botonmenos" onclick="MoneyExchange('Sub','Gold');"/>
            </td>
            <td width="200px" >
            	<input  type="button" name="ply_GoldToSilver"  class="botontosilver" onclick="ConvertMoney('PGtoS');"/>
            </td>
		</tr>
	    <tr>
            <td width="100px" class="datalabel"  style="font-size:24px; font-weight:bolder; text-align:left; " >Plata: </td>
            <td width="60px" class="datalabel"  style="font-size:24px; font-weight:bolder; text-align:right; "><?php echo $var_PlayerSilver ?></td>
			<td width="20px" class="datalabel"  style="font-size:24px; font-weight:bolder; text-align:center; "> --></td>
            <td width="110px" >
             <input name="NewPlayerSilver" id="NewPlayerSilver" type="text" readonly="readonly" size="8" value="<?php echo $var_NewPlayerSilver ?>" 				style="color:#CCC; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
            </td>
            <td width="100px" >
            	<input  type="button" name="ply_MoreSilver"  title="Dale plata a tu personaje"
                		class="botonmas" onclick="MoneyExchange('Add','Silver');"/> &nbsp;
				<input  type="button" name="ply_LessSilver" title="Toma plata de tu personaje"
                		class="botonmenos" onclick="MoneyExchange('Sub','Silver');"/>
            </td>
            <td width="200px" >
            	<input  type="button" name="ply_SilverToGold"  class="botontogold" onclick="ConvertMoney('PStoG');"/>
            </td>
            <td width="230px" >
            	<input  type="button" name="ply_SilverToBronze"  class="botontobronze" onclick="ConvertMoney('PStoB');"/>
            </td>
		</tr>
	    <tr>
            <td width="100px" class="datalabel"  style="font-size:24px; font-weight:bolder; text-align:left; " >Bronce: </td>
            <td width="60px" class="datalabel"  style="font-size:24px; font-weight:bolder; text-align:right; "><?php echo $var_PlayerBronze ?></td>
			<td width="20px" class="datalabel"  style="font-size:24px; font-weight:bolder; text-align:center; "> --></td>
            <td width="110px" >
            <input name="NewPlayerBronze" id="NewPlayerBronze" type="text" readonly="readonly" size="8" value="<?php echo $var_NewPlayerBronze ?>" 
                    style="color:#C60; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
            </td>
            <td width="100px" >
            	<input  type="button" name="ply_MoreBronze" title="Dale bronce a tu personaje"
                  class="botonmas" onclick="MoneyExchange('Add','Bronze');"/> &nbsp;
				<input  type="button" name="ply_LessBronze"  title="Toma bronce de tu personaje"
                class="botonmenos" onclick="MoneyExchange('Sub','Bronze');"/>
            </td>
            <td width="200px" >
            	<input  type="button" name="usr_PlayerToSilver"  class="botontosilver" onclick="ConvertMoney('PBtoS');"/>
            </td>
		</tr>
	</table>
        <p style=" position:absolute; left:430px; top:730px;">
            <input  type="submit" name="Procesar"  value="" class="botonentrar"/>
        </p>    


<?php } ?>

</div>	<!--bottonpane-->
    </form> <!-- form id="User-Form"-->
</div>	<!--moneyhandel-->
