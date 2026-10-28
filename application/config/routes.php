<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['dashboard'] = 'dashboard/index';
$route['code'] = 'code/index';
$route['material'] = 'material/index';

// CUSTOMER ROUTE 
$route['customer'] = 'customer/index';
$route['customer/load_data'] = 'customer/load_data';
$route['customer/preview_code'] = 'customer/preview_code';
$route['customer/create'] = 'customer/create';
$route['customer/store'] = 'customer/store';
$route['customer/edit/(:any)'] = 'customer/edit/$1';
$route['customer/update'] = 'customer/update';
$route['customer/detail'] = 'customer/detail';
$route['customer/credit-limit/(:any)'] = 'customer/credit_limit/$1';

// PO ROUTE
$route['po'] = 'PO/index';
$route['po/load_data'] = 'PO/load_data';
$route['po/create'] = 'PO/create';
$route['po/detail/(:any)'] = 'PO/detail/$1';
$route['po/edit/(:any)'] = 'PO/edit/$1';


