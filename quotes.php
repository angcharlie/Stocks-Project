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

require "dbUtil.php";
$objDBUtil = new DbUtil;

?>


<!-- <!DOCTYPE html> -->

<html>
<head>
<title>Quotes</title>
<!-- -->
<link rel="stylesheet" href="style.css">

<style>
table, th, td {
	border: 1px solid black;
}
</style>
</head>

<body background="stockpic.jpg">

<div id="container">


	<?php require "header.php" ?>

	
	<?php include "leftnav.php" ?>
	
	
	<?php include "rightnav.php" ?>
	
	
	
	<div id="main"; style="font-size: 1em; text-align: center">
		<br />
		
		
		<form name="quoteform" action="quotes.php" method="get">
		
		<p style="font-size: 1em;"><b>STOCK SYMBOL:</b><INPUT type="text" style="width: 200px; height: 30px;" placeholder="Stock symbol..." name="symbol" value=<?php print "'{$inputname}'";?>>
															 <INPUT type="submit" value="QUOTE" onClick="quoteform.action='quotes.php';"/>
															 <INPUT type="submit" value="HISTORY" onClick="quoteform.action='history.php';"/>
															 <INPUT type="submit" value="LOOKUP" onClick="quoteform.action='findsymbol.php';"/>
		
		</form>
		

		
		<!--Make quotes table here  -->
		
		<p style="text-align: center; font-family: verdana; font-size: large; text-decoration: italics;"><b>Enter the stock symbol of a company above if you know the symbol. Otherwise, use the find symbol page to search for a symbol.</b></p>
		
	
		
	<?php 
		$strSymbol = @$_REQUEST["symbol"];
		
		//Adding slashes
		if(! get_magic_quotes_gpc())
		{
			$strSymbol = addslashes($strSymbol);	
		}
		
		if(!empty($strSymbol))	//process form if not symbol not empty
		{
			//Databse Connection Parameters
			//$host = "cs.spu.edu";
			//$user = "quotesdb";
			//$pwd = "quotesdb";
			
			//Establish db server connection
			//$db = @new mysqli($host, $user, $pwd, 'quotesdb');
			$db = $objDBUtil->Open();
			
			if($db->connect_errno)
			{
				die("Could not connect to database. Error[{$db->connect_errno}]");
			}
			
		$query = "SELECT qQuoteDateTime from symbols left outer join quotes on symSymbol=qSymbol WHERE qSymbol='{$strSymbol}' order by qQuoteDateTime desc limit 1";
		//print "Query: {$query}"; 
		$result = @$db->query($query);
		if(! $result)
		{
			print "Invalid query result";
		}
		else
		{
			//Process row
			$row = @$result->fetch_assoc();
			
			print"<div style='text-align: left;'><b>{$row['qQuoteDateTime']}</b></div>"; 	
			@$result->free();	//free result 
		}
		
		// outputting the table after date and time 
		
			
		//Run a query to get company name, symbol, and exchange 
		$query = "SELECT symSymbol, symName, symExchange FROM symbols left outer join quotes on symSymbol=qSymbol WHERE symSymbol='" . $strSymbol . "'";
			
		//print "Query: {$query}"; 
			
		$result = @$db->query($query);
		if(! $result)
		{
			print "Invalid query result";
		}
		else
		{
		//Process row
		$row = @$result->fetch_assoc();
	
		print "<table id='t03' style='width: 55%; font-size: 1em; text-align: left;'>";
		print "<tr>";
		 print "<th>{$row['symName']}</th>";
		 print "<th></th>";
		 print "<th style='text-align:right;'>Symbol: {$row['symSymbol']}</th>";
		 print "<th style='text-align:right;'>{$row['symExchange']}</th>";
		print  "</tr> ";
		@$result->free();	//free result 
		//<!--end of first heading-->
		}	
		
		//Run a query to get recent quote data 
		$query = "SELECT * from quotes left outer join symbols on qSymbol=symSymbol where qSymbol='" . $strSymbol . "' order by qQuoteDateTime desc limit 1";
		//print "Query: {$query}"; 
		$result = @$db->query($query);
		if(! $result)
		{
			print "Invalid query result";
		}
		else 
		{
			//Process row
			$row = @$result->fetch_assoc();
		
			print"<tr>";
			print"<td><b> Last</b></td>";
			if($row['qLastSalePrice'] < 0)
			{
				$num = number_format($row['qLastSalePrice'], 2, '.', ',');
				print"<td style='color: Red;'>$num</td>";	
			}
			else if($row['qLastSalePrice'] == NULL)
			{
				print"<td>n/a</td>";
			}
			else
			{
				$num = number_format($row['qLastSalePrice'], 2, '.', ',');
				print"<td>$num</td>";
			}
			
			print"<td style='text-align:right;'><b>Prev Close</b></td>";
			if($row['qPreviousClosePrice'] < 0)
			{
				$num = number_format($row['qPreviousClosePrice'], 2, '.', ',');
				print"<td style='text-align:right; color: Red;'>$num</td>";	
			}
			else if($row['qPreviousClosePrice'] == NULL)
			{
				print"<td style='text-align:right;'>n/a</td>";
			}
			else
			{
				$num = number_format($row['qPreviousClosePrice'], 2, '.', ',');
				print"<td style='text-align:right;'>$num</td>";
			}
			print"</tr>";
			
			print"<tr>";
				print"<td><b> Change</b></td>";
				if($row['qNetChangePrice'] < 0)
				{
					$num = number_format($row['qNetChangePrice'], 2, '.', ',');
					print"<td style='color: Red;'>$num</td>";
				}
				else if($row['qNetChangePrice'] == NULL)
				{
					print"<td>n/a</td>";
				}
				else 
				{
					$num = number_format($row['qNetChangePrice'], 2, '.', ',');
					print"<td>+$num</td>";
				}
				
				print"<td style='text-align:right;'><b>Bid</b></td>";
				if($row['qBidPrice'] < 0)
				{
					$num = number_format($row['qBidPrice'], 2, '.', ',');
					print"<td style='text-align:right; color: Red;'>$num</td>";
				}
				else if ($row['qBidPrice'] == NULL)
				{
					print"<td style='text-align:right;'>n/a</td>";
				}
				else
				{
					$num = number_format($row['qBidPrice'], 2, '.', ',');
					print"<td style='text-align:right;'>$num</td>";
				}
			print"</tr>";
			
			print"<tr>";
				print"<td><b> % Change</b></td>";
				if($row['qNetChangePct'] < 0)
				{
					$num = number_format($row['qNetChangePct'], 2, '.', ',');
					print"<td style='color: Red;'>$num %</td>"; 
				}
				else if($row['qNetChangePct'] == NULL)
				{
					print"<td>n/a</td>"; 
				}
				else 
				{
					$num = number_format($row['qNetChangePct'], 2, '.', ',');
					print"<td>+$num %</td>"; 
				}
				print"<td style='text-align:right;'><b>Ask</b></td>";
				if($row['qAskPrice'] < 0)
				{
					$num = number_format($row['qAskPrice'], 2, '.', ',');
					print"<td style='text-align:right; color: Red;'>$num</td>";
				}
				else if($row['qAskPrice'] == NULL)
				{
					print"<td style='text-align:right;'>n/a</td>";
				}
				else
				{
					$num = number_format($row['qAskPrice'], 2, '.', ',');
					print"<td style='text-align:right;'>$num</td>";
				}
				
			print"</tr>";
			
			print"<tr>";
				print"<td><b> High</b></td>";
				if($row['qTodaysHigh'] < 0)
				{
					$num = number_format($row['qTodaysHigh'], 2, '.', ',');
					print"<td style='color: Red;'>$num</td>";
				}
				else if($row['qTodaysHigh'] == NULL)
				{
					print"<td>n/a</td>";
				}
				else
				{
					$num = number_format($row['qTodaysHigh'], 2, '.', ',');
					print"<td>$num</td>";
				}
				
				print"<td style='text-align:right;'><b>52 Week High</b></td>";
				if($row['q52WeekHigh'] < 0)
				{
					$num = number_format($row['q52WeekHigh'], 2, '.', ',');
					print"<td style='text-align:right; color: Red;'>$num</td>";
				}
				else if($row['q52WeekHigh'] == NULL)
				{
					print"<td style='text-align:right;'>n/a</td>";	
				}
				else 
				{
					$num = number_format($row['q52WeekHigh'], 2, '.', ',');
					print"<td style='text-align:right;'>$num</td>";
				}
			print"</tr>";
			
			print"<tr>";
				print"<td><b> Low</b></td>";
				if($row['qTodaysLow'] < 0)
				{
					$num = number_format($row['qTodaysLow'], 2, '.', ',');
					print"<td style='color: Red;'>$num</td>";
				}
				else if($row['qTodaysLow'] == NULL)
				{
					print"<td>n/a</td>";
				}
				else
				{
					$num = number_format($row['qTodaysLow'], 2, '.', ',');
					print"<td>$num</td>";
				}
				
				print"<td style='text-align:right;'><b>52 Week Low</b></td>";
				if($row['q52WeekLow'] < 0)
				{
					$num = number_format($row['q52WeekLow'], 2, '.', ',');
					print"<td style='text-align:right; color: Red;'>$num</td>";
				}
				else if($row['q52WeekLow'] == NULL)
				{
					print"<td style='text-align:right;'>n/a</td>";
				}
				else 
				{
					$num = number_format($row['q52WeekLow'], 2, '.', ',');
					print"<td style='text-align:right;'>$num</td>";
				}
			print"</tr>";
			
			print"<tr>";
				print"<td><b> Daily Volume</b></td>";
				if($row['qShareVolumeQty'] < 0)
				{
					$num = number_format($row['qShareVolumeQty'], 0, '.', ',');
					print"<td style='color: Red;'>$num</td>";
				}
				else if($row['qShareVolumeQty']== NULL)
				{
					print"<td>n/a</td>";
				}
				else
				{
					$num = number_format($row['qShareVolumeQty'], 0, '.', ',');
					print"<td>$num</td>";
				}	
			print"</tr>";
			
			//<!--separate top section from bottom section -->
			
			print"<tr>";
				print"<td>&nbsp;&nbsp; </td>";
			print"</tr>";

			
			print"<tr>";
				print"<th>Fundamentals</th>";
				//<!--end of second heading-->
			print"</tr>";
			print"<tr>";
				print"<td><b> PE Ratio</b></td>";
				if($row['qCurrentPERatio'] < 0)
				{
					$num = number_format($row['qCurrentPERatio'], 2, '.', ',');
					print"<td style='color: Red;'>$num</td>";
				}
				else if($row['qCurrentPERatio'] == NULL)
				{
					print"<td>n/a</td>";
				}
				else 
				{
					$num = number_format($row['qCurrentPERatio'], 2, '.', ',');
					print"<td>$num</td>";
				}
		@$result->free();	//free result 	
		}	

		//Run a query to get market cap. from symbols table  
		$query = "SELECT symMarketCap FROM symbols left outer join quotes on symSymbol=qSymbol WHERE symSymbol='" . $strSymbol . "'";
			
		//print "Query: {$query}"; 
			
		$result = @$db->query($query);
		if(! $result)
		{
			print "Invalid query result";
		}
		else
		{
		//Process row
		$row = @$result->fetch_assoc();
		
				print"<td style='text-align:right;'><b>Market Cap.</b></td>";
				if($row['symMarketCap'] < 0)
				{
					$num = number_format($row['symMarketCap'], 1, '.', ',');
					print"<td style='text-align:right; color: Red;'>$num Mil</td>";
				}
				else if($row['symMarketCap'] == NULL)
				{
					print"<td style='text-align:right;'>n/a</td>";
				}
				else
				{
					$num = number_format($row['symMarketCap'], 1, '.', ',');
					print"<td style='text-align:right;'>$num Mil</td>";
				}
			print"</tr>";
			@$result->free();	//free result
		}	
		
		//Run a query to get recent quote data after market cap.
		$query = "SELECT * from quotes left outer join symbols on qSymbol=symSymbol where qSymbol='" . $strSymbol . "' order by qQuoteDateTime desc limit 1";
		//print "Query: {$query}"; 
		$result = @$db->query($query);
		if(! $result)
		{
			print "Invalid query result";
		}
		else 
		{
			//Process row
			$row = @$result->fetch_assoc();
		
			print"<tr>";
				print"<td><b> Earning/Share</b></td>";
				if($row['qEarningsPerShare'] < 0)
				{
					$num = number_format($row['qEarningsPerShare'], 2, '.', ',');
					print"<td style='color: Red;'>$num</td>";
				}
				else if($row['qEarningsPerShare'] == NULL)
				{
					print"<td>n/a</td>";
				}
				else
				{
					$num = number_format($row['qEarningsPerShare'], 2, '.', ',');
					print"<td>$num</td>";
				}
				
				print"<td style='text-align:right;'><b># Shrs Out.</b></td>";
				if($row['qTotalOutstandingSharesQty'] < 0)
				{
					$num = number_format($row['qTotalOutstandingSharesQty'], 0, '.', ',');
					print"<td style='text-align:right; color: Red;'>$num</td>";
				}
				else if($row['qTotalOutstandingSharesQty'] == NULL)
				{
					print"<td style='text-align:right;'>n/a</td>";
				}
				else 
				{
					$num = number_format($row['qTotalOutstandingSharesQty'], 0, '.', ',');
					print"<td style='text-align:right;'>$num</td>";
				}
			print"</tr>";
			
			print"<tr>";
				print"<td><b> Div/Share</b></td>";
				if($row['qCashDividendAmount'] < 0)
				{
					$num = number_format($row['qCashDividendAmount'], 2, '.', ',');
					print"<td style='color: Red;'>$num</td>";
				}
				else if($row['qCashDividendAmount'] == NULL)
				{
					print"<td>n/a</td>";
				}
				else 
				{
					$num = number_format($row['qCashDividendAmount'], 2, '.', ',');
					print"<td>$num</td>";
				}
				
				print"<td style='text-align:right;'><b>Div. Yield</b></td>";
				if($row['qCurrentYieldPct'] < 0)
				{
					$num = number_format($row['qCurrentYieldPct'], 2, '.', ',');
					print"<td style='text-align:right; color: Red;'>$num %</td>";
				}
				else if($row['qCurrentYieldPct'] == NULL)
				{
					print"<td style='text-align:right;'>n/a</td>";
				}
				else 
				{
					$num = number_format($row['qCurrentYieldPct'], 2, '.', ',');
					print"<td style='text-align:right;'>$num %</td>";
				}
			print"</tr>";
			
		print"</table>"; 
		
		@$result->free(); //Release memory for result
		//@$db->close();	//Close database connection
		$objDBUtil->Close();	//Close connection
		}
		} //end of processing form 
	?> <!--end php --> 

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