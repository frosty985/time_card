<?php
  echo "<div name=\"header\" class=\"header\">\n";
  echo "<h3>Welcome $user[fname],";

  $comp_sql = "SELECT cname FROM user_comp JOIN company ON company.cid = user_comp.cid WHERE uid = \"$_SESSION[uid]\" ORDER BY edate DESC LIMIT 1;";
  $comp_query = mysqli_query($db, $comp_sql);

  if (mysqli_num_rows($comp_query) != 0)
  {
    $comp = mysqli_fetch_array($comp_query);
    echo " viewing time card for $comp[cname]\n";
  }
  echo "</h3>\n";
  echo "<nav>\n";
  echo "| <a href=\"index.php\" class=\"button button-medium button-primary\">Home</a> |\n";

  if (mysqli_num_rows($comp_query) != 0)
  {
    echo " <a href=\"company.php\" class=\"button button-medium button-primary\">Adjust company details</a> |\n";
  }
  else
  {
    echo " <a href=\"company.php\" class=\"button button-medium button-primary\">Add a company</a> |\n";
  }
  echo " <a href=\"reports.php\" class=\"button button-medium button-primary\">View reports</a> |\n";
  echo " <a href=\"logout.php\" class=\"button button-medium button-primary\">Logout</a> |\n";
?>
    </nav>
  </div>
