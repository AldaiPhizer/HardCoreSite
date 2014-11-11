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
	$var_Mundos = 'All';
	
	$var_CadenaJugadorOrig = 'All';
	$var_JugadorOrigen = 'All';
	$var_ServerName = '';
	
	$var_InventoryId = 0;
	$var_MundoItem = 'All';
	$var_CommercialName	= '';
	$var_CollectableName = '';
	$var_Categoria = '';
	
	$var_CadenaJugadorDest = '';
	$var_ServidorDestino = '';
	$var_JugadorDestino = '';

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
											  
	if (isset($_GET['lst_worlds'])) {
		$var_Mundos = $_GET['lst_worlds'];
	}
	if (isset($_GET['lst_origplayers'])) {
		$var_CadenaJugadorOrig = $_GET['lst_origplayers'];
		
		if ($var_CadenaJugadorOrig != 'All' && $var_CadenaJugadorOrig != 'Mis Objetos'){
			$pos_char = strpos($var_CadenaJugadorOrig, '-->');
			$var_JugadorOrigen = substr($var_CadenaJugadorOrig, $pos_char + 3);
			$var_ServerName = substr($var_CadenaJugadorOrig, 0, $pos_char);
		}else{
			$var_JugadorOrigen = $var_CadenaJugadorOrig;
			$var_ServerName = '';
		}
	}	
	if (isset($_GET['lst_destplayers'])) {
		$var_CadenaJugadorDest = $_GET['lst_destplayers'];
		if ($var_CadenaJugadorDest != 'All' && $var_CadenaJugadorDest != 'Mis Objetos'){
			$pos_char = strpos($var_CadenaJugadorDest, '-->');
			$var_JugadorDestino = substr($var_CadenaJugadorDest, $pos_char + 3);
			$var_ServidorDestino = substr($var_CadenaJugadorDest, 0, $pos_char);
		}else{
			$var_JugadorDestino = $var_CadenaJugadorDest;
			$var_ServidorDestino = '';
		}
	}

/*This part is for the Games List box only*/
//mysql_select_db($database_customersconn, $customersconn);
$query_rs_games = "select gm_Name as Etiqueta, gm_Name from games.gm_Head Order by gm_Name";
$rs_games = mysqli_query($customersconn_mysqli, $query_rs_games) or die(mysqli_error());
$row_rs_games = mysqli_fetch_row($rs_games);
$totalRows_rs_games = mysqli_num_rows($rs_games);

/*This part is for the Players List box only********************************************/

//mysql_select_db($database_customersconn, $customersconn);
if ($var_Mundos == 'All'){
	$query_rs_players ="Select Concat(ch_ServerName, '-->', ch_Name) As Player, ch_Name 
						  From games.gm_characters Join customers.cu_head 
							On ch_CustomerNumber = cu_Number
						 Where cu_Aka = '" . $var_Usuario . "'
						 Order By ch_Name";
}else{
	$query_rs_players ="Select Concat(ch_ServerName, '-->', ch_Name) As Player, ch_Name 
						  From games.gm_characters Join customers.cu_head 
							On ch_CustomerNumber = cu_Number
						 Where cu_Aka = '" . $var_Usuario . "'
							And ch_GameName = '" . $var_Mundos . "'
							Order By ch_Name";
}
$rs_players = mysqli_query($customersconn_mysqli, $query_rs_players) or die(mysqli_error());
$row_rs_players = mysqli_fetch_row($rs_players);
$totalRows_rs_players = mysqli_num_rows($rs_players);

/************************************* Get Selected Item Data *****************************************/
	if (isset($_GET['ChoosenId'])) {
		if ($_GET['ChoosenId'] != 0){
			//mysql_select_db($database_customersconn, $customersconn);
			$query_rs_choosen ="select  ct_Name,
										 cl_Name,
										 cl_ComName,
										 cl_GameName,
										 Case When IfNull(ci_CharacterName, '') = '' Then 'Sin Asignar'
												Else ci_CharacterName End	As CharacterName
								  from 	games.gm_character_inventory, 
										games.gm_collectables, 
										games.gm_collectable_categories
								 Where ci_Id = " . $_GET['ChoosenId'] . "
								  And cl_Item = ci_CollectableId
								  And ct_Number = cl_Category;";

			$rs_choosen = mysqli_query($customersconn_mysqli, $query_rs_choosen) or die(mysqli_error());
			if ($row_rs_choosen = mysqli_fetch_row($rs_choosen)){
				$var_InventoryId = $_GET['ChoosenId'];
				$var_CommercialName = $row_rs_choosen[2];
				$var_MundoItem = $row_rs_choosen[3];
				$var_CollectableName = $row_rs_choosen[1];
				$var_Categoria = $row_rs_choosen[0];
				$var_JugadorOrigen = $row_rs_choosen[4];
				mysqli_free_result($rs_choosen);
			}
		}
	}

/*************************************  Re Assign the thing - Start ********************************/
	if (isset($_GET['of_submit'])){
		if ($var_JugadorDestino == 'Mis Objetos'){
			$var_SetPlayer = '';
		}else{
			$var_SetPlayer = $var_JugadorDestino;
		}
		$query_rs_reassigned = "Call games.SetInventory('" 
												  . $var_ServidorDestino . "', " 
												  . $var_InventoryId . ", '"
												  . $var_Usuario . "', '" 
												  . $var_SetPlayer . "');";
		/*echo '<script>DoAlert("' . $query_rs_reassigned . '")</script>';*/ 	
		if ($rs_reassigned = mysqli_query($gamesconn_mysqli, $query_rs_reassigned))
		{
			$row_rs_reassigned = mysqli_fetch_row($rs_reassigned);
			$var_Retorno = $row_rs_reassigned['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_reassigned->close();
			$gamesconn_mysqli->next_result();
			$var_InventoryId = 0;
			echo '<META http-equiv="Refresh" Content="0; account.php?intercambia"';
		}else{
			$var_Retorno =  $gamesconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>';
			echo '<META http-equiv="Refresh" Content="0; account.php"';
		}
	}
/*************************************  Re Assign the thing - End ********************************/

/*Inventory recordset handling - Start ***********************************************************/
if ($var_Usuario <> ''){
	
	$currentPage = $_SERVER["PHP_SELF"];
	$maxRows_rs_available_items = 5;
	$pageNum_rs_available_items = 0;
	if (isset($_GET['pageNum_rs_available_items'])) {
	  $pageNum_rs_available_items = $_GET['pageNum_rs_available_items'];
	}
	$startRow_rs_available_items = $pageNum_rs_available_items * $maxRows_rs_available_items;

	if ($var_JugadorOrigen == 'Sin Asignar'){
		$var_SearchPlayer = 'Mis Objetos';
	}else{
		$var_SearchPlayer = $var_JugadorOrigen;
	}
	$query_rs_available_items =
	"Call games.GetInventory(0, 0, '" . $var_Mundos . "', 'S', '" . $var_Usuario . "', '" . $var_SearchPlayer . "') ";

	if ($rs_available_items = mysqli_query($gamesconn_mysqli, $query_rs_available_items))
	{
		if (isset($_GET['totalRows_rs_available_items'])) {
		  $totalRows_rs_available_items = $_GET['totalRows_rs_available_items'];
		} else {
		
		  $totalRows_rs_available_items = $rs_available_items->num_rows; 
		}
	}else{
		print $gamesconn_mysqli->error."<br />";
	}
	
	$rs_available_items->close();
	$gamesconn_mysqli->next_result();

	$query_rs_available_items =
	"Call games.GetInventory(" . $startRow_rs_available_items . ", "
							   . $maxRows_rs_available_items . ", '" 
							   . $var_Mundos . "', 'S', '" . $var_Usuario . "', '" . $var_SearchPlayer . "') ";
	
	if ($rs_available_items = mysqli_query($gamesconn_mysqli, $query_rs_available_items))
	{
		$row_rs_available_items = mysqli_fetch_row($rs_available_items);
		
	}else{
		print $gamesconn_mysqli->error."<br />";
	}
	
	$totalPages_rs_available_items = ceil($totalRows_rs_available_items/$maxRows_rs_available_items)-1;	
	$queryString_rs_available_items = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
	  $params = explode("&", $_SERVER['QUERY_STRING']);
	  $newParams = array();
	  foreach ($params as $param) {
		if (stristr($param, "pageNum_rs_available_items") == false && 
			stristr($param, "totalRows_rs_available_items") == false) {
		  array_push($newParams, $param);
		}
	  }
	  if (count($newParams) != 0) {
		$queryString_rs_available_items = "&" . htmlentities(implode("&", $newParams));
	  }
	}
	$queryString_rs_available_items = sprintf("&totalRows_rs_available_items=%d%s", $totalRows_rs_available_items, $queryString_rs_available_items);
	/*echo $queryString_rs_available_items;*/
}

/*Inventory recordset handling - End ************************************************************/




/*This part is for the Destination Player List box only*******************************************/

//mysql_select_db($database_customersconn, $customersconn);
if ($var_MundoItem == 'All'){
	$query_rs_destplayers ="Select Concat(ch_ServerName, '-->', ch_Name) As Player, ch_Name  
						  From games.gm_characters Join customers.cu_head 
							On ch_CustomerNumber = cu_Number
						 Where cu_Aka = '" . $var_Usuario . "'
						   And ch_Name <> '" . $var_JugadorOrigen . "' 
						   And ch_ServerName <> '" . $var_ServerName . "'
						 Order By ch_Name";
}else{
	$query_rs_destplayers ="Select Concat(ch_ServerName, '-->', ch_Name) As Player, ch_Name 
						  From games.gm_characters Join customers.cu_head 
							On ch_CustomerNumber = cu_Number
						 Where cu_Aka = '" . $var_Usuario . "'
						   And ch_GameName = '" . $var_MundoItem . "'
						   And ch_Name <> '" . $var_JugadorOrigen . "' 
						   And ch_ServerName <> '" . $var_ServerName . "'
						 Order By ch_Name";
}
$rs_destplayers = mysqli_query($customersconn_mysqli, $query_rs_destplayers) or die(mysqli_error());
$row_rs_destplayers = mysqli_fetch_row($rs_destplayers);
$totalRows_rs_destplayers = mysqli_num_rows($rs_destplayers);

?>
<a name="filtro"></a>
<div id="exchnghandel" style="background:url(images/backgrounds/WhiteStorm_big.png); width:1120px; height:825px" >
<a href="account.php" style="position:absolute">
	<img src="images/buttons/img_Volver.png"/>
</a>
<div id="header" class="pagetitle">
Administra tu Inventario <?php echo  $var_Usuario	?> 
</div>

<div id="upperpane" style="	height: 380px;
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
<form id="filter-form" name="filter-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="get">
<div>
	 <input name="intercambia" type="hidden" value="" />
     <br />
     <label style=" width:500px; height:60px; position:absolute; color:#900; margin-left:550px; 
     					font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif">Los objetos en uso o asignados a alguna oferta no aparecerán en la lista. Debes dejar de utilizarlos o retirar la oferta a la que están asociados antes de reasignarlos.</label>
     </br></br>
    <label class="datalabel" >Mundos
        <select name="lst_worlds" size="1" class="data" 
          onchange="form.action = window.location;this.form.submit();">  <!--"location.href='account.php?intercambia&lst_worlds='+this.value+'#filtro'"-->
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
     </label>&nbsp;	&nbsp;	

    <label class="datalabel">Jugadores 	&nbsp;	
        <select name="lst_origplayers" size="1" class="data" 
        onchange="form.action = window.location;this.form.submit();">  <!--"location.href='account.php?intercambia&lst_origplayers='+this.value+'#filtro'"-->
          <option class="drop" value="All" <?php if ($var_JugadorOrigen == 'All'){ ?> selected="selected" <?php } ?>>Todos</option>
          <option class="drop" value="Mis Objetos" <?php if ($var_JugadorOrigen == 'Mis Objetos'){ ?> selected="selected" <?php } ?>>Sin Asignar</option>
            <?php
            do {  
            ?>
            <option class="drop" value="<?php echo $row_rs_players[0]?>" 
            <?php if ($var_CadenaJugadorOrig == $row_rs_players[0])
					{ ?> selected="selected" <?php } ?>><?php echo $row_rs_players[1]?>
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

     </br></br>
</div> 
    
	<?php 
        if ($pageNum_rs_available_items > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rs_available_items=%d%s", $currentPage, 0, $queryString_rs_available_items); ?>">
                    <img src="images/buttons/first.png" alt="Primera" width="32" height="32" border="0" >
        </a> 
        <a href="<?php printf("%s?pageNum_rs_available_items=%d%s", $currentPage, max(0, $pageNum_rs_available_items - 1),
																					$queryString_rs_available_items); ?>">
                <img src="images/buttons/previous.png" alt="Anterior" width="32" height="32" border="0" >
        </a>
    <?php } // Show if not first page ?>
      
    <?php if ($pageNum_rs_available_items < $totalPages_rs_available_items) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rs_available_items=%d%s", $currentPage, min($totalPages_rs_available_items, $pageNum_rs_available_items + 1), $queryString_rs_available_items); ?>">
                <img src="images/buttons/next.png" alt="Siguiente" width="32" height="32" border="0" >
      </a>
      <a href="<?php printf("%s?pageNum_rs_available_items=%d%s", $currentPage, $totalPages_rs_available_items, $queryString_rs_available_items); ?>">
                <img src="images/buttons/last.png" alt="Última" width="32" height="32" border="0" >
      </a>
    <?php }// Show if not last page ?>
    <table border="0" style="margin-left:auto; margin-right:auto; table-layout:fixed; overflow:hidden" >
      <tr class="tableheaders">
        <th>Id</th>
        <th>Categoría</th>
        <th>Objeto</th>
        <th>Personaje</th>
       	<th>Servidor</th>    
        <th>Cantidad</th>
        <th style="display:none;">Nombre</th>
      </tr>
      <?php do { ?>
        <tr class="data">
      			<td style="width:60px; font-weight:bold; text-align:center; "> 
                    <a style="font-family: Verdana, Geneva, sans-serif; font-style:normal; color: #CCC;" 
                       href="account.php?intercambia=''&lst_worlds=<?php echo $var_Mundos 
					   							?>&ChoosenId=<?php echo $row_rs_available_items[0] 
											   	?>&CollectableName=<?php echo $row_rs_available_items[6] 
                                               	?>#detalle">
                            <?php echo $row_rs_available_items[0]; ?> 
                     </a>
                 </td>
          <td style="width:200px; font-weight:bold"><?php echo $row_rs_available_items[4]; ?></td>
          <td style="width:450px; font-weight:bold"><?php echo $row_rs_available_items[5]; ?></td>
          <td style="width:200px; font-weight:bold"><?php echo $row_rs_available_items[2]; ?></td>
          <td style="width:100px; font-weight:bold"><?php echo $row_rs_available_items[3]; ?></td>
          <td style="width:50px; font-weight:bold; text-align:center; "><?php echo $row_rs_available_items[1]; ?></td>
          <td style="display:none;"><?php echo $row_rs_available_items[6]; ?></td>
        </tr>
        <?php } while ($row_rs_available_items = mysqli_fetch_row($rs_available_items)); ?>
    </table>
</form>
<?php 

	$rs_available_items->close();
	$gamesconn_mysqli->next_result();
?>
</div>

<div id="bottonpane" style="height: 370px;
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
<?php if ($var_InventoryId != 0) { 

?>
<a name="detalle"></a>
    <h1 style="color: #900; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 28px; 
    text-align:center; width:500px; ">Inventario: <?php echo $var_InventoryId ?> </h1>
    <img style="padding-left:130px; position:absolute;" src="images/collectables/<?php echo $var_MundoItem . '/' .
                                                                         $var_Categoria . '/' .
                                                                         $var_CollectableName; ?>.jpg"  width="200" height="200" alt="<?php echo $var_Mundos . ', ' . $var_Categoria . ', ' . $var_CollectableName ?>"  />

    <p style="text-align:center;  width:400px; height:80px; padding-top:180px; padding-left:30px;  position:absolute; ">
        <b style="color:#FC0; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 20px; text-align:left;" >
                                                                                                    <?php echo $var_CommercialName?> </b>
		<br />
        <b style="color:#FC0; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 20px; text-align:left;" >
                                                                                                    <?php echo $var_Categoria?> </b>
    </p>
   
    <form id="ExchangeForm" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
    	<input name="intercambia" type="hidden" value="" />
    	<input name="ChoosenId" type="hidden" value="<?php echo $var_InventoryId; ?>" />
        <input name="lst_worlds" type="hidden" value="<?php echo $var_Mundos; ?>" />
        <input name="lst_origplayers" type="hidden" value="<?php echo $var_CadenaJugadorOrig; ?>" />

	    <p style=" position:absolute; left:615px; top:449px;" >
    	<label class="datalabel">Asignarlo a: 	&nbsp;	&nbsp;	&nbsp;
            <select name="lst_destplayers" size="1" class="data" 
			  onchange="form.action = window.location;this.form.submit();"> <!--"location.href='account.php?intercambia&ChoosenId=<?php /*echo $var_InventoryId?>&lst_worlds=<?php echo $var_Mundos?>&lst_origplayers=<?php echo $var_CadenaJugadorOrig; */?>&lst_destplayers='+this.value+'#detalle'"-->
              <option class="drop" 
              		value="Mis Objetos" <?php if ($var_JugadorDestino == 'Mis Objetos'){ ?> selected="selected" <?php } ?>>Sin Asignar</option>
                <?php
                do {  
                ?>
                <option class="drop" value="<?php echo $row_rs_destplayers[0]?>" 
                <?php if ($var_CadenaJugadorDest == $row_rs_destplayers[0]){ ?> 
                			selected="selected" <?php } ?> > <?php echo $row_rs_destplayers[1]?>
                </option>
                <?php
                } while ($row_rs_destplayers = mysqli_fetch_row($rs_destplayers));
                  $rows = mysqli_num_rows($rs_destplayers);
                  if($rows > 0) {
                      mysqli_data_seek($rs_destplayers, 0);
                      $row_rs_destplayers = mysqli_fetch_row($rs_destplayers);
                  }
                ?>
            </select>
     	</label>
    	</p>
        <p style=" position:absolute; left:700px; top:650px;">
            <input  type="submit" name="of_submit"  value="" class="botonentrar"/>
        </p>    
    </form>

<?php } ?>
</div>
</div>
