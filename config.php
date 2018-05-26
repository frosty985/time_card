<?php

$MySQL["user"] = "time_card";
$MySQL["pass"] = "T1me_C@rd";
$MySQL["host"] = "localhost";
$MySQL["data"] = "time_card";

$db = mysqli_connect($MySQL["host"], $MySQL["user"], $MySQL["pass"], $MySQL["data"]);

$debug = "on";
$uid = "0a25bf3160d211e899675254004146e6";

if (!$db)
{
  echo "Error connecting to MySQL." . PHP_EOL;
  echo "Debug Error No: " . mysqli_connect_errno() . PHP_EOL;
  if (isset($debug))
  {
    echo "Debug Error: " . mysqli_connect_error() . PHP_EOL;
  }

  exit();
}

?>
