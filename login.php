<?php

require_once("config.php");
require_once("header.php");
session_start();

if ($login)
{
  header("Location: ". $_GET["ref"]);
}


?>

<div class="login">
  You must be logged in to access this site.
  <form class="fLogin" action="login.php" method="post">
    <label for="uName">User Name:</label>
    <input name="uName" placeholder="Username" required />
    <label for="pWord">Password:</label>
    <input type="password" name="pWord" required />
    <input type="submit" name="login" value="Log in" />
  </form>
</div>
