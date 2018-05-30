<?php

require_once("config.php");
require_once("header.php");

session_start();

if (!isset($_SESSION["uid"]))
{
  header("Location: login.php?ref=company.php");
  exit();
}

if ($_POST["comp"] && $_POST["rate"] && $_POST["eDate"])
{
  foreach ($_POST as $key => $value)
  {
    echo "$key->$value<br />\n";
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
  document.getElementById("other").style.display = "inherit";
}

function check_form(frm)
{
  if (document.getElementById("other").style.display != "none")
  {
    if (document.getElementByName("oComp").value = "")
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
    <span><?php echo $_SESSION["fName"]; ?>, Please choose your company, and enter your rate of pay.</span>
    <span>
      <label for="comp">Company:</label>
      <select name="comp" onchange="show_other">
        <option>Please select</option>
        <?php
        ?>
        <option value="other">Other</option>
      </select>
    </span>
    <span style="display: none" id="other"><input name="oComp" placeholder="Other Company" /></span>
    <span>
      <label for="rate">Hourly rate:</label>
      <input name="rate" placeholder="£00.00" required />
    </span>
    <span>
      <label for="eDate">Effective date:</label>
      <input name="eDate placeholder="YYYY-MM-DD" required />
    </span>
    <span>
      <input type="submit" name="save" value="Save" />
    </span>
  </form>
</div>
