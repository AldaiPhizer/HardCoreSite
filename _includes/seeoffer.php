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

	$var_OfferId = 0;
	$var_ResponseId = 0;
	$var_Usuario = '';
	$muestra_detalle = false;
	$var_Remove = false;
	$var_Answer = false;

	$var_Interes = '';
	$var_TipoOferta = '';
	$var_CollectableId = 0;
	$var_InventoryId = 0;
	$var_Bronce = 0;
	$var_Plata = 0;
	$var_Oro = 0;
	$var_USD = 0;
	$var_Comentarios = '';
	
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
	
	if (isset($_GET['OfferId'])){
		$var_OfferId = $_GET['OfferId'];
	}

	if (isset($_GET['ResponseId'])){
		if ($_GET['ResponseId'] == ''){
			$var_ResponseId = 0;
		}else{
			$var_ResponseId = $_GET['ResponseId'];
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

/****************************  Response to Offer Handling - Start ***********************************/
	if (isset($_GET['of_answer']))
	{
		$query_rs_answer = "Call games.SetOffers('P', " 
												  . $var_OfferId . ", "
												  . $var_ResponseId . ", '" 
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
		/*echo '<script>DoAlert("' . $query_rs_answer . '")</script>';*/ 
		if ($rs_answer = mysqli_query($gamesconn_mysqli, $query_rs_answer))
		{
			$row_rs_answer = mysqli_fetch_row($rs_answer);
			$var_Retorno = $row_rs_answer['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_answer->close();
			$gamesconn_mysqli->next_result();
			echo '<META http-equiv="Refresh" Content="0; market.php"';
		}else{
			$var_Retorno =  $gamesconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
		}
	}
/****************************  Response to Offer Handling - End **************************/
/****************************  Remove Offer Handling - Start *******************************/
	if (isset($_GET['of_remove'])){
		if ($_GET['of_remove'] == ''){
			$var_OfferRemove = $var_OfferId;
		}else{
			$var_OfferRemove = $_GET['of_remove'];
		}
		$query_rs_remove = "Call games.SetOffers('R', " 
												  . $var_OfferRemove . ", "
												  . $var_ResponseId . ", '" 
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
/*		echo '<script>DoAlert("' . $query_rs_created . '")</script>'; 	*/
		if ($rs_remove = mysqli_query($gamesconn_mysqli, $query_rs_remove))
		{
			$row_rs_remove = mysqli_fetch_row($rs_remove);
			$var_Retorno = $row_rs_remove['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_remove->close();
			$gamesconn_mysqli->next_result();
			echo '<META http-equiv="Refresh" Content="0; market.php"';
			
		}else{
			$var_Retorno =  $gamesconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
		}
	}	
/*************************  Remove Offer Handling - Start **********************************/

/************************** Response Acceptance Handling - Start ****************************/
	if ($var_ResponseId != 0){
		$query_rs_response = "Call games.SetOffers('P', " 
												  . $var_OfferId . ", "
												  . $var_ResponseId . ", '" /* Selected answer */
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
/*		echo '<script>DoAlert("' . $query_rs_created . '")</script>'; 	*/
		if ($rs_response = mysqli_query($gamesconn_mysqli, $query_rs_response))
		{
			$row_rs_response = mysqli_fetch_row($rs_response);
			$var_Retorno = $row_rs_response['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_response->close();
			$gamesconn_mysqli->next_result();
			echo '<META http-equiv="Refresh" Content="0; market.php"';
			
		}else{
			$var_Retorno =  $gamesconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
		}
	}
/************************** Response Acceptance Handling - End ****************************/
/* The one offer handling - Start*/
$query_rs_offer = "Call games.GetOffers(0, 0, 'HardCore', 'A', 'U', 'User', " . $var_OfferId . ")";

	if ($rs_offer = mysqli_query($gamesconn_mysqli, $query_rs_offer))
	{
		$row_rs_offer = mysqli_fetch_row($rs_offer);
	}
	$_offer_id = $row_rs_offer[0];
	$_cl_Id = $row_rs_offer[1];
	$_cl_GameName = $row_rs_offer[2];
	$_of_BronzePrice = $row_rs_offer[3];
	$_of_SilverPrice = $row_rs_offer[4];
	$_of_GoldPrice = $row_rs_offer[5];
	$_of_DollarPrice = $row_rs_offer[6];
	$_of_CurrencyPrice = $row_rs_offer[7];
	$_of_OtherCurrency = $row_rs_offer[8];
	$_of_Remarks = $row_rs_offer[9];
	$_ct_Name = $row_rs_offer[10];
	$_cl_Name = $row_rs_offer[11];
	$_cl_ComName = $row_rs_offer[12];
	$_cl_BaseValue = $row_rs_offer[13];
	$_cl_ExtendedValue = $row_rs_offer[14];
	$_cl_description = $row_rs_offer[15];
	$_attributes = $row_rs_offer[16];
	$_of_Amount = $row_rs_offer[17];
	$_of_way = $row_rs_offer[18];
	$_of_Aka = $row_rs_offer[19];
	if ($row_rs_offer[20] == 'O'){
		$_of_Type = 'Abierta';
	}else{
		$_of_Type = 'Cerrada';
	}
	
	$rs_offer->close();
	$gamesconn_mysqli->next_result();

/*Te one offer handling - End*/
/*Offers answers recordset handling - Start*/
if ($var_Usuario <> ''){
	
	$currentPage = $_SERVER["PHP_SELF"];
	$maxRows_rs_offer_answers = 10;
	$pageNum_rs_offer_answers = 0;
	if (isset($_GET['pageNum_rs_offer_answers'])) {
	  $pageNum_rs_offer_answers = $_GET['pageNum_rs_offer_answers'];
	}
	$startRow_rs_offer_answers = $pageNum_rs_offer_answers * $maxRows_rs_offer_answers;
	
	$query_rs_offer_answers = 
		"Call games.GetOffers(0, 0, 'All', 'A', 'R', '" . $var_Usuario . "', " . $_offer_id .");"; /**/

	if ($rs_offer_answers = mysqli_query($gamesconn_mysqli, $query_rs_offer_answers))
	{
		if (isset($_GET['totalRows_rs_offer_answers'])) {
		  $totalRows_rs_offer_answers = $_GET['totalRows_rs_offer_answers'];
		} else {
		
		  $totalRows_rs_offer_answers = $rs_offer_answers->num_rows; 
		}
	}

	$totalPages_rs_offer_answers = ceil($totalRows_rs_offer_answers/$maxRows_rs_offer_answers)-1;
	
	$rs_offer_answers->close();
	$gamesconn_mysqli->next_result();

	$query_rs_offer_answers = 
		"Call games.GetOffers(". $startRow_rs_offer_answers . ", " 
							   . $maxRows_rs_offer_answers  . ", 'All', 'A', 'R', '" . $var_Usuario . "', " . $_offer_id .");"; 
	if ($rs_offer_answers = mysqli_query($gamesconn_mysqli, $query_rs_offer_answers))
	{
		$row_rs_offer_answers = mysqli_fetch_row($rs_offer_answers);
		
	}else{
		print $gamesconn_mysqli->error."<br />";
	}
	

	
	$queryString_rs_offer_answers = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
	  $params = explode("&", $_SERVER['QUERY_STRING']);
	  $newParams = array();
	  foreach ($params as $param) {
		if (stristr($param, "pageNum_rs_offer_answers") == false && 
			stristr($param, "totalRows_rs_offer_answers") == false) {
		  array_push($newParams, $param);
		}
	  }
	  if (count($newParams) != 0) {
		$queryString_rs_offer_answers = "&" . htmlentities(implode("&", $newParams));
	  }
	}
	$queryString_rs_offer_answers = sprintf("&totalRows_rs_offer_answers=%d%s", $totalRows_rs_offer_answers, $queryString_rs_offer_answers);
}
/*Offers answers recordset handling - End*/
?>
<a name="filtro"></a>
<a href="market.php" style="position:absolute">
	<img src="images/buttons/img_Volver.png"/>
</a>
<div id="header" class="pagetitle">
Oferta <?php echo ' ' . $_of_Type . ' ' ?>
Número <?php echo $_offer_id . ' de: ' . $_of_Aka	?> 
</div>

<div id="leftpane" style="	height: 460px;
                            width: 545px;
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
<h1 style=" font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; color:#600;
						font-size: 28px; text-align:center; "><?php echo $_cl_ComName?></h1>
<img style="padding-left:190px;" src="images/collectables/<?php echo $_cl_GameName . '/' .
																     $_ct_Name . '/' .
																     $_cl_Name; ?>.jpg"  width="200" height="200" alt="ItemPic" />
<h2 class="datalabel" style="font-size: 20px; text-align:center; ">
<?php echo $_cl_description . '. ' . str_replace('^', '; ', str_replace('@', ': ', $_attributes)) ?>
</h2>
<p style="text-align:center;">
    <em class="datalabel"  style="font-size: 20px; text-align:left;" >Precio regular:</em>
    <em style="color:#F30; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >
																	<?php echo $_cl_ExtendedValue?> Bronce</em>
    <em style="color: #036; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >, </em>
    <em style="color:#960; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >
																	<?php echo $_cl_BaseValue?> Oro</em>
</p>
<h3 class="datalabel" style=" font-size: 22px; text-align:center;" >Cantidad: 
																	<?php echo $_of_Amount?></h3>
</div>

<div id="rightpane" style="	
                            position:absolute;
                            left: 560px;
                            top: 49px;
                            height: 460px;
                            width: 545px;
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

<form id="OfferForm" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
<br/>
<input name="OfferId" type="hidden" value="<?php echo $var_OfferId ?>" />
  <table border="0" style="margin-left:auto; margin-right:auto; table-layout:fixed; overflow:hidden" >
    <tr class="tableheaders">
        <th style="font-size: 28px;"><?php if($_of_way == 'B'){echo 'Se Ofrece: ';}else{echo 'Se Pide: ';}?></th>
        <th style="width:100px;">--></th>
        <?php if ($var_Usuario != $_of_Aka) { ?>
        <th style="font-size: 28px;"><?php if($_of_way == 'S'){echo 'Yo Ofrezco: ';}else{echo 'Yo Pido: ';}?></th>
        <?php } ?>
    </tr>

    <tr>
        <td style="color:#F30; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" ><?php echo $_of_BronzePrice?></td>
        <td class="datalabel" style="font-size: 20px; text-align:center;" >Bronce</td>
       <?php if ($var_Usuario != $_of_Aka) { ?>
        <td >
            <input name="of_bronze" type="text" size="6" 
                style="color:#C60; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
        </td>
        <?php } ?>
    <tr/>
    <tr >
        <td style="color:#666; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" ><?php echo $_of_SilverPrice?></td>
        <td class="datalabel" style="font-size: 20px; text-align:center;"  >Plata</td>
       <?php if ($var_Usuario != $_of_Aka) { ?>
        <td>
            <input name="of_silver" type="text" size="6" 
                style="color:#999; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
        </td>
        <?php } ?>
    <tr/>
    <tr >
        <td style="color:#960; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" ><?php echo $_of_GoldPrice?></td>
        <td class="datalabel" style="font-size: 20px; text-align:center;" >Oro</td>
		<?php if ($var_Usuario != $_of_Aka) { ?>
        <td>
            <input name="of_gold" type="text" size="6" 
                style="color:#FC0; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
        </td>
        <?php } ?>
    <tr/>
    <tr >
        <td style="color:#030; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" ><?php echo $_of_DollarPrice?></td>
        <td class="datalabel" style="font-size: 20px; text-align:center;"  >USD</td>
        <?php if ($var_Usuario != $_of_Aka) { ?>
        <td>
            <input name="of_dollars" type="text" size="6" 
                style="color:#060; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
        </td>
        <?php } ?>
    <tr/>
<?php if (1 == 2) { /*Colocado provisional para no ver monedas extranjeras*/?>    
    <tr >
        <td style="color:#03C; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" ><?php echo $_of_CurrencyPrice?></td>
        <td style="color: #036; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:center;" ><?php echo $_of_OtherCurrency?></td>
        <?php if ($var_Usuario != $_of_Aka) { ?>
        <td>
            <input name="of_euros" type="text" size="6" 
                style="color:#06C; background-color:#300; font-family: 'Comic Sans MS', cursive; font-size: 18px;"/>
        </td>
        <?php } ?>
    <tr/>
<?php }/*Colocado provisional para no ver monedas extranjeras*/?>
</table>
<br/>
    <?php if ($muestra_detalle && $var_Usuario != $_of_Aka){ ?>
        <p style="text-align:center">
            <input  type="submit" name="of_answer"  value="" class="botonentrar"/>
        </p>
    <?php } ?>
    <?php if ($muestra_detalle && $var_Usuario == $_of_Aka){ ?>
        <p style="text-align:center">
            <input  type="submit" name="of_remove"  value="" class="botonretirar"/>
        </p>
    <?php } ?>


<p class="datalabel" style=" margin-left:3px; text-align:justify; font-size: 20px;"><?php echo $_of_Remarks?></p>
</form>
<script  type="text/javascript">
var frmvalidator = new Validator("OfferForm");
frmvalidator.addValidation("of_bronze","integer", "Bronce es un valor entero");
frmvalidator.addValidation("of_silver","integer", "Plata es un valor entero");
frmvalidator.addValidation("of_gold","integer", "Oro es un valor entero");
frmvalidator.addValidation("of_dollars","decimal", "Los dólares vienen en números");
frmvalidator.addValidation("of_euros","decimal", "Los dólares vienen en números");
 
 </script>
</div>

<div id="bottonpane" style="height: 300px;
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
<?php if ($var_Usuario <> '') { ?>
<?php 
	if ($pageNum_rs_offer_answers > 0) { // Show if not first page ?>
	<a href="<?php printf("%s?pageNum_rs_offer_answers=%d%s", $currentPage, 0, $queryString_rs_offer_answers); ?>">
				<img src="images/buttons/first.png" alt="Primera" width="32" height="32" border="0" >
	</a> 
	<a href="<?php printf("%s?pageNum_rs_offer_answers=%d%s", $currentPage, max(0, $pageNum_rs_offer_answers - 1), $queryString_rs_offer_answers); ?>">
  			<img src="images/buttons/previous.png" alt="Anterior" width="32" height="32" border="0" >
	</a>
<?php } // Show if not first page ?>
  
<?php if ($pageNum_rs_offer_answers < $totalPages_rs_offer_answers) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_rs_offer_answers=%d%s", $currentPage, min($totalPages_rs_offer_answers, $pageNum_rs_offer_answers + 1), $queryString_rs_offer_answers); ?>">
  			<img src="images/buttons/next.png" alt="Siguiente" width="32" height="32" border="0" >
  </a>
  <a href="<?php printf("%s?pageNum_rs_collectables=%d%s", $currentPage, $totalPages_rs_offer_answers, $queryString_rs_offer_answers); ?>">
  			<img src="images/buttons/last.png" alt="Última" width="32" height="32" border="0" >
  </a>
<?php }// Show if not last page ?>
<table border="0" style="margin-left:auto; margin-right:auto; table-layout:fixed; overflow:hidden" >
  <tr class="tableheaders">
    <th>Respuesta</th>
    <th>Proponente</th>
    <th>Propuesta</th>
    <th>Acción</th>
  </tr>
  <?php do { ?>
    <tr class="data">
      <td style="width:100px; font-weight:bold; text-align:center; "><?php echo $row_rs_offer_answers[0]; ?> </td>
      <td style="width:400px; font-weight:bold"><?php echo $row_rs_offer_answers[1]; ?></td>
      <td style="width:200px; font-weight:bold">
								<em style="color:#F60"><?php echo $row_rs_offer_answers[2] ?></em> /
								<em style="color:#CCC"><?php echo $row_rs_offer_answers[3] ?></em> /
								<em style="color:#FF3"><?php echo $row_rs_offer_answers[4] ?></em> /
	  				 			<em style="color:#090"><?php echo $row_rs_offer_answers[5] ?></em>
      </td>
      
      <td style="width:80px; font-weight:bold; text-align:center; "><?php if($_of_Aka == $var_Usuario && $row_rs_offer_answers[0] != '' ){ ?>
                    <a style="font-family: Verdana, Geneva, sans-serif; font-style:normal; color: #CCC;" 
                       href="market.php?OfferId=<?php echo $var_OfferId?>&ResponseId=<?php echo $row_rs_offer_answers[0]; ?>">Aceptar
                    </a>
      <?php }?>
	  <?php if($row_rs_offer_answers[1] == $var_Usuario){ ?>
                    <a style="font-family: Verdana, Geneva, sans-serif; font-style:normal; color: #CCC;" 
                       href="market.php?OfferId=<?php echo $var_OfferId?>&of_remove=<?php echo $row_rs_offer_answers[0]; ?>">Retirar
                    </a>
      <?php }?>
      	<!--<input  type="submit" name="accept_answer"  value="" class="botonentrar"/>-->
      </td>
      
    </tr>
    <?php } while ($row_rs_offer_answers = mysqli_fetch_row($rs_offer_answers)); ?>
</table>
<?php }?>
</div>
