<?php

require_once 'bootstrap.php';

class Core
{
	private $db;
	
	protected function __construct($db_config)
	{
		$this->db = new Database($db_config);
	}
	
	protected function getProducts($product_type)
	{
		$query = "SELECT " . PRODUCTS_DATA . " FROM " . PRODUCTS_TABLE . " WHERE type=?";
		$data_array = array($product_type);
		$type="s";
		 
		$ret = $this->db->executePreparedSelect($query, $data_array, $type);
		
		return json_encode($ret);
	}
	
	protected function getProduct($product_id)
	{
		$query = "SELECT " . PRODUCT_DATA . " FROM " . PRODUCTS_TABLE . " WHERE id=?";
		$data_array = array($product_id);
		$type = "i";
		
		$ret = $this->db->executePreparedSelect($query, $data_array, $type);
		
		if(count($ret) > 0)
		{
			return json_encode($ret[0]);
		}
		else 
		{
			return null;
		}
	}
	
	protected function login($username, $password)
	{
		/*
		 * example login:
		 * customer:12345
		 */
		
		$ret = array(USER_ID_KEY => null, LOGIN_STATUS_KEY => false);
		$query = "SELECT " . LOGIN_DATA . " FROM " . USERS_TABLE . " WHERE username=? LIMIT 1";
		$data_array = array($username);
		$type = "s";
		
		$result = $this->db->executePreparedSelect($query, $data_array, $type);
		
		if(count($result) > 0)
		{
			if(hash('sha512', $password . $result[0]['salt']) == $result[0]['password'])
			{
				$ret[USER_ID_KEY] = $result[0]['id'];
				$ret[LOGIN_STATUS_KEY] = true;
			}
		}
		
		return json_encode($ret);
	}
	
	protected function checkout($user_id, $json_arr_products_in_cart, $address = null)
	{
		
		/*
		 * harusnya ini nggak pake $json lagi tapi
		 * nge-SELECT dari carts untuk $user_id ini
		 */
		$ret = array(CHECKOUT_STATUS_KEY => false, MESSAGE_KEY => null);
		
		$total_price = $this->getTotalPrice($json_arr_products_in_cart);
		$user_balance = $this->getUserBalance($user_id);
		
		if($user_balance >= $total_price)
		{
			$ret[CHECKOUT_STATUS_KEY] = $this->processCheckOut($user_id, $json_arr_products_in_cart, $address, $user_balance, $user_balance - $total_price);
			$ret[MESSAGE_KEY] = CHECKOUT_IS_DONE_NOTIFICATION;
		}
		else 
		{
			$ret[MESSAGE_KEY] = BALANCE_IS_NOT_ENOUGH_NOTIFICATION;
		}
		
		return json_encode($ret);
	}
	
	private function getTotalPrice($json_arr_products_in_cart)
	{
		$products = json_decode($json_arr_products_in_cart);
		$total_price = 0;
		
		foreach($products as $product)
		{
			$arr_price = $this->getArrayProductPrice($product['id']);
			$price = $product['amount'] * $arr_price['price_per_divider'] / $arr_price['divider'];
			$total_price = $total_price + $price;
		}
		
		return $total_price;
	}
	
	private function getArrayProductPrice($product_id)
	{
		$query = "SELECT " . ARRAY_PRODUCT_PRICE_DATA . " FROM " . PRODUCTS_TABLE . " WHERE id=? LIMIT 1";
		$data_array = array($product_id);
		$type = "i";
		
		$ret = $this->db->executePreparedSelect($query, $data_array, $type);
		
		return $ret[0];
	}
	
	private function getUserBalance($user_id)
	{
		$query = "SELECT " . BALANCE_DATA . " FROM " . USERS_TABLE . " WHERE id=? LIMIT 1";
		$data_array = array($user_id);
		$type = "i";
		
		$ret = $this->db->executePreparedSelect($query, $data_array, $type);
		
		return $ret[0]['balance']; //asumsi $user_id selalu ada di db
	}
	
	private function processCheckOut($user_id, $json_arr_products_in_cart, $address, $start_balance, $end_balance)
	{
		/*
		 * ini belum dioptimasi kalau ada race condition
		 * --> race condition harusnya pas produk dimasukin 
		 * ke cart
		 */
		
		if(!$adress)
		{
			$address = $this->getUserAdress($user_id);
		}
		
		$data_array = array(
			'user_id' => $user_id,
			'goods' => $json_arr_products_in_cart,
			'address' => $address,
			'start_balance' => $start_balance,
			'end_balance' => $end_balance
		);
		
		$type = "issii";
				
		return $this->db->executePreparedInsert(LOGS_TABLE, $data_array, $type);
	}
	
	private function getUserAdress($user_id)
	{
		$query = "SELECT " . USER_ADDRESS_DATA . " FROM " . USERS_TABLE . " WHERE id=? LIMIT 1";
		$data_array = array($user_id);
		$type = 'i';
		
		$ret = $this->db->executePreparedSelect($query, $data_array, $type);
		
		return $ret[0]['address'];
	}
	
	protected function addToCart($product_id, $amount, $user_id)
	{
		$ret = array(ADD_TO_CART_STATUS_KEY => false, MESSAGE_KEY => FAILED_ADD_TO_CART_NOTIFICATION);
		
		if($this->isProductAvailable($product_id, $amount))
		{						
			$ret[ADD_TO_CART_STATUS_KEY] = $this->putProductToCart($user_id, $product_id, $amount);
			$ret[MESSAGE_KEY] = SUCCESS_ADD_TO_CART_NOTIFICATION;
		}
		
		return json_encode($ret);
	}
	
	private function isProductAvailable($product_id, $amount)
	{
		/*
		 * ini nanti kalau true, bakalan ngurangin juga
		 * logikanya, kalau dikurangi amount < 0 maka
		 * false, kalo nggak dia true lalu kurangi dan masukin
		 * ke table temp. Memungkinkan untuk atomic gak ya?
		 * Jadi biar nggak keputus gitu operasinya
		 */
		
		$current_amount = $this->getCurrentAmountOfProduct($product_id);
		
		if($current_amount >= $amount)
		{
			return true;
		}
		
		return false;
	}
	
	private function putProductToCart($user_id, $product_id, $amount)
	{
		$status = $this->db->executeDecrement(PRODUCTS_TABLE, 'amount', $amount, 'id', $product_id);
		
		$data_array = array
		(
			'user_id' => $user_id,
			'product_id' => $product_id,
			'amount' => $amount
		);
			
		$type = 'iii';
		
		/* 
		 * ini harusnya dicek dulu apakah recordnya udah ada atau belum
		 * kalo udah udah ada dia bakalan update, kalo belum dia bakalan
		 * insert
		 */
		
		return $status && $this->db->executePreparedInsert(CARTS_TABLE, $data_array, $type);
	}
	
	private function getCurrentAmountOfProduct($product_id)
	{
		$query = "SELECT " . PRODUCT_AMOUNT_DATA . " FROM " . PRODUCTS_TABLE . " WHERE id=? LIMIT 1";
		$data_array = array($product_id);
		$type = "i";
		
		$result = $this->db->executePreparedSelect($query, $data_array, $type);
		
		return $result[0]['amount']; // ini pasti ada soalnya
	}
	
	public function cancelPutProductToCart($user_id, $product_id, $amount)
	{
		/*
		 * tambahkan amount ke products, hapus data di carts
		 */
		
		/*
		 * harusnya ini nggak perlu $amount, karena yang
		 * penting berhasil dieksekusi
		 */
		$this->db->executeIncrement(PRODUCTS_TABLE, 'amount', $amount, 'id', $product_id);
		
		$conditions = array
		(
			array('field' => 'user_id', 'operator' => '=', 'value' => $user_id),
			array('field' => 'product_id', 'operator' => '=', 'value' => $product_id),
			array('field' => 'amount', 'operator' => '=', 'value' => $amount)
		);
		
		$this->db->executeDelete(CARTS_TABLE, $conditions, 'AND');
	}
}