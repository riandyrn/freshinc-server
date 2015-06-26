<?php

require_once 'core.php';

class Implementator extends Core
{
	function __construct()
	{
		parent::__construct(Configs::$db_config);
		header('Content-Type: application/json');
	}
	
	function getVegetables()
	{
		echo parent::getProducts(VEGETABLE_PRODUCT_TYPE);
	}
	
	function getFishMeats()
	{
		echo parent::getProducts(FISH_MEAT_PRODUCT_TYPE);
	}
	
	function getFruits()
	{
		echo parent::getProducts(FRUIT_PRODUCT_TYPE);
	}
	
	function getGroceries()
	{
		echo parent::getProducts(GROCERY_PRODUCT_TYPE);
	}
	
	function getProduct($product_id)
	{
		echo parent::getProduct($product_id);
	}
	
	function login($username, $password)
	{
		echo parent::login($username, $password);
	}
	
	function checkout($user_id, $address = null)
	{
		echo parent::checkout($user_id, $address);
	}
	
	function addToCart($product_id, $amount, $user_id)
	{
		echo parent::addToCart($product_id, $amount, $user_id);
	}
	
	function cancelPutProductToCart($user_id, $product_id, $amount)
	{
		echo parent::cancelPutProductToCart($user_id, $product_id, $amount);
	}
	
}