<?php

require_once("config.php");
require_once("header.php");
session_start();


header("Location: ". $_GET["ref"]);


?>
