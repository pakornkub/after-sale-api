<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class CountStock extends REST_Controller
{

    protected $MenuId = 'CountStock';

    public function __construct()
    {

        parent::__construct();

        // Load CountStock
        $this->load->model('CountStock_Model');
        $this->load->model('Auth_Model');

    }

    /**
     * Show CountStock All API
     * ---------------------------------
     * @method : GET
     * @link : countstock/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // CountStock Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load CountStock Function
            $output = $this->CountStock_Model->select_countstock();

            if (isset($output) && $output) {

                // Show CountStock All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Count Stock all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show CountStock All Error
                // $message = [
                //     'status' => false,
                //     'message' => 'CountStock data was not found in the database',
                // ];

                // $this->response($message, REST_Controller::HTTP_NOT_FOUND);

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
     * Create CountStock API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : countstock/create
     */
    public function create_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        
            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // CountStock Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $countstock_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $countstock_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $countstock_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });


                $countstock_header = json_decode($this->input->post('data1'), true); 

                if ($countstock_permission[array_keys($countstock_permission)[0]]['Created']) {



                    $countstock_no_output = json_decode(json_encode($this->CountStock_Model->select_countstock_no()), true);
                    $countstock_no = $countstock_no_output[array_keys($countstock_no_output)[0]]['CountStockNo'];

                    if (isset($countstock_no) && $countstock_no) {

                    
                        $countstock_data['data'] = [
                            'CountStock_Date' => $countstock_header['CountStock_Date'],
                            'Description' => (isset($countstock_header['CountStock_Description']) && $countstock_header['CountStock_Description']) ? $countstock_header['CountStock_Description'] : null,
                            'CountStock_DocNo' => $countstock_no,
                            'Status' => '1',
                            'Product_ID' => (isset($countstock_header['Product_Type']) && $countstock_header['Product_Type']) ? $countstock_header['Product_Type'] : null,
                            'Location_ID' => (isset($countstock_header['Location']) && $countstock_header['Location']) ? $countstock_header['Location'] : null,
                            'ITEM_ID' => (isset($countstock_header['Grade_ID']) && $countstock_header['Grade_ID']) ? $countstock_header['Grade_ID'] : null,
                            'Create_Date' => date('Y-m-d H:i:s'),
                            'Create_By' => $countstock_token['UserName'],
                            'Update_Date' => null,
                            'Update_By' => null,
                            
                        ];
    
                        // Create countstock Function
                        $countstock_output = $this->CountStock_Model->insert_countstock($countstock_data);
    
    
    
                        if (isset($countstock_output) && $countstock_output) {
    
                            //Create Item Success
                            $countstock_item = json_decode($this->input->post('data2'), true); 
                            
                            foreach ($countstock_item as $value) {
                                
                                $countstock_data_item['data'] = [
                                    'CountStock_ID' => $countstock_output,
                                    'ITEM_ID' => $value['ITEM_ID'],
                                    'Location_ID' => $value['Location_ID'],
                                    'Total_QTY' => $value['Count_Balance'],
                                    'Create_Date' => date('Y-m-d H:i:s'),
                                    'Create_By' => $countstock_token['UserName'],
                                    
                                ];
    
    
                                $countstock_output_item = $this->CountStock_Model->insert_countstock_item($countstock_data_item);
    
                            }
                            
    
                            $message = [
                                'status' => true,
                                'message' => 'Create Count Stock Successful',
                            ];
    
                            $this->response($message, REST_Controller::HTTP_OK);
    
    
    
                        } else {
    
                            // Create countstock Error
                            $message = [
                                'status' => false,
                                'message' => 'Create Count Stock Fail : [Insert Data Fail]',
                            ];
    
                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    
                        }
                    }else{
                            // Create CountStock NO Error
                            $message = [
                                'status' => false,
                                'message' => 'CountStock No Fail',
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
     * Update CountStock API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : countstock/update
     */
    public function update_post()
    { 

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // CountStock Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $countstock_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $countstock_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $countstock_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                $countstock_header = json_decode($this->input->post('data1'), true); 
                $countstock_header1 = json_decode($this->input->post('data3'), true); 
                

                    if ($countstock_permission[array_keys($countstock_permission)[0]]['Updated']) {

                        $Status_output = json_decode(json_encode($this->CountStock_Model->select_countstockstatus($countstock_header['CountStock_Index'])), true);
                        $countstock_Status = $Status_output[array_keys($Status_output)[0]]['Status'];

                        if($countstock_Status < 3){
                            $countstock_data['index'] = $countstock_header['CountStock_Index'];

                        $countstock_data['data'] = [
                            'CountStock_Date' => $countstock_header['CountStock_Date'],
                            'Description' => (isset($countstock_header['CountStock_Description']) && $countstock_header['CountStock_Description']) ? $countstock_header['CountStock_Description'] : null,
                            'Product_ID' => (isset($countstock_header1['Product_Type']) && $countstock_header1['Product_Type']) ? $countstock_header1['Product_Type'] : null,
                            'Location_ID' => (isset($countstock_header1['Location']) && $countstock_header1['Location']) ? $countstock_header1['Location'] : null,
                            'ITEM_ID' => (isset($countstock_header1['Grade_ID']) && $countstock_header1['Grade_ID']) ? $countstock_header1['Grade_ID'] : null,
                            
                            'Update_Date' => date('Y-m-d H:i:s'),
                            'Update_By' => $countstock_token['UserName'],
                        ];

                    // Update CountStock Function
                        $countstock_output = $this->CountStock_Model->update_countstock($countstock_data);

                        if (isset($countstock_output) && $countstock_output) {


                            $delete_output = $this->CountStock_Model->delete_countstock_item($countstock_data);

                            $countstock_item = json_decode($this->input->post('data2'), true); 
                            
                            foreach ($countstock_item as $value) {
                                
                                

                                $countstock_data_item['data'] = [
                                    'CountStock_ID' => $countstock_header['CountStock_Index'],
                                    'ITEM_ID' => $value['ITEM_ID'],
                                    'Location_ID' => $value['Location_ID'],
                                    'Total_QTY' => $value['Count_Balance'],
                                    'Create_Date' => date('Y-m-d H:i:s'),
                                    'Create_By' => $countstock_token['UserName'],
                                    
                                ];

                                $countstock_output_item = $this->CountStock_Model->insert_countstock_item($countstock_data_item);
                            }
                                // Update CountStock Success
                            $message = [
                                'status' => true,
                                'message' => 'Update Count Stock Successful',
                            ];

                            $this->response($message, REST_Controller::HTTP_OK);

                        } else {

                            // Update CountStock Error
                            $message = [
                                'status' => false,
                                'message' => 'Can’t save data due to stock counting',
                            ];

                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

                        }
                    }else{
                        // Update CountStock Error
                        $message = [
                            'status' => false,
                            'message' => 'Update Count Stock Fail : [Update Data Fail]',
                        ];

                        $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }

                        

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
     * Delete CountStock API
     * ---------------------------------
     * @param: CountStock_Index
     * ---------------------------------
     * @method : POST
     * @link : countstock/delete
     */
    public function delete_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // CountStock Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $countstock_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $countstock_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $countstock_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($countstock_permission[array_keys($countstock_permission)[0]]['Deleted']) {

                    $countstock_data['index'] = $this->input->post('CountStock_ID');

                    // Delete CountStock Function
                    $countstock_output = $this->CountStock_Model->delete_countstock($countstock_data);
                    $countstock_output_item = $this->CountStock_Model->delete_countstock_item($countstock_data);

                    if (isset($countstock_output) && $countstock_output) {

                        // Delete CountStock Success
                        $message = [
                            'status' => true,
                            'message' => $countstock_output,
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete CountStock Error
                        $message = [
                            'status' => false,
                            'message' => 'Delete Count Stock Fail : [Delete Data Fail]',
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
     * Show CountStock All API
     * ---------------------------------
     * @method : POST
     * @link : countstock/countstockitem
     */
    public function countstockitem_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Tag Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        //if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load Tag Function
            

            $CSI_data = [
                'CountStock_ID' => $this->input->post('CountStock_ID'),
               
            ];

            $CSI_output = $this->CountStock_Model->select_countstockitem($CSI_data);

            if (isset($CSI_output) && $CSI_output) {

                // Show Tag All Success
                $message = [
                    'status' => true,
                    'data' => $CSI_output,
                    'message' => 'Show Count Stock Item successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }

        // } else {
        //     // Validate Error
        //     $message = [
        //         'status' => false,
        //         'message' => $is_valid_token['message'],
        //     ];

        //     $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        // }
    }


        /**
     * Show CountStock item All API
     * ---------------------------------
     * @method : GET
     * @link : countstock/countstockno
     */
    public function countstockno_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // CountStockID Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load CountStockID Function

            $output = $this->CountStock_Model->select_countstock_no();

            if (isset($output) && $output) {

                // Show CountStockID All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Count Stock No successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
        }

    }

    

    /**
     * Show Snap API
     * ---------------------------------
     * @method : POST
     * @link : countstock/countstocksnap
     */
    public function countstocksnap_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        $is_valid_token = $this->authorization_token->validateToken();
        
            $Filter = $this->input->post('Filter');

            // $tag_data = [
            //     'Rec_ID' => $this->input->post('Rec_ID'),
               
            // ];

            $output = $this->CountStock_Model->select_countstocksnap($Filter);

            if (isset($output) && $output) {

                // Show Tag All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Snap successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
    }

    /**
     * Show Snap API
     * ---------------------------------
     * @method : POST
     * @link : countstock/countstockstatus
     */
    public function countstockstatus_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        $is_valid_token = $this->authorization_token->validateToken();
        
            $CountStock_ID = $this->input->post('CountStock_ID');

            // $tag_data = [
            //     'Rec_ID' => $this->input->post('Rec_ID'),
               
            // ];

            $output = $this->CountStock_Model->select_countstockstatus($CountStock_ID);

            if (isset($output) && $output) {

                // Show Tag All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Status successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
    }
}

    

