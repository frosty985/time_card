<?php

require_once("config.php");
require_once("header.php");
session_start();

if (!isset($_SESSION["uid"]))
{
	header ("Location: login.php?ref=todo.php");
	exit();
}

if (isset($_POST["save"]))
{
	$title = mysqli_real_escape_string($db, $_POST["title"]);
	$description = mysqli_real_escape_string($db, $_POST["description"]);
	if (strlen($_POST["priority"]) <= 2)
	{
		$priority = $_POST["priority"];
	}

	$todo_add_sql = "INSERT INTO todo (tid, uid, date, title, description, priority) ";
	$todo_add_sql .= "VALUES (REPLACE(UUID(), '-', ''), \"$uid\", NOW(), \"$title\", \"$description\", \"$priority\")";
	$todo_add_query = mysqli_query($db, $todo_add_sql);
}

if (isset($_GET["act"]))
{
	echo "<form method=\"post\" action=\"todo.php\">\n";
	echo "\t<table name=\"todoTable\">\n";
	echo "\t\t<tr>\n";
	echo "\t\t\t<td>Title:</td>\n";
	echo "\t\t\t<td><input name=\"title\"</td>\n";
	echo "\t\t</tr>\n";
	echo "\t\t<tr>\n";
	echo "\t\t\t<td>Priority:</td>\n";
	echo "\t\t\t<td><input name=\"priority\"></td>\n";
	echo "\t\t</tr>\n";
	echo "\t\t<tr>\n";
	echo "\t\t\t<td>Description:</td>\n";
	echo "\t\t\t<td><textarea name=\"description\"></textarea></td>\n";
	echo "\t\t</tr>\n";
	echo "\t\t<tr>\n";
	echo "\t\t\t<td><input type=\"submit\" name=\"save\" value=\"Submit\"></td>\n";
	echo "\t\t\t<td></td>\n";
	echo "\t\t</tr>\n";
	echo "\t</table>\n";
	echo "</form>\n";
}
else
{
	echo "\t<table name=\"todoTable\">\n";

	echo "\t\t<tr>\n";

	echo "\t\t\t<td>Title</td>\n";
	echo "\t\t\t<td>Description</td>\n";
	echo "\t\t\t<td>Requested by</td>\n";
	echo "\t\t\t<td>Date</td>\n";
	echo "\t\t\t<td>Priority</td>\n";

	echo "\t\t</tr>\n";


	$todo_sql = "SELECT title, description, uname, date, priority, completed FROM todo JOIN user on user.uid = todo.uid ORDER BY priority, date";
	$todo_query = mysqli_query($db, $todo_sql);

	if ($todo_query)
	{
		if (mysqli_num_rows($todo_query) == 0)
		{
			echo "\t\t<tr>\n\t\t\t<td colspan=\"5\" style=\"text-align: center\">Nothing has been added yet<br /><a href=\"?act=add\">Add a todo item</a></td>\n\t\t</tr>\n";
		}
		else
		{
			while ($todo_row = mysqli_fetch_array($todo_query) )
			{
				echo "\t\t<tr>\n";

				echo "\t\t\t<td>";
				if (isset($todo_row["completed"]))
				{
					echo "<strike>";
				}
				echo "$todo_row[title]";
				if (isset($todo_row["completed"]))
				{
					echo "</strike>";
				}
				echo "</td>\n";
				
				echo "\t\t\t<td>";
				if (isset($todo_row["completed"]))
				{
					echo "<strike>";
				}
				echo str_replace("\n", "<br />", $todo_row["description"]);
				if (isset($todo_row["completed"]))
				{
					echo "</strike>";
				}
				echo "</td>\n";
				echo "\t\t\t<td>";
				
				if (isset($todo_row["completed"]))
				{
					echo "<strike>";
				}
				echo "$todo_row[uname]";
				if (isset($todo_row["completed"]))
				{
					echo "</strike>";
				}
				echo "</td>\n";
				echo "\t\t\t<td>";
				
				if (isset($todo_row["completed"]))
				{
					echo "<strike>";
				}
				echo "$todo_row[date]";
				if (isset($todo_row["completed"]))
				{
					echo "</strike>";
				}
				echo "</td>\n";
				echo "\t\t\t<td>";
				
				if (isset($todo_row["completed"]))
				{
					echo "<strike>";
				}
				echo "$todo_row[priority]</td>\n";

				echo "\t</tr>\n";
			}
			echo "\t\t<tr>\n\t\t\t<td colspan=\"5\" style=\"text-align: center\"><a href=\"?act=add\">Add a todo item</a></td>\n\t\t</tr>\n";
		}
	}
	echo "\t</table>\n";
}
require_once("footer.php");
?>
