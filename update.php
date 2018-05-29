<?php
require_once("config.php");

if (isset($_POST["update"])) 
{
  // update data base
}

if (isset($_POST["delete"]))
{
  // delete from database
  
  $update_sql = "DELETE FROM time WHERE tid = \"$_POST[tid]\"; ";
}

mysqli_query($db, $update_sql);

header("Location: index.php");

?>
