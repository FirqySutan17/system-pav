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

// =====================================================
// ORDER
// =====================================================

$route['order'] = 'order/index';
$route['order/create'] = 'order/create';
$route['order/save'] = 'order/save';
$route['order/detail/(:any)'] = 'order/detail/$1';

$route['order/customer_credit_info/(:any)'] =
    'order/customer_credit_info/$1';

$route['order/cancel/(:any)'] =
    'order/cancel_action/$1';


// =====================================================
// ORDER EDIT
// =====================================================

$route['order/edit_customer_action/(:any)'] =
    'order/edit_customer_action/$1';

$route['order/edit_supplier_action/(:any)'] =
    'order/edit_supplier_action/$1';


// =====================================================
// ORDER APPROVAL
// =====================================================

$route['order/approval'] =
    'order/approval_index';

$route['order/approval_detail/(:num)'] =
    'order/approval_detail/$1';

$route['order/approval_action/(:num)'] =
    'order/approval_action/$1';

// =====================================================
// CREDIT NOTE
// =====================================================

$route['credit_note'] = 'credit_note/index';

// List
$route['credit_note/get_list'] = 'credit_note/get_list';

// Order selection
$route['credit_note/get_order_options'] = 'credit_note/get_order_options';
$route['credit_note/get_order_detail'] = 'credit_note/get_order_detail';

// Detail & Edit
$route['credit_note/get_detail/(:num)'] = 'credit_note/get_detail/$1';

// Save / Update
$route['credit_note/save'] = 'credit_note/save';

$route['credit_note/approval'] = 'credit_note/approval';

$route['credit_note/approval_detail/(:num)'] =
    'credit_note/approval_detail/$1';

$route['credit_note/approval_action/(:num)'] =
    'credit_note/approval_action/$1';


