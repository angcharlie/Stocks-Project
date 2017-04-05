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

//Add slashes if needed 
if(! get_magic_quotes_gpc())
{
	$inputname = addslashes($inputname);	
}

require "dbUtil.php";
$objDBUtil = new DbUtil;

//NEED TO LOOK IN BOTH SYMBOL NAME AND STOCK SYMBOL NAME symName and symSymbol...all in one query 
//pattern like this in symName or pattern like this in symSymbol 
//$string(from form) symSymbol LIKE(approx match) '%$string%' (% as wildcard match) or symName is like that 
?>

<!-- <!DOCTYPE html> -->

<html>
<head>
<title>Charlie's Stock Quotes</title>
<!-- -->
<link rel="stylesheet" href="style.css">

<style>

</style>
</head>

<body background="stockpic.jpg">

<div id="container">

	
	<?php require "header.php" ?>

	
	<?php include "leftnav.php" ?>
	
	
	<?php include "rightnav.php" ?>
	
	
	<div id="main"; style="font-size: 1em; text-align: center">
		<br />
		
		<form name="lookupform" action="findsymbol.php" method="get">
		<p style="font-size: 1em;"><b>COMPANY NAME - Enter all or part: </b><INPUT type="text" style="width: 200px; height: 30px;" placeholder="Company name..." name="symbol" value=<?php print "'{$inputname}'";?>>
																			<INPUT type="submit" value="LOOK UP" onClick="lookupform.action='findsymbol.php';"/>
																			<a href='javascript:history.back(1);'>BACK</a>
																			
		</form>


		<!--Column headings-->
		<table id="t02" border="5" style="width: 557px; font-size: 1em; text-align: left;">
			<tr>
				<td width=360><b>Company</b> </td>
				<td width=57><b>Symbol</b> </td>
				<td style="text-align:right;"><b></b> </td>
				<!--<td style="text-align:right;"><b>## Entries</b> </td>-->
			</tr>
		</table>
		
	<?php 
		
		if(! empty($inputname)) //process form 
		{
			//Establish dbserver connection
			$db = $objDBUtil->Open();
			if($db->connect_errno)
			{
				die("Could not connect to database. Error[{$db->connect_errno}]");
			}
		
		//<!--Scrollable region-->
		print"<div style='overflow:auto; width: 557px; height: 500px;'>";
		
		print"<table id='t01' border='1' style='width: 540px; font-size: 1em; text-align: left;'>";
	
		//Run a query to get some recent quote data 
		$query = "select symName, symSymbol from symbols where symSymbol like '%$inputname%' OR symName like '%$inputname%' order by symName asc;";			
		$result = @$db->query($query);
		//Process rows
		while($row = @$result->fetch_assoc())
		{	
			print"<tr>";
				print"<td style='text-align:left;'>{$row['symName']}</td>";
				print"<td style='text-align:left;'>{$row['symSymbol']}</td>";
				print"<td style='text-align:left;'> <a href='quotes.php?symbol={$row['symSymbol']}'>Quote</a></td>";
				print"<td style='text-align:left;'><a href='history.php?symbol={$row['symSymbol']}'>History</a></td>";
			print"</tr>";
			
		} //end of while loop
		print"</table>";
		
		@$result->free(); 
		$objDBUtil->Close();	//Close connection
		} //end of php processing 
		?>
		
		</div> <!--end of scrollable region-->

		

		
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