<?php
require_once('calendar/classes/tc_calendar.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<title>TriConsole - Programming, Web Hosting, and Entertainment Directory</title>


<link href="calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="calendar/calendar.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
<form name="form1" method="post" action="test.php">
  <tr>
              <p class="largetxt"><b>DatePicker with no input box</b></p>
              <table border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td nowrap>Date 3 :</td>
                  <td><?php
                 	  $i=0;
                 	  while($i<3){
						  $myCalendar = new tc_calendar("date$i", true, false);
						  $myCalendar->setIcon("calendar/images/iconCalendar.gif");
						  //$myCalendar->setDate(date('d'), date('m'), date('Y'));
						  $myCalendar->setPath("calendar/");
						  $myCalendar->setYearInterval(2000, 2020);
						  //$myCalendar->dateAllow('2008-05-13', '2015-03-01');
						  $myCalendar->setDateFormat('j F Y');
						  //$myCalendar->setHeight(350);
						  //$myCalendar->autoSubmit(true, "form1");
						  $myCalendar->setAlignment('left', 'bottom');
	
						  $myCalendar->writeScript();
						  $i++;
					  }
					  ?></td>
                  <td><input type="button" name="button" id="button" value="Check the value" onClick="javascript:alert(this.form.date5.value);"></td>
                  <td><input type="submit" value="Enviar" /></td>
                </tr>
              </table>
</form>              
</body>
</html>
