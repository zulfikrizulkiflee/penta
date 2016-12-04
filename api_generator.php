<?php
//include stuff needed for session, database connection, and stuff
include('system_prerequisite.php');

//get api info
$api = "select API_ID, API_NAME, API_DESC, API_URL, API_RETURN_FORMAT, API_BL, API_STATUS 
		from FLC_API where API_NAME = '".$_GET['api_name']."'";
$apiRs = $myQuery->query($api,'SELECT','NAME');

//if api exist
if($apiRs) {
	//get api domain/key restriction
	$getPerm = "select API_PERM_KEY from FLC_API_PERMISSION 
					where API_PERM_KEY != '' and API_PERM_KEY is not null and API_ID = ".$apiRs[0]['API_ID'];
	$getPermRs = $myQuery->query($getPerm,'SELECT','NAME');
	$getPermRsCount = count($getPermRs);

	//if key assigned
	if($getPermRsCount) {
		//get header info
		$headers = apache_request_headers();
		//$_SERVER['REQUEST_METHOD']

		//loop on count of permission
		for($x=0; $x<$getPermRsCount; $x++) {
			//check permission
			if($headers['OPEN-API-Key'] == $getPermRs[$x]['API_PERM_KEY']) {
				$allowed = true;
				break;
			}//eof if
		}//eof for
	}//eof if
	else
		$allowed = true;

	//execute if allowed
	if($allowed) {
		//if correct api
		if(count($apiRs)){
			//if have BL
			if($apiRs[0]['API_BL']) {
				//create global BL, and execute api's BL
				createPhpBl('');
				$result = executeBL($apiRs[0]['API_BL']);
			}//eof if
		}//eof if

		//return type
		switch ($apiRs[0]['API_RETURN_FORMAT']) {
			case 'JSON':
				header('Content-Type: application/json; charset=utf-8');
				$output = json_encode($result);
				break;

			case 'XML':
				$xml = new SimpleXMLElement('<root/>');
				$result = array_flip($result);
				array_walk_recursive($result, array ($xml, 'addChild'));

				header("Content-type: text/xml; charset=utf-8");
				$output = $xml->asXML();
				break;
			
			default:
				$output = $result;
				break;
		}

		echo $output; 
	}//eof if
	else
		echo "You don\'t have permission to view this API";
}//eof if
else
	echo 'API Not Exist!';
?>


<?php
/*
HTTP/1.1 401 Unauthorized
Content-Type: text/json
{
    "error" : {
        "code" : 401
        "message" : "The credentials provided are invalid."
        "more_info_url" : "http://example.com/help/errors/401"
    }
}
*/
?>