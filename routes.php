<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
|    example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|    https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|    $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|    $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|    $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:    my-controller/index    -> my_controller/index
|        my-controller/my-method    -> my_controller/my_method
 */
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = false;

// Auth API Routes
$route['login'] = 'api/auth/login';
$route['logout'] = 'api/auth/logout';
$route['validate_token'] = 'api/auth/validate_token';
$route['refresh_token'] = 'api/auth/refresh_token';

// User API Routes
$route['user'] = 'api/user/index';
$route['user/:(any)'] = 'api/user/show/$1';
$route['create_user'] = 'api/user/create';
$route['update_user'] = 'api/user/update';
$route['delete_user'] = 'api/user/delete';

// Menu API Routes
$route['menu'] = 'api/menu/index';
$route['menu/:(any)'] = 'api/menu/show/$1';
$route['create_menu'] = 'api/menu/create';
$route['update_menu'] = 'api/menu/update';
$route['delete_menu'] = 'api/menu/delete';
$route['parent_menu'] = 'api/menu/parent';

// Menu Type API Routes
$route['menu_type'] = 'api/MenuType/index';
$route['menu_type/:(any)'] = 'api/MenuType/show/$1';

// Platform API Routes
$route['platform'] = 'api/platform/index';
$route['platform/:(any)'] = 'api/platform/show/$1';


// Group API Routes
$route['group'] = 'api/group/index';
$route['group/:(any)'] = 'api/group/show/$1';
$route['create_group'] = 'api/group/create';
$route['update_group'] = 'api/group/update';
$route['delete_group'] = 'api/group/delete';

// Grade API Routes
$route['grade'] = 'api/grade/index';
$route['grade/:(any)'] = 'api/grade/show/$1';
$route['create_grade'] = 'api/grade/create';
$route['update_grade'] = 'api/grade/update';
$route['delete_grade'] = 'api/grade/delete';

// Bom API Routes
$route['bom'] = 'api/bom/index';
$route['bom/:(any)'] = 'api/bom/show/$1';
$route['create_bom'] = 'api/bom/create';
$route['update_bom'] = 'api/bom/update';
$route['delete_bom'] = 'api/bom/delete';
$route['bom_item'] = 'api/bom/bomitem';

// Grade FG  API Routes
$route['grade_fg'] = 'api/GradeFG/index';

// Grade SP  API Routes
$route['grade_sp'] = 'api/GradeSP/index';


// BOMID API Routes
$route['bomid'] = 'api/bomid/index';

//Receive Type  API Routes
$route['receive_type'] = 'api/ReceiveType/index';

