<?php

require_once("config.php");
require_once("header.php");

$login = false;

if (isset($_POST["login"]))
{
  $login_sql = "SELECT user.uid as uid, user.fname AS fname, pass.hashd AS hashd FROM user JOIN pass ON pass.uid = user.uid WHERE uname = \"". mysqli_real_escape_string($db, $_POST["uname"]) ."\";";
  $login_query = mysqli_query($db, $login_sql);
  if ($login_check = mysqli_fetch_array($login_query))
  {
    if (password_verify($_POST["pWord"], $login_check["hashd"]))
    {
      $login = true;
      $hashd = password_hash($_POST['pWord'], PASSWORD_DEFAULT);
      $_SESSION["uid"] = $login_check["uid"];
      $_SESSION["fname"] = $login_check["fname"];      
      
      mysqli_query($db, "UPDATE pass SET hashd='$hashd', updated=NOW() WHERE uid='$_SESSION[uid]'");

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

<div class="login center">
  You must be logged in to access this site.
<?php
  if (isset($_POST["login"]) && !$login)
  {
    echo "  <span class=\"logFailed\">Something went wrong, please check your username and password</span>\n";
  }
?>
  <form class="fLogin" action="login.php<?php if (isset($_GET["ref"])) { echo "?ref=$_GET[ref]"; } ?>" method="post">
    <label for="uname">User Name:</label>
    <input name="uname" placeholder="Username" required />
    <label for="pWord">Password:</label>
    <input type="password" name="pWord" required />
    <input type="submit" name="login" value="Log in" />
  </form>
  <a href="register.php">Register</a>
</div>
