<?php

require_once("config.php");
require_once("header.php");
session_start();

require_once("nav.php");

if (!isset($_SESSION["uid"]))
{
	header("Location: login.php?ref=reports.php");
	exit();
}

$monday = strtotime('last monnday',strtotime('tomorrow'));

echo "  <div name=\"dbody\">\n";
echo "Pay Period";
echo "<br />";

/// work out tax week number

//echo "April 6th: " . date("z W w l", mktime(0, 0, 0, 4, 6, date("Y") ) );
//echo "<br />";
//echo "Today: " . date("z W w l");

//echo "<br />";
echo "TAX YEAR " . date("Y", mktime(0, 0, 0, 4, 6, date("Y"))) . "-" . date("Y", mktime(0, 0, 0, 4, 6, date("Y")+1));

echo "<br />";
$pay_info = mysqli_fetch_array(mysqli_query($db, "SELECT ptype, pdate, cdays FROM user_comp WHERE uid = \"$_SESSION[uid]\" ORDER BY udate DESC LIMIT 1"));
//echo $pay_info["pdate"];
echo "<br />";

$nextpaydate = mktime(0, 0, 0, substr($pay_info["pdate"], 5, 2), substr($pay_info["pdate"], 8, 2), substr($pay_info["pdate"], 0, 4));

if ($pay_info["ptype"] == "month")
{
	while (date("U", $nextpaydate) <= date("U", mktime(0, 0, 0, 4, 6, date("Y")+1)))
	{
		if (date("U", $nextpaydate) > date("U", mktime(0, 0, 0, 4, 6, date("Y"))))
		{
			echo "Pay Date " . date("Y-m-d", $nextpaydate);
			
			// cut off 
			if (isset($lastpaydate))
			{
				$cutoffdates = date("Y-m-d", strtotime('this week', mktime(0, 0, 0, date("m", $lastpaydate), date("d", $lastpaydate), date("Y", $lastpaydate))));
			}
			$cutoffdatee =  date("Y-m-d", strtotime('this week', mktime(0, 0, 0, date("m", $nextpaydate), date("d", $nextpaydate), date("Y", $nextpaydate))));
			
			$total_sql = " SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(total))), \"%H:%i\") as total, SUM(rate) as rate FROM ";
			$total_sql .= " (SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(sType=\"Break\",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime))))), \"%H:%i\") AS \"total\", ";
			$total_sql .= " ROUND(SUM(TIME_TO_SEC(IF(sType=\"Break\",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime)))/60/60)*rate,2) AS \"rate\" ";
			$total_sql .= " FROM `time` ";
			$total_sql .= " JOIN user_comp on user_comp.uid = time.uid ";
			$total_sql .= " WHERE time.uid='$uid' AND time.cid='$cid' AND tdate >= \"$cutoffdates\" ";
			$total_sql .= " AND tdate <= \"$cutoffdatee\" ";
			$total_sql .= " GROUP BY rate, sType, stime, ftime) as maths ;";
			
			
			
			$total_query = mysqli_query($db, $total_sql);
			$total = mysqli_fetch_array($total_query);

			echo " $total[total] - £$total[rate]";
			
			echo "<br />";
		}
		// create next pay date
		$lastpaydate = $nextpaydate;
		$nextpaydate = date("U", mktime(0, 0, 0, date("m", $nextpaydate)+1, date("d", $nextpaydate), date("Y", $nextpaydate)));		
	}
}

//if ($pay_info["$ptype"] == "4")
else
{
	$extra = (60 * 60 * 24 * 7);
	if ($pay_info["ptype"] == "2week")
	{
		$extra = $extra + $extra;
	}
	elseif ($pay_info["ptype"] == "4week")
	{
		$extra = $extra * 4;
	}
	while (date("U", $nextpaydate) <= date("U", mktime(0, 0, 0, 4, 6, date("Y")+1)))
	{
		if (date("U", $nextpaydate) > date("U", mktime(0, 0, 0, 4, 6, date("Y"))))
		{
			echo "Pay Date " . date("Y-m-d", $nextpaydate);
			
			// cut off 
			if (isset($lastpaydate))
			{
				$cutoffdates = date("Y-m-d", mktime(0, 0, 0, date("m", $lastpaydate), date("d", $lastpaydate)-$pay_info["cdays"]+1, date("Y", $lastpaydate)));
				echo " [ $cutoffdates <-> ";
			}
			$cutoffdatee =  date("Y-m-d", mktime(0, 0, 0, date("m", $nextpaydate), date("d", $nextpaydate)-$pay_info["cdays"], date("Y", $nextpaydate)));
			echo " $cutoffdatee ] ";
			$total_sql = " SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(total))), \"%H:%i\") as total, SUM(rate) as rate FROM ";
			$total_sql .= " (SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(sType=\"Break\",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime))))), \"%H:%i\") AS \"total\", ";
			$total_sql .= " ROUND(SUM(TIME_TO_SEC(IF(sType=\"Break\",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime)))/60/60)*rate,2) AS \"rate\" ";
			$total_sql .= " FROM `time` ";
			$total_sql .= " JOIN user_comp on user_comp.uid = time.uid ";
			$total_sql .= " WHERE time.uid='$uid' AND time.cid='$cid' AND tdate >= \"$cutoffdates\" ";
			$total_sql .= " AND tdate <= \"$cutoffdatee\" ";
			$total_sql .= " GROUP BY rate, sType, stime, ftime) as maths ;";

			$total_query = mysqli_query($db, $total_sql);
			$total = mysqli_fetch_array($total_query);

			echo " $total[total] - £$total[rate]";
			echo "<br />";
		}
		// create next pay date
		$lastpaydate = $nextpaydate;
		$nextpaydate = date("U", mktime(0, 0, $extra, date("m", $nextpaydate), date("d", $nextpaydate), date("Y", $nextpaydate)));		
		$I++;
	}
}
/*
echo "<br />\n";
echo "<br />\n";

for ($m = 0; $m <= 12; $m++)
{
	echo "$m: " . date("z W w l - d-m-Y", mktime(0, 0, 0, 4 + $m, 6, date("Y") ) );
	$diff = date("U", mktime(0, 0, 0, 4+$m, 6+1, date("Y") ) ) - date("U");
	//$diff = date("U") - date("U", mktime(0 - date("H"), 0-date("i"), 0-date("S"), 4 + $m, 6, date("Y") ) );
	//echo " ... " . date("U") . " - " . date("U", mktime(0,0,0,4,6,date("Y"))) . " = $diff > ";
	echo " " . round($diff / 60 / 60 / 24 /7, 0, PHP_ROUND_HALF_UP);
	echo "<br />\n";
	
}



echo "<br />\n";
echo "<br />\n";

$days = 0;
for ($m = 0; $m <= 12; $m++)
{
	$diff = round((date("U", mktime(0, 0, 0, 4, 7+$days, date("Y") ) ) - date("U")) / 60 / 60 / 24 /7, 0, PHP_ROUND_HALF_UP);
	echo "$m: " . date("z W w l - d-m-Y", mktime(0, 0, 0, 4, 6+$days, date("Y") ) ) .  " > $diff ($days)<br />\n";
	$days = $days + 28;
}
*/
//require_once("footer.php");
