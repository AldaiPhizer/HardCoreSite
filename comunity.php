<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HardCore Games | Comunity</title>
<meta name="description" content="Mantente fresco" />
<meta name="keywords" content="comunidad HardCore" />
<link href="images/icons/HardCore_iconV2.ico" rel="shortcut icon" />
<link href="css/HardCoreStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_nbGroup(event, grpName) { //v6.0
  var i,img,nbArr,args=MM_nbGroup.arguments;
  if (event == "init" && args.length > 2) {
    if ((img = MM_findObj(args[2])) != null && !img.MM_init) {
      img.MM_init = true; img.MM_up = args[3]; img.MM_dn = img.src;
      if ((nbArr = document[grpName]) == null) nbArr = document[grpName] = new Array();
      nbArr[nbArr.length] = img;
      for (i=4; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
        if (!img.MM_up) img.MM_up = img.src;
        img.src = img.MM_dn = args[i+1];
        nbArr[nbArr.length] = img;
    } }
  } else if (event == "over") {
    document.MM_nbOver = nbArr = new Array();
    for (i=1; i < args.length-1; i+=3) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = (img.MM_dn && args[i+2]) ? args[i+2] : ((args[i+1])? args[i+1] : img.MM_up);
      nbArr[nbArr.length] = img;
    }
  } else if (event == "out" ) {
    for (i=0; i < document.MM_nbOver.length; i++) {
      img = document.MM_nbOver[i]; img.src = (img.MM_dn) ? img.MM_dn : img.MM_up; }
  } else if (event == "down") {
    nbArr = document[grpName];
    if (nbArr)
      for (i=0; i < nbArr.length; i++) { img=nbArr[i]; img.src = img.MM_up; img.MM_dn = 0; }
    document[grpName] = nbArr = new Array();
    for (i=2; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = img.MM_dn = (args[i+1])? args[i+1] : img.MM_up;
      nbArr[nbArr.length] = img;
  } }
}
//-->
</script>
<script src="_includes/gen_validatorv4.js" type="text/javascript"></script>
<?php 
	if (!isset($_SESSION))
	{
		session_start(); //Iniciamos la Sesion o la Continuamos
	}
?>
</head>

<body background="images/backgrounds/BackGround_WebSite.jpg" >
<div class="mainframe">
  <div class="header"></div>
  <div class="menu"><a href="index.php" target="_top" onclick="MM_nbGroup('down','group1','menu1','images/buttons/Mundos_Click.png',0)" onmouseover="MM_nbGroup('over','menu1','images/buttons/Mundos_Hover.png','images/buttons/Mundos_Click.png',0)" onmouseout="MM_nbGroup('out')"><img src="images/buttons/Mundos_Normal.png" alt="Mundos" name="menu1" width="315" height="60" border="0" id="menu1" onload="" /></a><a href="contests.php" target="_top" onclick="MM_nbGroup('down','group1','menu2','images/buttons/Concursos_Click.png',0)" onmouseover="MM_nbGroup('over','menu2','images/buttons/Concursos_Hover.png','images/buttons/Concursos_Click.png',0)" onmouseout="MM_nbGroup('out')"><img src="images/buttons/Concursos_Normal.png" alt="Concursos" name="menu2" width="315" height="60" border="0" id="menu2" onload="" /></a><a href="account.php" target="_top" onclick="MM_nbGroup('down','group1','menu3','images/buttons/MiCuenta_Click.png',0)" onmouseover="MM_nbGroup('over','menu3','images/buttons/MiCuenta_Hover.png','images/buttons/MiCuenta_Click.png',0)" onmouseout="MM_nbGroup('out')"><img src="images/buttons/MiCuenta_Normal.png" alt="MiCuenta" name="menu3" width="315" height="60" border="0" id="menu3" onload="" /></a><a href="market.php" target="_top" onclick="MM_nbGroup('down','group1','menu4','images/buttons/LaPlaza_Click.png',0)" onmouseover="MM_nbGroup('over','menu4','images/buttons/LaPlaza_Hover.png','images/buttons/LaPlaza_Click.png',0)" onmouseout="MM_nbGroup('out')"><img src="images/buttons/LaPlaza_Normal.png" alt="Market" name="menu4" width="315" height="60" border="0" id="menu4" onload="" /></a> </div>
<?php include("_includes/userinfo.php")?>
<div class="products" id="market" >
	<script>
    function DoAlert(message)
    {
        alert(message); // this is the message in ""
    }
    
    </script>
	<?php 
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
		
		if (!$muestra_detalle){
			echo '<script>DoAlert("Debes registrar un usuario para entrar a esta área")</script>';
			echo '<META http-equiv="Refresh" Content="0; account.php?"';
		}else {
			if (isset($_GET['mensajes']) || (!isset($_GET['soporte']) && !isset($_GET['chat']) && !isset($_GET['foro'])) )
			{
		?>
			<div id="offers" style="background:url(images/backgrounds/Messages_bkg.png); width:1120px; height:825px" >
				<div id="table" style="margin-left:12px" >
					<?php 
						include("_includes/messages.php");
					?>
				</div>
			</div>
		<?php } /*if (isset($_GET['mensajes']) || (!isset($_GET['soporte']) && !isset($_GET['chat']) && !isset($_GET['foro'])) )*/?>
		<?php 
			if (isset($_GET['soporte']))
			{
		?>
			<div id="offers" style="background:url(images/backgrounds/Support_bkg.png); width:1120px; height:825px" >
				<div id="table" style="margin-left:12px" >
					<?php 
						include("_includes/support.php");
					?>
				</div>
			</div>
		<?php } /*if (isset($_GET['soporte']))*/?>
		<?php 
			if (isset($_GET['chat']))
			{
		?>
			<div id="offers" style="background:url(images/backgrounds/Chat_bkg.png); width:1120px; height:825px" >
				<div id="table" style="margin-left:12px" >
					<?php 
						include("_includes/chat.php");
					?>
				</div>
			</div>
		<?php } /*if (isset($_GET['chat']))*/?>
		<?php 
			if (isset($_GET['foro']))
			{
		?>
			<div id="offers" style="background:url(images/backgrounds/Foros_bkg.png); width:1120px; height:825px" >
				<div id="table" style="margin-left:12px" >
					<?php 
						include("_includes/foro.php");
					?>
				</div>
			</div>
		<?php } /*if (isset($_GET['chat']))*/ ?>
	 <?php } /*if (!$muestra_detalle){*/ ?>   
  </div>	<!--<div class="products" id="market" >-->


<?php include("_includes/leftcolumn.php")?>
<?php include("_includes/footer.php")?>
</div>	<!--<div class="mainframe">-->
</body>
</html>