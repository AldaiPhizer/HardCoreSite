<?php
	$grabado="Usuario";

	if (!isset($_SESSION))
	{
		session_start(); //Iniciamos la Sesion o la Continuamos
	}

	if (isset($_GET['logout']))
	{
		session_unset();
		session_destroy();
	}


	if (isset($_SESSION['usuario']))
	{
		$grabado=$_SESSION['usuario']; //Si existe un nickname generamos el mensaje
	}
	else
	{
		$grabado="Usuario"; //Mensaje que no existe nada Grabado
	}

	function currency_convert($from, $to, $amount)
	{
		// process API and convert
		$currency = json_decode(file_get_contents('http://rate-exchange.appspot.com/currency?from=' . $from . '&to=' . $to));
		return number_format(($currency->rate * $amount), 2, '.', '');
	}

?>

  <div class="initinfo">
  	<a href="http://us.battle.net/sc2/es/" target="_blank" >
	    <img src="images/banners/StarCraft.JPG" width="500" height="60" alt="Comm1" />
    </a>
    <a href="http://operation7.axeso5.com/" target="_blank">
    	<img src="images/banners/logo-operation7.png" width="500" height="60" alt="Comm2" />
    </a>
    <a href="http://eu.battle.net/wow/es/" target="_blank">
    	<img src="images/banners/WoW_pandaria.JPG" width="240" height="60" alt="Comm2" />
    </a>

    <div class="userinfo">Usuario: <?php echo ($grabado) /*. " $" . currency_convert('USD', 'COP', 1)*/;?>
      <?php 
		if ($grabado != "Usuario")
		{
		?>
		  <a href="account.php?logout" style="color:#FC0">Log Out</a>
	  <?php

		}
		?>
    </div>

  </div>