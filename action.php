<?php 
$dataToPost = $_REQUEST;
//echo print_r($_REQUEST);
switch ($_REQUEST["action"]) {
	case 'Send data to database':
		$urlToPost = "index.php?page=add_database";
		break;
	
	default:
		
		break;
}

$ch = curl_init ($urlToPost);
curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($dataToPost));
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec ($ch);

?>