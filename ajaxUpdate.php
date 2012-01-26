<?php 
/*	Coder: Truc Do - Round2 : Laptop Dept 
*	Filename : ajaxUpdate.php 
*	Last Edit Date: 01 January 2012 
*	Version 2,0 
*/
include("includes/config.php");

$opt = (isset($_GET["ac"])) ? $_GET["ac"] : "";

switch ($opt)
{
	case 'tech':
		$query = mysql_query("SELECT count(id) As total FROM  daily_work														
								WHERE badgeid = '" . mysql_real_escape_string($_GET['userid']) . "'
								AND DATE_FORMAT(Repaired_date,'%m/%d/%Y') = '".date('m/d/Y',time())."'
								AND QCstatus = 3");
		$row = mysql_fetch_array($query);					
		$row==0 ? print ('N/A') : print ($row['total'].'&nbsp;&nbsp;');		
		break;	
			
	case 'srep': 
		$sreport = mysql_query("SELECT count_date, sum(Qty_repaired) AS Qty, sum(Qty_scrapped) AS scrapped
									FROM daily_report 
									WHERE DATE_FORMAT(count_date,'%m/%d/%Y') = '".date('m/d/Y',time())."'");												
		
		$row = mysql_fetch_array($sreport);		
		$row==0 ? print ('N/A') : print ($row['Qty'] . ' ~ ' . $row['scrapped']);													
		break;
		
	case 'srepdetail':
		//stats 10 daily
		include('includes/functions.php');
		$sreportDetail = mysql_query("SELECT * FROM daily_report 
										WHERE DATE_FORMAT(count_date,'%m/%d/%Y') = '".date('m/d/Y',time())."'
										GROUP BY badgeid
										ORDER BY Qty_repaired DESC
										LIMIT 0 , 30");
		$cnt = 0;
		echo '<tr class="tcat"><td colspan="5">Today\'s Status</td></tr>';
		echo '<tr class="thead">
            <td width="5%">#</td>
            <td width="40%">Full Name</td>
            <td width="15%">BadgeID</td>
            <td width="20%">Completed</td>
            <td width="20%">Scrapped</td>
          </tr>
		';
	
		while($row = mysql_fetch_array($sreportDetail))				
		{
			$cnt++;
			echo '<tr>
            <td>&nbsp;'.$cnt.'</td>
            <td>&nbsp;'.getNameByBadgeId($row[1]).'</td>
            <td>&nbsp;'.$row[1].'</td>
            <td>&nbsp;'.$row[2].'</td>
            <td>&nbsp;'.$row[4].'</td>
          </tr>';
		  
		}
		break;
		
	case 'scaprep':
		$getscrap_Qty = mysql_fetch_row(mysql_query("SELECT *,  count(Scrapped_date) FROM  scrapped
													WHERE Accepted = 0"));
		
		$getscrap_Qty==0 ?  $scapped_qty = 0 : 	$scapped_qty = 	$getscrap_Qty[5];
		echo "<a href='cpanel.php?job=scrapcheck' target='_parent'><strong>".$scapped_qty."</strong></a>";
		break;
			
	
}//end switch
?>