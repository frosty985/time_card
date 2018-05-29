<?php

require_once("config.php");
require_once("header.php");
session_start();

?>

<div class="login">
  You must be logged in to access this site.
  <label for="uName">User Name:</lable>
  <input name="uName" placeholder="Username" required />
  <label for="pWord">Password:</lable>
  <input name="pWord" required />
  <input type="submit" name="login" value="Log in" />
</div>

<?php

//header("Location: ". $_GET["ref"]);

?>
