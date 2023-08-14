<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class RequestBranch extends REST_Controller
{

    protected $MenuId = 'RequestBranch';

    public function __construct()
    {

        parent::__construct();

        // Load RequestBranch
        $this->load->model('RequestBranch_Model');
        $this->load->model('Auth_Model');

    }

    /**
     * Show RequestBranch All API
     * ---------------------------------
     * @method : GET
     * @link : requestbranch/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // RequestBranch Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load RequestBranch Function
            $output = $this->RequestBranch_Model->select_requestbranch();

            if (isset($output) && $output) {

                // Show RequestBranch All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Request Branch all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

            }

        } else {
            // Validate Error
            $message = [
                'status' => false,
                'message' => $is_valid_token['message'],
            ];

            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        }

    }

    /**
     * Create Request Branch API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : requestbranch/create
     */
    public function create_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        
            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // Request Branch Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $request_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $request_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $request_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });


                $request_header = json_decode($this->input->post('data1'), true); 

                if ($request_permission[array_keys($request_permission)[0]]['Created']) {



                    $request_no_output = json_decode(json_encode($this->RequestBranch_Model->select_request_no($request_header['Request_Type'])), true);
                    $request_no = $request_no_output[array_keys($request_no_output)[0]]['RequestNo'];

                    if (isset($request_no) && $request_no) {

                    
                        $request_data['data'] = [
                            'RequestBranch_No' => $request_no,
                            'RequestBranch_Date' => $request_header['Request_Date'],
                            'RequestBranch_type' => $request_header['Request_Type'],
                            'Quotation_No' => $request_header['Quotation_No'],
                            'Customer_Name' => $request_header['Customer_Name'],
                            'User_Request' => $request_header['User'],
                            'Plan_Team' => $request_header['Plan_Team'],
                            'Ref_No1' => null,
                            'Remark' => (isset($request_header['Request_Remark']) && $request_header['Request_Remark']) ? $request_header['Request_Remark'] : null,
                            'status' => $request_header['Branch_Status'],
                            'Create_Date' => date('Y-m-d H:i:s'),
                            'Create_By' => $request_token['UserName'],
                            'Update_Date' => null,
                            'Update_By' => null,
                            
                        ];

    
                        // Create request Function
                        $request_output = $this->RequestBranch_Model->insert_requestbranch($request_data);

    
    
    
                        if (isset($request_output) && $request_output) {
    
                            //Create Item Success
                            $request_item = json_decode($this->input->post('data2'), true); 
                            
                            foreach ($request_item as $value) {
                                
                                $request_data_item['data'] = [
                                    'RequestBranch_ID' => $request_output,
                                    'RequestBranchItem_Datetime' => date('Y-m-d H:i:s'),
                                    'ITEM_ID' => $value['ITEM_ID'],
                                    'Status' => '1',
                                    'Create_Date' => date('Y-m-d H:i:s'),
                                    'Create_By' => $request_token['UserName'],
                                    
                                ];
    
                                $request_output_item = $this->RequestBranch_Model->insert_requestbranch_item($request_data_item);
                                
    
                            }
                            
    
                            $message = [
                                'status' => true,
                                'message' => 'Create Request Branch Successful',
                            ];
    
                            $this->response($message, REST_Controller::HTTP_OK);
    
    
    
                        } else {
    
                            // Create Request Branch Error
                            $message = [
                                'status' => false,
                                'message' => 'Create Request Branch Fail : [Insert Data Fail]',
                            ];
    
                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    
                        }

                    }else{
                            // Create Request NO Error
                            $message = [
                                'status' => false,
                                'message' => 'Request Branch Fail',
                            ];

                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }

                    

                } else {
                    // Permission Error
                    $message = [
                        'status' => false,
                        'message' => 'You don’t currently have permission to Create',
                    ];

                    $this->response($message, REST_Controller::HTTP_NOT_FOUND);
                }

            } else {
                // Validate Error
                $message = [
                    'status' => false,
                    'message' => $is_valid_token['message'],
                ];

                $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
            }

        

    }

    /**
     * Update RequestBranch API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : requestbranch/update
     */
    public function update_post()
    { 

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // RequestBranch Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $request_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $request_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $request_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                    $request_header = json_decode($this->input->post('data1'), true); 

                    if ($request_permission[array_keys($request_permission)[0]]['Updated']) {

                        $request_data['index'] = $request_header['Request_Index'];
                        
                        $request_data['data'] = [
                            'RequestBranch_No' => $request_header['Request_No'],
                            'RequestBranch_Date' => $request_header['Request_Date'],
                            'RequestBranch_type' => $request_header['Request_Type'],
                            'Quotation_No' => $request_header['Quotation_No'],
                            'Customer_Name' => $request_header['Customer_Name'],
                            'User_Request' => $request_header['User'],
                            'Plan_Team' => $request_header['Plan_Team'],
                            'Ref_No1' => null,
                            'Remark' => (isset($request_header['Request_Remark']) && $request_header['Request_Remark']) ? $request_header['Request_Remark'] : null,
                            'status' => $request_header['Branch_Status'],
                            'Update_Date' => date('Y-m-d H:i:s'),
                            'Update_By' => $request_token['UserName'],
                            
                        ];

    
                   // Update RequestBranch Function
                    $request_output = $this->RequestBranch_Model->update_requestbranch($request_data);

                    if (isset($request_output) && $request_output) {
                        
                        $delete_output = $this->RequestBranch_Model->delete_requestbranch_item($request_data);
                        //Create Item Success
                        $request_item = json_decode($this->input->post('data2'), true); 
                        
                        foreach ($request_item as $value) {
                            
                            $request_data_item['data'] = [
                                'RequestBranch_ID' => $request_header['Request_Index'],
                                'RequestBranchItem_Datetime' => date('Y-m-d H:i:s'),
                                'ITEM_ID' => $value['ITEM_ID'],
                                'Status' => '1',
                                'Create_Date' => date('Y-m-d H:i:s'),
                                'Create_By' => $request_token['UserName'],
                                
                            ];

                            $request_output_item = $this->RequestBranch_Model->insert_requestbranch_item($request_data_item);
                            

                        }
                        

                        $message = [
                            'status' => true,
                            'message' => 'Create Request Branch Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);



                    } else {

                        // Create Request Branch Error
                        $message = [
                            'status' => false,
                            'message' => 'Create Request Branch Fail : [Insert Data Fail]',
                        ];

                        $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

                    }    

                    // if (isset($request_output) && $request_output) {

                    //         // Update RequestBranch Success
                    //     $message = [
                    //         'status' => true,
                    //         'message' => 'Update Request Branch Successful',
                    //     ];

                    //     $this->response($message, REST_Controller::HTTP_OK);

                    // } else {

                    //     // Update RequestBranch Error
                    //     $message = [
                    //         'status' => false,
                    //         'message' => 'Update Request Branch Fail : [Update Data Fail]',
                    //     ];

                    //     $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

                    // }


                } else {
                    // Permission Error
                    $message = [
                        'status' => false,
                        'message' => 'You don’t currently have permission to Update',
                    ];

                    $this->response($message, REST_Controller::HTTP_NOT_FOUND);
                }

            } else {
                // Validate Error
                $message = [
                    'status' => false,
                    'message' => $is_valid_token['message'],
                ];

                $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
            }

        

    }

    /**
     * Delete RequestBranch API
     * ---------------------------------
     * @param: RequestBranch_Index
     * ---------------------------------
     * @method : POST
     * @link : requestbranch/delete
     */
    public function delete_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // RequestBranch Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $request_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $request_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $request_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($request_permission[array_keys($request_permission)[0]]['Deleted']) {


                    $RequestBranch_No = json_decode($this->input->post('data1'), true); 

                    $request_del['RequestBranch_No'] = $RequestBranch_No;
                    $request_del['data'] = [
                        'status' => -1,
                        'Update_Date' => date('Y-m-d H:i:s'),
                        'Update_By' => $request_token['UserName'],
                        
                    ];


                    // Delete RequestBranch Function
                    $request_output = $this->RequestBranch_Model->delete_requestbranch($request_del);
                    

                    if (isset($request_output) && $request_output)  {

                        // Delete RequestBranch Success
                        $message = [
                            'status' => true,
                            'message' => $request_output,
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete RequestBranch Error
                        $message = [
                            'status' => false,
                            'message' => 'Delete Request Branch Fail : [Delete Data Fail]',
                        ];

                        $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

                    }

                } else {
                    // Permission Error
                    $message = [
                        'status' => false,
                        'message' => 'You don’t currently have permission to Delete',
                    ];

                    $this->response($message, REST_Controller::HTTP_NOT_FOUND);
                }

            } else {
                // Validate Error
                $message = [
                    'status' => false,
                    'message' => $is_valid_token['message'],
                ];

                $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
            }

        

    }

        /**
     * Show RequestBranch item All API
     * ---------------------------------
     * @method : GET
     * @link : requestbranch/requestbranch_item
     */
    public function requestbranchitem_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // RequestBranchID Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load RequestBranchID Function
            $Request_ID = $this->input->get('RequestBranch_ID');

            $output = $this->RequestBranch_Model->select_requestbranchitem($Request_ID);

            if (isset($output) && $output) {

                // Show RequestBranchID All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show RequestBranchItem all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
        }

    }

    



    /**
     * Show RequestBranch Quotation API
     * ---------------------------------
     * @method : GET
     * @link : quotation_requestbranch/index
     */
    public function quotation_requestbranch_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // RequestBranch Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load RequestBranch Function
            $output = $this->RequestBranch_Model->select_quotation_requestbranch();

            if (isset($output) && $output) {

                // Show RequestBranch All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Quotation Request Branch all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

            }

        } else {
            // Validate Error
            $message = [
                'status' => false,
                'message' => $is_valid_token['message'],
            ];

            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        }

    }

}
