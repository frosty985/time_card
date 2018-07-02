<?php

require_once("config.php");
require_once("header.php");

/// create multi user, need session

if (!isset($_SESSION["uid"]))
{
  header("Location: login.php?ref=index.php");
  exit();
}

$startDay_sql = "SELECT sweek FROM user_comp WHERE uid = \"$_SESSION[uid]\" AND cid = \"$_SESSION[cid]\"";
$startDay = mysqli_fetch_array(mysqli_query($db, $startDay_sql));
$sweek = $startDay["sweek"];

if (!isset($_GET["start_date"]))
{
  $start_date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
}
else
{
  $start_date = $_GET["start_date"];
}

$startDay_sql = "SELECT sweek FROM user_comp WHERE uid = \"$_SESSION[uid]\" AND cid = \"$_SESSION[cid]\" ORDER BY edate DESC LIMIT 1;";
$startDay_query = mysqli_query($db, $startDay_sql);
if (mysqli_num_rows($startDay_query) == 1) {
  $startDay = mysqli_fetch_array($startDay_query);
  $sweek = $startDay["sweek"];
}
else { $sweek = 0; }
    
require_once("nav.php");
?>
    <div name="dbody" class="dbody">
    <nav>
	  <span>Current week is <?php echo date("D jS M 'y", mktime(0, 0, 0, date("m"), date("d", $start_date) - date("w", $start_date)+$sweek, date("Y", $start_date))); ?>&nbsp;&ndash;&nbsp;<?php echo date("D jS M 'y", mktime(0, 0, 0, date("m"), date("d", $start_date) + 6 - date("w", $start_date)+$sweek, date("Y", $start_date))) . "\n"; ?>
        <a href="?start_date=<?php echo mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)-7, date("Y", $start_date))?>" class="button button-tiny button-primary">Back a week</a>&nbsp;
        <a href="?start_date=<?php echo mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)+7, date("Y", $start_date))?>" class="button button-tiny button-primary">Forward a week</a>
      </span>
    </nav>
<?php
/// create a table of week
/// week start day Sunday
/// get today's day, build the current week
/// loop 0 - 6 (Sun - Sat)
/// highlight today


echo "    <div class=\"dTable\">\n";
echo "      <div class=\"dTableBody\">\n";
echo "        <div class=\"dTableHeadRow\">\n";
echo "          <div class=\"dTableHeadCell\">Day</div>\n";
echo "          <div class=\"dTableHeadCell\">Type</div>\n";
echo "          <div class=\"dTableHeadCell\">Start</div>\n";
echo "          <div class=\"dTableHeadCell\">Finish</div>\n";
echo "          <div class=\"dTableHeadCell\">Time</div>\n";
echo "          <div class=\"dTableHeadCell\">Action</div>\n";
echo "          <div class=\"dTableHeadCell\">&nbsp;</div>\n";
echo "        </div>\n";

for ($d = 0; $d < 7; $d++)
{
  /// create a "date" based on differance of days of today and loop number
  //if ($d < date("w"))
  //{
  //  $mkd = mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)-$d+date("w", $start_date), date("Y", $start_date));
  //}
  //elseif ($d > date("w"))
  //{
  //  $mkd = mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)+$d-date("w", $start_date)+$sweek, date("Y", $start_date));
  //}
  //else
  //{
  //  $mkd = mktime(0, 0, 0, date("m", $start_date), date("d", $start_date), date("Y", $start_date));
  //}
    $mkd = mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)+$d-date("w", $start_date)+$sweek, date("Y", $start_date));

  // build query
  $day_sql = "SELECT tid, sType, tdate, TIME_FORMAT(stime, \"%H:%i\") AS stime, TIME_FORMAT(ftime, \"%H:%i\") AS ftime, TIME_FORMAT(TIMEDIFF(ftime, stime), \"%H:%i\") AS dtime FROM time WHERE uid='$_SESSION[uid]' AND cid='$_SESSION[cid]' AND tdate = \"" . date("Y-m-d", $mkd) . "\"";
  $day_query = mysqli_query($db, $day_sql);
  while ($day = mysqli_fetch_array($day_query))
  {
    //echo "\t\t\t\t<div class=\"dTableRowGroup\">\n";
    echo "          <form class=\"dTableRow\" id=\"day_$d\" method=\"post\" action=\"update.php";
    if (isset($_GET["start_date"]))
	{
	  echo "?start_date=$_GET[start_date]";
	}
	echo "\" onsubmit=\"return valid_form(this)\">\n";
    echo "          <input type=\"hidden\" name=\"tid\" value=\"$day[tid]\" />\n";
    echo "          <div class=\"dTableCell\">" . date("l", $mkd) . "</div>\n";
    echo "            <div class=\"dTableCell\">\n";
    echo "              <select name=\"type\">\n";
    echo "                <option value=\"Shift\"";
    if ($day["sType"] == "Shift")
    {
      echo " selected";
    }
    echo ">Shift</option>\n";
    echo "                <option value=\"Break\"";
    if ($day["sType"] == "Break")
    {
      echo " selected";
    }
    echo ">Break</option>\n";
    echo "                <option value=\"Holiday\"";
    if ($day["sType"] == "Holiday")
    {
      echo " selected";
    }
    echo ">Holiday</option>\n";
      echo "                <option value=\"Bank Holiday\"";
    if ($day["sType"] == "Bank Holiday")
    {
      echo " selected";
    }
    echo ">Bank Holiday</option>\n";
    echo "              </select>\n";
    echo "            </div>\n";
    echo "            <div class=\"dTableCell\"><input name=\"start\" id=\"start\" placeeholder=\"HH:mm\" value=\"$day[stime]\"></div>\n";
    echo "            <div class=\"dTableCell\"><input name=\"finish\" id=\"finish\" placeholder=\"HH:mm\" value=\"$day[ftime]\"></div>\n";
    echo "            <div class=\"dTableCell\"><input name=\"time\" id=\"time\" value=\"$day[dtime]\"></div>\n";
    echo "            <div class=\"dTableCell\">&nbsp;</div>\n";
    echo "            <div class=\"dTableCell\">\n";
    echo "              <input type=\"submit\" name=\"update\" value=\"Update\" class=\"button button-tiny button-primary\">\n";
    echo "              <input type=\"Submit\" name=\"delete\" value=\"Delete\" class=\"button button-tiny button-primary\">\n";
    echo "            </div>\n";
    echo "          </form>\n";
  }

  echo "        <form class=\"dTableRow\" id=\"day_$d\" method=\"post\" action=\"insert.php";
  if (isset($_GET["start_date"]))
  {
	  echo "?start_date=$_GET[start_date]";
  }
  echo "\" onsubmit=\"return valid_form(this)\">\n";
  echo "          <input type=\"hidden\" name=\"tdate\" value=\"". date("Y-m-d", $mkd) . "\">\n";
  echo "          <div class=\"dTableCell\">" . date("l", $mkd) . "</div>\n";
  echo "            <div class=\"dTableCell\">\n";
  echo "              <select name=\"type\">\n";
  echo "                <option value=\"Shift\">Shift</option>\n";
  echo "                <option value=\"Break\">Break</option>\n";
  echo "                <option value=\"Holiday\">Holiday</option>\n";
  echo "                <option value=\"Bank Holiday\">Bank Holiday</option>\n";
  echo "              </select>\n";
  echo "            </div>\n";
  echo "            <div class=\"dTableCell\"><input name=\"start\" id=\"start_$d\" placeholder=\"HH:mm\" onchange=\"calc_time(this)\"></div>\n";
  echo "            <div class=\"dTableCell\"><input name=\"finish\" id=\"finish_$d\" placeholder=\"HH:mm\" onchange=\"calc_time(this)\"></div>\n";
  echo "            <div class=\"dTableCell\"><input name=\"time\" id=\"time_$d\"></div>\n";
  echo "            <div class=\"dTableCell\"><input type=\"submit\" name=\"save\" value=\"Save\" class=\"button button-tiny button-primary\"></div>\n";
  echo "            <div class=\"dTableCell\">&nbsp;</div>\n";
  echo "          </form>\n";
}
//SELECT SUM(total) as total, SUM(rate) as rate FROM (SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(sType="Break",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime))))), "%H:%i") AS "total", ROUND(sum((TIME_TO_SEC(IF(sType="Break",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime))))/60/60)*rate,2) AS rate FROM `time` JOIN user_comp on user_comp.uid = time.uid WHERE time.uid='0a25bf3160d211e899675254004146e6' AND time.cid='a406ab1860d111e899675254004146e6' AND tdate >= "2018-05-27" AND tdate <= "2018-06-03" GROUP BY sType, stime, ftime, rate) AS maths

$total_sql = " SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(total))), \"%H:%i\") as total, SUM(rate) as rate FROM ";
$total_sql .= " (SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(sType=\"Break\",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime))))), \"%H:%i\") AS \"total\", ";
$total_sql .= " ROUND(SUM(TIME_TO_SEC(IF(sType=\"Break\",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime)))/60/60)*rate,2) AS \"rate\" ";
$total_sql .= " FROM `time` ";
$total_sql .= " JOIN user_comp on user_comp.uid = time.uid ";
$total_sql .= " WHERE time.uid='$_SESSION[uid]' AND time.cid='$_SESSION[cid]' AND tdate >= \"" . date("Y-m-d", mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)-date("w", $start_date), date("Y", $start_date))) . "\" ";
$total_sql .= " AND tdate <= \"" . date("Y-m-d", mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)+7-date("w", $start_date), date("Y", $start_date))) . "\" ";
$total_sql .= " GROUP BY rate, sType, stime, ftime) as maths ;";

//echo $total_sql;
$total_query = mysqli_query($db, $total_sql);
$total = mysqli_fetch_array($total_query);

//echo "\t\t\t\t<div class=\"dTableRowGroup\">\n";
echo "\t\t\t\t<div class=\"dTableRow\">\n";
echo "\t\t\t\t\t<div class=\"dTableCell\">&nbsp;</div>\n";
echo "\t\t\t\t\t<div class=\"dTableCell\">&nbsp;</div>\n";
echo "\t\t\t\t\t<div class=\"dTableCell\">Weekly Total:</div>\n";
echo "\t\t\t\t\t<div class=\"dTableCell\">$total[total]</div>\n";
echo "\t\t\t\t\t<div class=\"dTableCell\">Weekly Pay:</div>\n";
echo "\t\t\t\t\t<div class=\"dTableCell\">£ $total[rate]</div>\n";
echo "\t\t\t\t\t<div class=\"dTableCell\">&nbsp;</div>\n";

//echo "\t\t\t</div\n";
echo "\t\t</div>\n";
echo "\t</div>\n";
echo "</div>\n";
