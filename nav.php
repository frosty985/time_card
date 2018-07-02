    <div name="header" class="header">
      <h3>Welcome <?php echo "$_SESSION[fname]" . ",";

  
  $comp_sql = "SELECT cname, company.cid FROM user_comp JOIN company ON company.cid = user_comp.cid WHERE uid = \"$_SESSION[uid]\" ORDER BY edate DESC LIMIT 1;";
  $comp_query = mysqli_query($db, $comp_sql);
  if (mysqli_num_rows($comp_query) != 0) {
    $comp = mysqli_fetch_array($comp_query);
    $_SESSION["cid"] = $comp["cid"];
    echo " viewing time card for $comp[cname]</h3>\n";
  }

  echo "      <nav>\n";
  echo "        <ul>\n";
  echo "          <li><a href=\"index.php\" class=\"button button-medium button-primary\">Home</a></li>\n";

  if (mysqli_num_rows($comp_query) != 0)
  {
    echo "          <li><a href=\"company.php\" class=\"button button-medium button-primary\">Adjust company details</a></li>\n";
  }
  else
  {
    echo "          <li><a href=\"company.php\" class=\"button button-medium button-primary\">Add a company</a></li>\n";
  }
?>
          <li><a href="reports.php" class="button button-medium button-primary">View reports</a></li>
          <li><a href="logout.php" class="button button-medium button-primary">Logout</a></li>
        </ul>
      </nav>
    </div>
