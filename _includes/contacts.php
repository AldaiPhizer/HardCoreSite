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
	
	$var_Address = '';
	$var_AddressType = 0;
	$var_AddTypeName = '';
	$var_Priority = 0;
	$var_NewValue = '';
	$var_NewPrior = 0;
	
	$var_SelAddType = '';
	$var_Email = '';
	$var_SomeAdd = '';
	


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



	if (isset($_GET['AddPriority'])){
		if($_GET['AddPriority'] != 0){
			$var_Priority = $_GET['AddPriority'];

		}	
	}

	if (isset($_GET['Address'])){
		$var_Address = $_GET['Address'];
	}
	
	if (isset($_GET['NewValue'])){
		if($_GET['NewValue'] != ''){
			$var_NewValue = $_GET['NewValue'];
		}
	}

	if (isset($_GET['Prior'])){
		if($_GET['Prior'] != 0){
			$var_NewPrior = $_GET['Prior'];

		}	
	}

	if (isset($_GET['AddType'])){
		if($_GET['AddType'] != 0){
			$var_AddressType = $_GET['AddType'];

		}	
	}

	if (isset($_GET['AddTypeName'])){
		if($_GET['AddTypeName'] != ''){
			$var_AddTypeName = $_GET['AddTypeName'];

		}	
	}

	if (isset($_GET['TipoAdd'])){
		if($_GET['TipoAdd'] != ''){
			$var_SelAddType = $_GET['TipoAdd'];

		}	
	}

	if (isset($_GET['SomeAdd'])){
		$var_SomeAdd = $_GET['SomeAdd'];
	}

	if (isset($_GET['Email'])){
		$var_Email = $_GET['Email'];
	}

/*This part is for the User Number only*/
//mysql_select_db($database_customersconn, $customersconn);
$query_rs_cusnum = "Select cu_number 
					  From customers.cu_head
					 Where cu_Aka = '". $var_Usuario . "';";
$rs_cusnum = mysqli_query($customersconn_mysqli, $query_rs_cusnum) or die(mysqli_error());
$row_rs_cusnum = mysqli_fetch_row($rs_cusnum);

$var_NumUsuario = $row_rs_cusnum[0];
mysqli_free_result($rs_cusnum);

/****************************************** This part is for the Contacts Grid only ********************************************/
if ($var_NumUsuario != 0){

	$currentPage = $_SERVER["PHP_SELF"];
	$maxRows_rs_contacts = 10;
	$pageNum_rs_contacts = 0;
	if (isset($_GET['pageNum_rs_contacts'])) {
	  $pageNum_rs_contacts = $_GET['pageNum_rs_contacts'];
	}
	$startRow_rs_contacts = $pageNum_rs_contacts * $maxRows_rs_contacts;
	
	$query_rs_contacts = "Select co_AddressPriority, co_AddressReference, ct_TypeDescription, co_AddressType ";
	$query_rs_contacts = $query_rs_contacts . " From customers.cu_contacts Join customers.cu_contact_types On ct_TypeNumber = co_AddressType ";
	$query_rs_contacts = $query_rs_contacts . "Where co_CustomerNumber = " . $var_NumUsuario ;
	$query_rs_contacts = $query_rs_contacts . " Order By co_AddressPriority ";

	if ($rs_contacts = mysqli_query($customersconn_mysqli, $query_rs_contacts))
	{
		if (isset($_GET['totalRows_rs_contacts'])) {
		  $totalRows_rs_contacts = $_GET['totalRows_rs_contacts'];
		} else {
		
		  $totalRows_rs_contacts =  mysqli_num_rows($rs_contacts); //$rs_contacts->num_rows; 
		}
	}else{
		print $customersconn_mysqli->error."<br />";
	}
	
	$rs_contacts->close();
	if($customersconn_mysqli->more_results()){
		$customersconn_mysqli->next_result();
	}
		
	$query_rs_contacts = "Select co_AddressPriority, co_AddressReference, ct_TypeDescription, co_AddressType ";
	$query_rs_contacts = $query_rs_contacts . " From customers.cu_contacts Join customers.cu_contact_types On ct_TypeNumber = co_AddressType ";
	$query_rs_contacts = $query_rs_contacts . "Where co_CustomerNumber = " . $var_NumUsuario ;
	$query_rs_contacts = $query_rs_contacts . " Order By co_AddressPriority"; 
	$query_rs_contacts = $query_rs_contacts . " Limit " . $startRow_rs_contacts . ", " . $maxRows_rs_contacts . ";";
						 

	if ($rs_contacts = mysqli_query($customersconn_mysqli, $query_rs_contacts))
	{
		$row_rs_contacts = mysqli_fetch_row($rs_contacts);
	}else{
		print $customersconn_mysqli->error."<br />";
	}

	$totalPages_rs_contacts = ceil($totalRows_rs_contacts/$maxRows_rs_contacts)-1;	
	$queryString_rs_contacts = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
	  $params = explode("&", $_SERVER['QUERY_STRING']);
	  $newParams = array();
	  foreach ($params as $param) {
		if (stristr($param, "pageNum_rs_contacts") == false && 
			stristr($param, "totalRows_rs_contacts") == false) {
		  array_push($newParams, $param);
		}
	  }
	  if (count($newParams) != 0) {
		$queryString_rs_contacts = "&" . htmlentities(implode("&", $newParams));
	  }
	}
	$queryString_rs_contacts = sprintf("&totalRows_rs_contacts=%d%s", $totalRows_rs_contacts, $queryString_rs_contacts);
	/*echo '<script>DoAlert("' . $queryString_rs_contacts . '")</script>';*/
}

/****************************************** This part is for the Contacts Grid only - End ********************************************/

	if (isset($_GET['AddPriority'])) {
		if ($_GET['AddPriority'] != 0){
			$var_Address = $_GET['Address'];
			$var_Priority = $_GET['AddPriority'];
			$var_AddressType = $_GET['AddType'];			
		}
	}
	if (isset($_GET['of_clear'])){
		$var_Priority = 0;
	}

/************************************** This part is for the Addres Type list box only *************/
//	mysql_select_db($database_customersconn, $customersconn);
	$query_rs_addtype = "select ct_TypeNumber, ct_TypeDescription from customers.cu_contact_types Order by ct_TypeDescription";
	$rs_addtype = mysqli_query($customersconn_mysqli, $query_rs_addtype) or die(mysqli_error());
	$row_rs_addtype = mysqli_fetch_row($rs_addtype);
	$totalRows_rs_addtype = mysqli_num_rows($rs_addtype);

/************************************* This part is for the Priority List Only **********************/
if ($var_Priority != 0) {
	//mysql_select_db($database_customersconn, $customersconn);
	$query_rs_prior = "Select co_AddressPriority
						   From customers.cu_contacts
						  Where co_CustomerNumber = " . $var_NumUsuario . "
						  Order By co_AddressPriority;";
	$rs_prior = mysqli_query($customersconn_mysqli, $query_rs_prior) or die(mysqli_error());
	$row_rs_prior = mysqli_fetch_row($rs_prior);
	$totalRows_rs_prior = mysqli_num_rows($rs_prior);
	
}
/*************************************  Create the thing - Start ********************************/
	if (isset($_GET['of_create'])){
		if ($var_Email != ''){
			$var_SendAddress = $var_Email;
		}else{
			$var_SendAddress = $var_SomeAdd;
		}

		$query_rs_create = "Call customers.SetContacts('C', " 
												  . $var_NumUsuario . ", " 
												  . 0 . ", '"
												  . $var_SendAddress . "', "
												  . $var_SelAddType . ", '', 0);";
		/*echo '<script>DoAlert("' . $query_rs_create . '")</script>'; */	
		if ($rs_create = mysqli_query($customersconn_mysqli, $query_rs_create))
		{
			$row_rs_create = mysqli_fetch_row($rs_create);
			$var_Retorno = $row_rs_create['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_create->close();
			$customersconn_mysqli->next_result();
			$var_Priority = 0;
			echo '<META http-equiv="Refresh" Content="0; account.php?contacts"';
		}else{
			$var_Retorno =  $customersconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>';
			echo '<META http-equiv="Refresh" Content="0; account.php"';
		}
	}
/*************************************  Create the thing - End ********************************/

/*************************************  Remove the thing - Start ********************************/
	if (isset($_GET['of_remove'])){
		$query_rs_remove = "Call customers.SetContacts('R', " 
												  . $var_NumUsuario . ", " 
												  . $var_Priority . ", '', 0,  '',  0 );";
		/*echo '<script>DoAlert("' . $query_rs_remove . '")</script>';*/ 	
		if ($rs_remove = mysqli_query($customersconn_mysqli, $query_rs_remove))
		{
			$row_rs_remove = mysqli_fetch_row($rs_remove);
			$var_Retorno = $row_rs_remove['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_remove->close();
			$customersconn_mysqli->next_result();
			$var_Priority = 0;
			echo '<META http-equiv="Refresh" Content="0; account.php?contacts"';
		}else{
			$var_Retorno =  $customersconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>';
			echo '<META http-equiv="Refresh" Content="0; account.php"';
		}
	}
/*************************************  Remove the thing - End ********************************/

/*************************************  Update the thing - Start ********************************/
	if (isset($_GET['of_update'])){
		$query_rs_update = "Call customers.SetContacts('U', " 
												  . $var_NumUsuario . ", " 
												  . $var_Priority . ", '"
												  . $var_Address . "', "
												  . $var_AddressType . ", '"
												  . $var_NewValue . "', "
												  . $var_NewPrior . " );";
		/*echo '<script>DoAlert("' . $query_rs_update . '")</script>';*/ 	
		if ($rs_update = mysqli_query($customersconn_mysqli, $query_rs_update))
		{
			$row_rs_update = mysqli_fetch_row($rs_update);
			$var_Retorno = $row_rs_update['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_update->close();
			$customersconn_mysqli->next_result();
			$var_Priority = 0;
			echo '<META http-equiv="Refresh" Content="0; account.php?contacts"';
		}else{
			$var_Retorno =  $customersconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>';
			echo '<META http-equiv="Refresh" Content="0; account.php"';
		}
	}
/*************************************  Update the thing - End ********************************/

?>
<a name="filtro"></a>
<div id="contacthandel" style="background:url(images/backgrounds/WhiteStorm_big.png); width:1120px; height:825px" >
<a href="account.php" style="position:absolute">
	<img src="images/buttons/img_Volver.png"/>
</a>
<div id="header" class="pagetitle">
Organiza tus Contactos <?php echo  $var_Usuario	?> 
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
	 <input name="contacts" type="hidden" value="" />
     <br />
     <label style=" width:800px; height:60px; position:absolute; color:#FC0; margin-left:100px; 
     					font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 16px;">La prioridad que le des a tus contactos marcará el orden en que serás localizado. La prioridad uno (1) está siempre reservada al correo electrónico principal.</label>
     </br>   </br>
</div>     
	<?php 
        if ($pageNum_rs_contacts > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rs_contacts=%d%s", $currentPage, 0, $queryString_rs_contacts); ?>">
                    <img src="images/buttons/first.png" alt="Primera" width="32" height="32" border="0" >
        </a> 
        <a href="<?php printf("%s?pageNum_rs_contacts=%d%s", $currentPage, max(0, $pageNum_rs_contacts - 1),
																					$queryString_rs_contacts); ?>">
                <img src="images/buttons/previous.png" alt="Anterior" width="32" height="32" border="0" >
        </a>
    <?php } // Show if not first page ?>
      
    <?php if ($pageNum_rs_contacts < $totalPages_rs_contacts) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rs_contacts=%d%s", $currentPage, min($totalPages_rs_contacts, $pageNum_rs_contacts + 1), $queryString_rs_contacts); ?>">
                <img src="images/buttons/next.png" alt="Siguiente" width="32" height="32" border="0" >
      </a>
      <a href="<?php printf("%s?pageNum_rs_contacts=%d%s", $currentPage, $totalPages_rs_contacts, $queryString_rs_contacts); ?>">
                <img src="images/buttons/last.png" alt="Última" width="32" height="32" border="0" >
      </a>
    <?php }// Show if not last page ?>
    <table border="0" style="margin-left:auto; margin-right:auto; table-layout:fixed; overflow:hidden" >
      <tr class="tableheaders">
        <th>Prioridad</th>
        <th>Contacto</th>
        <th>Tipo Contacto</th>
        <th style="display:none;">Add type</th>
      </tr>
      <?php do { ?>
        <tr class="data">
            <td style="width:100px; font-weight:bold; text-align:center; "> 
                <a style="font-family: Verdana, Geneva, sans-serif; font-style:normal; color: #CCC;" 
                   href="account.php?contacts=''&AddPriority=<?php echo $row_rs_contacts[0] 
				   								?>&AddType=<?php echo $row_rs_contacts[3]
												?>&Address=<?php echo $row_rs_contacts[1]
												?>&AddTypeName=<?php echo $row_rs_contacts[2]
												?>#detalle">
                        <?php echo $row_rs_contacts[0]; ?> 
                 </a>
            </td>
			<td style="width:600px; font-weight:bold"><?php echo $row_rs_contacts[1]; ?></td>
          	<td style="width:250px; font-weight:bold"><?php echo $row_rs_contacts[2]; ?></td>
          	<td style="display:none;"><?php echo $row_rs_contacts[3]; ?></td>
        </tr>
        <?php } while ($row_rs_contacts = mysqli_fetch_row($rs_contacts)); ?>
    </table>
</form>
<?php 
	$rs_contacts->close();
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
<?php if ($var_Priority == 0) { //We're going to add a new contact

?>
    <form id="CreateForm" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
    	<input name="contacts" type="hidden" value="" />
    	<input name="AddPriority" type="hidden" value="0" />
	    <p style="position:absolute; left:215px;"  >
        <label style="color: #900; 
        			font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 24px;">Introduce el contacto</label>
        <br />
        <?php if ($var_SelAddType == '5' || $var_SelAddType == '8'){ ?>
        <input id="Email" name="Email" type="text" size="30" value="<?php echo $var_Email; ?>" 
                    class="data"/>
        <?php } ?>
        <?php if ($var_SelAddType != '5' && $var_SelAddType != '8'){ ?>
        <input id="SomeAdd" name="SomeAdd" type="text" size="30" value="<?php echo $var_SomeAdd; ?>" 
                    class="data"/>
        <?php } ?>
    	<label class="datalabel" style="font-size: 24px;">Tipo: 	&nbsp;</label>
        <select name="TipoAdd" size="1" class="data" onchange="form.action = window.location;this.form.submit();">  <!--"this.form.submit();"-->
          <option class="drop" value="" <?php if ($var_SelAddType == ''){ ?> selected="selected" <?php } ?>>Selecciona un tipo</option>
            <?php
            do {  
            ?>
            <option class="drop" value="<?php echo $row_rs_addtype[0]?>" 
            <?php if ($var_SelAddType == $row_rs_addtype[0]){ ?> 
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
			<?php if ($var_SelAddType == '1' || $var_SelAddType == '2' || $var_SelAddType == '4'){ ?>
                    <label style="color: #666; 
                        font-family: 'Comic Sans MS', cursive; font-size: 24px;">00 57 4 9999999</label>
             <?php } ?>
			<?php if ($var_SelAddType == '6'){ ?>
                    <label style="color: #666; 
                        font-family: 'Comic Sans MS', cursive; font-size: 24px;">www.misitio.com</label>
             <?php } ?>             
			<?php if ($var_SelAddType == '5' || $var_SelAddType == '8'){ ?>
                    <label style="color: #666; 
                        font-family: 'Comic Sans MS', cursive; font-size: 24px;">algun@dominio.com</label>
             <?php } ?>       
    	</p>
        <p style=" position:absolute; left:700px; top:650px;">
            <input  type="submit" name="of_create"  value="" class="botoncrear"/>
        </p>    
    </form> <!-- form id="UpdateForm"-->
    <script  type="text/javascript">
		var frmvalidator = new Validator("CreateForm");
		
		frmvalidator.addValidation("TipoAdd","req", "Selecciona un tipo de contacto");

		var correo = document.getElementById("Email");
		if (correo != null){
			frmvalidator.addValidation("Email","req", "Introduce un correo electrónico");
			frmvalidator.addValidation("Email","email", "Digita un correo válido");
		}
		
		var otroContacto = document.getElementById("SomeAdd");
		if (otroContacto != null){
			frmvalidator.addValidation("SomeAdd","req", "Introduce algun valor");
			frmvalidator.addValidation("SomeAdd","maxlen=100", "No excedas 100 caracteres");
		}
     </script>
<?php }else{ //We're going to modify an existing contact?>
    <form id="UpdateForm" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
    	<input name="contacts" type="hidden" value="" />
    	<input name="AddPriority" type="hidden" value="<?php echo $var_Priority ?>" />
    	<input name="Address" type="hidden" value="<?php echo $var_Address ?>" />
    	<input name="AddType" id="AddType" type="hidden" value="<?php echo $var_AddressType ?>" />
    	<input name="AddTypeName" type="hidden" value="<?php echo $var_AddTypeName ?>" />
	    <p style="position:absolute; left:150px; width:800px; height:300px;"  >
        <label class="datalabel" style=" font-size: 26px;">Contacto</label>
        <label class="datalabel" style=" font-size: 24px;">Prioridad  <?php echo $var_Priority ?></label>
        <br />
        <label class="datalabel" style="font-size: 24px;"><?php echo $var_AddTypeName . ' - ' ?></label>
        <label class="datalabel" style=" font-size: 24px;"><?php echo $var_Address ?> </label>
        <br />
        <input class="data" id="NewValue" name="NewValue" type="text" size="70" value="<?php echo $var_NewValue; ?>"/>

        </p>
        <p style=" position:absolute; left:800px; top:500px;">
    	<label class="datalabel" style="font-size: 24px;">Prioridad: 	&nbsp;</label>
        <select class="data" name="Prior" size="1" >
            <?php
            do {  
            ?>
            <option class="drop" value="<?php echo $row_rs_prior['co_AddressPriority']?>" 
            <?php if ($var_Priority == $row_rs_prior['co_AddressPriority']){ ?> 
                        selected="selected" <?php } ?> > <?php echo $row_rs_prior['co_AddressPriority']?>
            </option>
            <?php
            } while ($row_rs_prior = mysqli_fetch_row($rs_prior));
              $rows = mysqli_num_rows($rs_prior);
              if($rows > 0) {
                  mysqli_data_seek($rs_prior, 0);
                 $row_rs_prior = mysqli_fetch_row($rs_prior);
              }
            ?>
        </select>
            <br /><br />
            
        </p>
        <p style=" position:absolute; left:200px; top:600px;">
            <input  type="submit" name="of_update"  value="" class="botonactualizar"/>
			<?php if ($var_Priority >= 2) { //You cannot remove the first contact.
            
            ?>
            <input  type="submit" name="of_remove"  value="" class="botonremover"/> 
			<?php } ?>

            <input  type="submit" name="of_clear"  value="" class="botonlimpiar"/> 

        </p>
     </form>
    <script  type="text/javascript">
		var frmvalidator = new Validator("UpdateForm");
		frmvalidator.addValidation("NewValue","maxlen=100", "No excedas 100 caracteres");
		
		var campo = document.getElementById("AddType");
		if (campo.value == 5 || campo.value == 8){
			frmvalidator.addValidation("NewValue","email", "Digita un correo válido");
		}

     </script>

<?php } ?>

</div>
</div>
