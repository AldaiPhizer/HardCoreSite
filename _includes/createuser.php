

<script>
function DoAlert(message)
{
	alert(message); // this is the message in ""
}

</script>

<?php 
	include_once('/Connections/gamesconn.php'); 
 	include_once('/Connections/customersconn.php');
	include_once('/Connections/worldconn.php');
/*	include_once ('/Tools/PearMail/Mail.php');*/
	
	if (!isset($_SESSION))
	{
		session_start(); //Iniciamos la Sesion o la Continuamos
	}

	$var_Usuario = '';
	$var_NumUsuario = 0;
	$var_NombrePila = '';
	$var_SegundoNombre = '';
	$var_NombreIntermedio = '';
	$var_PrimerApellido = '';
	$var_ApellidoIntermedio = '';
	$var_SegundoApellido	= '';
	$var_Sexo	= '';
	$var_Genero	= '';
	$var_FechaNac = '';
	$var_Pais	= '';
	$var_CiudadNombre = '';
	$var_CiudadNumero = '';
	$var_Email = '';
	$var_Password	= '';
	$var_RePassword	= '';
	
	if (isset($_SESSION['UsuarioRequerido']))
	{
		if ($_SESSION['UsuarioRequerido'] <> ''){
			$var_Usuario = $_SESSION['UsuarioRequerido'];
		}
		else{
			$var_Usuario = '';
		}
	}
	
	if (isset($_SESSION['NumUser'])){
		if ($_SESSION['NumUser'] <> 0){
			$var_NumUsuario = $_SESSION['NumUser'];
		}
	}
	
	if (isset($_GET['NombrePila'])){
		$var_NombrePila = $_GET['NombrePila'];
	}

	if (isset($_GET['SegundoNombre'])){
		$var_SegundoNombre = $_GET['SegundoNombre'];
	}

	if (isset($_GET['NombreIntermedio'])){
		$var_NombreIntermedio = $_GET['NombreIntermedio'];
	}
	
	if (isset($_GET['PrimerApellido'])){
		$var_PrimerApellido = $_GET['PrimerApellido'];
	}
	
	if (isset($_GET['ApellidoIntermedio'])){
		$var_ApellidoIntermedio = $_GET['ApellidoIntermedio'];
	}
	
	if (isset($_GET['SegundoApellido'])){
		$var_SegundoApellido = $_GET['SegundoApellido'];
	}

	if (isset($_GET['Sexo'])){
		$var_Sexo = $_GET['Sexo'];
	}

	if (isset($_GET['Genero'])){
		$var_Genero = $_GET['Genero'];
	}

	if (isset($_GET['FecNacimiento'])){
		$var_FechaNac = $_GET['FecNacimiento'];
	}

	if (isset($_GET['Pais'])){
		$var_Pais = $_GET['Pais'];
	}

	if (isset($_GET['Ciudad'])){
		$var_CiudadNumero = $_GET['Ciudad'];
	}

	if (isset($_GET['Email'])){
		$var_Email = $_GET['Email'];
	}

	if (isset($_GET['Password'])){
		$var_Password = $_GET['Password'];
	}

	if (isset($_GET['RePassword'])){
		$var_RePassword = $_GET['RePassword'];
	}

/****************************  Create User - Start ***********************************/
	if (isset($_GET['Someter']))
	{
		$query_rs_create = "Call customers.SetUsers('C', '" 
												  . $var_Usuario . "', "
												  . $var_NumUsuario . ", '"
												  . $var_NombrePila . "', '" 
												  . $var_NombreIntermedio . "', '" 
												  . $var_SegundoNombre . "', '" 
												  . $var_PrimerApellido . "', '" 
												  . $var_ApellidoIntermedio . "', '" 
												  . $var_SegundoApellido . "', '"
												  . $var_Genero . "', '"
												  . $var_FechaNac  . "', '" 
												  . $var_Pais  . "', " 
												  . $var_CiudadNumero . ", '" 
												  . $var_Email . "',  '" 
												  . $var_Password .
											"');";
		/*echo '<script>DoAlert("' . $query_rs_create . '")</script>';*/ 
		if ($rs_create = mysqli_query($customersconn_mysqli, $query_rs_create))
		{
			$row_rs_create = mysqli_fetch_row($rs_create);
			$var_Retorno = $row_rs_create['1'];
			$var_Resultado = $row_rs_create['0'];
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
			$rs_create->close();
			$customersconn_mysqli->next_result();
			if ($var_Resultado == 8){
				echo '<META http-equiv="Refresh" Content="0; account.php"';
		/*******************  Send the email - Start************************				
			 $from = "HardCore Games <aldai_phizer@example.com>";
			 $to = $var_NombrePila . " " . $var_PrimerApellido . "<" . $var_Email . ">";
			 $subject = "Has creado un usuario HardCore Games!";
			 $body = "Hi,\n\nHow are you?";
			 
			 $host = "mail.example.com";
			 $username = "smtp_username";
			 $password = "smtp_password";
			 
			 $headers = array ('From' => $from,
			   'To' => $to,
			   'Subject' => $subject);
			 $smtp = Mail::factory('smtp',
			   array ('host' => $host,
				 'auth' => true,
				 'username' => $username,
				 'password' => $password));
			 
			 $mail = $smtp->send($to, $headers, $body);
			 
			 if (PEAR::isError($mail)) {
			   echo("<p>" . $mail->getMessage() . "</p>");
			  } else {
			   echo("<p>Message successfully sent!</p>");
			  }
		
		********************  Send the email - End ************************/
			}
		}else{
			$var_Retorno =  $customersconn_mysqli->error;
			echo '<script>DoAlert("' . $var_Retorno . '")</script>'; 
		}
	}
/****************************  Create User - End **************************/

/****************** For Countries only *************************/
//mysql_select_db($database_worldconn, $worldconn);
$query_rs_countries = "select Code, Name from world.country  order by Name;";
$rs_countries = mysqli_query($worldconn_mysqli, $query_rs_countries) or die(mysqli_error());
$row_rs_countries = mysqli_fetch_row($rs_countries);
$totalRows_rs_countries = mysqli_num_rows($rs_countries);

/****************** For Cities only *************************/
if ($var_Pais != ''){
	//mysql_select_db($database_worldconn, $worldconn);
	$query_rs_city = "select ID, Name from world.city where CountryCode = '" . $var_Pais . "' order by Name;";
	$rs_city = mysqli_query($worldconn_mysqli, $query_rs_city) or die(mysqli_error());
	$row_rs_city = mysqli_fetch_row($rs_city);
	$totalRows_rs_city = mysqli_num_rows($rs_city);
}
?>

<div id="userhandel" style="background:url(images/backgrounds/WhiteStorm_big.png); width:1120px; height:825px" >
    <a href="account.php" style="position:absolute">
        <img src="images/buttons/img_Volver.png"/>
    </a>
    <div id="header" class="pagetitle">
    REGISTRA TUS DATOS 
    </div>
    
    <div id="thepane" style="	height: 700px;
                                width: 1090px;
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
    <form id="CreateUser" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" >
    <br/>
	  <input name="CrearUsuario" type="hidden" value="" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <label style="font-size: 40px; color:#FC0; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif;  "    				 
      			left="50px"	width="1090px">Usuario reservado por 30 minutos: <?php echo $var_Usuario; ?></label>
      <table border="0" style="margin-left:auto; margin-right:auto; table-layout:fixed; overflow:hidden" >
        <tr>
            <td width="10px" style="color:#C00; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" >*</td>
            <td width="250px" class="datalabel" >Nombre de pila: </td>
            <td width="300px" >
                <input name="NombrePila" type="text" size="12" value="<?php echo $var_NombrePila; ?>" 
                    class="data"/>
            </td>
            <td width="200px" style="color:#666; font-style:italic; 
            				font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >María</td>
        <tr/>
        <tr>
            <td width="10px" style="color:#C00; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" > </td>
            <td width="250px"  class="datalabel" >Nombre del medio: </td>
            <td width="300px" >
                <input name="NombreIntermedio" type="text" size="6" value="<?php echo $var_NombreIntermedio; ?>" 
                    class="data" />
            </td>
            <td width="200px" style="color:#666; font-style:italic; 
            				font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >Del</td>
        <tr/>
        <tr>
            <td width="10px" style="color:#C00; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" > </td>
            <td width="250px"  class="datalabel" >Segundo nombre: </td>
            <td width="300px" >
                <input name="SegundoNombre" type="text" size="12" value="<?php echo $var_SegundoNombre; ?>" 
                		class="data"/>
            </td>
            <td width="200px" style="color:#666; font-style:italic; 
            				font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >Carmen</td>
        <tr/>
        <tr>
            <td width="10px" style="color:#C00; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" >*</td>
            <td width="250px"  class="datalabel" >Primer apellido: </td>
            <td width="300px" >
                <input name="PrimerApellido" type="text" size="12" value="<?php echo $var_PrimerApellido; ?>" 
                		class="data"/>
            </td>
            <td width="200px" style="color:#666; font-style:italic; 
            			font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >González</td>
        <tr/>
        <tr>
            <td width="10px" style="color:#C00; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" > </td>
            <td width="250px"  class="datalabel" >Apellido del medio: </td>
            <td width="300px" >
                <input name="ApellidoIntermedio" type="text" size="6" value="<?php echo $var_ApellidoIntermedio; ?>" class="data"/>
            </td>
            <td width="200px" style="color:#666; 
            			font-style:italic; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >de</td>
        <tr/>
        <tr>
            <td width="10px" style="color:#C00; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" > </td>
            <td width="250px"  class="datalabel" >Segundo apellido: </td>
            <td width="300px" >
                <input name="SegundoApellido" type="text" size="12" value="<?php echo $var_SegundoApellido; ?>" class="data"/>
            </td>
            <td width="200px" style="color:#666; font-style:italic; 
            			font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >Rendón</td>
        <tr/>
        <tr>
            <td width="10px" style="color:#C00; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" > </td>
            <td width="250px"  class="datalabel" >Sexo: </td>
            <td width="300px" >
                <select name="Sexo" size="1" class="data">
                  <option class="drop" value="D" <?php if ($var_Sexo == 'D'){ ?> selected="selected" <?php } ?>>Diario</option>
                  <option class="drop" value="S" <?php if ($var_Sexo == 'S'){ ?> selected="selected" <?php } ?>>Semanal</option>
                  <option class="drop" value="M" <?php if ($var_Sexo == 'M'){ ?> selected="selected" <?php } ?>>Mensual</option>
                  <option class="drop" value="A" <?php if ($var_Sexo == 'A'){ ?> selected="selected" <?php } ?>>Anual</option>
                  <option class="drop" value="C" <?php if ($var_Sexo == 'C'){ ?> selected="selected" <?php } ?>>No gracias, soy célibe</option>
                </select>        
            </td>
            <td width="200px" style="color:#666; font-style:italic; 
            			font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >;)</td>
        <tr/>
        <tr>
            <td width="10px" style="color:#C00; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" > </td>
            <td width="250px"  class="datalabel" >Género: </td>
            <td width="300px" >
                <select name="Genero" size="1" class="data" >
                  <option class="drop"  value="F" <?php if ($var_Genero == 'F'){ ?> selected="selected" <?php } ?>>Femenino</option>
                  <option class="drop" value="M" <?php if ($var_Genero == 'M'){ ?> selected="selected" <?php } ?>>Masculino</option>
                  <option class="drop" value="L" <?php if ($var_Genero == 'L'){ ?> selected="selected" <?php } ?>>Lesbiana</option>
                  <option class="drop" value="G" <?php if ($var_Genero == 'G'){ ?> selected="selected" <?php } ?>>Gay</option>
                  <option class="drop" value="T" <?php if ($var_Genero == 'T'){ ?> selected="selected" <?php } ?>>Transexo</option>
                  <option class="drop" value="Bf" <?php if ($var_Genero == 'Bf'){ ?> selected="selected" <?php } ?>>Bi-F</option>
                  <option class="drop" value="Bm" <?php if ($var_Genero == 'Bm'){ ?> selected="selected" <?php } ?>>Bi-M</option>
                  <option class="drop" value="I" <?php if ($var_Genero == 'I'){ ?> selected="selected" <?php } ?>>Intersexual</option>
                </select>        
            </td>
            <td width="200px" style="color:#666; 
            			font-style:italic; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >;)</td>
        <tr/>
        <tr>
            <td width="10px" style="color:#C00; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" >*</td>
            <td width="250px"  class="datalabel" >Fecha de nacimiento: </td>
            <td width="300px" >
                <input name="FecNacimiento" type="text" size="12" value="<?php echo $var_FechaNac; ?>"  onfocus="showCalendarControl(this);"
                    class="data"/>
            </td>
            <td width="200px" style="color:#666; font-style:italic; 
            			font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" > </td>
        <tr/>
        <tr>
            <td width="10px" style="color:#C00; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" >*</td>
            <td width="250px"  class="datalabel" >País: </td>
            <td width="300px" ><a name="detalle"></a>
            <select name="Pais" size="1" class="data" 
            						onchange="form.action = window.location;this.form.submit();">
              <option class="drop" value="" <?php if ($var_Pais == ''){ ?> selected="selected" <?php } ?>>Seleccione un país</option>
                <?php
                do {  
                ?>
                <option class="drop" value="<?php echo $row_rs_countries[0]?>" 
                <?php if ($var_Pais == $row_rs_countries[0]){ ?> selected="selected" <?php } ?>><?php echo $row_rs_countries[1]?>
                </option>
                <?php
                } while ($row_rs_countries = mysqli_fetch_row($rs_countries));
                  $rows = mysqli_num_rows($rs_countries);
                  if($rows > 0) {
                      mysqli_data_seek($rs_countries, 0);
                      $row_rs_countries = mysqli_fetch_row($rs_countries);
                  }
                ?>
          </select>
            </td>
            <td width="200px" style="color:#666; font-style:italic; 
            			font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" > </td>
        <tr/>
        <tr>
            <td width="10px" style="color:#C00; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" >*</td>
            <td width="250px"  class="datalabel" >Ciudad: </td>
            <td width="300px" ><?php if ($var_Pais != ''){ ?>
            <select name="Ciudad" size="1" class="data">
              <option class="drop" value="" <?php if ($var_CiudadNumero == ''){ ?> selected="selected" <?php } ?>>Seleccione una ciudad</option>
                <?php
                do {  
                ?>
                <option class="drop" value="<?php echo $row_rs_city[0]?>" 
                <?php if ($var_CiudadNumero == $row_rs_city[0]){ ?> selected="selected" <?php } ?>><?php echo $row_rs_city[1]?>
                </option>
                <?php
                } while ($row_rs_city = mysqli_fetch_row($rs_city));
                  $rows = mysqli_num_rows($rs_city);
                  if($rows > 0) {
                      mysqli_data_seek($rs_countries, 0);
                      $row_rs_city = mysqli_fetch_row($rs_city);
                  }
                ?>
          </select> <?php } ?>
            </td>
            <td width="200px" style="color:#666; font-style:italic; 
            			font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" > </td>
        <tr/>
        <tr>
            <td width="10px" style="color:#C00; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" >*</td>
            <td width="250px"  class="datalabel" >Email: </td>
            <td width="300px" >
                <input name="Email" type="text" size="20" value="<?php echo $var_Email; ?>" 
                    class="data"/>
            </td>
            <td width="200px" style="color:#666; font-style:italic; 
            			font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" >yo_soy@dealgun.lado</td>
        <tr/>
        <tr>
            <td width="10px" style="color:#C00; font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" >*</td>
            <td width="250px"  class="datalabel">Contraseña: </td>
            <td width="300px" >
                <input name="Password" type="password"  size="16" value="<?php echo $var_Password; ?>" 
                    class="data"/>
            </td>
            <td width="200px" style="color:#666; font-style:italic; 
            			font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" > </td>
        <tr/> 
        <tr>
            <td width="10px" style="color:#C00; 
            			font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:right;" >*</td>
            <td width="250px"  class="datalabel" >Otra vez ...: </td>
            <td width="300px" >
                <input name="RePassword" type="password"  size="16" value="<?php echo $var_RePassword; ?>" 
                   class="data"/>
            </td>
            <td width="200px" style="color:#666; font-style:italic; 
            			font-family: 'Comic Sans MS', cursive; font-size: 20px; text-align:left;" > </td>
        <tr/>
    </table>
    <br/>
            <p style="text-align:center">
                <input  type="submit" name="Someter"  value="" class="botonentrar"/>
            </p>

    </form>
    <script  type="text/javascript">
    var frmvalidator = new Validator("CreateUser");
    frmvalidator.addValidation("NombrePila","req", "Proporciona tu nombre de pila");
    frmvalidator.addValidation("PrimerApellido","req", "Proporciona tu apellido");
    frmvalidator.addValidation("FecNacimiento","req", "Proporciona fecha de nacimiento");
	
    frmvalidator.addValidation("Pais","req", "Selecciona tu pais");
	frmvalidator.addValidation("Ciudad","req", "Selecciona tu ciudad");

    frmvalidator.addValidation("Email","req", "Proporciona el correo electrónico");
	frmvalidator.addValidation("Email","email", "Digita un correo válido");
	
	frmvalidator.addValidation("Password","minlen=4", "La contraseña debe tener de 4 a 15 caracteres");
	frmvalidator.addValidation("Password","maxlen=15", "La contraseña debe tener de 4 a 15 caracteres");
	frmvalidator.addValidation("RePassword","minlen=4", "La contraseña debe tener de 4 a 15 caracteres");
	frmvalidator.addValidation("RePassword","maxlen=15", "La contraseña debe tener de 4 a 15 caracteres");
    frmvalidator.addValidation("RePassword","eqelmnt=Password", "Las contraseñas no coinciden");
     
     </script>
    </div> <!--thepane-->
</div>  <!--id="userhandel"-->