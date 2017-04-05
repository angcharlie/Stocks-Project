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
?>

<!-- <!DOCTYPE html> -->


<html>
<head>
<title>Charlie's Stock Quotes</title>
<!-- -->
<link rel="stylesheet" href="style.css">
</head>

<body background="stockpic.jpg">

<div id="container">

	
	<?php require "header.php" ?>

	
	<?php include "leftnav.php" ?>
	
	
	<?php include "rightnav.php" ?>
	
	
	<div id="main"; style="font-size: 1em; text-align: center">
		<br />
		
		<form name="historyform" action="history.php" method="get">
		<p style="font-size: 1em;"><b>STOCK SYMBOL: </b><INPUT type="text" style="width: 200px; height: 30px;" placeholder="Stock symbol..." name="symbol" value=<?php print "'{$inputname}'";?>>
															  <INPUT type="submit" value="QUOTE" onClick="historyform.action='quotes.php';"/>
															  <INPUT type="submit" value="HISTORY" onClick="historyform.action='history.php';"/>
															  <INPUT type="submit" value="LOOKUP" onClick="historyform.action='findsymbol.php';"/>
		</form>

		<?php 
		
		if(! empty($inputname)) //process form 
		{
			//Establish dbserver connection
			$db = $objDBUtil->Open();
			if($db->connect_errno)
			{
				die("Could not connect to database. Error[{$db->connect_errno}]");
			}
			
		//Run a query to get company name, symbol, and exchange 
		$query = "SELECT symSymbol, symName, symExchange FROM symbols left outer join quotes on symSymbol=qSymbol WHERE symSymbol='" . $inputname . "'";
		//print "Query: {$query}"; 	
		$result = @$db->query($query);
		$row = @$result->fetch_assoc();

		//<!--Header Row-->
		print"<table style='width: 55%; font-size: 1em; text-align: left;'>";
			print"<tr>";
				print"<td>Symbol: <b>{$row['symSymbol']}</b></td>";
				print"<td>{$row['symExchange']}</td>";
			print"</tr>";
			print"<tr>";
				print"<td><b>{$row['symName']}</b></td>";
			print"</tr>";
		print"</table>";
		@$result->free();	//free result 
		
		//<!--Column headings-->
		print"<table id='t02' border='5' style='width: 557px; font-size: 1em; text-align: center;'>";
			print"<tr>";
				print"<td width=150><b>Date</b> </td>";
				print"<td width=77><b>Last</b> </td>";
				print"<td width=72><b>Change</b> </td>";
				print"<td width=71><b>% Chg</b> </td>";
				print"<td><b>Volume</b> </td>";
			print"</tr>";
		print"</table>";
		
	
		//<!--Scrollable region-->
		print"<div style='overflow:auto; width: 557px; height: 450px;'>";
		
		print"<table id='t01' border='1' style='width: 540px; font-size: 1em; text-align: left;'>";
		
		//Run a query to get some recent quote data 
		$query = "SELECT qQuoteDateTime, qLastSalePrice, qNetChangePrice, qNetChangePct, qShareVolumeQty from quotes left outer join symbols on qSymbol=symSymbol WHERE qSymbol='{$inputname}' order by qQuoteDateTime desc";			//print "Query: {$query}"; 
		$result = @$db->query($query);
		//Process rows
		while($row = @$result->fetch_assoc())
		{			
			print"<tr>";
			$date = $row['qQuoteDateTime'];
			$date = date("m/d/Y", strtotime($date));
				print"<td style='text-align:center;'>{$date}</td>";
				
				if($row['qLastSalePrice'] < 0)
				{
					$num = number_format($row['qLastSalePrice'], 2, '.', ',');
					print"<td style='text-align:center; color: Red;'>$num</td>";	
				}
				else if($row['qLastSalePrice'] == NULL)
				{
					print"<td style='text-align:center;'>n/a</td>";
				}
				else
				{
					$num = number_format($row['qLastSalePrice'], 2, '.', ',');
					print"<td style='text-align:center;'>$num</td>";
				}
				
				if($row['qNetChangePrice'] < 0)
				{
					$num = number_format($row['qNetChangePrice'], 2, '.', ',');
					print"<td style='text-align:center; color: Red;'>$num</td>";
				}
				else if($row['qNetChangePrice'] == NULL)
				{
					print"<td style='text-align:center;'>n/a</td>";
				}
				else 
				{
					$num = number_format($row['qNetChangePrice'], 2, '.', ',');
					print"<td style='text-align:center;'>+$num</td>";
				}
				
				if($row['qNetChangePct'] < 0)
				{
					$num = number_format($row['qNetChangePct'], 2, '.', ',');
					print"<td style='text-align:center; color: Red;'>$num</td>"; 
				}
				else if($row['qNetChangePct'] == NULL)
				{
					print"<td style='text-align:center;'>n/a</td>";
				}
				else 
				{
					$num = number_format($row['qNetChangePct'], 2, '.', ',');
					print"<td style='text-align:center;'>+$num</td>";
				}
				
				if($row['qShareVolumeQty'] < 0)
				{
					$num = number_format($row['qShareVolumeQty'], 2, '.', ',');
					print"<td style='text-align:center; color: Red;'>$num</td>";
				}
				else if($row['qShareVolumeQty']== NULL)
				{
					print"<td style='text-align:center;'>n/a</td>";
				}
				else
				{
					$num = number_format($row['qShareVolumeQty'], 0, '.', ',');
					print"<td style='text-align:center;'>$num</td>";
				}		
			print"</tr>";	
		}
		
		print"</table>";
		@$result->free(); 
		$objDBUtil->Close();	//Close connection
		} //end of php processing 
		?> 
		<!--end of php processing -->
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