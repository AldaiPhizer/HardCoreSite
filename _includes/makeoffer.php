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
	$_GET['OfferId'] = '0';
	$var_Usuario = '';
	$var_TipoOferta = 'O'; /*Letra vocal no número cero*/
	$var_Mundos = 'All';
	$var_Jugadores = 'All';
	$var_Interes = 'B';
	$var_CollectableId = 0;
	$var_InventoryId = 0;
	$var_ServerName = '';
	$var_GameName	= '';
	$var_Retorno = '';
	
	$var_Bronce = 0;
	$var_Plata = 0;
	$var_Oro = 0;
	$var_USD = 0;
	$var_Comentarios = '';
	
	$_ct_Name = '';
	$_cl_Name = '';
	$_cl_ComName = '';
	$_cl_BaseValue = '';
	$_cl_ExtendedValue = '';
	$_cl_description = '';
	$_attributes = '';	

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

	if (isset($_GET['lst_wish'])) {
		$var_Interes = $_GET['lst_wish'];
	}

	if (isset($_GET['lst_tipo'])) {
		$var_TipoOferta = $_GET['lst_tipo'];
	}

	if (isset($_GET['lst_worlds'])) {
		$var_Mundos = $_GET['lst_worlds'];
	}
	if (isset($_GET['lst_players'])) {
		$var_Jugadores = $_GET['lst_players'];
	}	
	
	if (isset($_GET['ChoosenId'])) {
		If ($var_Interes == 'B'){
			$var_CollectableId = $_GET['ChoosenId'];
		}else{
			//mysql_select_db($database_customersconn, $customersconn);
				$query_rs_choosen ="Select ci_CollectableId 
									  From games.gm_character_inventory
									 Where ci_Id = " . $_GET['ChoosenId'] . ";";

				$rs_choosen = mysqli_query($customersconn_mysqli, $query_rs_choosen) or die(mysqli_error());
				$row_rs_choosen = mysqli_fetch_row($rs_choosen);
				
				$var_InventoryId = $_GET['ChoosenId'];
				$var_CollectableId = $row_rs_choosen['ci_CollectableId'];
		}
	}
	
	if (isset($_GET['of_bronze'])){
		if ($_GET['of_bronze'] == ''){
			$var_Bronce = 0;
		}else{
			$var_Bronce = $_GET['of_bronze'];
		}
	}
	
	if (isset($_GET['of_silver'])){
		if ($_GET['of_silver'] == ''){
			$var_Plata = 0;
		}else{
			$var_Plata = $_GET['of_silver'];
		}
	}
	
	if (isset($_GET['of_gold'])){
		if ($_GET['of_gold'] == ''){
			$var_Oro = 0;
		}else{
			$var_Oro = $_GET['of_gold'];
		}
	}
	
	if (isset($_GET['of_dollars'])){
		if ($_GET['of_dollars'] == ''){
			$var_USD = 0;
		}else{
			$var_USD = $_GET['of_dollars'];
		}
	}

	if (isset($_GET['of_remarks'])){
		$var_Comentarios = $_GET['of_remarks'];
	}
/*************************************  Create the Offer - Start ********************************/
	if (isset($_GET['of_submit'])){
		$query_rs_created = "Call games.SetOffers('C', 0, 0, '" 
												  . $var_Usuario . "', '" 
												  . $var_Interes . "', '" 
												  . $var_TipoOferta . "', " 
												  . $var_CollectableId . ", " 
												  . $var_InventoryId 
												  . ", 1, " 
												  . $var_Bronce . ", " 
												  . $var_Plata . ", " 
												  . $var_Oro . ",  " 
												  . $var_USD 
												  . ", 0, '', '" 	/*Currency for the future*/
												  . $var_Comentarios . 
							"');";
		/*echo '<script>DoAlert("' . $query_rs_created . '")</script>';*/ 	
		if ($rs_created = mysqli_query($gamesconn_mysqli, $query_rs_created))
		{
			$row_rs_created = mysqli_fetch_row($rs_created);
			$var_Retorno = $row_rs_created['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_created->close();
			$gamesconn_mysqli->next_result();
		}else{
			$var_Retorno =  $gamesconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
		}
	}
/*************************************  Create the Offer - End ********************************/
/*Inventory recordset handling - Start*/
if ($var_Usuario <> ''){
	
	$currentPage = $_SERVER["PHP_SELF"];
	$maxRows_rs_available_items = 5;
	$pageNum_rs_available_items = 0;
	if (isset($_GET['pageNum_rs_available_items'])) {
	  $pageNum_rs_available_items = $_GET['pageNum_rs_available_items'];
	}
	$startRow_rs_available_items = $pageNum_rs_available_items * $maxRows_rs_available_items;

	$query_rs_available_items =
	"Call games.GetInventory(0, 0, '" . $var_Mundos . "', '" . $var_Interes . "', '" . $var_Usuario . "', '" . $var_Jugadores . "') ";

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
							   . $var_Mundos . "', '" . $var_Interes . "', '" . $var_Usuario . "', '" . $var_Jugadores . "') ";
	
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
/*Offers answers recordset handling - End*/
/*This part is for the Games List box only*/
//mysql_select_db($database_customersconn, $customersconn);
	$query_rs_games = "select gm_Name as Etiqueta, gm_Name from games.gm_Head Order by gm_Name";
	$rs_games = mysqli_query($customersconn_mysqli, $query_rs_games) or die(mysqli_error());
	$row_rs_games = mysqli_fetch_row($rs_games);
	$totalRows_rs_games = mysqli_num_rows($rs_games);

/*This part is for the Players List box only*/
if ($var_Interes == 'S'){
	//mysql_select_db($database_customersconn, $customersconn);
	if ($var_Mundos == 'All'){
		$query_rs_players ="Select ch_Name 
							  From games.gm_characters Join customers.cu_head 
								On ch_CustomerNumber = cu_Number
							 Where cu_Aka = '" . $var_Usuario . "'
							 Order By ch_Name";
	}else{
		$query_rs_players ="Select ch_Name 
							  From games.gm_characters Join customers.cu_head 
								On ch_CustomerNumber = cu_Number
							 Where cu_Aka = '" . $var_Usuario . "'
								And ch_GameName = '" . $var_Mundos . "'
								Order By ch_Name";
	}
	$rs_players = mysqli_query($customersconn_mysqli, $query_rs_players) or die(mysqli_error());
	$row_rs_players = mysqli_fetch_row($rs_players);
	$totalRows_rs_players = mysqli_num_rows($rs_players);
}

?>
<a name="filtro"></a>
<a href="market.php" style="position:absolute">
	<img  src="images/buttons/img_Volver.png"/>
</a>
<div id="header" class="pagetitle">
Crear Oferta a Nombre de: <?php echo  $var_Usuario	?> 
</div>

<div id="upperpane" style="	height: 380px;
                            width: 1092px;
                            margin-top: 2px;
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
<div style="padding-left:10px;">
	 <input name="OfferId" type="hidden" value="0" />
     <br />
     <label class="datalabel" style="color:#FC0">Deseo &nbsp;	&nbsp;
     	<select name="lst_wish" size="1" class="data" 
        onchange="form.action = window.location;this.form.submit();"> <!--"location.href='market.php?OfferId=0&lst_worlds=<?php /*echo $var_Mundos?>&lst_tipo=<?php echo $var_TipoOferta?>&lst_players=<?php echo $var_Jugadores*/?>&lst_wish='+this.value+'#filtro'"-->
     	  <option class="drop" value="B" <?php if ($var_Interes == 'B'){ ?> selected="selected" <?php } ?>>Comprar</option>
     	  <option class="drop" value="S" <?php if ($var_Interes == 'S'){ ?> selected="selected" <?php } ?>>Vender</option>
     	</select>
     </label>&nbsp;	&nbsp;	&nbsp; 	&nbsp; 	&nbsp;	&nbsp;
     <label class="datalabel" style="color:#FC0">Tipo de Oferta
     	<select name="lst_tipo" size="1" class="data" 
        onchange="form.action = window.location;this.form.submit();">  <!--"location.href='market.php?OfferId=0&lst_worlds=<?php /*echo $var_Mundos?>&lst_players=<?php echo $var_Jugadores?>&lst_wish=<?php echo $var_Interes*/?>&lst_tipo='+this.value+'#filtro'"-->
     	  <option class="drop" value="O" <?php if ($var_TipoOferta == 'O'){ ?> selected="selected" <?php } ?>>Abierta</option>
     	  <option class="drop" value="C" <?php if ($var_TipoOferta == 'C'){ ?> selected="selected" <?php } ?>>Cerrada</option>
     	</select>
     </label>
     <label style=" padding-left:30px; width:500px; height:20px; position:absolute; top:60px; color:#FF0; font-family:Arial, Helvetica, sans-serif; font-size:12px;">Las ofertas abiertas pueden recibir contraofertas, para que selecciones la que más te convenga.  Las ofertas cerradas reaccionan automáticamente a la primera respuesta que se ajuste al pedido.</label>
     </br>
    <label class="datalabel" style="color:#FC0">Mundos
        <select name="lst_worlds" size="1" class="data" 
        onchange="form.action = window.location;this.form.submit();"> <!--"location.href='market.php?OfferId=0&lst_tipo=<?php/* echo $var_TipoOferta?>&lst_players=<?php/* echo $var_Jugadores?>&lst_wish=<?php/* echo $var_Interes?>&lst_worlds='+this.value+'#filtro'"-->
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
     </label>&nbsp;	&nbsp;	&nbsp; 	&nbsp; 	&nbsp; 	
     <?php if ($var_Interes == 'S') { ?>
    <label class="datalabel" style="color: #FC0;">Jugadores &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <select name="lst_players" size="1" class="data" 
        onchange="form.action = window.location;this.form.submit();"> <!--"location.href='market.php?OfferId=0&lst_tipo=<?php/* echo $var_TipoOferta?>&lst_worlds=<?php/* echo $var_Mundos?>&lst_wish=<?php/* echo $var_Interes?>&lst_players='+this.value+'#filtro'"-->
        <option class="drop" value="All" <?php if ($var_Jugadores == 'All'){ ?> selected="selected" <?php } ?>>Todos</option>
        <option class="drop" value="Mis Objetos" <?php if ($var_Jugadores == 'Mis Objetos'){ ?> selected="selected" <?php } ?>>Sin Asignar</option>
            <?php
            do {  
            ?>
            <option class="drop" value="<?php echo $row_rs_players[0]?>" 
            <?php if ($var_Jugadores == $row_rs_players[0]){ ?> selected="selected" <?php } ?>><?php echo $row_rs_players[0]?>
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
        <?php if ($var_Interes == 'B') { ?>
	        <th>Mundo</th>
        <?php }else{	?>
        	<th>Servidor</th>    
        <?php }	?>
        <th>Cantidad</th>
      </tr>
      <?php do { ?>
        <tr class="data">
      			<td style="width:60px; font-weight:bold; text-align:center; "> 
                    <a style="font-family: Verdana, Geneva, sans-serif; font-style:normal; color: #CCC;" 
                       href="market.php?OfferId=0&lst_wish=<?php echo $var_Interes 
					   						   ?>&lst_tipo=<?php echo $var_TipoOferta 
											   ?>&lst_worlds=<?php echo $var_Mundos 
											   ?>&lst_players=<?php echo $var_Jugadores 
											   ?>&ChoosenId=<?php echo $row_rs_available_items[0] 
											   ?>#detalle">
                            <?php echo $row_rs_available_items[0]; ?> 
                     </a>
                 </td>
          <td style="width:200px; font-weight:bold"><?php echo $row_rs_available_items[4]; ?></td>
          <td style="width:500px; font-weight:bold"><?php echo $row_rs_available_items[5]; ?></td>
          <td style="width:100px; font-weight:bold"><?php echo $row_rs_available_items[2]; ?></td>
          <td style="width:100px; font-weight:bold"><?php echo $row_rs_available_items[3]; ?></td>
          <td style="width:50px; font-weight:bold"><?php echo $row_rs_available_items[1]; ?></td>
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
<?php if ($var_CollectableId != 0) { 
/* The one Thing handling - Start*/

	$query_rs_collectable = "Call games.GetItems('', '', '', " . $var_CollectableId . ")";
	
	if ($rs_collectable = mysqli_query($gamesconn_mysqli, $query_rs_collectable))
	{
		$row_rs_collectable = mysqli_fetch_row($rs_collectable);
		$_ct_Name = $row_rs_collectable[2];
		$_cl_Name = $row_rs_collectable[1];
		$_cl_ComName = $row_rs_collectable[0];
		$_cl_BaseValue = $row_rs_collectable[6];
		$_cl_ExtendedValue = $row_rs_collectable[7];
		$_cl_description = $row_rs_collectable[10];
		$_attributes = $row_rs_collectable[11];
		$_cl_GameName = $row_rs_collectable[12];
		
		$rs_collectable->close();
		$gamesconn_mysqli->next_result();
	}
/*The one Thing handling - End*/
?>
<a name="detalle"></a>
    <h1 style="color: #900; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; 
    					font-size: 28px; text-align:center; width:500px; padding-left:20px; "><?php echo $_cl_ComName?></h1>
    <img style="padding-left:20px; position:absolute;" src="images/collectables/<?php echo $_cl_GameName . '/' .
                                                                         $_ct_Name . '/' .
                                                                         $_cl_Name; ?>.jpg"  width="200" height="200"  />
    <p style=" width:300px; height:100px; left:240px; position:absolute; 
    				color: #900; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif font-size: 16px; text-align:justify; ">
    <?php if ($_cl_description != ''){ 
            echo $_cl_description . '. '; ?>
			<br>
    <?php  echo str_replace('^', '; ', str_replace('@', ': ', $_attributes));
        }
    ?>
    </p>
    <p style="text-align:center;  width:400px; height:80px; padding-top:180px; padding-left:30px;  position:absolute; 
    		color:#F90; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; 
        				font-size: 24px; text-align:left;">
        <em >Precio regular: <?php echo $_cl_ExtendedValue?> Bronce</em> , <?php echo $_cl_BaseValue?> Oro</em>
    </p>
   
    <form id="OfferForm" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
    	<input name="OfferId" type="hidden" value="0" />
    	<input name="lst_tipo" type="hidden" value="<?php echo $var_TipoOferta ?>" />
        <input name="lst_wish" type="hidden" value="<?php echo $var_Interes ?>" />
    	<input name="ChoosenId" type="hidden" value="<?php if ($var_Interes == 'S'){echo $var_InventoryId;}else{echo $var_CollectableId;} ?>" />
        <table border="0" style=" position:absolute; left:600px; top:450px; table-layout:fixed; overflow:hidden" >
            <tr class="tableheaders">
                <th style="font-size: 28px; text-align:left"><?php if($var_Interes == 'B'){echo 'Ofrezco: ';}else{echo 'Pido: ';}?></th>
            </tr>
            <tr>
                <td width="30px;" class="datalabel">Bronce</td>
                <td>
                    <input name="of_bronze" type="text" size="6" 
                        style="color:#C60; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
                </td>
            </tr>
            <tr>
                <td width="30px;" class="datalabel">Plata</td>
                <td>
                    <input name="of_silver" type="text" size="6" 
                        style="color:#999; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
                </td>
            </tr>
            <tr>
                <td width="30px;" class="datalabel">Oro</td>
                <td>
                    <input name="of_gold" type="text" size="6" 
                        style="color:#FF0; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
                </td>
            </tr>
            <tr>
                <td width="30px;" class="datalabel">USD</td>
                <td>
                    <input name="of_dollars" type="text" size="6" 
                        style="color:#060; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
                </td>
            </tr>
        </table>
	    <p style=" position:absolute; left:815px; top:449px;" >
	    	<label   class="datalabel" >Comentario</label>
            <textarea name="of_remarks" cols="38" rows="6" class="data"  
                    style="resize:none; font-size: 12px;"></textarea>

    	</p>
        <p style=" position:absolute; left:700px; top:650px;">
            <input  type="submit" name="of_submit"  value="" class="botonentrar"/>
        </p>    
    </form>

<script  type="text/javascript">
var frmvalidator = new Validator("OfferForm");
frmvalidator.addValidation("of_bronze","integer", "Bronce es un valor entero");
frmvalidator.addValidation("of_silver","integer", "Plata es un valor entero");
frmvalidator.addValidation("of_gold","integer", "Oro es un valor entero");
frmvalidator.addValidation("of_dollars","decimal", "Los dólares vienen en números");

 
 </script>

<?php } /*This one ends de if ($var_CollectableId != 0)*/	?> 
</div>
