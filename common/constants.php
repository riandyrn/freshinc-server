<?php

define('VEGETABLE_PRODUCT_TYPE', 'vegetable');
define('FRUIT_PRODUCT_TYPE', 'fruit');
define('FISH_MEAT_PRODUCT_TYPE', 'fish_meat');
define('GROCERY_PRODUCT_TYPE', 'grocery');

define('PRODUCTS_TABLE', 'products');
define('USERS_TABLE', 'users');
define('CARTS_TABLE', 'carts');
define('LOGS_TABLE', 'logs');

define('PRODUCTS_DATA', 'id, product_name, image');
define('PRODUCT_DATA', '*');
define('LOGIN_DATA', 'id, password, salt');
define('ARRAY_PRODUCT_PRICE_DATA', 'divider, price_per_divider');
define('BALANCE_DATA', 'balance');
define('USER_ADDRESS_DATA', 'address');
define('PRODUCT_AMOUNT_DATA', 'amount');

define('CHECKOUT_IS_DONE_NOTIFICATION', 'checkout is done');
define('BALANCE_IS_NOT_ENOUGH_NOTIFICATION', 'balance is not enough');
define('SUCCESS_ADD_TO_CART_NOTIFICATION', 'product is successfully added to cart');
define('FAILED_ADD_TO_CART_NOTIFICATION', 'product is not available at the moment');

// ini untuk constant key di response json
define('USER_ID_KEY', 'user_id');
define('LOGIN_STATUS_KEY', 'login');
define('MESSAGE_KEY', 'message');
define('CHECKOUT_STATUS_KEY', 'checkout');
define('ADD_TO_CART_STATUS_KEY', 'add_to_cart');
define('CANCEL_ADD_TO_CART_STATUS_KEY', 'cancel_add_to_cart');
define('PRODUCT_ID_KEY', 'product_id');
define('PRODUCT_AMOUNT_KEY', 'amount');