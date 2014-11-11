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
	
	$var_Usuario = '';
	$var_NumUsuario = 0;
	
	$var_Way = 'All';
	$var_Status = 'A';

	$var_Sender = '';
	$var_Receiver = '';
	$var_Subject = '';
	$var_Body = '';

	$var_SendTime = '';
	$var_RetrievedSender = '';
	$var_RetrievedReceiver = '';
	$var_RetrievedSubject = '';

	$var_ReplyTo = '';
	
	$var_RetrievedBody = '';

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

	if (isset($_GET['Way'])) {
		if($_GET['Way'] != ''){
			$var_Way = $_GET['Way'];
		}
	}

	if (isset($_GET['Status'])){
		if($_GET['Status'] != ''){
			$var_Status = $_GET['Status'];
		}
	}

	if (isset($_GET['Sender'])){
		if($_GET['Sender'] != ''){
			$var_Sender = $_GET['Sender'];

		}
	}
	
	if (isset($_GET['Receiver'])){
		if($_GET['Receiver'] != ''){
			$var_Receiver = $_GET['Receiver'];

		}
	}
	
	if (isset($_GET['Subject'])){
		if($_GET['Subject'] != ''){
			$var_Subject = $_GET['Subject'];
		}
	}
	
	if (isset($_GET['Body'])){
		if($_GET['Body'] != ''){
			$var_Body = $_GET['Body'];
		}
	}

	if (!isset($_GET['Nuevo'])){
		if (isset($_GET['SendTime'])){
			if($_GET['SendTime'] != ''){
				$var_SendTime = $_GET['SendTime'];
	
			}
		}
	}
	if (isset($_GET['ReplyTo'])){
		if($_GET['ReplyTo'] != ''){
			$var_ReplyTo = $_GET['ReplyTo'];

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

/*************** This part is for the Retrieved Message only ***********************/
if ($var_SendTime != ''){
	//mysql_select_db($database_customersconn, $customersconn);
	
	$query_rs_retmsg = "Select ms_Subject, ms_Body 
						  From customers.cu_messages 
						 Where ms_SendTime = '" . $var_SendTime . "' 
						   And ms_Sender = '"   . $var_Sender . "'  
						   And ms_Receiver = '" . $var_Receiver . "';";

	$rs_retmsg = mysqli_query($customersconn_mysqli, $query_rs_retmsg) or die(mysqli_error());
	$row_rs_retmsg = mysqli_fetch_row($rs_retmsg);
	
	$var_RetrievedSubject = $row_rs_retmsg[0];
	$var_RetrievedBody =  $row_rs_retmsg[1];

	$var_ReplyTo = $var_SendTime . '/From:' . $var_Sender . '/To:' . $var_Receiver;
	$var_RetrievedSender = $var_Sender;
	$var_RetrievedReceiver = $var_Receiver;
	
}


/*************************************  Delete the message - Start ********************************/
	if (isset($_GET['Eliminar'])){
		$query_rs_delete = "Call customers.SetMessages('D', '"
												  . $var_SendTime . "', '"
												  . $var_RetrievedSender . "', '" 
												  . $var_RetrievedReceiver . "', '', '', '" . $var_Usuario . "');";
		/*echo '<script>DoAlert("' . $query_rs_delete . '")</script>'; */	
		if ($rs_delete = mysqli_query($customersconn_mysqli, $query_rs_delete))
		{
			$row_rs_delete = mysqli_fetch_row($rs_delete);
			$var_Retorno = $row_rs_delete['1'];
			/*echo '<script>DoAlert("' . $var_Retorno . '")</script>'; */
			$rs_delete->close();
			$customersconn_mysqli->next_result();
			$var_SendTime = '';
			$var_Receiver = '';
		}else{
			$var_Retorno =  $customersconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>';
			echo '<META http-equiv="Refresh" Content="0; index.php"';
		}
		/*$customersconn_mysqli->next_result();*/
	}
/************************************* Delete the message - End ********************************/
/*************************************  Update the message - Start ********************************/
	if ($var_SendTime != '' && $var_Receiver == $var_Usuario){
		$query_rs_update = "Call customers.SetMessages('U', '"
												  . $var_SendTime . "', '"
												  . $var_RetrievedSender . "', '" 
												  . $var_RetrievedReceiver . "', '', '', '');";
		/*echo '<script>DoAlert("' . $query_rs_update . '")</script>'; */	
		if ($rs_update = mysqli_query($customersconn_mysqli, $query_rs_update))
		{
			$row_rs_update = mysqli_fetch_row($rs_update);
			$var_Retorno = $row_rs_update['1'];
			/*echo '<script>DoAlert("' . $var_Retorno . '")</script>'; */
			$rs_update->close();
			$customersconn_mysqli->next_result();
			
		}else{
			$var_Retorno =  $customersconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>';
			echo '<META http-equiv="Refresh" Content="0; index.php"';
		}
		/*$customersconn_mysqli->next_result();*/
	}
/************************************* Update the message - End ********************************/
/*************************************  Send the message - Start ********************************/
	if (isset($_GET['GoFor_It'])){
		$var_Enviado = date("Y-m-d H:i:s");
		$query_rs_create = "Call customers.SetMessages('C', '"
												  . $var_Enviado . "', '"
												  . $var_Usuario . "', '" 
												  . $var_Receiver . "', '"
												  . $var_Subject . "', '"
												  . $var_Body . "', '"
												  . $var_ReplyTo . "');";
		/*echo '<script>DoAlert("' . $query_rs_create . '")</script>';*/ 	
		if ($rs_create = mysqli_query($customersconn_mysqli, $query_rs_create))
		{
			$row_rs_create = mysqli_fetch_row($rs_create);
			$var_Retorno = $row_rs_create['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_create->close();
			$customersconn_mysqli->next_result();
			$var_Priority = 0;
			echo '<META http-equiv="Refresh" Content="0; comunity.php?#filtro"';
		}else{
			$var_Retorno =  $customersconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>';
			echo '<META http-equiv="Refresh" Content="0; account.php"';
		}
		/*$customersconn_mysqli->next_result();*/
	}
/************************************* Send the message - End ********************************/
	
/****************************  Collectables Handling - Start ***********************************/
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rs_messages = 7;
$pageNum_rs_messages = 0;
if (isset($_GET['pageNum_rs_messages'])) {
  $pageNum_rs_messages = $_GET['pageNum_rs_messages'];
}
$startRow_rs_messages = $pageNum_rs_messages * $maxRows_rs_messages;

	$query_rs_messages = 
		"Call customers.GetMessages(0, 0, '" . $var_Usuario . "', '" . $var_Way . "', '" . $var_Status . "');";
		/*echo '<script>DoAlert("' . $query_rs_messages . '")</script>';*/
	if ($rs_messages = mysqli_query($customersconn_mysqli, $query_rs_messages))
	{
		if (isset($_GET['totalRows_rs_messages'])) {
		  $totalRows_rs_messages = $_GET['totalRows_rs_messages'];
		} else {
		
		  $totalRows_rs_messages = $rs_messages->num_rows; 
		}
	}
	$totalPages_rs_messages = ceil($totalRows_rs_messages/$maxRows_rs_messages)-1;
	$rs_messages->close();
	$customersconn_mysqli->next_result();
	$query_rs_messages = 
		"Call customers.GetMessages(" .$startRow_rs_messages .", " . $maxRows_rs_messages . ", '" 
							    . $var_Usuario . "', '" . $var_Way . "', '" . $var_Status . "');";
		
	if ($rs_messages = mysqli_query($customersconn_mysqli, $query_rs_messages))
	{
		$row_rs_messages = mysqli_fetch_row($rs_messages);
	}else{
		print $customersconn_mysqli->error."<br />";
	}

$queryString_rs_messages = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
	if (stristr($param, "pageNum_rs_messages") == false && 
		stristr($param, "totalRows_rs_messages") == false) {
	  array_push($newParams, $param);
	}
  }
  if (count($newParams) != 0) {
	$queryString_rs_messages = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_messages = sprintf("&totalRows_rs_messages=%d%s", $totalRows_rs_messages, $queryString_rs_messages);
/****************************  Collectables Handling - End ***********************************/

?>

<div id="header" class="pagetitle" style="color:#999">
.
   
 <a href="comunity.php?mensajes" style="position:absolute; top:0px; left:0px;">
 <img 
 	src="images/buttons/mnu_mensajes_normal.png" onmouseout="this.src='images/buttons/mnu_mensajes_normal.png'" 
                                                onmouseover="this.src='images/buttons/mnu_mensajes_hover.png'" 
            alt="Crear" border="0" />
 </a>
 <a href="comunity.php?foro" style="position:absolute; top:0px; left:280px;">
 <img 
 	src="images/buttons/mnu_foro_normal.png" onmouseout="this.src='images/buttons/mnu_foro_normal.png'" 
                                                onmouseover="this.src='images/buttons/mnu_foro_hover.png'" 
            alt="Crear" border="0" />
 </a>
 <a href="comunity.php?chat" style="position:absolute; top:0px; left:560px;">
 <img 
 	src="images/buttons/mnu_chat_normal.png" onmouseout="this.src='images/buttons/mnu_chat_normal.png'" 
                                                onmouseover="this.src='images/buttons/mnu_chat_hover.png'" 
            alt="Crear" border="0" />
 </a>
 <a href="comunity.php?soporte" style="position:absolute; top:0px; left:840px;">
 <img 
 	src="images/buttons/mnu_soporte_normal.png" onmouseout="this.src='images/buttons/mnu_soporte_normal.png'" 
                                                onmouseover="this.src='images/buttons/mnu_soporte_hover.png'" 
            alt="Crear" border="0" />
 </a>

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
    <label class="datalabel" style="font-size: 24px; color:#FC0">Sentido
        <select name="Way" size="1" class="anydata" 
        onchange="location.href='comunity.php?Status=<?php echo $var_Status ?>&Way='+this.value+'#filtro'">
          <option class="anydrop" value="All" <?php if ($var_Way == 'All'){ ?> selected="selected" <?php } ?>>Todos</option>
          <option class="anydrop" value="Sent" <?php if ($var_Way == 'Sent'){ ?> selected="selected" <?php } ?>>Enviados</option>
          <option class="anydrop" value="Rec" <?php if ($var_Way == 'Rec'){ ?> selected="selected" <?php } ?>>Recibidos</option>
      </select>
     </label>
    <label class="datalabel" style="font-size: 24px; color:#FC0">Estado
        <select name="Status" size="1" class="anydata" 
        onchange="location.href='comunity.php?Way=<?php echo $var_Way ?>&Status='+this.value+'#filtro'">
          <option class="anydrop" value="A" <?php if ($var_Status == 'A'){ ?> selected="selected" <?php } ?>>Todos</option>
          <option class="anydrop" value="R" <?php if ($var_Status == 'R'){ ?> selected="selected" <?php } ?>>Leídos</option>
          <option class="anydrop" value="U" <?php if ($var_Status == 'U'){ ?> selected="selected" <?php } ?>>No Leídos</option>
      </select>
     </label>

	</div>
<br /><br />
<?php 
	if ($pageNum_rs_messages > 0) { // Show if not first page ?>
	<a href="<?php printf("%s?pageNum_rs_messages=%d%s", $currentPage, 0, $queryString_rs_messages); ?>">
				<img src="images/buttons/first.png" alt="Primera" width="32" height="32" border="0" >
	</a> 
	<a href="<?php printf("%s?pageNum_rs_messages=%d%s", $currentPage, max(0, $pageNum_rs_messages - 1), $queryString_rs_messages); ?>">
  			<img src="images/buttons/previous.png" alt="Anterior" width="32" height="32" border="0" >
	</a>
<?php } // Show if not first page ?>
  
<?php if ($pageNum_rs_messages < $totalPages_rs_messages) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_rs_messages=%d%s", $currentPage, min($totalPages_rs_messages, $pageNum_rs_messages + 1), $queryString_rs_messages); ?>">
  			<img src="images/buttons/next.png" alt="Siguiente" width="32" height="32" border="0" >
  </a>
  <a href="<?php printf("%s?pageNum_rs_messages=%d%s", $currentPage, $totalPages_rs_messages, $queryString_rs_messages); ?>">
  			<img src="images/buttons/last.png" alt="Última" width="32" height="32" border="0" >
  </a>
<?php }// Show if not last page ?>

  <table border="0" style="margin-left:auto; margin-right:auto; table-layout:fixed; overflow:hidden" >
  <tr style="font-size:30px;">
    <th>Momento</th>
    <th>Visto</th>
    <th>De</th>
    <th>Para</th>
    <th>Asunto</th>
  </tr>
  <?php do { ?>
    <tr class="anydata">
      <td style="width:200px; font-weight:bold">
      			<a style="font-family: Verdana, Geneva, sans-serif; font-style:normal; color: #CCC;" 
                   href="comunity.php?SendTime=<?php echo $row_rs_messages[0] 
				   					?>&Sender=<?php echo $row_rs_messages[1]  
									?>&Receiver=<?php echo $row_rs_messages[2] 
									?>&Status=<?php echo $var_Status 
									?>&Way=<?php echo $var_Way 
									?>#detalle">
	  						<?php echo $row_rs_messages[0]; ?>
                </a></td>
      <td style="width:50px; font-weight:bold; text-align:center">
	  			<?php  if ($row_rs_messages[0] != ''){ if ($row_rs_messages[4] == 'R'){ ?>Sí<?php }else{?>No<?php } }?>
      
      		</td>          
      <td style="width:150px; font-weight:bold"><?php echo $row_rs_messages[1]; ?></td>
      <td style="width:150px; font-weight:bold"><?php echo $row_rs_messages[2] ?></td>
      <td style="width:400px; font-weight:bold"><?php echo $row_rs_messages[3] ?></td>
    </tr>
    <?php } while ($row_rs_messages = mysqli_fetch_row($rs_messages)); ?>
</table>
</form>
<?php
	$rs_messages->close();
	$customersconn_mysqli->next_result();
/*mysqli_free_result($rs_messages);*/
/*mysql_free_result($rs_messages);*/

?>

    <a name="detalle"></a>
    <form id="SendMail" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
       <p style="position:absolute; width:1050px; left:40px; top:500px;"  >
       <label class="datalabel" style="font-size: 16px; color:#FC0">Destino</label>
       <br />
       <?php if($var_SendTime != '') { ?>
       	   <input name="Receiver" type="hidden" value="<?php echo $var_RetrievedReceiver ?>"/>
           <input name="Subject" type="hidden" value="<?php echo $var_RetrievedSubject ?>"/>
           <input name="ReplyTo" type="hidden" value="<?php echo $var_ReplyTo ?>"/>
	       <label class="datalabel" style="font-size: 16px; color:#FFF"><?php echo $var_RetrievedReceiver ?></label>
       <?php }else{ ?>
    	   <input id="Receiver" name="Receiver" class="anydata" value="<?php echo $var_Receiver ?>"/>
       <?php } ?>
       <br />
       <label class="datalabel" style="font-size: 16px; color:#FC0">Asunto</label>
       <br />
       <?php if($var_SendTime != '') { ?>
	       <label class="datalabel" style="font-size: 16px; color:#FFF"><?php echo $var_RetrievedSubject ?></label>
       <?php }else{ ?>
    	   <input id="Subject" name="Subject" class="anydata" value="<?php echo $var_Subject ?>"/>
       <?php } ?>
       <br />
       <?php if($var_SendTime != '') { ?>
           <label class="datalabel" style="font-size: 16px; color:#FC0">Texto</label>
           <br />
           <label class="datalabel" style="font-size: 16px; color:#FFF; width:600px;"><?php echo $var_RetrievedBody ?></label>
       <?php } ?>
       <br />
       <label class="datalabel" style="font-size: 16px; color:#FC0">Escribe</label>
       <br />
       <textarea name="Body" id="Body" cols="100" rows="4" class="anydata"  value="<?php echo $var_Body ?>"
        style="resize:none; font-size: 12px;"></textarea>

       <br />

      </p>
     <p style=" position:absolute; left:600px; top:500px;">
		<?php if ($var_SendTime != '') { ?>
        	
            <input  type="submit" name="GoFor_It"  value="" class="botonresponder"/> 
	    <?php }else{ ?>
           <input  type="submit" name="GoFor_It"  value="" class="botonenviar"/> 
    	<?php } ?>
     </p>    
   </form>
	<script  type="text/javascript">
        var frmvalidator = new Validator("SendMail");
        frmvalidator.addValidation("Receiver","req", "Introduce un usuario destino");
        frmvalidator.addValidation("Subject","req", "Introduce un usuario asunto");
        frmvalidator.addValidation("Receiver","maxlen=15", "El destino no puede superar 15 caracteres");
        frmvalidator.addValidation("Subject","maxlen=45", "El asunto no puede superar 45 caracteres");
        frmvalidator.addValidation("Body","maxlen=255", "El texto no puede superar 255 caracteres");
		
    </script>
	<?php if ($var_SendTime != '') { ?>
        <form id="Mail" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
			<input name="SendTime" type="hidden" value="<?php echo $var_SendTime ?>"/>
			<input name="Sender" type="hidden" value="<?php echo $var_RetrievedSender ?>"/>
			<input name="Receiver" type="hidden" value="<?php echo $var_RetrievedReceiver ?>"/>
            <p style=" position:absolute; left:345px; top:500px;">
                <input  type="submit" name="Eliminar"  value="" class="botonremover"/>
            </p>
            <p style=" position:absolute; left:850px; top:500px;">
                <input  type="submit" name="Nuevo"  value="" class="botonlimpiar"/>
            </p>
        </form>
    <?php } ?>
</div>