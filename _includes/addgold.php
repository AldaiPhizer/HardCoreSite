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
	$var_PackageNumber = 0;
	$var_PackageName = '';
	$var_PackageGold = 0;
	$var_PackageCost = 0;
	$var_PackageAccepted = 0;
	
	
	$var_PaymentCode = '';
	$var_PaymentMethod = '';
	
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


	if (isset($_GET['PackageNumber'])){
		if($_GET['PackageNumber'] != 0){
			$var_PackageNumber = $_GET['PackageNumber'];
		}
	}

	if (isset($_GET['PackageName'])){
		if($_GET['PackageName'] != ''){
			$var_PackageName = $_GET['PackageName'];
		}
	}

	if (isset($_GET['PackageGold'])){
		if($_GET['PackageGold'] != 0){
			$var_PackageGold = $_GET['PackageGold'];
		}
	}

	if (isset($_GET['PackageCost'])){
		if($_GET['PackageCost'] != 0){
			$var_PackageCost = $_GET['PackageCost'];
		}
	}
	
	if (isset($_GET['PaymentCode'])){
		if($_GET['PaymentCode'] != ''){
			$var_PaymentCode = $_GET['PaymentCode'];
		}
	}
	
	if (isset($_GET['Package'])){
		if($_GET['Package'] != 0 && $_GET['Package'] != '')
		$var_PackageAccepted = $_GET['Package'];
	}
/*This part is for the User Number only*/
//mysql_select_db($database_customersconn, $customersconn);
$query_rs_cusnum = "Select cu_number 
					  From customers.cu_head
					 Where cu_Aka = '". $var_Usuario . "';";

$rs_cusnum = mysqli_query($customersconn_mysqli, $query_rs_cusnum) or die(mysqli_error());
$row_rs_cusnum = mysqli_fetch_row($rs_cusnum);

$var_NumUsuario = $row_rs_cusnum[0]; 


/****************************************** This part is for the Packages Grid only ********************************************/
if ($var_NumUsuario != 0){

	$currentPage = $_SERVER["PHP_SELF"];
	$maxRows_rs_packages = 10;
	$pageNum_rs_packages = 0;
	if (isset($_GET['pageNum_rs_packages'])) {
	  $pageNum_rs_packages = $_GET['pageNum_rs_packages'];
	}
	$startRow_rs_packages = $pageNum_rs_packages * $maxRows_rs_packages;
	
	$query_rs_packages = "Select pk_Number, pk_Name, pk_Gold, pk_Cost ";
	$query_rs_packages = $query_rs_packages . " From customers.cu_packages ";
	$query_rs_packages = $query_rs_packages . " Order By pk_Number ";
						 

	if ($rs_packages = mysqli_query($customersconn_mysqli, $query_rs_packages))
	{
		if (isset($_GET['totalRows_rs_packages'])) {
		  $totalRows_rs_packages = $_GET['totalRows_rs_packages'];
		} else {
		
		  $totalRows_rs_packages =  mysqli_num_rows($rs_packages);
		}
	}else{
		print $customersconn_mysqli->error."<br />";
	}
	
	$rs_packages->close();
	if($customersconn_mysqli->more_results()){
		$customersconn_mysqli->next_result();
	}
		
	$query_rs_packages = "Select pk_Number, pk_Name, pk_Gold, pk_Cost ";
	$query_rs_packages = $query_rs_packages . " From customers.cu_packages ";
	$query_rs_packages = $query_rs_packages . " Order By pk_Number "; 
	$query_rs_packages = $query_rs_packages . " Limit " . $startRow_rs_packages . ", " . $maxRows_rs_packages . ";";
						 

	if ($rs_packages = mysqli_query($customersconn_mysqli, $query_rs_packages))
	{
		$row_rs_packages = mysqli_fetch_row($rs_packages);
	}else{
		print $customersconn_mysqli->error."<br />";
	}

	$totalPages_rs_packages = ceil($totalRows_rs_packages/$maxRows_rs_packages)-1;	
	$queryString_rs_packages = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
	  $params = explode("&", $_SERVER['QUERY_STRING']);
	  $newParams = array();
	  foreach ($params as $param) {
		if (stristr($param, "pageNum_rs_packages") == false && 
			stristr($param, "totalRows_rs_packages") == false) {
		  array_push($newParams, $param);
		}
	  }
	  if (count($newParams) != 0) {
		$queryString_rs_packages = "&" . htmlentities(implode("&", $newParams));
	  }
	}
	$queryString_rs_packages = sprintf("&totalRows_rs_packages=%d%s", $totalRows_rs_packages, $queryString_rs_packages);
	/*echo '<script>DoAlert("' . $queryString_rs_packages . '")</script>';*/
}

/****************************************** This part is for the Contacts Grid only - End ********************************************/

/************************************** This part is for the Payment Type list box only *************/
//	mysql_select_db($database_customersconn, $customersconn);
	$query_rs_addtype = "select pm_Number, pm_Description 
						   from customers.cu_payment_methods 
						  where pm_CustomerNumber = " . $var_NumUsuario . " Order by pm_Number";
	$rs_addtype = mysqli_query($customersconn_mysqli, $query_rs_addtype) or die(mysqli_error());
	$row_rs_addtype = mysqli_fetch_row($rs_addtype);
	$totalRows_rs_addtype = mysqli_num_rows($rs_addtype);

/************************************** This part is for the Payment Method Value only *************/
	if ($var_PaymentCode != 0){
		//mysql_select_db($database_customersconn, $customersconn);
		$query_rs_method = "select pm_MethodType
							   from customers.cu_payment_methods 
							  where pm_CustomerNumber = " . $var_NumUsuario 
							  . " and pm_Number = " . $var_PaymentCode . ";";
		$rs_method = mysqli_query($customersconn_mysqli, $query_rs_method) or die(mysqli_error());
		$row_rs_method = mysqli_fetch_row($rs_method);
		$totalRows_rs_method = mysqli_num_rows($rs_method);
	
		$var_PaymentMethod = $row_rs_method[0];
	}
/*************************************  Update the Money - Start ********************************/
	if ($var_PackageAccepted != 0){
		$query_rs_update = "Call customers.SetMoney('B', '"
												  . $var_Usuario . "', '', '', "
												  . $var_PackageAccepted . ", 0, 0, 0, 0, 0);";
		/*echo '<script>DoAlert("' . $query_rs_update . '")</script>'; */	
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
<div id="paymenthandel" style="background:url(images/backgrounds/WhiteStorm_big.png); width:1120px; height:825px" >
<a href="account.php?admoney" style="position:absolute">
	<img src="images/buttons/img_Volver.png"/>
</a>
<div id="header" class="pagetitle">
Escoge un Paquete <?php echo  $var_Usuario	?> 
</div>

<div id="upperpane" style="	height: 520px;
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
     <label style=" width:680px; height:60px; position:absolute; color:#FF0; margin-left:280px; 
     					font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 18px;">Entre más inviertes, más ahorras. Haz que tu dinero valga.</label>
     </br>   
</div>     
	<?php 
        if ($pageNum_rs_packages > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rs_packages=%d%s", $currentPage, 0, $queryString_rs_packages); ?>">
                    <img src="images/buttons/first.png" alt="Primera" width="32" height="32" border="0" >
        </a> 
        <a href="<?php printf("%s?pageNum_rs_packages=%d%s", $currentPage, max(0, $pageNum_rs_packages - 1),
																					$queryString_rs_packages); ?>">
                <img src="images/buttons/previous.png" alt="Anterior" width="32" height="32" border="0" >
        </a>
    <?php } // Show if not first page ?>
      
    <?php if ($pageNum_rs_packages < $totalPages_rs_packages) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rs_packages=%d%s", $currentPage, min($totalPages_rs_packages, $pageNum_rs_packages + 1), $queryString_rs_packages); ?>">
                <img src="images/buttons/next.png" alt="Siguiente" width="32" height="32" border="0" >
      </a>
      <a href="<?php printf("%s?pageNum_rs_packages=%d%s", $currentPage, $totalPages_rs_packages, $queryString_rs_packages); ?>">
                <img src="images/buttons/last.png" alt="Última" width="32" height="32" border="0" >
      </a>
    <?php }// Show if not last page ?>
    <table border="0" style="margin-left:auto; margin-right:auto; table-layout:fixed; overflow:hidden" >
      <tr class="tableheaders">
        <th>Paquete</th>
        <th>Costo</th>
        <th>Selecciona</th>
      </tr>
      <?php do { ?>
        <tr class="data">
			<td style="width:200px; font-weight:bold; font-size:24px; text-align:center;" ><?php echo $row_rs_packages[1]; ?></td>
          	<td style="width:200px; font-weight:bold; font-size:24px; text-align:center;">US$.: <?php echo $row_rs_packages[3]; ?></td>
            <td style="width:200px; font-weight:bold; text-align:center; "> 
                <a href="account.php?AddGold=''&admoney=''&PackageNumber=<?php echo $row_rs_packages[0] 
				   								?>&PackageName=<?php echo $row_rs_packages[1]
												?>&PackageGold=<?php echo $row_rs_packages[2]
												?>&PackageCost=<?php echo $row_rs_packages[3]
												?>&PaymentCode=<?php echo $var_PaymentCode 
												?>#detalle">
    				<img  src="images/arts/oro_bars_<?php echo $row_rs_packages[0]; ?>.png"  
                    	width="100" height="70" alt="<?php echo $row_rs_packages[1]; ?>"  />

                 </a>
            </td>
        </tr>
        <?php } while ($row_rs_packages = mysqli_fetch_row($rs_packages)); ?>
    </table>
</form>
<?php 
	$rs_packages->close();
	if($customersconn_mysqli->more_results()){
		$customersconn_mysqli->next_result();
	}
?>
</div>

<div id="bottonpane" style="height: 230px;
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
<?php if ($var_PackageNumber != 0) { //We're going to pick up one option

?>
    <form id="SelectForm" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
		<input name="admoney" type="hidden" value="" />
    	<input name="AddGold" type="hidden" value="" />
    	<input name="PackageNumber" type="hidden" value="<?php echo $var_PackageNumber; ?>" />
        
	    <p style="position:absolute; left:150px; top:550px;" >
    				<img src="images/arts/oro_bars_<?php echo $var_PackageNumber; ?>.png"  
                    	width="200" height="170" alt="<?php echo $var_PackageName; ?>"  />
        </p>
   		<p style="position:absolute; top:600px; left:360px;">
    	<label class="datalabel" style="font-size: 24px;">Selecciona un Método de Pago: 	&nbsp;</label>
        <select name="PaymentCode" id="PaymentCode" size="1" class="data" 
          onchange="location.href='account.php?admoney&AddGold&PackageNumber=<?php echo $var_PackageNumber?>&PaymentCode='+this.value+'#detalle'">
          <option class="drop" value="" <?php if ($var_PaymentCode == ''){ ?> selected="selected" <?php } ?>>Selecciona un tipo</option>
            <?php
            do {  
            ?>
            <option class="drop" value="<?php echo $row_rs_addtype[0]?>" 
            <?php if ($var_PaymentCode == $row_rs_addtype[0]){ ?> 
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
     	</p>
    </form> <!-- form id="CreateForm"-->
    <?php if ($var_PaymentMethod == 'PPL') { ?>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <?php if ($var_PackageNumber == 1) {?>
            <input type="hidden" name="hosted_button_id" value="W7MNX32UU3PE2">
            <?php } ?>
            <?php if ($var_PackageNumber == 2) {?>
            <input type="hidden" name="hosted_button_id" value="V78BW5F9YWBZ4">
            <?php } ?>
            <?php if ($var_PackageNumber == 3) {?>
            <input type="hidden" name="hosted_button_id" value="P8TWTX2UJ986E">
            <?php } ?>
            <?php if ($var_PackageNumber == 4) {?>
            <input type="hidden" name="hosted_button_id" value="2FBQX4AS9Y422">
            <?php } ?>
            <?php if ($var_PackageNumber == 5) {?>
            <input type="hidden" name="hosted_button_id" value="VQ6P5JDEW92EW">
            <?php } ?>
            <?php if ($var_PackageNumber == 6) {?>
            <input type="hidden" name="hosted_button_id" value="SSYSXSJT6JSK6">
            <?php } ?>
            <p style=" position:absolute; left:660px; top:660px;">
                <input type="image" src="https://www.paypalobjects.com/es_ES/ES/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" 
                        alt="PayPal. La forma rápida y segura de pagar en Internet.">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </p>
        </form>
	<?php } ?>
    <script  type="text/javascript">
		var frmvalidator = new Validator("SelectForm");
		
		frmvalidator.addValidation("PaymentCode","req", "Selecciona un tipo de pago");


     </script>
     
<?php }?>

</div>
</div>
