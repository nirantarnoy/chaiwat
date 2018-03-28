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
	  	public function updateCart($id,$product_code,$name,$price,$qty){
	  		$session=Yii::$app->session;
	  		if(isset($session['cart'])){
	  			$cart = $session['cart'];
	  			if(array_key_exists($id, $cart)){
	  				$cart[$id]=[
			  					"product_id"=>$product_code,
			  					"name"=>$name,
			  					"price"=>$price,
			  					"qty"=>$qty,
	  							];
	  				$session['cart'] = $cart;
	  				return 1;			
	  			}else{
	  				return 100;
	  			}
	  			
	  		}else{
	  			return 0;
	  		}
	  		
	  	}
	  	public function removeItemCart($id){
	  		$session=Yii::$app->session;
	  		if(isset($session['cart'])){
	  			$cart = $session['cart'];
	  			unset($cart[$id]);
	  			$session['cart'] = $cart;
	  			return 1;
	  		}else{
	  			return 0;
	  		}
	  	}
  }
?>