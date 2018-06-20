<?php
require_once("config.php");
require_once("header.php");

$user_check = 0;

if (isset($_POST["register"]))
{
  session_start();
  // check username doesn't exsist
  $user_check = mysqli_num_rows(mysqli_query($db, "SELECT uid FROM user WHERE uname = \"" . mysqli_real_escape_string($db, $_POST["uname"]) . "\" ;"));
  if ($user_check == 0)
  {
    // create new user
    $user_sql = "INSERT INTO user (uid, uname, fname, lname, lacdate) ";
    $user_sql .= " VALUES (REPLACE(UUID(), '-', ''), ";
    $user_sql .= " \"" . mysqli_real_escape_string($db, $_POST["uname"]) . "\", \"" . mysqli_real_escape_string($db, $_POST["fname"]) . "\", \"" . mysqli_real_escape_string($db, $_POST["lname"]);
    $user_sql .= "\", NOW() ); ";

    $create_user = mysqli_query($db, $user_sql);

    if ($create_user)
    {
      $user = mysqli_fetch_array(mysqli_query($db, "SELECT uid, fname FROM user WHERE uname = \"" . mysqli_real_escape_string($db, $_POST["uname"]) . "\" ;"));

      $pass = password_hash($_POST['pass1'], PASSWORD_DEFAULT);

      $pass_sql = "INSERT INTO pass (uid, hashd, updated) VALUES (\"$user[uid]\", \"$pass\", NOW())";
      if (mysqli_query($db, $pass_sql))
      {
      }
      else
      {
        mysqli_query($db, "DELETE FROM user WHERE uid = \"$user[uid]\"");
?>
        <div class="login">
          User creation has failed, please try again later.
        </div>
<?php
        exit();
      }

      // fill session

      $_SESSION["uid"] = $user["uid"];
      $_SESSION["fname"] = $user["fname"];
      header("Location: company.php");
    }
  }
}

?>

<div class="login">
  <form class="register" action="register.php" method="post">
    <div class="dTable">
      <div class="dTableRow">
        <div class="dTableCell bgWhite" style="width: 100%">
          <span>
            <?php
            if ($user_check > 0)
            {
              echo "Sorry, that username is already taken";
            }
            ?>
            <label for="uname">Username:</label>
            <input name="uname" placeholder="username" <?php
              if (isset($_POST["uname"]))
              {
                echo "value=\"$_POST[uname]\"";
              }
            ?> required />
          </span>
        </div>
      </div>
      
      <div class="dTableRow">
        <div class="dTableCell bgWhite">
          <span>
            <label for="fname">First name:</label>
            <input name="fname" placeholder="First name" <?php
              if (isset($_POST["fname"]))
              {
                echo "value=\"$_POST[fname]\"";
              }
            ?> required />
          </span>
        </div>
          
        <div class="dTableCell bgWhite">
          <span>
            <label for="lname">Last name:</label>
            <input name="lname" placeholder="Last name" <?php
              if (isset($_POST["lname"]))
              {
                echo "value=\"$_POST[lname]\"";
              }
            ?> required />
          </span>
        </div>
      </div>

      <div class="dTableRow">
        <div class="dTableCell bgWhite">
          <span>
            <label for="pass1">Password:</label>
            <input name="pass1" placeholder="Password" required />
          </span>
        </div>
        <div class="dTableCell">
          <span>
            <label for="pass2">Reconfirm Password:</label>
            <input name="pass2" placeholder="Password" required />
          </span>
        </div>
      </div>
      
      <div class="dTableRow">
        <div class="dTableCell" style="width: 100%;">
          <input type="submit" name="register" value="Register" />
        </div>
      </div>
    </div>
  </form>
</div>
