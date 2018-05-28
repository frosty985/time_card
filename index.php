
<?php

require_once("config.php");
require_once("header.php");

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

function calc_time(inp) {
  // get row number
  var row = inp.name.substring(inp.name.length-1, inp.name.length)
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

</script>


<div name="page">
  <div name="header">
    Welcome <?php echo "$user[fname]"; ?>. Current date is <?php echo date("D M jS", $start_date); ?>
    <nav>
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
echo "\t\t\t\t\t<div class=\"dTableHeadCell\">Finsih</div>\n";
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

  echo "\t\t\t\t<div class=\"dTableRow\">\n";

  echo "\t\t\t\t\t<div class=\"dTableCell\">" . date("l", $mkd) . "</div>\n";


  // build query
  $day_sql = "SELECT sType, tdate, stime, ftime, TIME_FORMAT(TIMEDIFF(ftime, stime), \"%H:%i\") AS dtime FROM time WHERE uid='$uid' AND cid='$cid' AND tdate = \"" . date("Y-m-d", $mkd) . "\"";
  $day_query = mysqli_query($db, $day_sql);
  while ($day = mysqli_fetch_array($day_query))
  {
    echo "\t\t\t\t\t<div class=\"dTableCell\">\n";
    echo "\t\t\t\t\t\t<select name=\"type_$d\">\n";
    echo "\t\t\t\t\t\t\t<option value=\"Shift\"";
    if ($day["sType"] == "Shift")
    {
      echo " selected";
    }
    echo ">Shift</option>\n";
    echo "\t\t\t\t\t\t\t<option value=\"Break\"";
    if ($day["sType"] == "Break")
    {
      echo " selected";
    }
    echo ">Break</option>\n";
    echo "\t\t\t\t\t\t</select>\n";
    echo "\t\t\t\t\t</div>\n";
    echo "\t\t\t\t\t<div class=\"dTableCell\"><input name=\"start\" id=\"start_$d_\" placeeholder=\"HH:mm\" value=\"$day[stime]\"></div>\n";
    echo "\t\t\t\t\t<div class=\"dTableCell\"><input name=\"finish\" id=\"finish_$d_\" placeholder=\"HH:mm\" value=\"$day[ftime]\"></div>\n";
    echo "\t\t\t\t\t<div class=\"dTableCell\"><input name=\"time\" id=\"time_$d_\" value=\"$day[dtime]\"></div>\n";
    //echo "\t\t\t\t\t<div class=\"dTableCell\"><input type=\"Submit\" value=\"Delete\"></div>\n";
    echo "\t\t\t\t</div>";

    echo "\t\t\t\t<div class=\"dTableRow\">\n";
    echo "\t\t\t\t\t<div class=\"dTableCell\">&nbsp;</div>\n";

  }

  echo "\t\t\t\t\t<div class=\"dTableCell\">\n";
  echo "\t\t\t\t\t\t<select name=\"type_$d\">\n";
  echo "\t\t\t\t\t\t\t<option value=\"Shift\">Shift</option>\n";
  echo "\t\t\t\t\t\t\t<option value=\"Break\">Break</option>\n";
  echo "\t\t\t\t\t\t</select>\n";
  echo "\t\t\t\t\t</div>\n";

  echo "\t\t\t\t\t<form method=\"post\" action=\"insert.php\">";
  echo "\t\t\t\t\t\t<div class=\"dTableCell\"><input name=\"start\" id=\"start_$d\" placeholder=\"HH:mm\" onchange=\"calc_time(this)\"></div>\n";
  echo "\t\t\t\t\t\t<div class=\"dTableCell\"><input name=\"finish\" id=\"finish_$d\" placeholder=\"HH:mm\" onchange=\"calc_time(this)\"></div>\n";
  echo "\t\t\t\t\t\t<div class=\"dTableCell\"><input name=\"time\" id=\"time_$d\"></div>\n";
  echo "\t\t\t\t\t\t<div class=\"dTableCell\"><input type=\"Submit\" value=\"Save\"></div>\n";
  echo "\t\t\t\t\t</form>";

  echo "\t\t\t\t</div>";


}
$total_sql = "SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(sType=\"Break\",TIMEDIFF(stime, ftime),TIMEDIFF(ftime, stime))))), \"%H:%i\") AS \"total\" FROM `time` WHERE uid='$uid' AND cid='$cid'";
$total_query = mysqli_query($db, $total_sql);
$total = mysqli_fetch_array($total_query);

echo "$total[total]";

echo "\t\t\t</div\n";
echo "\t\t</div>\n";
echo "\t</div\n";
echo "</div\n";
