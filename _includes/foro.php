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

	if (isset($_SESSION['usuario']))
	{
		if ($_SESSION['usuario'] <> 'usuario'){
			$var_Usuario = $_SESSION['usuario'];
		}
		else{
			$var_Usuario = '';
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

</div>