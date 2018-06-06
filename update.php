<?php
require_once("config.php");

//foreach($_POST as $Key => $val)
//{
//  echo "$Key -> $val<br>\n";
//}

if (isset($_POST["update"])) 
{
  // update database
  $update_sql = "UPDATE time SET stype = \"$_POST[type]\", stime = \"$_POST[start]\", ftime = \"$_POST[finish]\", utime = NOW() WHERE tid = \"$_POST[tid]\"; ";
}

if (isset($_POST["delete"]))
{
  // delete from database
  $update_sql = "DELETE FROM time WHERE tid = \"$_POST[tid]\"; ";
}

mysqli_query($db, $update_sql);
//echo $update_sql;

$ref = "index.php";
if (isset($_GET["start_date"]))
{
  $ref .= "?start_date=$_GET[start_date]";
}
header("location: $ref");

?>
