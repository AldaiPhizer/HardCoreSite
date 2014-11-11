<script>
function DoAlert(message)
{
	alert(message); // this is the message in ""
}

</script>


<?php 
	include_once('/Connections/customersconn.php'); 
	include_once('/Connections/gamesconn.php'); 
	
	if (!isset($_SESSION))
	{
		session_start(); //Iniciamos la Sesion o la Continuamos
	}
/*	if (isset($_GET['logout']))
	{
		session_unset();
		session_destroy();
	}	*/
	$var_Usuario = '';
	$var_NumUsuario = 0;
	$var_Bronze = 0;
	$var_Silver = 0;
	$var_Gold = 0;
	$var_USD = 0.00;
/****************************************  Validation - Start **********************************/	
	if (isset($_POST['userName']) && isset($_POST['userPassword']))
	{
		/*This part is for the User Validation only*/
		//mysql_select_db($database_customersconn, $customersconn);
		$query_rs_user = "select 
							cu_Aka, 
							cu_Bronze, 
							cu_Silver, 
							cu_Gold, 
							(select my_Amount from customers.cu_money where my_CustomerId = A.cu_Number and my_Currency = 'USD') As Dollars 
							  from customers.cu_head A
							 where cu_Aka = '" . $_POST['userName'] . "'
							   and cu_Password = '" . $_POST['userPassword'] . "';";

		$rs_user = mysqli_query($customersconn_mysqli, $query_rs_user) or die(mysqli_error());
		$row_rs_user = mysqli_fetch_row($rs_user);
		$totalRows_rs_user = mysqli_num_rows($rs_user);
		if ($totalRows_rs_user > 0){
			$_SESSION['usuario'] = $_POST['userName'] ;
			$var_Usuario = $_SESSION['usuario'];
			$_SESSION['usr_Bronze'] = $row_rs_user[1];
			$_SESSION['usr_Silver'] = $row_rs_user[2];
			$_SESSION['usr_Gold'] = $row_rs_user[3];
			$_SESSION['usr_USD'] = $row_rs_user[4];
			
			$var_Bronze = $_SESSION['usr_Bronze'];
			$var_Silver = $_SESSION['usr_Silver'];
			$var_Gold = $_SESSION['usr_Gold'];
			$var_USD = $_SESSION['usr_USD'];
			echo '<META http-equiv="Refresh" Content="0; account.php"';
		}else{
			echo '<script>DoAlert("Usuario y/o contraseña inválidos")</script>';
		}
		mysqli_free_result($rs_user);
	}
/****************************************  Validation - End **********************************/		
/****************************************  Request Name - Start **********************************/	
	if (isset($_POST['verificar'])){
		/*This part is for the User Request only*/
		//mysql_select_db($database_customersconn, $customersconn);
		$query_rs_requser = "select cu_Aka from customers.cu_head where cu_Aka = '" . $_POST['userReqName'] . "';";

		$rs_requser = mysqli_query($customersconn_mysqli, $query_rs_requser) or die(mysqli_error());
		$row_rs_requser = mysqli_fetch_row($rs_requser);

		$totalRows_rs_requser = mysqli_num_rows($rs_requser);
		mysqli_free_result($rs_requser);
		if ($totalRows_rs_requser == 0){
			$_SESSION['UsuarioRequerido'] = $_POST['userReqName'];
			/***************************  Place Call SetUsers here *********************************/
			$query_rs_reserve = "Call customers.SetUsers('R', '" 
													  . $_POST['userReqName'] . "', "
													  . 0 . ", '"
													  . '' . "', '" 
													  . '' . "', '" 
													  . '' . "', '" 
													  . '' . "', '" 
													  . '' . "', '" 
													  . '' . "', '" 
													  . '' . "', '"  
													  . '' . "', '" 
													  . '' . "', " 
													  . 0  . ", '" 
													  . '' . "',  '" 
													  . '' .
												"');";
			/*echo '<script>DoAlert("' . $query_rs_reserve . '")</script>'; */
			if ($rs_reserve = mysqli_query($customersconn_mysqli, $query_rs_reserve))
			{
				$row_rs_reserve = mysqli_fetch_row($rs_reserve);
				$var_Retorno = $row_rs_reserve['1'];
				$var_NumUsuario = $row_rs_reserve['2'];
				echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
				$rs_reserve->close();
				$customersconn_mysqli->next_result();
				echo '<META http-equiv="Refresh" Content="0; account.php?CrearUsuario="';
			}else{
				$var_Retorno =  $gamesconn_mysqli->error;
				echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			}
			$_SESSION['NumUser'] = $var_NumUsuario;
			/***************************  Place Call SetUsers here *********************************/
			
		}else{
			echo '<script>DoAlert("Debes ser más original.  Ese nombre ya fue tomado.")</script>';
		}

	}
/****************************************  Request Name - End **********************************/	
	if (isset($_SESSION['usuario']))
	{
		if ($_SESSION['usuario'] <> 'usuario'){
			$var_Usuario = $_SESSION['usuario'];
			$var_Bronze = $_SESSION['usr_Bronze'];
			$var_Silver = $_SESSION['usr_Silver'];
			$var_Gold = $_SESSION['usr_Gold'];
			$var_USD = $_SESSION['usr_USD'];
/****************************  Collectables Handling - Start ***********************************/
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
				$totalRows_rs_worlds = 0;
				if ($rs_worlds = mysqli_query($gamesconn_mysqli, $query_rs_worlds))
				{
					if (isset($_GET['totalRows_rs_worlds'])) {
					  $totalRows_rs_worlds = $_GET['totalRows_rs_worlds'];
					} else {
					
					  $totalRows_rs_worlds = mysqli_num_rows($rs_worlds); 
					}

					$totalPages_rs_worlds = ceil($totalRows_rs_worlds/$maxRows_rs_worlds)-1;
					$rs_worlds->close();
					$gamesconn_mysqli->next_result();
				}				
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
/****************************  Collectables Handling - End ***********************************/
		} /* $var_Usuario asignado: $_SESSION['usuario'] <> 'usuario'*/
	}	/* isset($_SESSION['usuario'])*/								

?>

	<div id="loginarea" style="position: absolute; margin-left: 10px; margin-top: 5px; width: 540px; height: 200px; 
        							background-image:url(images/backgrounds/WhiteStorm_sml.png)" >
            <img src="images/buttons/yo_soy.png" width="540" height="50" alt="Yo soy" />
            <form id="form1" name="form1" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
                <?php if (!isset($_SESSION['usuario'])){ ?>
				<span id="userfield" style="margin-left:80px">
                  <label style="color:#900; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 24px;">Usuario
                    <input class="data" type="text" name="userName" id="txt_usuario" tabindex="1" />
                  </label>
				</span>
              <br />
              <span id="passwordfield" style="margin-left:42px">
              <label style="color: #900; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 24px;">Contraseña
                <input class="data" type="password" name="userPassword" id="txt_password" tabindex="2" />
              </label>
			  </span>
              <br />
              <input type="submit" name="entrar"  value="" class="botonentrar" style="margin-left:180px" />
              <?php }else{ ?>
              <span id="userdata" style="margin-left:150px">
                  <label style="color:#600; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 36px; text-align:center">
				  			<?php echo $var_Usuario ?></label>
                  <br />
                  <p style="width:400px; font-weight:bold; color:#000; text-align:center; padding-left:40px;">
                   <label style="color: #600; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; 
                   						font-size: 30px; text-align:center">Saldos:</label>
                    <em style="color:#C60; font-family: 'Comic Sans MS', cursive; font-size: 30px;"><?php echo $var_Bronze ?></em> /
                    <em style="color:#666; font-family: 'Comic Sans MS', cursive; font-size: 30px;"><?php echo $var_Silver ?></em> /
                    <em style="color:#960; font-family: 'Comic Sans MS', cursive; font-size: 30px;"><?php echo $var_Gold ?></em> /
                    <em style="color:#030; font-family: 'Comic Sans MS', cursive; font-size: 30px;"><?php echo $var_USD ?></em>
                  </p>
               </span>    
                <?php }   /*$var_Usuario == ''*/  ?>              
            </form>
<script  type="text/javascript">
	var frmvalidator = new Validator("form1");
	frmvalidator.addValidation("userName","req", "Introduce tu usuario");
	frmvalidator.addValidation("userPassword","req", "Introduce tu contrtaseña");
</script>
      </div>
      <?php if ($var_Usuario == ''){ ?>
      	<div id="signinarea" style="position: absolute; margin-left: 10px; margin-top: 5px; left: 557px; width: 540px; height: 200px; 
        							background-image:url(images/backgrounds/WhiteStorm_sml.png)">
			<img src="images/buttons/quiero_ser.png" width="540" height="50" alt="Quiero ser" />
            <form id="form2" name="form2" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
				<span id="userfield" style="margin-left:80px">
                  <label style="color: #600; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-size: 24px;">Usuario
                    <input class="data" type="text" name="userReqName" id="txt_reqUsuario" tabindex="3" />
                  </label>
				</span>
              <br />
              <br />
	            <input name="verificar" type="submit" class="botonverificar" style="margin-left:100px" value="" />
            </form>
<script  type="text/javascript">
	var frmvalidator = new Validator("form2");
	frmvalidator.addValidation("userReqName","req", "Introduce el usuario requerido");
	frmvalidator.addValidation("userReqName","maxlen=15", "No más de 15 caracteres, por favor");
</script>
        </div>
	<?php }else{ ?>
      	<div id="managearea" style="position: absolute; margin-left: 3px; margin-top: 5px; left: 557px; width: 540px; height: 200px; 
        							background-image:url(images/backgrounds/WhiteStorm_sml.png)">
			<img src="images/buttons/Actualiza_Datos.png" width="540" height="50" alt="Update" />
            <form id="form3" name="form3" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
                <input name="intercambia" type="submit" class="botonintercambia" style="margin-left:115px" value="" />
                <br />
                <input name="admoney" type="submit" class="botonmoneyadmin" style="margin-left:115px" value="" />
                <br />
                <input name="contacts" type="submit" class="botoncontactos" style="margin-left:115px" value="" />
            </form>

        </div>
     <?php } ?>
        <div id="myworldsarea" style="position: absolute; margin-left: 10px; margin-top: 5px; top: 220px; 
        							  width: 1090px; height: 600px;
        							  background-image:url(images/backgrounds/WhiteStorm_mid.png);">
        	<img src="images/buttons/mis_mundos.png" width="1090" height="100" alt="Mis Mundos" />
            <br />
<label style=" width:700px; height:60px; padding-left:180px; position:absolute; 
			color:#300; text-align:justify; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif ; font-size: 16px;">Para crear nuevos personajes debes ir a la página "Mundos" y seleccionar el juego donde deseas crear tu nuevo personaje.  Recuerda que para crear más de un personaje por juego es necesario colocar Oro en tu cuenta.</label>
<br /><br /><br /><br />
&nbsp;&nbsp;
            <?php 
			if ($var_Usuario != ''){
                if ($pageNum_rs_worlds > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rs_worlds=%d%s", $currentPage, 0, $queryString_rs_worlds); ?>">
                            <img src="images/buttons/first.png" alt="Primera" width="32" height="32" border="0" >
                </a> 
                <a href="<?php printf("%s?pageNum_rs_worlds=%d%s", $currentPage, 
									  max(0, $pageNum_rs_worlds - 1), $queryString_rs_worlds); ?>">
                        <img src="images/buttons/previous.png" alt="Anterior" width="32" height="32" border="0" >
                </a>
            <?php } // Show if not first page ?>
              
            <?php if ($pageNum_rs_worlds < $totalPages_rs_worlds) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_rs_worlds=%d%s", $currentPage, 
									min($totalPages_rs_worlds, $pageNum_rs_worlds + 1), $queryString_rs_worlds); ?>">
                        <img src="images/buttons/next.png" alt="Siguiente" width="32" height="32" border="0" >
              </a>
              <a href="<?php printf("%s?pageNum_rs_worlds=%d%s", $currentPage, $totalPages_rs_worlds, $queryString_rs_worlds); ?>">
                        <img src="images/buttons/last.png" alt="Última" width="32" height="32" border="0" >
              </a>
            <?php }// Show if not last page ?>  
            <table border="0" style="margin-left:auto; margin-right:auto; table-layout:fixed; overflow:hidden" >
              <tr class="tableheaders">
                <th>Mundo</th>
                <th>Personaje</th>
                <th>Servidor</th>
                <th>Nivel</th>
                <th>Dinero</th>
              </tr>
              <?php do { ?>
                <tr class="data">
                  <td style="width:200px; font-weight:bold"><?php echo $row_rs_worlds[0]; ?></td>
                  <td style="width:300px; font-weight:bold"><?php echo $row_rs_worlds[1]; ?></td>
                  <td style="width:150px; font-weight:bold"><?php echo $row_rs_worlds[2] ?></td>
                  <td style="width:160px; font-weight:bold"><?php echo $row_rs_worlds[3] ?></td>
                  <td style="width:150px; font-weight:bold; color:#FFF">
						                    <b style="color:#F60"><?php echo $row_rs_worlds[6] ?></b> /
                                            <b style="color:#CCC"><?php echo $row_rs_worlds[5] ?></b> /
                                            <b style="color:#FF3"><?php echo $row_rs_worlds[4] ?></b>
                  </td>
                </tr>
                <?php } while ($row_rs_worlds = mysqli_fetch_row($rs_worlds)); ?>
            </table>
            <?php
				$rs_worlds->close();
				$gamesconn_mysqli->next_result();
			} /* <---	$var_Usuario != '' */
			?>
        </div>  <!-- id="myworldsarea"-->