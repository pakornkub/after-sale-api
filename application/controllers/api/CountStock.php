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

                // Show Receive Part All Error
                // $message = [
                //     'status' => false,
                //     'message' => 'Receive Part data was not found in the database',
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
     * Create Receive Part API
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

            // Receive Part Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $receive_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $receive_permission = array_filter($receive_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });


                $receive_header = json_decode($this->input->post('data1'), true); 

                if ($receive_permission[array_keys($receive_permission)[0]]['Created']) {



                    $receive_no_output = json_decode(json_encode($this->CountStock_Model->select_receive_no()), true);
                    $receive_no = $receive_no_output[array_keys($receive_no_output)[0]]['ReceiveNo'];

                    if (isset($receive_no) && $receive_no) {

                    
                        $receive_data['data'] = [
                            'Rec_type' => '1',
                            'Rec_NO' => $receive_no,
                            'Rec_Datetime' => $receive_header['Receive_Date'],
                            'status' => '1',
                            'Ref_DocNo_1' => (isset($receive_header['Ref_No1']) && $receive_header['Ref_No1']) ? $receive_header['Ref_No1'] : null,
                            'Ref_DocNo_2' => (isset($receive_header['Ref_No2']) && $receive_header['Ref_No2']) ? $receive_header['Ref_No2'] : null,
                            'Remark' => (isset($receive_header['Receive_Remark']) && $receive_header['Receive_Remark']) ? $receive_header['Receive_Remark'] : null,
                            'Create_Date' => date('Y-m-d H:i:s'),
                            'Create_By' => $receive_token['UserName'],
                            'Update_Date' => null,
                            'Update_By' => null,
                            
                        ];
    
                        // Create receive Function
                        $receive_output = $this->CountStock_Model->insert_countstock($receive_data);
    
    
    
                        if (isset($receive_output) && $receive_output) {
    
                            //Create Item Success
                            $receive_item = json_decode($this->input->post('data2'), true); 
                            
                            foreach ($receive_item as $value) {
                                
                                $receive_data_item['data'] = [
                                    'Rec_ID' => $receive_output,
                                    'Qty' => $value['QTY'],
                                    'Item_ID' => $value['Grade_ID'],
                                    'Lot_No' => $value['Lot_No'],
                                    'ItemStatus_ID' => $value['QTY'],
                                    'Create_Date' => date('Y-m-d H:i:s'),
                                    'Create_By' => $receive_token['UserName'],
                                    
                                ];
    
    
                                $receive_output_item = $this->CountStock_Model->insert_countstock_item($receive_data_item);
    
                            }
                            
    
                            $message = [
                                'status' => true,
                                'message' => 'Create Count Stock Successful',
                            ];
    
                            $this->response($message, REST_Controller::HTTP_OK);
    
    
    
                        } else {
    
                            // Create receive Error
                            $message = [
                                'status' => false,
                                'message' => 'Create Count Stock Fail : [Insert Data Fail]',
                            ];
    
                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    
                        }
                    }else{
                            // Create Receive NO Error
                            $message = [
                                'status' => false,
                                'message' => 'Receive No Fail',
                            ];

                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }

                    

                } else {
                    // Permission Error
                    $message = [
                        'status' => false,
                        'message' => 'You don’t currently have permission to Create',
                    ];

                    $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
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

                $receive_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $receive_permission = array_filter($receive_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                    $receive_header = json_decode($this->input->post('data1'), true); 

                    if ($receive_permission[array_keys($receive_permission)[0]]['Created']) {

                        $receive_data['index'] = $receive_header['Receive_Index'];

                        $receive_data['data'] = [
                            'Rec_type' => '1',
                            'Rec_NO' => $receive_header['Receive_No'],
                            'Rec_Datetime' => $receive_header['Receive_Date'],
                            'Ref_DocNo_1' => (isset($receive_header['Ref_No1']) && $receive_header['Ref_No1']) ? $receive_header['Ref_No1'] : null,
                            'Ref_DocNo_2' => (isset($receive_header['Ref_No2']) && $receive_header['Ref_No2']) ? $receive_header['Ref_No2'] : null,
                            'Remark' => (isset($receive_header['Receive_Remark']) && $receive_header['Receive_Remark']) ? $receive_header['Receive_Remark'] : null,
                            'Update_Date' => date('Y-m-d H:i:s'),
                            'Update_By' => $receive_token['UserName'],
                            
                        ];

                    

                   // Update CountStock Function
                    $receive_output = $this->CountStock_Model->update_countstock($receive_data);

                    if (isset($receive_output) && $receive_output) {


                        $delete_output = $this->CountStock_Model->delete_countstock_item($receive_data);

                        $receive_item = json_decode($this->input->post('data2'), true); 
                        
                        foreach ($receive_item as $value) {
                            
                            $receive_data_item['data'] = [
                                'Rec_ID' => $receive_header['Receive_Index'],
                                'Qty' => $value['QTY'],
                                'Item_ID' => $value['Grade_ID'],
                                'Lot_No' => $value['Lot_No'],
                                'ItemStatus_ID' => $value['QTY'],
                                'Create_Date' => date('Y-m-d H:i:s'),
                                'Create_By' => $receive_token['UserName'],
                                
                            ];

                            $receive_output_item = $this->CountStock_Model->insert_countstock_item($receive_data_item);
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

                    $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
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

                $receive_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $receive_permission = array_filter($receive_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($receive_permission[array_keys($receive_permission)[0]]['Deleted']) {

                    $receive_data['index'] = $this->input->post('Rec_ID');

                    // Delete CountStock Function
                    $receive_output = $this->CountStock_Model->delete_countstockt($receive_data);
                    $receive_output_item = $this->CountStock_Model->delete_countstock_item($receive_data);

                    if (isset($receive_output) && $receive_output) {

                        // Delete CountStock Success
                        $message = [
                            'status' => true,
                            'message' => $receive_output,
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

                    $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
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
     * Show CountStock item All API
     * ---------------------------------
     * @method : GET
     * @link : countstock/countstock_item
     */
    public function countstockitem_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // CountStockID Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load CountStockID Function
            $Rc_ID = $this->input->get('CountStock_ID');

            $output = $this->CountStock_Model->select_countstockitem($Rc_ID);

            if (isset($output) && $output) {

                // Show CountStockID All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Count Stock Item all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
        }

    }

}
