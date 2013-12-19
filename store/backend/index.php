<?php

/**
 * \brief index.php is used as a REST layer to interface between the front end HTML files and backend controller methods.
 * Events can be triggered from this page, using "<Base URL>/backend/?type=<ActionName>"
 * 
 * V1.05
 */


// function __autoload($class){
//   @include('./model/' . $class . '.php');
//   @include('./controller/' . $class . '.php');
//   @include('./sfdc/' . $class . '.php');
// }



include('./config.php');

include('./RestRequest.php');

include('./Cart.php');	//Something wrong here

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
		case 'EmptyCart' : emptyCart();
		break;
		case 'GetInitialCart' : getInitialCart();
		break;
		case 'AddItemToCart' : addItemToCart();
		break;
		case 'RemoveItemFromCart' : removeItemFromCart();
		break;
		default:
			addErrors(null,'no action specified');
	}
}

function emptyCart(){
	global $messages;
	
	$_SESSION['cart'] = new Cart();
	echo "Hello emptyCart!, session created";
	$messages = $_SESSION['cart'];
}

function getInitialCart(){
	global $messages;
	echo "Hello getInitialCart!1";
	//session_unset(); //resets session variables.
	if(!isset($_SESSION['cart'])){
		echo "Hello getInitialCart!2";
		emptyCart();
	}
	
	$messages = $_SESSION['cart'];
}

function addItemToCart(){

	echo "hello! AddItemToCart button has been clicked!";
	global $messages;
	if(!isset($_SESSION['cart'])){
		emptyCart();
	}
	
	$ratePlanId = $_REQUEST['ratePlanId'];
	//$ratePlanId = "2c92c0f941a5f16e0141b54d97dd6aa3";
	$quantity = 1;
	// if(isset($_REQUEST['quantity']))	//quantity is always 1 for testing purposes.
	// 	$quantity = $_REQUEST['quantity'];
	error_log('ratePlanId is:' . $ratePlanId, 0) ;
	
	echo "ratePlanId is: " . $ratePlanId;
	echo "quantity is: " . $quantity;

	if(isset($_SESSION['cart'])){
		$_SESSION['cart']->addCartItem($ratePlanId, $quantity);

	} else {
		addErrors(null,'Cart has not been set up.');
		return;	
	}
	error_log('SESSION variable is: ' . print_r($_SESSION['cart'], true), 0);
	$messages = $_SESSION['cart'];
}

function removeItemFromCart(){
	global $messages;

	$itemId;
	if(isset($_REQUEST['itemId'])){
		$itemId = $_REQUEST['itemId'];
	} else {
		addErrors(null,'Item Id not specified.');
		return;		
	}

	if(isset($_SESSION['cart'])){
		$removed = $_SESSION['cart']->removeCartItem($itemId);
		if(!$removed){
			addErrors(null,'Item no longer exists.');
		}
	} else {
		addErrors(null,'Cart has not been set up.');
		return;		
	}

	$messages = $_SESSION['cart'];
}

function refreshCatalog(){

	global $messages;
	//$newCatalog = new Catalog();
	//$refreshResult = Catalog::refreshCache();
	//$refreshResult = $newCatalog->refreshCache();
	

	// Base URL of Zuora REST services (SANDBOX)
	$baseUrl = 'https://apisandbox-api.zuora.com/rest/v1/';

	//Product Url
	$newUrl = $baseUrl . 'catalog/products';
	

	$refreshResult = new RestRequest($newUrl, 'GET', null);
	
	$refreshResult->execute();
	
	
	echo print_r($refreshResult->responseBody, true);	//We only want the body of the HTTP response.
													//Otherwise was will get the entire "response" object with headers and body.
	
		//$myFile = $cachePath;
		$myFile = "catalogCache.txt";
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, print_r($refreshResult->responseBody, true));
		//fwrite($fh, print_r($refreshResult, true));
		//fwrite($fh, $catalogJson);
		fclose($fh);

	//$messages = $catalogJson;
	$messages = $refreshResult;
	
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