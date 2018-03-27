<?php
namespace backend\common;
use yii\web\Session;
use Yii;

 class cart
 {
	  	public function addCart($id,$arrayData){	
	  		$session=Yii::$app->session;
	  		if(!isset($session['cart'])){
	  			$cart[$id] = [
			  					"product_id"=>$arrayData['prodid'],
			  					"name"=>$arrayData['name'],
			  					"price"=>$arrayData['price'],
			  					"qty"=>$arrayData['qty'],
	  						];
	  		}else{
	  			$cart = $session['cart'];
	  			if(array_key_exists($id, $cart)){
	  				$cart[$id]=[
			  					"product_id"=>$arrayData['prodid'],
			  					"name"=>$arrayData['name'],
			  					"price"=>$arrayData['price'],
			  					"qty"=>$cart[$id]['qty'] + 1,
	  							];
	  			}else{
	  				$cart[$id] = [
				  					"product_id"=>$arrayData['prodid'],
				  					"name"=>$arrayData['name'],
			  						"price"=>$arrayData['price'],
				  					"qty"=>$arrayData['qty'],
	  							];
	  			}
	  		}
	  		$session['cart'] = $cart;
	  	}
  }
?>