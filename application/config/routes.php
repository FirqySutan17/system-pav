<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['dashboard'] = 'dashboard/index';
$route['property'] = 'property/index';
$route['property/(:any)'] = 'property/property_detail/$1';

$route['properties']               = 'properties/index';
$route['properties/create']        = 'properties/create';
$route['properties/store']         = 'properties/store';
$route['properties/view/(:num)'] = 'properties/view/$1';
$route['properties/edit/(:num)']   = 'properties/edit/$1';
$route['properties/update/(:num)'] = 'properties/update/$1';
$route['properties/delete/(:num)'] = 'properties/delete/$1';

$route['properties/quick-add-category']
    = 'properties/quick_add_category';

$route['properties/quick-add-location']
    = 'properties/quick_add_location';


