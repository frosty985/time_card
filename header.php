<?php
require_once("config.php");

$user_sql = "SELECT uname, fname, lname FROM user WHERE uid='$uid'";
$user_query = mysqli_query($db, user_sql);
$user = mysqli_fetch_array($user_query);

?>

<html>
  <head>
    <title>Time Card</title>
    <link rel="stylesheet" type="text/css" href="layout.css">
    <link rel="stylesheet" type="text/css" href="fonts.css">
    <link rel="stylesheer" type="text/css" href="colors.css">
  </head>

  <body>
