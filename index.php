
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
echo "\t\t\t\t\t<div class=\"dTableHeadCell\">Save</div>\n";
echo "\t\t\t\t\t<div class=\"dTableHeadCell\">Delete</div>\n";

echo "\t\t\t\t</div>\n";

for ($d = 0; $d < 7; $d++)
{
  /// create a "date" based on differance of days of today and loop number
  if ($d < date("w"))
  {
    $mkd = mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)-$d+date("w", $start_date), date("Y", $start_date));
  }
  elseif ($d > date("w"))
  {
    $mkd = mktime(0, 0, 0, date("m", $start_date), date("d", $start_date)+$d-date("w", $start_date), date("Y", $start_date));
  }
  else
  {
    $mkd = mktime(0, 0, 0, date("m", $start_date), date("d", $start_date), date("Y", $start_date));
  }

  echo "\t\t\t\t<div class=\"dTableRow\">\n";

  echo "\t\t\t\t\t<div class=\"dTableCell\">" . date("l", $mkd) . "</div>\n";
  echo "\t\t\t\t\t<div class=\"dTableCell\">\n";
  echo "\t\t\t\t\t\t<select name=\"type_$d\">\n";
  echo "\t\t\t\t\t\t\t<option value=\"________\">_________</option>\n";
  echo "\t\t\t\t\t\t</select>\n";
  echo "\t\t\t\t\t</div>\n";
  echo "\t\t\t\t\t<div class=\"dTableCell\"><input name=\"start_$d\"></div>\n";
  echo "\t\t\t\t\t<div class=\"dTableCell\"><input name=\"finish_$d\"></div>\n";
  echo "\t\t\t\t\t<div class=\"dTableCell\"><input name=\"time_$d\"></div>\n";
  echo "\t\t\t\t\t<div class=\"dTableCell\"><input type=\"Submit\" value=\"Save\"></div>\n";
  echo "\t\t\t\t\t<div class=\"dTableCell\"><a href=\"#\">X</a></div>\n";

  echo "\t\t\t\t</div>";


}

echo "\t\t\t</div\n";
echo "\t\t</div>\n";
echo "\t</div\n";
echo "</div\n";
