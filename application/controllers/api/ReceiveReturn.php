<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class ReceiveReturn extends REST_Controller
{

    protected $MenuId = 'ReceiveReturn';

    public function __construct()
    {

        parent::__construct();

        // Load ReceiveReturn
        $this->load->model('ReceiveReturn_Model');

    }

    /**
     * Show ReceiveReturn All API
     * ---------------------------------
     * @method : GET
     * @link : receivereturn/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // ReceiveReturn Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load ReceiveReturn Function
            $output = $this->ReceiveReturn_Model->select_receivereturn();

            if (isset($output) && $output) {

                // Show ReceiveReturn All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Receive Return all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show Receive Return All Error
                // $message = [
                //     'status' => false,
                //     'message' => 'Receive Return data was not found in the database',
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
     * Create Receive Return API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : receivereturn/create
     */
    public function create_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        
            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // Receive Return Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $receive_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $receive_permission = array_filter($receive_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });


                $receive_header = json_decode($this->input->post('data1'), true); 

                if ($receive_permission[array_keys($receive_permission)[0]]['Created']) {

                    $receive_data['data'] = [
                        'Rec_type' => '2',
                        'Rec_NO' => $receive_header['Receive_No'],
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
                    $receive_output = $this->ReceiveReturn_Model->insert_receivereturn($receive_data);



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


                            $receive_output_item = $this->ReceiveReturn_Model->insert_receivereturn_item($receive_data_item);

                        }
                        

                        $message = [
                            'status' => true,
                            'message' => 'Create Receive Return Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);



                    } else {

                        // Create receive Error
                        $message = [
                            'status' => false,
                            'message' => 'Create Receive Return Fail : [Insert Data Fail]',
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
     * Update ReceiveReturn API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : receivereturn/update
     */
    public function update_post()
    { 

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // ReceiveReturn Token Validation
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
                            'Rec_type' => '2',
                            'Rec_NO' => $receive_header['Receive_No'],
                            'Rec_Datetime' => $receive_header['Receive_Date'],
                            'Ref_DocNo_1' => (isset($receive_header['Ref_No1']) && $receive_header['Ref_No1']) ? $receive_header['Ref_No1'] : null,
                            'Ref_DocNo_2' => (isset($receive_header['Ref_No2']) && $receive_header['Ref_No2']) ? $receive_header['Ref_No2'] : null,
                            'Remark' => (isset($receive_header['Receive_Remark']) && $receive_header['Receive_Remark']) ? $receive_header['Receive_Remark'] : null,
                            'Update_Date' => date('Y-m-d H:i:s'),
                            'Update_By' => $receive_token['UserName'],
                            
                        ];

                    

                   // Update ReceiveReturn Function
                    $receive_output = $this->ReceiveReturn_Model->update_receivereturn($receive_data);

                    if (isset($receive_output) && $receive_output) {


                        $delete_output = $this->ReceiveReturn_Model->delete_receivereturn_item($receive_data);

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

                            $receive_output_item = $this->ReceiveReturn_Model->insert_receivereturn_item($receive_data_item);
                        }
                            // Update ReceiveReturn Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Grade Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update ReceiveReturn Error
                        $message = [
                            'status' => false,
                            'message' => 'Update Receive Return Fail : [Update Data Fail]',
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
     * Delete ReceiveReturn API
     * ---------------------------------
     * @param: ReceiveReturn_Index
     * ---------------------------------
     * @method : POST
     * @link : receivereturn/delete
     */
    public function delete_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // ReceiveReturn Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $receive_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $receive_permission = array_filter($receive_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($receive_permission[array_keys($receive_permission)[0]]['Deleted']) {

                    $receive_data['index'] = $this->input->post('Rec_ID');

                    // Delete ReceiveReturn Function
                    $receive_output = $this->Receivereturn_Model->delete_receivereturn($receive_data);
                    $receive_output_item = $this->ReceiveReturn_Model->delete_receivereturn_item($receive_data);

                    if (isset($receive_output) && $receive_output) {

                        // Delete ReceiveReturn Success
                        $message = [
                            'status' => true,
                            'message' => $receive_output,
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete ReceiveReturn Error
                        $message = [
                            'status' => false,
                            'message' => 'Delete Receive Return Fail : [Delete Data Fail]',
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
     * Show ReceiveReturn item All API
     * ---------------------------------
     * @method : GET
     * @link : receivereturn/receivereturn_item
     */
    public function receivereturnitem_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // ReceiveReturnID Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load ReceiveReturnID Function
            $Rc_ID = $this->input->get('ReceiveReturn_ID');

            $output = $this->ReceiveReturn_Model->select_receivereturnitem($Rc_ID);

            if (isset($output) && $output) {

                // Show ReceiveReturnID All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show ReceiveReturnItem all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
        }

    }

}
