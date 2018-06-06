<?php

require_once("config.php");
require_once("header.php");

/// create multi user, need session
session_start();

if (!isset($_SESSION["uid"]))
{
  header("Location: login.php?ref=index.php");
  exit();
}


if (!isset($_GET["start_date"]))
{
  $start_date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
}
else
{
  $start_date = $_GET["start_date"];
}
?>

<script>

function calc_time(input) {
  // get row number
  var row = input.id.substring(input.id.length-1, input.id.length)
  // var inType = inp.name.substring(0, inp.name.length-2)

  // create vars for time
  var starttime = document.getElementById('start_' + row).value
  var stoptime = document.getElementById('finish_' + row).value

  // build date
  var startdate = new Date("01/01/2001 " + starttime)
  var stopdate = new Date("01/01/2001 " + stoptime)

  // calculate differance
  var hours = stopdate - startdate
  //alert(hours)

  // show differance
  document.getElementById('time_' + row).value = new Date(hours).toISOString().substr(11,8)
}

function valid_time(input)
{
  re = /^(\d{1,2}):(\d{2})/;
  if (input.value != "")
  {
    if (regs = input.value.match(re))
    {
      if (regs[1] < 23)
      {
        if (regs[2] > 59)
        {
          alert("Invalid time");
          return false;
        }
      }
      else
      {
        alert("Invalid time");
        return false;
      }
    }
    return true;
  }
  alert("Invalid time");
  return false;
}

function valid_form(fInput) {
  if (fInput.type.value != "Holiday" || fInput.type.value != "Bank Holiday")
  {
	if (!valid_time(fInput.start)) return false;
	if (!valid_time(fInput.finish)) return false;
	return true;
  }
}


</script>


<div name="page">
  <div name="header">
    Welcome <?php echo "$user[fname]"; ?>.<br />
    <?php
    $comp_sql = "SELECT cname FROM user_comp JOIN company ON company.cid = user_comp.cid WHERE uid = \"$_SESSION[uid]\" ORDER BY edate DESC LIMIT 1;";
    $comp_query = mysqli_query($db, $comp_sql);
    if (mysqli_num_rows($comp_query) != 0)
    {
      $comp = mysqli_fetch_array($comp_query);
      echo "Viewing time card for $comp[cname]<br />\n";
      echo "<a href=\"company.php\">Adjust company details</a><br />\n";
    }
    else
    {
      echo "<a href=\"company.php\">Add a company</a><br />\n";
    }
    ?>
    Current date is <?php echo date("D M jS", $start_date); ?><br />
    <nav>
      <a href="?start_date=<?php echo mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)-7, date("Y", $start_date))?>">Back a week</a>
      &nbsp;
      <a href="?start_date=<?php echo mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)+7, date("Y", $start_date))?>">Forward a week</a>
    </nav>
  </div>

  <div name="dbody">
<?php
/// create a table of week
/// week start day Sunday
/// get today's day, build the current week
/// loop 0 - 6 (Sun - Sat)
/// highlight today


echo "\t\t<div class=\"dTable\">\n";
echo "\t\t\t<div class=\"dTableBody\">\n";

echo "\t\t\t\t<div class=\"dTableHeadRow\">\n";

echo "\t\t\t\t\t<div class=\"dTableHeadCell\">Day</div>\n";
echo "\t\t\t\t\t<div class=\"dTableHeadCell\">Type</div>\n";
echo "\t\t\t\t\t<div class=\"dTableHeadCell\">Start</div>\n";
echo "\t\t\t\t\t<div class=\"dTableHeadCell\">Finish</div>\n";
echo "\t\t\t\t\t<div class=\"dTableHeadCell\">Time</div>\n";
echo "\t\t\t\t\t<div class=\"dTableHeadCell\">Action</div>\n";

echo "\t\t\t\t</div>\n";

for ($d = 0; $d < 7; $d++)
{
  /// create a "date" based on differance of days of today and loop number
  //if ($d < date("w"))
  //{
  //  $mkd = mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)-$d+date("w", $start_date), date("Y", $start_date));
  //}
  //elseif ($d > date("w"))
  //{
    $mkd = mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)+$d-date("w", $start_date), date("Y", $start_date));
  //}
  //else
  //{
  //  $mkd = mktime(0, 0, 0, date("m", $start_date), date("d", $start_date), date("Y", $start_date));
  //}

  // build query
  $day_sql = "SELECT tid, sType, tdate, TIME_FORMAT(stime, \"%H:%i\") AS stime, TIME_FORMAT(ftime, \"%H:%i\") AS ftime, TIME_FORMAT(TIMEDIFF(ftime, stime), \"%H:%i\") AS dtime FROM time WHERE uid='$uid' AND cid='$cid' AND tdate = \"" . date("Y-m-d", $mkd) . "\"";
  $day_query = mysqli_query($db, $day_sql);
  while ($day = mysqli_fetch_array($day_query))
  {
    echo "\t\t\t\t<div class=\"dTableRowGroup\">\n";
    echo "\t\t\t\t\t<form class=\"dTableRow\" id=\"day_$d\" method=\"post\" action=\"update.php";
    if (isset($_GET["start_date"]))
	{
	  echo "?start_date=$_GET[start_date]";
	}
	echo "\" onsubmit=\"return valid_form(this)\">\n";
    echo "\t\t\t\t\t\t<input type=\"hidden\" name=\"tid\" value=\"$day[tid]\" />\n";
    echo "\t\t\t\t\t\t<div class=\"dTableCell\">" . date("l", $mkd) . "</div>\n";
    echo "\t\t\t\t\t\t<div class=\"dTableCell\">\n";
    echo "\t\t\t\t\t\t\t<select name=\"type\">\n";
    echo "\t\t\t\t\t\t\t\t<option value=\"Shift\"";
    if ($day["sType"] == "Shift")
    {
      echo " selected";
    }
    echo ">Shift</option>\n";
    echo "\t\t\t\t\t\t\t\t<option value=\"Break\"";
    if ($day["sType"] == "Break")
    {
      echo " selected";
    }
    echo ">Break</option>\n";
    echo "\t\t\t\t\t\t\t\t<option value=\"Holiday\"";
    if ($day["sType"] == "Holiday")
    {
      echo " selected";
    }
    echo ">Holiday</option>\n";
      echo "\t\t\t\t\t\t\t\t<option value=\"Bank Holiday\"";
    if ($day["sType"] == "Bank Holiday")
    {
      echo " selected";
    }
    echo ">Bank Holiday</option>\n";
    echo "\t\t\t\t\t\t\t</select>\n";
    echo "\t\t\t\t\t\t</div>\n";
    echo "\t\t\t\t\t\t<div class=\"dTableCell\"><input name=\"start\" id=\"start\" placeeholder=\"HH:mm\" value=\"$day[stime]\"></div>\n";
    echo "\t\t\t\t\t\t<div class=\"dTableCell\"><input name=\"finish\" id=\"finish\" placeholder=\"HH:mm\" value=\"$day[ftime]\"></div>\n";
    echo "\t\t\t\t\t\t<div class=\"dTableCell\"><input name=\"time\" id=\"time\" value=\"$day[dtime]\"></div>\n";
    echo "\t\t\t\t\t\t<div class=\"dTableCell\">&nbsp;</div>\n";
    echo "\t\t\t\t\t\t<div class=\"dTableCell\">\n";
    echo "\t\t\t\t\t\t\t<input type=\"submit\" name=\"update\" value=\"Update\">\n";
    echo "\t\t\t\t\t\t\t<input type=\"Submit\" name=\"delete\" value=\"Delete\">\n";
    echo "\t\t\t\t\t\t</div>\n";
    echo "\t\t\t\t\t</form>\n";
    
    echo "\t\t\t\t</div>\n";
  }

  echo "\t\t\t\t<div class=\"dTableRowGroup\">\n";
  //echo "\t\t\t\t\t<div class=\"dTableCell\">&nbsp;</div>\n";
  echo "\t\t\t\t\t\t<form class=\"dTableRow\" id=\"day_$d\" method=\"post\" action=\"insert.php";
  if (isset($_GET["start_date"]))
  {
	  echo "?start_date=$_GET[start_date]";
  }
  echo "\" onsubmit=\"return valid_form(this)\">\n";
  echo "\t\t\t\t\t\t\t<input type=\"hidden\" name=\"tdate\" value=\"". date("Y-m-d", $mkd) . "\">\n";
  echo "\t\t\t\t\t\t\t<div class=\"dTableCell\">" . date("l", $mkd) . "</div>\n";
  echo "\t\t\t\t\t\t\t<div class=\"dTableCell\">\n";
  echo "\t\t\t\t\t\t\t\t<select name=\"type\">\n";
  echo "\t\t\t\t\t\t\t\t\t<option value=\"Shift\">Shift</option>\n";
  echo "\t\t\t\t\t\t\t\t\t<option value=\"Break\">Break</option>\n";
  echo "\t\t\t\t\t\t\t\t\t<option value=\"Holiday\">Holiday</option>\n";
  echo "\t\t\t\t\t\t\t\t\t<option value=\"Bank Holiday\">Bank Holiday</option>\n";
  echo "\t\t\t\t\t\t\t\t</select>\n";
  echo "\t\t\t\t\t\t\t</div>\n";

  echo "\t\t\t\t\t\t\t<div class=\"dTableCell\"><input name=\"start\" id=\"start_$d\" placeholder=\"HH:mm\" onchange=\"calc_time(this)\"></div>\n";
  echo "\t\t\t\t\t\t\t<div class=\"dTableCell\"><input name=\"finish\" id=\"finish_$d\" placeholder=\"HH:mm\" onchange=\"calc_time(this)\"></div>\n";
  echo "\t\t\t\t\t\t\t<div class=\"dTableCell\"><input name=\"time\" id=\"time_$d\"></div>\n";
  echo "\t\t\t\t\t\t\t<div class=\"dTableCell\"><input type=\"submit\" name=\"save\" value=\"Save\"></div>\n";
  echo "\t\t\t\t\t\t</form>\n";

  echo "\t\t\t\t\t</div>";

}
//SELECT SUM(total) as total, SUM(rate) as rate FROM (SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(sType="Break",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime))))), "%H:%i") AS "total", ROUND(sum((TIME_TO_SEC(IF(sType="Break",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime))))/60/60)*rate,2) AS rate FROM `time` JOIN user_comp on user_comp.uid = time.uid WHERE time.uid='0a25bf3160d211e899675254004146e6' AND time.cid='a406ab1860d111e899675254004146e6' AND tdate >= "2018-05-27" AND tdate <= "2018-06-03" GROUP BY sType, stime, ftime, rate) AS maths

$total_sql = " SELECT SUM(total) as total, SUM(rate) as rate FROM ";
$total_sql .= " (SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(sType=\"Break\",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime))))), \"%H:%i\") AS \"total\", ";
$total_sql .= " ROUND(SUM(TIME_TO_SEC(IF(sType=\"Break\",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime)))/60/60)*rate,2) AS \"rate\" ";
$total_sql .= " FROM `time` ";
$total_sql .= " JOIN user_comp on user_comp.uid = time.uid ";
$total_sql .= " WHERE time.uid='$uid' AND time.cid='$cid' AND tdate >= \"" . date("Y-m-d", mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)-date("w", $start_date), date("Y", $start_date))) . "\" ";
$total_sql .= " AND tdate <= \"" . date("Y-m-d", mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)+7-date("w", $start_date), date("Y", $start_date))) . "\" ";
$total_sql .= " GROUP BY rate, sType, stime, ftime) as maths ;";

//echo $total_sql;
$total_query = mysqli_query($db, $total_sql);
$total = mysqli_fetch_array($total_query);

echo "\t\t\t\t<div class=\"dTableRowGroup\">\n";
echo "\t\t\t\t<div class=\"dTableRow\">\n";
echo "\t\t\t\t\t<div class=\"dTableCell\">&nbsp;</div>\n";
echo "\t\t\t\t\t<div class=\"dTableCell\">&nbsp;</div>\n";
echo "\t\t\t\t\t<div class=\"dTableCell\">Weekly Total:</div>\n";
echo "\t\t\t\t\t<div class=\"dTableCell\">$total[total]</div>\n";
echo "\t\t\t\t\t<div class=\"dTableCell\">Weekly Pay:</div>\n";
echo "\t\t\t\t\t<div class=\"dTableCell\">£ $total[rate]</div>\n";
echo "\t\t\t</div\n";
echo "\t\t</div>\n";
echo "\t</div>\n";
echo "</div>\n";
