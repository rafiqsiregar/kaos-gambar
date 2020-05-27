<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = '';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// authentication
$route['v1/auth/register']['post'] = 'AuthController/register';//register
$route['v1/auth/login']['post'] = 'AuthController/login';//login
$route['v1/auth/logout']['post'] = 'AuthController/logout';//logout
$route['v1/auth/checktoken']['post'] = 'AuthController/check_token'; //check token
$route['v1/auth/checktokenadmin']['post'] = 'AuthController/check_token_admin'; //check token admin

//balance
$route['v1/balance/topup']['post'] = 'AuthController/topup';

// product
$route['v1/product/all']['post'] = 'ProductController/all';
$route['v1/product/create']['post'] = 'ProductController/create'; //admin
$route['v1/product/edit']['post'] = 'ProductController/edit'; //admin
$route['v1/product/view']['post'] = 'ProductController/view'; //admin
$route['v1/product/delete']['post'] = 'ProductController/delete'; //admin

// purchase & cart
$route['v1/cart/all']['post'] = 'PurchaseController/all';
$route['v1/cart/view']['post'] = 'PurchaseController/view';
$route['v1/cart/add']['post'] = 'PurchaseController/add';
$route['v1/cart/edit']['post'] = 'PurchaseController/edit';
$route['v1/cart/delete']['post'] = 'PurchaseController/delete';

// order
$route['v1/order/cancel']['post'] = 'PurchaseController/cancel';
$route['v1/order/accept']['post'] = 'PurchaseController/accept';
$route['v1/order/notif']['post'] = 'PurchaseController/notif';// notification

// admin 
$route['admin'] = 'AdminController';
$route['admin/dashboard'] = 'AdminController/dashboard';
$route['admin/product'] = 'AdminController/product';
$route['admin/product/edit/(:any)'] = 'AdminController/productedit/$1';
$route['admin/purchase'] = 'AdminController/purchase';