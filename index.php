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

<?php
/// create a table of week
/// week start day Sunday
/// get today's day, build the current week

$today = "";

/// loop 0 - 6 (Sun - Sat)
/// highlight today


for ($d = 0; $d < 7; $d++)
{
  /// create a "date" based on differance of days of today and loop number
  if ($d < date("w"))
  {
    $wd = date("l", mkdate(0, 0, 0, date("m"), date("d")-date("w"), date("Y"));
  }
  else if ($d > date("w"))
  {
    $wd = date("l", mkdate(0, 0, 0, date("m"), date("d")-$d+date("w"), date("Y"));
  }
  else
  {
    $wd = date("l");
  }



  if ($d = date("w"))
  {
    echo "<b>";
  }
  echo "$d $wd";
  if ($d = date("w"))
  {
    echo "<b>";
  }
  echo "<br>";
}
?>


</div>
