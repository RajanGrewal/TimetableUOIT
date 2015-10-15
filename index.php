<!DOCTYPE html>
<html>
<head>
  <title>UOIT Timetable Retriever</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
</head>
<body style="background: #F0F0F3;">
<div style="margin:auto; width:200px; display:block">
  <h1>UOIT Timetable</h1>
        <form class="pure-form pure-form-stacked" method="POST">
            <fieldset>
              <label>Student Number</label>
              <input name="user" type="text">
              <label>MyCampus Password</label>
              <input name="pass" type="password">
              <label>Week of</label>
                    <select name="date">
                      <option value="current">Current week</option>
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
                <button type="submit" class="pure-button pure-button-primary">Submit</button>
            </fieldset>
        </form>
</div>
<br>
<div id="timetable">
<?php
  function get_web_page( $url , $cookieFile = NULL, $postData = NULL) {

        $options = array();
        $options[CURLOPT_RETURNTRANSFER] = true; // to return web page
        $options[CURLOPT_HEADER] = false; // do not return headers
        $options[CURLOPT_FOLLOWLOCATION] = true; // follow redirects
        $options[CURLOPT_ENCODING] = "";   // to handle all encodings
        $options[CURLOPT_AUTOREFERER] = true; // to set referer on redirect
        $options[CURLOPT_CONNECTTIMEOUT] = 5;  // set a timeout on connect
        $options[CURLOPT_TIMEOUT] = 30;  // set a timeout on response
        $options[CURLINFO_HEADER_OUT] = true; // no header out
        $options[CURLOPT_SSL_VERIFYPEER] = false;// to disable SSL Cert checks
        $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;

          if($cookieFile != NULL)
          {
            $options[CURLOPT_COOKIEJAR] = $cookieFile; //recv cookies
            $options[CURLOPT_COOKIEFILE] = $cookieFile; //send cookies
          }

          if($postData != NULL)
          {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $postData;
          }

          $handle = curl_init( $url );
          curl_setopt_array( $handle, $options );

          $result = array();
          $result["Content"] = curl_exec( $handle );
          $result["ErrorNumber"] = curl_errno( $handle );
          $result["ErrorMessage"] = curl_error( $handle );
          $result["Header"] = curl_getinfo( $handle );

          curl_close( $handle );

      return $result;
  }

  function get_timetable($user,$pass,$date)
  {
    // TODO: Randomize uuid ?
    $query = "pass=" . $pass . "&user=" . $user . "&uuid=0xACA021";

    $req_url = "https://ssbp.mycampus.ca/prod/bwskfshd.P_CrseSchd?start_date_in=" . $date . "&institution_in=UOIT";

    //Create temp file for cookie storage
    $tmpfile = tempnam ("/tmp", "foo");

    //TODO: Error checking?
    $result1 = get_web_page("https://portal.mycampus.ca/cp/home/login", $tmpfile, $query);
    $result2 = get_web_page("http://portal.mycampus.ca/cp/ip/login?sys=sct&api=bmenu.P_StuMainMnu2", $tmpfile);
    $result3 = get_web_page($req_url,$tmpfile);

    //Delete the temp file
    unlink($tmpfile);

    $matches = array();

    //Get everything in that div from result of 3rd request
    preg_match("/<DIV ID=\"CrseSchd\">(.*?)<\\/DIV>/s", $result3["Content"], $matches);

    //Bad error checking
    if(count($matches) == 0)
      return "Error retrieveing timetable. Is your info correct?";
    else
      return $matches[0];
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

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

      if($date == "current")
      {
        $date = date("m/d/Y");
      }

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
    link.innerHTML = "";
  }

  link.href = "#timetable"; //Disable ?
  console.log("Removed anchor code");
}
</script>
</body>
</html>
