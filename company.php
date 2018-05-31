<?php

require_once("config.php");
require_once("header.php");

session_start();

if (!isset($_SESSION["uid"]))
{
  header("Location: login.php?ref=company.php");
  exit();
}

//if (isset($_POST["comp"]))
if (isset($_POST["comp"]) && isset($_POST["rate"]) && isset($_POST["edate"]))
{
  echo "POST";
  foreach ($_POST as $key => $value)
  {
    echo "$key->$value<br />\n";
  }
  if (isset($_POST["oComp"]))
  {
    if ($_POST["oComp"] != "")
    {
      // check new company does not exist
      $checkComp_query = mysqli_query($db, "SELECT cid FROM company WHERE cname = \"" . mysqli_real_escape_string($db, $_POST["oComp"]) . "\" ; ");
      if (mysqli_num_rows($checkComp_query) > 0)
      {
        $checkComp = mysqli_fetch_array($checkComp_query);
        $cid = $checkComp["cid"];
      }
      else
      {
        $newComp_sql = "INSERT INTO company (cid, cname) VALUES (REPLACE(UUID(), '-', ''), \"";
        $newComp_sql .= mysqli_real_escape_string($db, $_POST["oComp"]) . "\") ;";
        $newComp_query = mysqli_query($db, $newComp_sql);
        if ($newComp_query)
        {
          // get new uuid
          $newComp_query = mysqli_query($db, "SELECT cid FROM company WHERE cname = \"" . mysqli_real_escape_string($db, $_POST["oComp"]) . "\" ;");
          $newComp = mysqli_fetch_array($newComp_query);
          $cid = $newComp["cid"];
        }
      }
    }
    // check varibles
    if (strlen($_POST["rate"]) <= 5)
    {
      $rate = mysqli_real_escape_string($db, $_POST["rate"]);
    }
    else
    {
      $comp_failed = true;
    }

    if (strlen($_POST["edate"])  == 10)
    {
      $edate = mysqli_real_escape_string($db, $_POST["edate"]);
    }
    else
    {
      $comp_failed = true;
    }

    if (!isset($comp_failed))
    {
      $comp_sql = "INSERT INTO user_comp (ucid, uid, cid, rate, edate, udate) VALUES (REPLACE(UUID(), '-', ''), \"$_SESSION[uid]\", \"$cid\", \"$rate\", \"$edate\", NOW()) ;";
      $comp_query = mysqli_query($db, $comp_sql);
    }
  }
}

if (isset($comp_query))
{
  header("Location: index.php");
}

?>
<script>
function show_other()
{
  if (document.getElementById("comp").value == "other")
  {
    document.getElementById("oComp").style.display = "block";
    //document.getElementById("oComp").style.visibility = "visible";
  }
  else
  {
    document.getElementById("oComp").style.display = "none";
    //document.getElementById("oComp").style.visibility = "hidden";
  }
}

function check_form(frm)
{
  if (document.getElementById("other").style.display != "none")
  {
    if (document.getElementById("oComp").value = "")
    {
      return false;
    }
    else
    {
      return true;
    }
  }
  else
  {
    return true;
  }
}

</script>

<div class="login">
  <form class="company" action="company.php" method="post" onsubmit="return check_form(this)">
    <span><?php echo "$_SESSION[fname]"; ?>, Please choose your company, and enter your rate of pay.</span>
    <span>
      <label for="comp">Company:</label>
      <select name="comp" id="comp" onchange="show_other()">
        <option>Please select</option>
        <?php
          $comp_sql = "SELECT cid, cname FROM company ORDER BY cname";
          $comp_query = mysqli_query($db, $comp_sql);
          while ($comp = mysqli_fetch_array($comp_query))
          {
            echo "<option value=\"$comp[cid]\">$comp[cname]</option>\n;";
          }
        ?>
        <option value="other">Other</option>
      </select>
    </span>
    <!-- <span style="visibility: hidden" id="other"> --><input name="oComp" id="oComp" placeholder="Other Company" style="display: none" /><!--</span>-->
    <span>
      <label for="rate">Hourly rate:</label>
      <input name="rate" placeholder="£00.00" required />
    </span>
    <span>
      <label for="edate">Effective date:</label>
      <input name="edate" placeholder="YYYY-MM-DD" required />
    </span>
    <span>
      <input type="submit" name="save" value="Save" />
    </span>
  </form>
</div>
