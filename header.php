<?php  // This must be the FIRST line in a PHP webpage file
ob_start();    // Enable output buffering

// Specify no-caching header controls for page
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");	//Date in the past 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified 
header("Cache-Control:no-store,no-cache,must-revalidate"); //HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
?>


	<div id="header"; style="background-image: url(plant.jpg); max-width:100%; max-height:100%;"> 
		<!--<img src="seattle.jpg" alt="seattle" style="width:1000px; height:100px;">-->
		<span style=font-family:verdana><b>Charlie's Stock Quotes</b></span>
	</div>  <!--end #header--> 


<?php // This is the LAST section in a PHP webpage file
ob_end_flush();
?>