<?php  // This must be the FIRST line in a PHP webpage file
ob_start();    // Enable output buffering

// Specify no-caching header controls for page
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");	//Date in the past 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified 
header("Cache-Control:no-store,no-cache,must-revalidate"); //HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
?>


	
	<div id="leftnav"; style="text-align:center">
		<br /><br /><br /><br />
	
		
		<li><a href="default.php">Home</a></li><br /><br />
		<li><a href="quotes.php">Quotes</a></li><br /><br />
		<li><a href="history.php">History</a></li><br /><br />
		<li><a href="findsymbol.php">Find Symbol</a></li><br /><br />
	</div> <!--end #leftnav-->

<?php // This is the LAST section in a PHP webpage file
ob_end_flush();
?>