<?php

/**
 * \brief index.php is used as a REST layer to interface between the front end HTML files and backend controller methods.
 * Events can be triggered from this page, using "<Base URL>/backend/?type=<ActionName>"
 * 
 * V1.05
 */

/*
function __autoload($class){
  @include('./model/' . $class . '.php');
  @include('./controller/' . $class . '.php');
  @include('./sfdc/' . $class . '.php');
}
*/

include ('./catalog.php');
include('./config.php');
include('./RestRequest.php');

session_start();

$debug = 1; //debug mode

$errors = array();
$messages = null;
//debug($client->__getFunctions());


$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
dispatcher($type);

function addErrors($field,$msg){
	global $errors;
	$error['field']=$field;
	$error['msg']=$msg;
	$errors[] = $error;
}
function dispatcher($type){
	switch($type) {
		case 'RefreshCatalog' :
			refreshCatalog();
		break;
		case 'ReadCatalog' : 
			readCatalog();
		break;
		default:
			addErrors(null,'no action specified');
	}
}

function refreshCatalog(){
	global $messages;
	
	//$refreshResult = Catalog::refreshCache();

	

	// Base URL of Zuora REST services (SANDBOX)
	$baseUrl = 'https://apisandbox-api.zuora.com/rest/v1/';

	//Product Url
	$newUrl = $baseUrl . 'catalog/products';
	// echo "baseurl is  " . $baseUrl;
	// echo "<br>";
	// echo "newurl is  " . $newUrl;
	// echo "<br>";
	$refreshResult = new RestRequest($newUrl, 'GET', null);
	$refreshResult->execute();

	// echo "<br>";
	// echo "<br>";
	// echo "GET Response Body";
	// echo "<br>";
	// echo "<pre>" . print_r($refreshResult, true) . "</pre>";	//<pre> creates the indents for the output.

	echo print_r($refreshResult->responseBody, true);	//We only want the body of the HTTP response.
														//Otherwise was will get the entire "response" object with headers and body.
	//echo json_encode($refreshResult);
	//echo json_encode($refreshResult->responseBody);
	//Cache product list
		//$myFile = $cachePath;
		$myFile = "catalogCache.txt";
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, print_r($refreshResult->responseBody, true));
		//fwrite($fh, $catalogJson);
		fclose($fh);

	
	$messages = $refreshResult;
	//echo "Refresh result is: " . $refreshResult;
}

function readCatalog(){
	global $messages;
	//$messages = Catalog::readCache();
	$cachePath = 'catalogCache.txt';

		require('./config.php');
		if(!file_exists($cachePath)){
			return self::refreshCatalog();
		}
		$myFile = $cachePath;
		$fh = fopen($myFile, 'r');
		$catalogJson = fread($fh, filesize($myFile));
		fclose($fh);
		$catalog_groups = json_decode($catalogJson);
		//return $catalog_groups;

		$messages = $catalog_groups;
		echo $catalogJson;
}


function debug($a) {
	global $debug ;
	if($debug) {
		echo "/*";
		var_dump($a);
		echo "*/";
	}
}

function output(){
	global $errors, $messages;
	$msg = array();
	
	if(count($errors)>0) {
		debug($errors);
		$msg['success'] = false;
		$msg['msg'] = $errors;
	}
	else {
		$msg['success'] = true;
		if(!is_array($messages)) $messages = array($messages);
		$msg['msg'] = $messages;
	}
	
	//debug($msg);
	echo json_encode($msg);

}

//output();
?>