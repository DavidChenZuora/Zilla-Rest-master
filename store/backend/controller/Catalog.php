<?php
/**
*Catalog file written in REST
*Author: David Chen
*/

class Catalog {

	public static function refreshCache(){


		/*function __autoload($class){
  			@include('./model/' . $class . '.php');
  			@include('./controller/' . $class . '.php');
  			@include('./sfdc/' . $class . '.php');
		}*/

	//include('config_REST.php');
	include('./config.php');
	include('./RestRequest.php');

// Base URL of Zuora REST services (SANDBOX)
	$baseUrl = 'https://apisandbox-api.zuora.com/rest/v1/';

	//Product Url
	$newUrl = $baseUrl . 'catalog/products';
	echo "baseurl is  " . $baseUrl;
	echo "<br>";
	echo "newurl is  " . $newUrl;
	echo "<br>";
	//echo "hello world!" . $class;

	$request = new RestRequest($newUrl, 'GET', null);
	$request->execute();

	echo "<br>";
	echo "<br>";
	echo "GET Response Body";
	echo "<br>";
	echo "<pre>" . print_r($request, true) . "</pre>";	//<pre> creates the indents for the output.

	//}
	$catalogJson = json_encode($request);

	//Cache product list
		$myFile = $cachePath;
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, print_r($request, true));
		//fwrite($fh, $catalogJson);
		fclose($fh);

		return $request;
		//return $catalogJson;
	}
}

?>