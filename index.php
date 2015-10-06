<!DOCTYPE html>
<html>
<head>
  <title>UOIT Timetable Retriever</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<form method="POST">
  Student Number:<br>
  <input type="text" name="user">
  <br>
  Password:<br>
  <input  type="password" name="pass">
  <br>
  Week:<br>
  <select name="date">
    <option value="09/07/2015">09/07/2015</option>
    <option value="09/14/2015">09/14/2015</option>
    <option value="09/21/2015">09/21/2015</option>
    <option value="09/28/2015">09/28/2015</option>
    <option value="10/05/2015">10/05/2015</option>
    <option value="10/12/2015">10/12/2015</option>
    <option value="10/19/2015">10/19/2015</option>
    <option value="10/26/2015">10/26/2015</option>
    <option value="11/02/2015">11/02/2015</option>
    <option value="11/09/2015">11/09/2015</option>
    <option value="11/16/2015">11/16/2015</option>
    <option value="11/23/2015">11/23/2015</option>
    <option value="11/30/2015">11/30/2015</option>
    <option value="12/07/2015">12/07/2015</option>
    <option value="12/14/2015">12/14/2015</option>
    <option value="12/21/2015">12/21/2015</option>
    <option value="12/28/2015">12/28/2015</option>
    <option value="01/04/2016">01/04/2016</option>
    <option value="01/11/2016">01/11/2016</option>
    <option value="01/18/2016">01/18/2016</option>
    <option value="01/25/2016">01/25/2016</option>
    <option value="02/01/2016">02/01/2016</option>
    <option value="02/08/2016">02/08/2016</option>
    <option value="02/15/2016">02/15/2016</option>
    <option value="02/22/2016">02/22/2016</option>
    <option value="02/29/2016">02/29/2016</option>
    <option value="03/07/2016">03/07/2016</option>
    <option value="03/14/2016">03/14/2016</option>
    <option value="03/21/2016">03/21/2016</option>
    <option value="03/28/2016">03/28/2016</option>
    <option value="04/04/2016">04/04/2016</option>
    <option value="04/11/2016">04/11/2016</option>
  </select>
  <br>
  <br>
  <input type="submit" value="Submit">
</form>

<br/>

<div id="timetable">
<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require("test.php");

    $user = "";
    $pass = "";
    $date = "";

    $success = true;

    $user_post = $_POST["user"];
    $pass_post = $_POST["pass"];
    $date_post = $_POST["date"];

    if (empty($user_post)) {
      $success = false;
    } else {
      $user = trim($user_post);
    }

    if (empty($pass_post)) {
      $success = false;
    } else {
      $pass = trim($pass_post);
    }

    if (empty($date_post)) {
      $success = false;
    } else {
      $date = trim($date_post);
    }

    if($success) {
      $data = get_timetable($user,$pass,$date);
      echo($data);
    }
  }

  ?>
</div>

<script type="text/javascript">

var x = document.getElementsByTagName("A");

for(var i = 0;i < x.length;i++)
{
	var link = x[i];

  //Easier to do DOM on client side
	if(link.innerHTML == "Previous Week" || link.innerHTML == "Next Week")
	{
    link.innerHTML = ""; //Hide?
		console.log("Removed anchor code");
	}
}

</script>

</body>
</html>
