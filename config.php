<?php
session_start();

$MySQL["user"] = "time_card";
$MySQL["pass"] = "T1me_C@rd";
$MySQL["host"] = "localhost";
$MySQL["data"] = "time_card";

$db = mysqli_connect($MySQL["host"], $MySQL["user"], $MySQL["pass"], $MySQL["data"]);

$debug = "on";

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
