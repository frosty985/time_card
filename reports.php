<?php

require_once("config.php");
require_once("header.php");

require_once("nav.php");

if (!isset($_SESSION["uid"]))
{
	header("Location: login.php?ref=reports.php");
	exit();
}

$monday = strtotime('last monnday',strtotime('tomorrow'));

echo "  <div name=\"dbody\">\n";
echo "Pay Year";
echo "<br />";

/// work out tax week number

//echo "April 6th: " . date("z W w l", mktime(0, 0, 0, 4, 6, date("Y") ) );
//echo "<br />";
//echo "Today: " . date("z W w l");

//echo "<br />";

$pay_info = mysqli_fetch_array(mysqli_query($db, "SELECT ptype, pdate, cdays FROM user_comp WHERE uid = \"$_SESSION[uid]\" ORDER BY udate DESC LIMIT 1"));
//echo $pay_info["pdate"];
echo "<br />";
echo "\t\t\t\tTAX YEAR " . date("Y", mktime(0, 0, 0, 4, 6, date("Y"))) . "-" . date("Y", mktime(0, 0, 0, 4, 6, date("Y")+1));

$nextpaydate = mktime(0, 0, 0, substr($pay_info["pdate"], 5, 2), substr($pay_info["pdate"], 8, 2), substr($pay_info["pdate"], 0, 4));

echo "<div class=\"dTable\">\n";
echo "\t<div class=\"dTableBody\">\n";
//echo "\t\t<div class=\"dTableHeadRow\">\n";
//echo "\t\t\t<div class=\"dTableCell\">\n";
//echo "\t\t\t</div>\n";
//echo "\t\t</div>\n";

echo "\t\t<div class=\"dTableHeadRow\">\n";
echo "\t\t\t<div class=\"dTableCell\">\n";
echo "\t\t\t\tPay Date\n";
echo "\t\t\t</div>\n";
echo "\t\t\t<div class=\"dTableCell\">\n";
echo "\t\t\t\tHours Worked\n";
echo "\t\t\t</div>\n";
echo "\t\t\t<div class=\"dTableCell\">\n";
echo "\t\t\t\tGross Pay\n";
echo "\t\t\t</div>\n";
echo "\t\t</div>\n";

if ($pay_info["ptype"] == "month")
{
  while (date("U", $nextpaydate) <= date("U", mktime(0, 0, 0, 4, 6, date("Y")+1)))
  {
    if (date("U", $nextpaydate) > date("U", mktime(0, 0, 0, 4, 6, date("Y"))))
	{
	  echo "\t\t<div class=\"dTableRow\">\n";
	  echo "\t\t\t<div class=\"dTableCell\">\n";
	  echo "\t\t\t\t" . date("Y-m-d", $nextpaydate);
	  echo "\t\t\t</div>\n";
	  
	  // cut off
	  if (isset($lastpaydate))
	  {
        $cutoffdates = date("Y-m-d", strtotime('this week', mktime(0, 0, 0, date("m", $lastpaydate), date("d", $lastpaydate), date("Y", $lastpaydate))));
      }
      $cutoffdatee =  date("Y-m-d", strtotime('this week', mktime(0, 0, 0, date("m", $nextpaydate), date("d", $nextpaydate), date("Y", $nextpaydate))));
			
      $row_sql = " SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(total))), \"%H:%i\") as total, SUM(rate) as rate FROM ";
      $row_sql .= " (SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(sType=\"Break\",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime))))), \"%H:%i\") AS \"total\", ";
      $row_sql .= " ROUND(SUM(TIME_TO_SEC(IF(sType=\"Break\",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime)))/60/60)*rate,2) AS \"rate\" ";
      $row_sql .= " FROM `time` ";
      $row_sql .= " JOIN user_comp on user_comp.uid = time.uid ";
      $row_sql .= " WHERE time.uid='$_SESSION[uid]' AND time.cid='$_SESSION[cid]' AND tdate >= \"$cutoffdates\" ";
      $row_sql .= " AND tdate <= \"$cutoffdatee\" ";
      $row_sql .= " GROUP BY rate, sType, stime, ftime) as maths ;";

      $row_query = mysqli_query($db, $row_sql);
      $row = mysqli_fetch_array($row_query);

      echo "\t\t\t<div class=\"dTableCell\">\n";
      echo "\t\t\t\t $row[total]\n";
      echo "\t\t\t</div>\n";
      echo "\t\t\t<div class=\"dTableCell\">\n";
      echo "\t\t\t\t&pound;&nbsp;$row[rate]";
      echo "\t\t\t</div>\n";

      echo "\t\t</div>\n";
        
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
	  echo "\t\t<div class=\"dTableRow\">\n";
	  echo "\t\t\t<div class=\"dTableCell\">\n";
	  echo "\t\t\t\t" . date("Y-m-d", $nextpaydate);
	  echo "\t\t\t</div>\n";

      // cut off 
      if (isset($lastpaydate))
      {
        $cutoffdates = date("Y-m-d", mktime(0, 0, 0, date("m", $lastpaydate), date("d", $lastpaydate)-$pay_info["cdays"]+1, date("Y", $lastpaydate)));
        echo " [ $cutoffdates <-> ";
      }
      $cutoffdatee =  date("Y-m-d", mktime(0, 0, 0, date("m", $nextpaydate), date("d", $nextpaydate)-$pay_info["cdays"], date("Y", $nextpaydate)));
      echo " $cutoffdatee ] ";
      $row_sql = " SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(total))), \"%H:%i\") as total, SUM(rate) as rate FROM ";
      $row_sql .= " (SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(sType=\"Break\",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime))))), \"%H:%i\") AS \"total\", ";
      $row_sql .= " ROUND(SUM(TIME_TO_SEC(IF(sType=\"Break\",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime)))/60/60)*rate,2) AS \"rate\" ";
      $row_sql .= " FROM `time` ";
      $row_sql .= " JOIN user_comp on user_comp.uid = time.uid ";
      $row_sql .= " WHERE time.uid='$_SESSION[uid]' AND time.cid='$_SESSION[cid]' AND tdate >= \"$cutoffdates\" ";
      $row_sql .= " AND tdate <= \"$cutoffdatee\" ";
      $row_sql .= " GROUP BY rate, sType, stime, ftime) as maths ;";

      $row_query = mysqli_query($db, $row_sql);
      $row = mysqli_fetch_array($row_query);

      echo "\t\t\t<div class=\"dTableCell\">\n";
      echo "\t\t\t\t $row[total]\n";
      echo "\t\t\t</div>\n";
      echo "\t\t\t<div class=\"dTableCell\">\n";
      echo "\t\t\t\t&pound;&nbsp;$row[rate]";
      echo "\t\t\t</div>\n";
    }
    // create next pay date
    $lastpaydate = $nextpaydate;
    $nextpaydate = date("U", mktime(0, 0, $extra, date("m", $nextpaydate), date("d", $nextpaydate), date("Y", $nextpaydate)));		
    $I++;
    echo "</div>\n";
  }
}
echo "</div>\n";
echo "</div>\n";


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
