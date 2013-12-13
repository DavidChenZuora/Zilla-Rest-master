<?php

function __autoload($class){
  @include('./model/' . $class . '.php');
  @include('./controller/' . $class . '.php');
  @include('./sfdc/' . $class . '.php');
}

include ('config.php');

/** GET call without parameters **/
$requestGetWithNoParams = new RestRequest($baseUrl . 'catalog/products', 'GET', null);
$requestGetWithNoParams->execute();

echo 'Catalog Test (GET, no params)<br/>';
echo '<pre>' . print_r($requestGetWithNoParams, true) . '</pre>';



/** GET call with parameters **/
$getParams = array('pageSize'=>'1');
$requestGetWithParams = new RestRequest($baseUrl . 'catalog/products', 'GET', $getParams);
$requestGetWithParams->execute();

echo 'Catalog Test (GET, with params, pageSize = 1)<br/>';
echo '<pre>' . print_r($requestGetWithParams, true) . '</pre>';



/** POST call without parameters**/
$requestPost = new RestRequest($baseUrl . 'connections', 'POST', null);
$requestPost->execute();

echo 'Connections Test (POST)<br/>';
echo '<pre>' . print_r($requestPost, true) . '</pre>';


//TODO
/** POST call with parameters **/
/** Put call without parameters (?) **/
/** PUT call with parameters **/
/** DELETE call without parameters **/
/** DELETE call with parameters (?) **/
?>