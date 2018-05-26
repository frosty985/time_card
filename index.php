<?php

require_once("config.php");
require_once("header.php");

?>

<div name="page">
  <div name="header">
    Welcome <?php echo "$user[fname]"; ?>.
    <nav>
    </nav>
  </div>

  </div name="dbody">
<?php
/// create a table of week
/// week start day Sunday
/// get today's day, build the current week
/// loop 0 - 6 (Sun - Sat)
/// highlight today


echo "<div class=\"dTable\">\n";
echo "<div class=\"dTableBody\">\n";

echo "<div class=\"dTableRow\">\n";
echo "<div class=\"dTableHeading\">Day</div>\n";
echo "<div class=\"dTableHeading\">Start</div>\n";
echo "<div class=\"dTableHeading\">Break Start</div>\n";
echo "<div class=\"dTableHeading\">Break Finsih</div>\n";
echo "<div class=\"dTableHeading\">Finish</div>\n";
echo "</div>\n";

for ($d = 0; $d < 7; $d++)
{
  /// create a "date" based on differance of days of today and loop number
  if ($d < date("w"))
  {
    $mkd = mktime(0, 0, 0, date("m"), date("d")+$d-date("w"), date("Y"));
  }
  elseif ($d > date("w"))
  {
    $mkd = mktime(0, 0, 0, date("m"), date("d")-$d+date("w"), date("Y"));
  }
  else
  {
    $mkd = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
  }

  echo "<div class=\"dTableRow\">\n";
  echo "<div class=\"dTableCell\">" . date("l", $mkd) . "</div>\n";
  echo "<div class=\"dTableHeading\"><input name=\"start_$d\"></div>\n";
  echo "<div class=\"dTableHeading\"><input name=\"bstart_$d\"></div>\n";
  echo "<div class=\"dTableHeading\"><input name=\"bfinish_$d\"></div>\n";
  echo "<div class=\"dTableHeading\"><input name=\"finish_$d\"></div>\n";
  echo "</div";


}
