<?php
require_once("config.php");

?>

<html>
  <head>
    <title>Time Card</title>
    <link rel="stylesheet" type="text/css" href="layout.css">
    <link rel="stylesheet" type="text/css" href="fonts.css">
    <link rel="stylesheet" type="text/css" href="colors.css">
    <script>
      function calc_time(input) {
        // get row number
        var row = input.id.substring(input.id.length-1, input.id.length)
        // var inType = inp.name.substring(0, inp.name.length-2)

        // create vars for time
        var starttime = document.getElementById('start_' + row).value
        var stoptime = document.getElementById('finish_' + row).value

        // build date
        var startdate = new Date("01/01/2001 " + starttime)
        var stopdate = new Date("01/01/2001 " + stoptime)

        // calculate differance
        var hours = stopdate - startdate
        //alert(hours)

        // show differance
        document.getElementById('time_' + row).value = new Date(hours).toISOString().substr(11,8)
      }

      function valid_time(input) {
        re = /^(\d{1,2}):(\d{2})/;
        if (input.value != "")
        {
          if (regs = input.value.match(re))
          {
            if (regs[1] < 23)
            {
              if (regs[2] > 59)
              {
                alert("Invalid time");
                return false;
              }
            }
            else
            {
              alert("Invalid time");
              return false;
            }
          }
          return true;
        }
        alert("Invalid time");
        return false;
      }

      function valid_form(fInput) {
        if (fInput.type.value != "Holiday" || fInput.type.value != "Bank Holiday")
        {
          if (!valid_time(fInput.start)) return false;
          if (!valid_time(fInput.finish)) return false;
        return true;
        }
      }
    </script>
  </head>
  <body>
    <div name="page">
