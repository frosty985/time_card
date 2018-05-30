<?php
require_once("config.php");
require_once("header.php");

if (isset($_POST["register"]))
{
  // check username doesn't exsist
  $user_check = mysql_num_rows(mysqli_query($db, "SELECT uid FROM user WHERE uName = \". mysqli_real_escape_string("$_POST[uName]") . "\" ;"));
  if (!$user_check)
  {
    // create new user
    $user_sql = "INSERT INTO user (uid, uName, fName, lName, lacc) VALUES (REPLACE(UUID(), '-', ''), mysqli_real_escape_string($_POST["uName"]), mysqli_real_escape_string($_POST["fName"]), mysqli_real_escape_string($_POST["lName"])";
    mysqli_query($db, $user_sql);
    $user = mysql_fetch_array(mysqli_query($db, "SELECT uid FROM user WHERE uName = \". mysqli_real_escape_string($_POST["uName"]) . "\" ;"));
        
    $pass = password_hash($_POST['pass1'], PASSWORD_DEFAULT);
    $pass_sql = "INSERT INTO user (uid, hash, updated) VALUES (\"$user[uid]\", \"$pass\", NOW())";
    mysqli_query($db, $pass_sql);

    // fill session

    session_start();
    $_SESSION["uid"] = user["uid"];
    header("Location: company.php");
   }
}

?>

<div class="login">
  <form class="register" action="register.php" method="post">
    <span>
      <?php 
      if ($user_check)
      {
        echo "Sorry, that username is already taken";
      }
      ?>
      <label for="uName">Username:</label>
      <input name="uName" placeholder="username" required />
    </span>
    <fieldset class="fsName">
      <span>
        <label for="fName">First name:</label>
        <input name="fName" placeholder="First name" required />
      </span>
      <span>
        <label for="lName">Last name:</label>
        <input name="lName" placeholder="Last name" required />
      </span>
    </fieldset>
    <fieldset class="fsPass">
      <span>
        <label for="pass1">Password:</label>
        <input name="pass1" placeholder="Password" required />
      </span>
      <span>
        <label for="pass2">Reconfirm Password:</label>
        <input name="pass2" placeholder="Password" required />
      </span>
    </fieldset>
  </form>
</div>
