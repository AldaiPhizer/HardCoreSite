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
	$var_OptionNumber = 0;
	$var_OptionName = '';
	$var_OptionTypeName = '';
	$var_OptionTypeCode = '';
	$var_OptionString = '';
	$var_OptionFormatedString = '';
	
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


	if (isset($_GET['OptionNumber'])){
		if($_GET['OptionNumber'] != ''){
			$var_OptionNumber = $_GET['OptionNumber'];
		}
	}

	if (isset($_GET['OptionName'])){
		if($_GET['OptionName'] != ''){
			$var_OptionName = $_GET['OptionName'];

		}	
	}

	if (isset($_GET['OptionTypeCode'])){
		if($_GET['OptionTypeCode'] != ''){
			$var_OptionTypeCode = $_GET['OptionTypeCode'];
		}
	}

	if (isset($_GET['OptionAddress'])){
		if($_GET['OptionAddress'] != ''){
			$var_OptionString = $_GET['OptionAddress'];
		}
	}

	if (isset($_GET['of_clear'])){
		$var_OptionNumber = 0;
		$var_OptionName = '';
		$var_OptionTypeCode = '';
		$var_OptionString = '';
	}

	if (isset($_GET['NewValue'])){
		if($_GET['NewValue'] != ''){
			$var_NewValue = $_GET['NewValue'];
		}
	}
/*This part is for the User Number only*/
//mysql_select_db($database_customersconn, $customersconn);
$query_rs_cusnum = "Select cu_number 
					  From customers.cu_head
					 Where cu_Aka = '". $var_Usuario . "';";
$rs_cusnum = mysqli_query($customersconn_mysqli, $query_rs_cusnum) or die(mysqli_error());
$row_rs_cusnum = mysqli_fetch_row($rs_cusnum);

$var_NumUsuario = $row_rs_cusnum[0]; 

/*This part is for the Type Name only*/
//mysql_select_db($database_customersconn, $customersconn);
$query_rs_cusnum = "Select pt_Name 
					  From customers.cu_payment_types
					 Where pt_Code = '". $var_OptionTypeCode . "';";
$rs_cusnum = mysqli_query($customersconn_mysqli, $query_rs_cusnum) or die(mysqli_error());
$row_rs_cusnum = mysqli_fetch_row($rs_cusnum);

$var_OptionTypeName = $row_rs_cusnum[0]; 

/****************************************** This part is for the Contacts Grid only ********************************************/
if ($var_NumUsuario != 0){

	$currentPage = $_SERVER["PHP_SELF"];
	$maxRows_rs_paymentMethods = 5;
	$pageNum_rs_paymentMethods = 0;
	if (isset($_GET['pageNum_rs_paymentMethods'])) {
	  $pageNum_rs_paymentMethods = $_GET['pageNum_rs_paymentMethods'];
	}
	$startRow_rs_paymentMethods = $pageNum_rs_paymentMethods * $maxRows_rs_paymentMethods;
	
	$query_rs_paymentMethods = "Select pm_Number, pm_Description, pm_Address, pm_MethodType, pm_Frequency ";
	$query_rs_paymentMethods = $query_rs_paymentMethods . " From customers.cu_payment_methods ";
	$query_rs_paymentMethods = $query_rs_paymentMethods . "Where pm_CustomerNumber = " . $var_NumUsuario ;
	$query_rs_paymentMethods = $query_rs_paymentMethods . " Order By pm_Number ";
						 

	if ($rs_paymentMethods = mysqli_query($customersconn_mysqli, $query_rs_paymentMethods))
	{
		if (isset($_GET['totalRows_rs_paymentMethods'])) {
		  $totalRows_rs_paymentMethods = $_GET['totalRows_rs_paymentMethods'];
		} else {
		
		  $totalRows_rs_paymentMethods = $rs_paymentMethods->num_rows; 
		}
	}else{
		print $customersconn_mysqli->error."<br />";
	}
	
	$rs_paymentMethods->close();
	if($customersconn_mysqli->more_results()){
		$customersconn_mysqli->next_result();
	}
		
	$query_rs_paymentMethods = "Select pm_Number, pm_Description, pm_Address, pm_MethodType, pm_Frequency ";
	$query_rs_paymentMethods = $query_rs_paymentMethods . " From customers.cu_payment_methods ";
	$query_rs_paymentMethods = $query_rs_paymentMethods . "Where pm_CustomerNumber = " . $var_NumUsuario ;
	$query_rs_paymentMethods = $query_rs_paymentMethods . " Order By pm_Number "; 
	$query_rs_paymentMethods = $query_rs_paymentMethods . " Limit " . $startRow_rs_paymentMethods . ", " . $maxRows_rs_paymentMethods . ";";
						 

	if ($rs_paymentMethods = mysqli_query($customersconn_mysqli, $query_rs_paymentMethods))
	{
		$row_rs_paymentMethods = mysqli_fetch_row($rs_paymentMethods);
	}else{
		print $customersconn_mysqli->error."<br />";
	}

	$totalPages_rs_paymentMethods = ceil($totalRows_rs_paymentMethods/$maxRows_rs_paymentMethods)-1;	
	$queryString_rs_paymentMethods = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
	  $params = explode("&", $_SERVER['QUERY_STRING']);
	  $newParams = array();
	  foreach ($params as $param) {
		if (stristr($param, "pageNum_rs_paymentMethods") == false && 
			stristr($param, "totalRows_rs_paymentMethods") == false) {
		  array_push($newParams, $param);
		}
	  }
	  if (count($newParams) != 0) {
		$queryString_rs_paymentMethods = "&" . htmlentities(implode("&", $newParams));
	  }
	}
	$queryString_rs_paymentMethods = sprintf("&totalRows_rs_paymentMethods=%d%s", $totalRows_rs_paymentMethods, $queryString_rs_paymentMethods);
	/*echo '<script>DoAlert("' . $queryString_rs_paymentMethods . '")</script>';*/
}

/****************************************** This part is for the Contacts Grid only - End ********************************************/

/************************************** This part is for the Addres Type list box only *************/
	//mysql_select_db($database_customersconn, $customersconn);
	$query_rs_addtype = "select pt_Code, pt_Name from customers.cu_payment_types  where pt_Status = 'A' Order by pt_Name";
	$rs_addtype = mysqli_query($customersconn_mysqli, $query_rs_addtype) or die(mysqli_error());
	$row_rs_addtype = mysqli_fetch_row($rs_addtype);
	$totalRows_rs_addtype = mysqli_num_rows($rs_addtype);


/*************************************  Create the thing - Start ********************************/
	if (isset($_GET['of_create'])){
		if ($var_OptionFormatedString != ''){
			$var_SendAddress = $var_OptionFormatedString;
		}else{
			$var_SendAddress = $var_OptionString;
		}

		$query_rs_create = "Call customers.SetPaymetMethod('C', " 
												  . $var_NumUsuario . ", " 
												  . 0 . ", '"
												  . $var_OptionName . "', '"
												  . $var_OptionTypeCode . "', '"
												  . $var_SendAddress . "', '');";

		/*echo '<script>DoAlert("' . $query_rs_create . '")</script>';*/
		if ($rs_create = mysqli_query($customersconn_mysqli, $query_rs_create))
		{
			$row_rs_create = mysqli_fetch_row($rs_create);
			$var_Retorno = $row_rs_create['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_create->close();
			$customersconn_mysqli->next_result();
			$var_Priority = 0;
			echo '<META http-equiv="Refresh" Content="0; account.php?admoney&AddPaymentMethod"';
		}else{
			$var_Retorno =  $customersconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>';
			echo '<META http-equiv="Refresh" Content="0; account.php"';
		}
	}
/*************************************  Create the thing - End ********************************/

/*************************************  Remove the thing - Start ********************************/
	if (isset($_GET['of_remove'])){
		$query_rs_remove = "Call customers.SetPaymetMethod('R', " 
												  . $var_NumUsuario . ", " 
												  . $var_OptionNumber . ", '"
												  . $var_OptionName . "', '"
												  . $var_OptionTypeCode . "', '', '');";
			
		if ($rs_remove = mysqli_query($customersconn_mysqli, $query_rs_remove))
		{
			$row_rs_remove = mysqli_fetch_row($rs_remove);
			$var_Retorno = $row_rs_remove['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_remove->close();
			$customersconn_mysqli->next_result();
			$var_Priority = 0;
			echo '<META http-equiv="Refresh" Content="0; account.php?admoney&AddPaymentMethod"';
		}else{
			$var_Retorno =  $customersconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>';
			echo '<META http-equiv="Refresh" Content="0; account.php"';
		}
	}
/*************************************  Remove the thing - End ********************************/

/*************************************  Update the thing - Start ********************************/
	if (isset($_GET['of_update'])){
		if ($var_OptionFormatedString != ''){
			$var_SendAddress = $var_OptionFormatedString;
		}else{
			$var_SendAddress = $var_OptionString;
		}
		$query_rs_update = "Call customers.SetPaymetMethod('U', " 
												  . $var_NumUsuario . ", " 
												  . $var_OptionNumber . ", '"
												  . $var_OptionName . "', '"
												  . $var_OptionTypeCode . "', '"
												  . $var_SendAddress . "', '');";
		/*echo '<script>DoAlert("' . $query_rs_update . '")</script>'; 	*/
		if ($rs_update = mysqli_query($customersconn_mysqli, $query_rs_update))
		{
			$row_rs_update = mysqli_fetch_row($rs_update);
			$var_Retorno = $row_rs_update['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_update->close();
			$customersconn_mysqli->next_result();
			$var_Priority = 0;
			echo '<META http-equiv="Refresh" Content="0; account.php?admoney&AddPaymentMethod"';
		}else{
			$var_Retorno =  $customersconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>';
			echo '<META http-equiv="Refresh" Content="0; account.php"';
		}
	}
/*************************************  Update the thing - End ********************************/

?>
<div id="paymenthandel" style="background:url(images/backgrounds/WhiteStorm_big.png); width:1120px; height:825px" >
<a href="account.php?admoney" style="position:absolute">
	<img src="images/buttons/img_Volver.png"/>
</a>
<div id="header" class="pagetitle">
Gestiona tus Formas de Pago <?php echo  $var_Usuario	?> 
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
	 <input name="AddPaymentMethod" type="hidden" value="" />
     <br /> <br />
     <label style=" width:680px; height:60px; position:absolute; color:#600; margin-left:200px; 
     					font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 18px;">Debes poseer al menos una forma de pago asociada a tu cuenta para permitirse tu acceso al contenido adulto.  Lo que hagas con ese poder es cosa tuya.</label>
     </br>   </br></br>
</div>     
	<?php 
        if ($pageNum_rs_paymentMethods > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rs_paymentMethods=%d%s", $currentPage, 0, $queryString_rs_paymentMethods); ?>">
                    <img src="images/buttons/first.png" alt="Primera" width="32" height="32" border="0" >
        </a> 
        <a href="<?php printf("%s?pageNum_rs_paymentMethods=%d%s", $currentPage, max(0, $pageNum_rs_paymentMethods - 1),
																					$queryString_rs_paymentMethods); ?>">
                <img src="images/buttons/previous.png" alt="Anterior" width="32" height="32" border="0" >
        </a>
    <?php } // Show if not first page ?>
      
    <?php if ($pageNum_rs_paymentMethods < $totalPages_rs_paymentMethods) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rs_paymentMethods=%d%s", $currentPage, min($totalPages_rs_paymentMethods, $pageNum_rs_paymentMethods + 1), $queryString_rs_paymentMethods); ?>">
                <img src="images/buttons/next.png" alt="Siguiente" width="32" height="32" border="0" >
      </a>
      <a href="<?php printf("%s?pageNum_rs_paymentMethods=%d%s", $currentPage, $totalPages_rs_paymentMethods, $queryString_rs_paymentMethods); ?>">
                <img src="images/buttons/last.png" alt="Última" width="32" height="32" border="0" >
      </a>
    <?php }// Show if not last page ?>
    <table border="0" style="margin-left:auto; margin-right:auto; table-layout:fixed; overflow:hidden" >
      <tr class="tableheaders">
        <th>Modo de Pago</th>
        <th>Tipo</th>
        <th>Descripción</th>
        <th style="display:none;">Add type</th>
      </tr>
      <?php do { ?>
        <tr class="data">
            <td style="width:100px; font-weight:bold; text-align:center; "> 
                <a style="font-family: Verdana, Geneva, sans-serif; font-style:normal; color: #CCC;" 
                   href="account.php?AddPaymentMethod=''&admoney=''&OptionNumber=<?php echo $row_rs_paymentMethods[0] 
				   								?>&OptionName=<?php echo $row_rs_paymentMethods[1]
												?>&OptionTypeCode=<?php echo $row_rs_paymentMethods[3]
												?>&OptionAddress=<?php echo $row_rs_paymentMethods[2]
												?>#detalle">
                        <?php echo $row_rs_paymentMethods[0]; ?> 
                 </a>
            </td>
			<td style="width:600px; font-weight:bold"><?php echo $row_rs_paymentMethods[1]; ?></td>
          	<td style="width:150px; font-weight:bold"><?php echo $row_rs_paymentMethods[3]; ?></td>
        </tr>
        <?php } while ($row_rs_paymentMethods = mysqli_fetch_row($rs_paymentMethods)); ?>
    </table>
</form>
<?php 
	$rs_paymentMethods->close();
	if($customersconn_mysqli->more_results()){
		$customersconn_mysqli->next_result();
	}
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
<a name="detalle"></a>
<?php if ($var_OptionNumber == 0) { //We're going to add a new payment type

?>
    <form id="CreateForm" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
		<input name="admoney" type="hidden" value="" />
    	<input name="AddPaymentMethod" type="hidden" value="" />
    	<input name="OptionNumber" type="hidden" value="<? echo $var_OptionNumber ?>" />
	    <p style="position:absolute; left:215px;"  >
        <label style="color: #900; 
        			font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 24px;">Asígnale un nombre</label>
        <br />
        
        <input id="OptionName" name="OptionName" type="text" size="30" value="<?php echo $var_OptionName; ?>" 
                    class="data"/>
    	<label class="datalabel" style="font-size: 24px;">Tipo: 	&nbsp;</label>
        <select name="OptionTypeCode" id="OptionTypeCode" size="1" class="data" 
          onchange="form.action = window.location;this.form.submit();">  <!--"location.href='account.php?admoney&AddPaymentMethod&OptionNumber=<?php/* echo $var_OptionNumber?>&OptionTypeCode='+this.value+'#detalle'"-->
          <option class="drop" value="" <?php if ($var_OptionTypeCode == ''){ ?> selected="selected" <?php } ?>>Selecciona un tipo</option>
            <?php
            do {  
            ?>
            <option class="drop" value="<?php echo $row_rs_addtype[0]?>" 
            <?php if ($var_OptionTypeCode == $row_rs_addtype[0]){ ?> 
                        selected="selected" <?php } ?> > <?php echo $row_rs_addtype[1]?>
            </option>
            <?php
            } while ($row_rs_addtype = mysqli_fetch_row($rs_addtype));
              $rows = mysqli_num_rows($rs_addtype);
              if($rows > 0) {
                  mysqli_data_seek($rs_addtype, 0);
                  $row_rs_addtype = mysqli_fetch_row($rs_addtype);
              }
            ?>
        </select>
     	<br />
			<?php if ($var_OptionTypeCode == 'PPL'){ ?>
<!------------------------------------------------- PLACE PAYPAL BUTTON HERE	----------------------------------------------------->
             <?php } ?>
         <br />
        <label style="color: #900; 
        			font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 24px;">Cuenta</label>
        <br />
        <input id="OptionAddress" name="OptionAddress" type="text" size="30" value="<?php echo $var_OptionString; ?>" 
                    class="data"/>
    	</p>
        <p style=" position:absolute; left:700px; top:650px;">
            <input  type="submit" name="of_create"  value="" class="botoncrear"/>
        </p>    
    </form> <!-- form id="CreateForm"-->
    <script  type="text/javascript">
		var frmvalidator = new Validator("CreateForm");
		
		frmvalidator.addValidation("OptionName","req", "Introduce el nombre de tu cuenta");
		frmvalidator.addValidation("OptionTypeCode","req", "Selecciona un tipo de pago");
		frmvalidator.addValidation("OptionAddress","req", "Introduce una cuenta");


     </script>
<?php }else{ //We're going to modify an existing contact?>

    <form id="UpdateForm" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
    	<input name="admoney" type="hidden" value="" />    
    	<input name="AddPaymentMethod" type="hidden" value="" />
    	<input name="OptionNumber" type="hidden" value="<?php echo $var_OptionNumber ?>" />
        <input name="OptionName" type="hidden" value="<?php echo $var_OptionName; ?>"  />

	    <p style="position:absolute; left:150px; width:800px; height:300px;"  >
        <label class="datalabel" style=" font-size: 26px;">Forma de Pago</label>
        <label class="datalabel" style=" font-size: 24px;">Número:  <?php echo $var_OptionNumber ?></label>
        <br />
        <label class="datalabel" style="font-size: 24px;"><?php echo $var_OptionTypeName . ' - ' ?></label>
        <label class="datalabel" style=" font-size: 24px;"><?php echo $var_OptionString ?> </label>
        <br />
        <input id="OptionAddress" name="OptionAddress" type="text" size="30" value="<?php echo $var_OptionString; ?>" 
                    class="data"/>
        </p>
        <p style=" position:absolute; left:600px; top:500px;">
    	<label class="datalabel" style="font-size: 24px;">Tipo: 	&nbsp;</label>
        <select name="OptionTypeCode" size="1" class="data" onchange="this.form.submit();">
          <option class="drop" value="" <?php if ($var_OptionTypeCode == ''){ ?> selected="selected" <?php } ?>>Selecciona un tipo</option>
            <?php
            do {  
            ?>
            <option class="drop" value="<?php echo $row_rs_addtype[0]?>" 
            <?php if ($var_OptionTypeCode == $row_rs_addtype[0]){ ?> 
                        selected="selected" <?php } ?> > <?php echo $row_rs_addtype[1]?>
            </option>
            <?php
            } while ($row_rs_addtype = mysqli_fetch_row($rs_addtype));
              $rows = mysqli_num_rows($rs_addtype);
              if($rows > 0) {
                  mysqli_data_seek($rs_addtype, 0);
                  $row_rs_addtype = mysqli_fetch_row($rs_addtype);
              }
            ?>
        </select>
            <br /><br />
            
        </p>
        <p style=" position:absolute; left:200px; top:600px;">
            <input  type="submit" name="of_update"  value="" class="botonactualizar"/>
            <input  type="submit" name="of_remove"  value="" class="botonremover"/> 
            <input  type="submit" name="of_clear"  value="" class="botonlimpiar"/> 

        </p>
     </form>
    <script  type="text/javascript">
		var frmvalidator = new Validator("UpdateForm");
		frmvalidator.addValidation("OptionTypeCode","req", "Selecciona un tipo de pago");
		frmvalidator.addValidation("OptionAddress","req", "Introduce una cuenta");

     </script>

<?php } ?>

</div>
</div>
