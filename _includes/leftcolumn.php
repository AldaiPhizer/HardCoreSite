<?php include("DateUtility.php")?>

  <div class="leftcolumn">
  	<p style="color:#FFF" ><strong>Usuarios Conectados</strong> </p>
  	<div class="connusers">aldai_phizer <br />lord_raoh  <br />Astrakhan <br />DaveVolek
     <br />Nigromante
    </div>
    <div style="width:95px; height:450">
    	
        <img src="images/banners/paypal.jpg" width="95" height="175" alt="Ad1" />
        <img src="images/banners/BitCoin_Alpha.png" width="95" height="90" alt="Ad2" />
        
    </div>
  	<div class="currtime">
    	<p style="color:#FFF" ><strong>Hora UTC</strong><br />
		<?php 
			$dateutil = new DateUtility();
			$dateutil -> getCurrentDate();
		?><br />
		<?php 		
			$dateutil = new DateUtility();
			$dateutil -> getCurrentTime();
		?></p>
    </div>
  </div>