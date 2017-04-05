<?php  // This must be the FIRST line in a PHP webpage file
ob_start();    // Enable output buffering

// Specify no-caching header controls for page
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");	//Date in the past 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified 
header("Cache-Control:no-store,no-cache,must-revalidate"); //HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
?>

	<div id="rightnav"; style="text-align:center">
		<span style="font-family:Comic Sans MS; font-size:x-large; color:white; text-decoration:underline" ><b>LINKS</b></span><br /><hr>
		<br />
		<br />
		<div> <!--style="font-size:large; color:white; text-align:left;"-->
			<li><a href="http://cs.spu.edu" target="blank">CS Server</a></li><br /><br /><br />
			<li><a href="http://spu.edu">SPU Home</a></li><br /><br /><br />
			<li><a href="http://spu.edu/depts/csc/">SPU Comp Sci</a></li><br /><br /><br />
			<li><a href="https://www.nyse.com/index">NYSE</a></li><br /><br /><br />
		</div>
	</div> <!--end #rightnav-->


<?php // This is the LAST section in a PHP webpage file
ob_end_flush();
?>