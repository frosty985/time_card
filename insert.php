<?php

require_once("config.php");

if (isset($_POST["save"]))
{
  $insert_query = "INSERT INTO time tid, uid, cid, sType, tdate, stime, ftim, utime ";
  $insert_query .= " VALUES REPLACE(UUID(), '-', ''), $uid, $cid, $_POST[start], $_POST[finish], $_POST[time], NOW() ;";
  mysqli_query($db,$insert_query);
}

header('location: index.php');

?>