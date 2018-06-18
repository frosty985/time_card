  <div name="header">
    Welcome <?php echo "$user[fname]"; ?>.<br />
<?php
    $comp_sql = "SELECT cname FROM user_comp JOIN company ON company.cid = user_comp.cid WHERE uid = \"$_SESSION[uid]\" ORDER BY edate DESC LIMIT 1;";
    $comp_query = mysqli_query($db, $comp_sql);
?>
    <nav>
<?php
    echo "| <a href=\"index.php\">Home</a> |\n";
    if (mysqli_num_rows($comp_query) != 0)
    {
      $comp = mysqli_fetch_array($comp_query);
      echo "Viewing time card for $comp[cname]\n";
      echo " <a href=\"company.php\">Adjust company details</a> |\n";
    }
    else
    {
      echo " <a href=\"company.php\">Add a company</a> |\n";
    }
    echo " <a href=\"reports.php\">View reports</a> |\n";
    echo " <a href=\"logout.php\">Logout</a> |\n";
?>
    </nav>
  </div>
