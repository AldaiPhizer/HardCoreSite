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
	$var_CuantosPersonajes = 0;
	$var_UserGold = 0;
	$var_NewCharacterPrice = 0;
	
	$var_GameServer = '';
	$var_GameName = 'Auriga';

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

/*************** This part is for the User Number only ***********************/
//	mysql_select_db($database_customersconn, $customersconn);
	$query_rs_cusnum = "Select cu_number 
						  From customers.cu_head
						 Where cu_Aka = '". $var_Usuario . "';";
	$rs_cusnum = mysqli_query($customersconn_mysqli, $query_rs_cusnum) or die(mysqli_error());
	$row_rs_cusnum = mysqli_fetch_row($rs_cusnum);
	
	$var_NumUsuario = $row_rs_cusnum[0];

/************************************** This part is for the Servers list box only *************/
	//mysqli_select_db($gamesconn_mysqli, $database_gamesconn);
	$query_rs_server = "select sv_Name from gm_servers Where sv_GameName = '" . $var_GameName . "' Order by sv_Name";
	$rs_server = mysqli_query($gamesconn_mysqli, $query_rs_server) or die(mysqli_error());
	$row_rs_server = mysqli_fetch_row($rs_server);
	$totalRows_rs_server = 0;
	if (mysqli_num_rows($rs_server) > 0 ){
		$totalRows_rs_server = mysqli_num_rows($rs_server);
	}


/************************************* This part is for the amount of players Only **********************/
	if ($var_NumUsuario != 0) {
		//mysql_select_db($database_gamesconn, $gamesconn);
		$query_rs_prior = "Select ch_Name 
							 From games.gm_characters Join customers.cu_head
							   						    On ch_CustomerNumber = cu_Number
													  Join games.gm_servers
														On ch_ServerName = sv_Name
							 Where cu_Aka = '" . $var_Usuario . "'
							  And sv_GameName = '" . $var_GameName . "'";

		$rs_prior = mysqli_query($gamesconn_mysqli, $query_rs_prior) or die(mysqli_error());
		$row_rs_prior = mysqli_fetch_row($rs_prior);
		$var_CuantosPersonajes = 0;
		if (mysqli_num_rows($rs_prior) > 0){
			$var_CuantosPersonajes = mysqli_num_rows($rs_prior);
		}
	}

/*************************************  Create the player - Start ********************************/
	if (isset($_GET['Create_It'])){
		$query_rs_create = "Call games.SetPlayers('C', '" 
												  . $var_Usuario . "', '" 
												  . $var_GameServer . "', '"
												  . $var_PlayerName . "');";
		/*echo '<script>DoAlert("' . $query_rs_create . '")</script>'; */	
		if ($rs_create = mysqli_query($gamesconn_mysqli, $query_rs_create))
		{
			$row_rs_create = mysqli_fetch_row($rs_create);
			$var_Retorno = $row_rs_create['1'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_create->close();
			$gamesconn_mysqli->next_result();
			echo '<META http-equiv="Refresh" Content="0; Auriga.php?#detalle"';
		}else{
			$var_Retorno =  $gamesconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>';
			echo '<META http-equiv="Refresh" Content="0; account.php"';
		}
	}
/*************************************  Create the player - End ********************************/



?>
    <a href="index.php" style="position:absolute; top:-25px; ">
        <img src="images/buttons/img_Volver.png"/>
    </a>
<a name="filtro"></a>
<div id="gamehandel" style="background:url(images/backgrounds/Auriga_Wallpaper1.png); width:1120px; height:825px" >

<br />
    


    <div id="upperpane" style="	height: 550px;
                                width: 1092px;
                                margin-top: 2px;
                                margin-left:10px;
                                border-top-width: 2px;
                                border-right-width: 1px;
                                border-bottom-width: 2px;
                                border-left-width: 3px;

    ">
    <div>
    
         <label style=" width:700px; height:60px; position:absolute; color:#FC0; left:200px; top:500px; 
                            font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 16px;">Juega gratis. Con tu inscripción recibirás un par de caballos, un carruaje y un puñado de monedas para que inicies tu carrera en el competitivo mundo de los Aurigas.</label>
         </br>   </br>
         <?php if ($var_NumUsuario == 0){ ?>
         <label style=" width:700px; height:60px; position:absolute; color:#FC0; left:200px; top:550px;
                            font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 16px;">Antes de descargar el juego debes <a href="account.php" style="color:#FC0; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif"> crear un usuario.</a></label>
		 <?php } ?>
        </br>   </br>
    </div>     
    
    
    
    <?php if ($var_NumUsuario != 0) { //El usuario ya está logeado
    
    ?>
        <form id="CreatePlayer" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
            <p style="position:absolute; left:200px; top:600px;"  >
            
                <label class="datalabel" style="font-size: 24px; ">Servidor 	&nbsp;</label>
                <select name="Servers" size="1" class="aurigadata" 
                onchange="location.href='Auriga.php?Servers='+this.value+'#detalle'">
              <!-- "form.action = window.location;this.form.submit();" -->
                  <option class="aurigadrop" value="" <?php if ($var_GameServer == ''){ ?> selected="selected" <?php } ?>>
                  																							Selecciona un servidor</option>
                    <?php
                    do {  
                    ?>
                    <option class="aurigadrop" value="<?php echo $row_rs_server[0]?>" 
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
                <br />
                <label class="datalabel" style="font-size: 24px;">
                			Introduce el nombre requerido</label>
                <br />
                <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                <input id="PlayerName" name="PlayerName" type="text" size="36" value="<?php echo $var_PlayerName; ?>" 
                            class="aurigadata"/>
         
            </p>
            <p style=" position:absolute; left:250px; top:700px;">
            <a name="detalle"></a>
                <input  type="submit" name="Create_It"  value="" class="botoncrear"/>
            </p>
            <?php if ($var_PlayerName != '' || $var_CuantosPersonajes > 0) { ?>
                <p style=" position:absolute; left:600px; top:700px;">
                    <input  type="submit" name="Download_It"  value="" class="botondownload1"/> <!-- Es Uno no "ele"-->
                </p>    
			<?php } ?>
        </form> <!-- form id="CreatePlayer"-->
        <script  type="text/javascript">
            var frmvalidator = new Validator("CreatePlayer");
            
            frmvalidator.addValidation("Servers","req", "Selecciona un servidor");
            frmvalidator.addValidation("PlayerName","req", "Introduce un nombre válido");
            frmvalidator.addValidation("PlayerName","maxlen=35", "No puiedes pasar de 35 caracteres");
        </script>
    <?php } //El usuario ya está logeado
    ?>

	</div>	<!--id="upperpane"-->
</div>	<!--id="gamehandel"-->
