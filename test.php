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
  $query = "pass=" . $pass . "&user=" . $user . "&uuid=0x133337";

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
?>
