<?php  // This must be the FIRST line in a PHP webpage file
ob_start();    // Enable output buffering

// Specify no-caching header controls for page
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");	//Date in the past 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified 
header("Cache-Control:no-store,no-cache,must-revalidate"); //HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
?>

	<div id="footer">
		<p> All information displayed is non-realtime and is used for demonstration purposes only. </p>
		<p> &copy 2016 Charlie Ang | Seattle Pacific University</p>
	</div> <!--end #footer-->
	
	
<?php // This is the LAST section in a PHP webpage file
ob_end_flush();
?>