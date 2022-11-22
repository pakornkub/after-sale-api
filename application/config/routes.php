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
//$route['user/:(any)'] = 'api/user/show/$1';
$route['create_user'] = 'api/user/create';
$route['update_user'] = 'api/user/update';
$route['delete_user'] = 'api/user/delete';

// Menu API Routes
$route['menu'] = 'api/menu/index';
//$route['menu/:(any)'] = 'api/menu/show/$1';
$route['create_menu'] = 'api/menu/create';
$route['update_menu'] = 'api/menu/update';
$route['delete_menu'] = 'api/menu/delete';
$route['parent_menu'] = 'api/menu/parent';

// Menu Type API Routes
$route['menu_type'] = 'api/MenuType/index';
//$route['menu_type/:(any)'] = 'api/MenuType/show/$1';

// Platform API Routes
$route['platform'] = 'api/platform/index';
//$route['platform/:(any)'] = 'api/platform/show/$1';

// Group API Routes
$route['group'] = 'api/group/index';
//$route['group/:(any)'] = 'api/group/show/$1';
$route['create_group'] = 'api/group/create';
$route['update_group'] = 'api/group/update';
$route['delete_group'] = 'api/group/delete';

// Grade API Routes
$route['grade'] = 'api/grade/index';
//$route['grade/:(any)'] = 'api/grade/show/$1';
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

// SKUMapping API Routes
$route['skumapping'] = 'api/skumapping/index';
$route['skumapping/:(any)'] = 'api/skumapping/show/$1';
$route['create_skumapping'] = 'api/skumapping/create';
$route['update_skumapping'] = 'api/skumapping/update';
$route['delete_skumapping'] = 'api/skumapping/delete';
$route['skumapping_item'] = 'api/skumapping/skumappingitem';

// Grade FG  API Routes
$route['grade_fg'] = 'api/GradeFG/index';

// Grade Package  API Routes
$route['grade_package'] = 'api/GradePackage/index';

// Grade SP  API Routes
$route['grade_sp'] = 'api/GradeSP/index';

// BOMID API Routes
$route['bomid'] = 'api/bomid/index';

//Receive Type  API Routes
$route['receive_type'] = 'api/ReceiveType/index';

//Request Type  API Routes
$route['request_type'] = 'api/RequestType/index';

//Receive No  API Routes
$route['receive_no'] = 'api/ReceiveNo/index';

// Receive Part API Routes
$route['receivepart'] = 'api/receivepart/index';
$route['receivepart/:(any)'] = 'api/receivepart/show/$1';
$route['create_receivepart'] = 'api/receivepart/create';
$route['update_receivepart'] = 'api/receivepart/update';
$route['delete_receivepart'] = 'api/receivepart/delete';
$route['receivepart_item'] = 'api/receivepart/receivepartitem';

// Tag API Routes
$route['tag'] = 'api/tag/index';
$route['tag/:(any)'] = 'api/tag/show/$1';
$route['select_receivestatus'] = 'api/tag/selectreceivestatus';
$route['select_tag'] = 'api/tag/select';
$route['create_tag'] = 'api/tag/create';
$route['delete_tag'] = 'api/tag/delete';
$route['receive_auto'] = 'api/tag/receive_auto';

// Tag Return API Routes
$route['tagreturn'] = 'api/tagreturn/index';
$route['tagreturn/:(any)'] = 'api/tagreturn/show/$1';
$route['select_receivestatusreturn'] = 'api/tagreturn/selectreceivestatus';
$route['select_tagreturn'] = 'api/tagreturn/select';
$route['create_tagreturn'] = 'api/tagreturn/create';
$route['delete_tagreturn'] = 'api/tagreturn/delete';



// Receive Return API Routes
$route['receivereturn'] = 'api/receivereturn/index';
$route['receivereturn/:(any)'] = 'api/receivereturn/show/$1';
$route['create_receivereturn'] = 'api/receivereturn/create';
$route['update_receivereturn'] = 'api/receivereturn/update';
$route['delete_receivereturn'] = 'api/receivereturn/delete';
$route['receivereturn_item'] = 'api/receivereturn/receivereturnitem';

// Job Repack API Routes
$route['jobrepack'] = 'api/jobrepack/index';
$route['jobrepack/:(any)'] = 'api/jobrepack/show/$1';
$route['create_jobrepack'] = 'api/jobrepack/create';
$route['update_jobrepack'] = 'api/jobrepack/update';
$route['delete_jobrepack'] = 'api/jobrepack/delete';
$route['jobrepack_item'] = 'api/jobrepack/jobrepackitem';
$route['select_qrbox'] = 'api/jobrepack/selectqrbox';
$route['select_withdrawitem'] = 'api/jobrepack/selectwithdrawitem';

//Job Type  API Routes
$route['job_type'] = 'api/jobType/index';

//Job No  API Routes
$route['job_no'] = 'api/JobNo/index';

//Bom of job repack  API Routes
$route['bomforjob'] = 'api/BomForJob/index';
$route['select_gradeplan'] = 'api/BomForJob/selectgrade';
$route['select_bomrev'] = 'api/BomForJob/selectrev';
$route['select_bomitem'] = 'api/BomForJob/selectitem';

// Job Plan API Routes
$route['jobplan'] = 'api/jobplan/index';
$route['jobplan/:(any)'] = 'api/jobplan/show/$1';
$route['create_jobplan'] = 'api/jobplan/create';
$route['update_jobplan'] = 'api/jobplan/update';
$route['delete_jobplan'] = 'api/jobplan/delete';

// Count Stock API Routes
$route['countstock'] = 'api/CountStock/index';
$route['countstock/:(any)'] = 'api/CountStock/show/$1';
$route['create_countstock'] = 'api/CountStock/create';
$route['update_countstock'] = 'api/CountStock/update';
$route['delete_countstock'] = 'api/CountStock/delete';
$route['countstock_item'] = 'api/CountStock/countstockitem';
$route['countstock_no'] = 'api/CountStock/countstockno';
$route['snap_countstock'] = 'api/CountStock/countstocksnap';
$route['countstock_status'] = 'api/CountStock/countstockstatus';

// Product Type  API Routes
$route['producttype'] = 'api/ProductType/index';

// Location  API Routes
$route['location'] = 'api/Location/index';

// Stock Monitor API Routes
$route['stockmonitor'] = 'api/StockMonitor/index';
$route['stockmonitorgroup'] = 'api/StockMonitor/stockgroup';
$route['stockmonitordetail'] = 'api/StockMonitor/stockdetail';
$route['stockmonitorwh'] = 'api/StockMonitor/stockwh';
$route['stockmonitorwh_header'] = 'api/StockMonitor/stockwhheader';

// Permission API Routes
$route['permission'] = 'api/permission/index';
$route['create_permission'] = 'api/permission/create';

// ReceiveSP API Routes (Mobile)
$route['receive_sp'] = 'api/mobile/ReceiveSP/index';
$route['receive_sp_item'] = 'api/mobile/ReceiveSP/item';
$route['update_receive_sp'] = 'api/mobile/ReceiveSP/update';
$route['exec_receive_sp_transaction'] = 'api/mobile/ReceiveSP/exec_transaction';

// UnlockSP API Routes (Mobile)
$route['unlock_sp'] = 'api/mobile/UnlockSP/index';
$route['update_unlock_sp'] = 'api/mobile/UnlockSP/update';
$route['exec_unlock_sp_tag'] = 'api/mobile/UnlockSP/exec_tag';

// ReceiveReturn API Routes (Mobile)
$route['receive_return'] = 'api/mobile/ReceiveReturn/index';
$route['receive_return_item'] = 'api/mobile/ReceiveReturn/item';
$route['update_receive_return'] = 'api/mobile/ReceiveReturn/update';
$route['exec_receive_return_transaction'] = 'api/mobile/ReceiveReturn/exec_transaction';

// JobRepack API Routes (Mobile)
$route['job_repack'] = 'api/mobile/JobRepack/index';
$route['job_repack_bom'] = 'api/mobile/JobRepack/bom';
$route['update_job_repack'] = 'api/mobile/JobRepack/update';
$route['exec_job_repack_item'] = 'api/mobile/JobRepack/exec_item';
$route['exec_job_repack_transaction'] = 'api/mobile/JobRepack/exec_transaction';

// JobRecheck API Routes (Mobile)
$route['job_recheck'] = 'api/mobile/JobRecheck/index';
$route['job_recheck_bom'] = 'api/mobile/JobRecheck/bom';
$route['update_job_recheck'] = 'api/mobile/JobRecheck/update';
$route['exec_job_recheck_item'] = 'api/mobile/JobRecheck/exec_item';
$route['exec_job_recheck_transaction'] = 'api/mobile/JobRecheck/exec_transaction';

// ShipToWH API Routes (Mobile)
$route['update_ship_to_wh'] = 'api/mobile/ShipToWH/update';

// WHReceive API Routes (Mobile)
$route['update_wh_receive'] = 'api/mobile/WHReceive/update';

// Withdraw API Routes (Mobile)
$route['update_withdraw'] = 'api/mobile/Withdraw/update';

// CheckStock API Routes (Mobile)
$route['check_stock'] = 'api/mobile/CheckStock/index';

// CountStock API Routes (Mobile)
$route['count_stock'] = 'api/mobile/CountStock/index';
$route['count_stock_item'] = 'api/mobile/CountStock/item';
$route['update_count_stock'] = 'api/mobile/CountStock/update';
$route['exec_count_stock_item'] = 'api/mobile/CountStock/exec_item';


// Split Part API Routes
$route['splitpart'] = 'api/splitpart/index';
$route['splitpart/:(any)'] = 'api/splitpart/show/$1';
$route['create_splitpart'] = 'api/splitpart/create';
$route['update_splitpart'] = 'api/splitpart/update';
$route['delete_splitpart'] = 'api/splitpart/delete';
$route['splitpart_item'] = 'api/splitpart/splitpartitem';
$route['skumapping_split'] = 'api/splitpart/skumappingsplit';



// Tag Split API Routes
$route['tagsplit'] = 'api/tagsplit/index';
$route['tagsplit/:(any)'] = 'api/tagsplit/show/$1';
$route['select_receivestatussplit'] = 'api/tagsplit/selectreceivestatus';
$route['select_tagsplit'] = 'api/tagsplit/select';
$route['create_tagsplit'] = 'api/tagsplit/create';
$route['delete_tagsplit'] = 'api/tagsplit/delete';
$route['receive_auto_split'] = 'api/tagsplit/receive_auto';

//Request No  API Routes
$route['request_no'] = 'api/RequestNo/index';


// Request Sale API Routes
$route['requestsale'] = 'api/requestsale/index';
$route['requestsale/:(any)'] = 'api/requestsale/show/$1';
$route['create_requestsale'] = 'api/requestsale/create';
$route['update_requestsale'] = 'api/requestsale/update';
$route['delete_requestsale'] = 'api/requestsale/delete';
$route['requestsale_item'] = 'api/requestsale/requestsaleitem';
$route['confirm_request'] = 'api/requestsale/confirm_request';