<?php

require_once("config.php");
require_once("header.php");
session_start();

$login = false;

if (isset($_POST["login"]))
{
  $login_sql = "SELECT user.uid as uid, pass.hash AS hash FROM user JOIN pass ON pass.uid = user.uid WHERE uName = \"". mysqli_real_escape_string($db, $_POST["uName"]) ."\";";
  $login_query = mysqli_query($db, $login_sql);
  if ($login_check = mysqli_fetch_array($login_query))
  {
    if (password_verify($_POST["pWord"], $login_check["hash"]))
    {
      $login = true;
      $hash = password_hash($_POST['pWord'], PASSWORD_DEFAULT);
      mysql_query($db, "UPDATE pass SET hash='$hash', updated=NOW() WHERE uid='$uid'");
      $_SESSION["uid"] = $login_check["uid"];
    }
    else
    {
      $login = false;
    }
  }
  else
  {
    $login = false;
  }
}

if ($login)
{
  if (isset($_GET["ref"]))
  {
    header("Location: ". $_GET["ref"]);
  }
  else
  {
    header("Location: index.php");
  }
  exit();
}


?>

<div class="login">
  You must be logged in to access this site.
<?php
  if (!$login)
  {
    echo "  <span class=\"logFailed\">Something went wrong, please check your username and password</span>\n";
  }
?>
  <form class="fLogin" action="login.php" method="post">
    <label for="uName">User Name:</label>
    <input name="uName" placeholder="Username" required />
    <label for="pWord">Password:</label>
    <input type="password" name="pWord" required />
    <input type="submit" name="login" value="Log in" />
  </form>
</div>
