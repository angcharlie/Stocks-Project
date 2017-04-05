<?php  // This must be the FIRST line in a PHP webpage file
ob_start();    // Enable output buffering

// Specify no-caching header controls for page
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");	//Date in the past 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified 
header("Cache-Control:no-store,no-cache,must-revalidate"); //HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0

//Gather incoming form data
$inputname = @$_REQUEST["symbol"];
if(empty($inputname))
	$inputname = "";

?>

<!-- <!DOCTYPE html> -->

<html>
<head>
<title>Charlie's Stock Quotes</title>
<!-- -->
<link rel="stylesheet" href="style.css">
</head>

<body background="stockpic.jpg"> <!--got from free stock images site-->

<div id="container">

	
	<?php require "header.php" ?>

	
	<?php include "leftnav.php" ?>
	
	
	<?php include "rightnav.php" ?>
	
	
	
	<div id="main"; style="font-size: 2em; text-align: center">
		<br />
		
		<form name = "defaultform" action="default.php" method="get">
		
		<b>QUICK QUOTE: </b> <INPUT type="text" style="width: 200px; height: 30px;" placeholder="Stock symbol..." name="symbol" value=<?php print "'{$inputname}'";?>>
							 <INPUT type="submit" value="Search" onClick="defaultform.action='quotes.php';"/>
		
		</form>
		<p style="text-align: center; font-family: verdana; font-size: x-large; text-decoration: italics;"><b>Welcome to Charlie's Stock Quotes!</b></p>
		<br/><br/><p style="font-size: x-large; color: White; font-family: arial ">Here, you will find it quick and easy to access all your favorite stock quotes.
		To get started, click on one of the menu items on the left to view your desired quotes, view
		quotes history, or to look up a stock symbol. If you know what you are looking for,
		simply enter your stock symbol into the quick search box. </p>
		<br /><br /><br />
		
	
	</div> <!--end #main-->
	
	<!-- Clearing element follows #main div in order to force #container div to contain all child floats -->
	<br class="clearfloat" />
	
	<?php include "footer.php" ?>	
</div> <!--end #container-->



</body>
</html>



<?php // This is the LAST section in a PHP webpage file
ob_end_flush();
?>