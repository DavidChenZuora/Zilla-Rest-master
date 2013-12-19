<?php

/**
 * \brief The Cart class manages a user's cart. One of these is stored for each user in a session variable to keep track of all of their selected items before they've purchased them.
 * 
 * V1.05
 */

include('./config.php');
include('./Cart_Item.php');
class Cart{
	/*! A list of cart item models that each store a rate plan to be displayed to the user*/
	public $cart_items; 
	/*! A tally of cart items used to generate a unique cart id for each item added*/
	public $latestItemId; 

	/**
	 * Initializes an empty cart instance.
	 */
	public function __construct(){
		$this->clearCart();
	}

	/**
	 * Clears all items from this cart instance.
	 */
	public function clearCart(){
		$this->cart_items = array();
		$this->latestItemId = 1;		
	}

	public function refreshCache(){
		//Initialize Zuora API Instance
		require('./config.php');
		include('./RestRequest.php');
		//require('./model/RestRequest.php');
		$newUrl = $baseUrl . 'catalog/products';

		$refreshResult = new RestRequest($newUrl, 'GET', null);
	
		$refreshResult->execute();

		echo print_r($refreshResult->responseBody, true);	//We only want the body of the HTTP response.
													//Otherwise was will get the entire "response" object with headers and body.
		//Cache product list
		$myFile = $cachePath;
		////$myFile = "catalogCache.txt";
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, print_r($refreshResult->responseBody, true));
		//fwrite($fh, print_r($refreshResult, true));
		//fwrite($fh, $catalogJson);
		fclose($fh);

		return $refreshResult;
	}
	/**
	 * Reads the Product Catalog Data from the locally saved JSON cache. If no cache exists, this will refresh the catalog from Zuora first.
	 * @return A model containing all necessary information needed to display the products and rate plans in the product catalog
	 */
	public static function readCache(){
		require('./config.php');
		if(!file_exists($cachePath)){
			return self::refreshCache();
		}
		//$myFile = $cachePath;
		$myFile = "catalogCache.txt";
		$fh = fopen($myFile, 'r');
		$catalogJson = fread($fh, filesize($myFile));
		fclose($fh);
		$catalog_groups = json_decode($catalogJson);



		//error_log(print_r($catalog_groups, true));

		return $catalog_groups;
	}

	/**
	 * Adds a new item to this cart instance. Each item is added with a ProductRatePlanId, a Quantity, and a unique Cart Item identification number
	 * @param $rateplanId The ProductRatePlanId of the item being added
	 * @param $quantity The number of UOM to be applied to this rateplan for all charges with a Per Unit quantity
	 */
	public function addCartItem($rateplanId, $quantity){
		error_log('cart.php rateplanId is: ' . $rateplanId, 0);
		error_log('cart.php quantity is: ' . $quantity, 0);
		//$newCartItem = new Cart_Item($rateplanId, $quantity, $this->latestItemId);



		$newCartItem = new Cart_Item();
		$newCartItem->ratePlanId = $rateplanId;
		error_log('cart.php ratePlanId2 is: ' . $newCartItem->ratePlanId, 0);
		$newCartItem->itemId = $this->latestItemId++;
		error_log('cart.php itemId is: ' . $newCartItem->itemId, 0);
		$newCartItem->quantity = $quantity;

		//$plan = Catalog::getRatePlan($newCartItem->ratePlanId);
		$rpId = $newCartItem->ratePlanId;

	


		$catalog_groups = self::readCache();	//should be in associative array already.

		$catalog_products = $catalog_groups->products;	//returns enture product array.
		//error_log(print_r($catalog_products, true));
		//error_log(print_r($catalog_products[2], true));	//returns a product array with productrateplan and productrateplancharges arrays embedded inside.
		$catalog_rateplans = $catalog_products[0]->productRatePlans;
		//error_log(print_r($catalog_rateplans, true));



		//Commented out for DEMO
		// foreach($catalog_groups as $group){
		// 	
		// 	foreach($group->products as $product){
		// 		
		// 		foreach($product->productRatePlans as $ratePlan){
		// 			if($ratePlan->id == $rpId){
		// 				$plan = $ratePlan;
		// 			}
		// 		}
		// 	}
		// }
		// $plan = NULL;

	/**
	 * Given a RatePlan ID, retrieves all rateplan information by searching through the cached catalog file
	 * @return RatePlan model
	 */
	// public static function getRatePlan($rpId){
	// 	$catalog_groups = self::readCache();
	// 	foreach($catalog_groups as $group){
	// 		foreach($group->products as $product){
	// 			foreach($product->ratePlans as $ratePlan){
	// 				if($ratePlan->Id == $rpId){
	// 					return $ratePlan;
	// 				}
	// 			}
	// 		}
	// 	}
	// 	return NULL;
	// }


/*		//Not needed? no Uom. 
		if(isset($plan->Uom)){
			$newCartItem->uom = $plan->Uom;
		} else {
			$newCartItem->uom = null;			
		}
*/	
		// $newCartItem->ratePlanName = $plan!=null ? $plan->Name : 'Invalid Product';
		// $newCartItem->ProductName = $plan!=null ? $plan->productName : 'Invalid Product';

		//Commented out for DEMO
		// $newCartItem->ratePlanName = $plan!=null ? $plan->name : 'Invalid Product';
		// $newCartItem->productName = $plan!=null ? $plan->productName : 'Invalid Product';
		array_push($this->cart_items, $newCartItem);
	}

	/**
	 * Removes an item from the user's cart.
	 * @param $itemId The unique cart item ID of the item being removed from the cart
	 */
	public function removeCartItem($itemId){
		for($i=0;$i<(count($this->cart_items));$i++){
			if($this->cart_items[$i]->itemId==$itemId){
				unset($this->cart_items[$i]);
				$this->cart_items = array_values($this->cart_items);
				return true;
			}
		}
		return false;
	}
}

?>