<?php

require_once("config.php");

//foreach($_POST as $Key => $val)
//{
//  echo "$Key -> $val<br>\n";
//}

if (isset($_POST["save"]))
{
  $insert_query = "INSERT INTO time (tid, uid, cid, sType, tdate, stime, ftime, utime) ";
  $insert_query .= " VALUES (REPLACE(UUID(), '-', ''), \"$_SESSION[uid]\", \"$_SESSION[cid]\", \"$_POST[type]\", \"$_POST[tdate]\", \"$_POST[start]\", \"$_POST[finish]\", NOW()) ;";
  mysqli_query($db,$insert_query);
  //echo "$insert_query";

}

$ref = "index.php";
if (isset($_GET["start_date"]))
{
  $ref .= "?start_date=$_GET[start_date]";
}
header("location: $ref");

?>
