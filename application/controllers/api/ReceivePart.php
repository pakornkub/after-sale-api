<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class ReceivePart extends REST_Controller
{

    protected $MenuId = 'ReceivePart';

    public function __construct()
    {

        parent::__construct();

        // Load ReceivePart
        $this->load->model('ReceivePart_Model');

    }

    /**
     * Show ReceivePart All API
     * ---------------------------------
     * @method : GET
     * @link : receivepart/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // ReceivePart Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load ReceivePart Function
            $output = $this->ReceivePart_Model->select_receivepart();

            if (isset($output) && $output) {

                // Show ReceivePart All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Receive Part all successful',
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
     * @link : receivepart/create
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

                $bom_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $bom_permission = array_filter($bom_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });


                $bom_header = json_decode($this->input->post('data1'), true); 

                if ($bom_permission[array_keys($bom_permission)[0]]['Created']) {

                    $bom_data['data'] = [
                        'BOM_Name' => $bom_header['Bom_Id'],
                        'BOM_Date' => $bom_header['Bom_Date'],
                        'FG_ITEM_ID' => $bom_header['Grade_ID_FG'],
                        'Bom_Rev_No' => $bom_header['Rev_No'],
                        'Remark' => $bom_header['Bom_Remark'],
                        'Status' => intval($bom_header['Bom_Status']),
                        'Create_Date' => date('Y-m-d H:i:s'),
                        'Create_By' => $bom_token['UserName'],
                        'Update_Date' => null,
                        'Update_By' => null,
                        
                    ];

                    // Create bom Function
                    $bom_output = $this->Bom_Model->insert_receivepart($bom_data);



                    if (isset($bom_output) && $bom_output) {

                        // Create Bom Success
                        $bom_item = json_decode($this->input->post('data2'), true); 
                        $i = 1;
                        foreach ($bom_item as $value) {
                            
                            $bom_data_item['data'] = [
                                'BOM_ID' => $bom_output,
                                'ITEM_Seq' => $i,
                                'ITEM_ID' => $value['Grade_ID'],
                                'ITEM_QTY' => $value['QTY'],
                                'Create_Date' => date('Y-m-d H:i:s'),
                                'Create_By' => $bom_token['UserName'],
                                
                            ];

                            $i = $i+1;

                            $bom_output_item = $this->ReceivePart_Model->insert_receivepart_item($bom_data_item);

                        }
                        

                        $message = [
                            'status' => true,
                            'message' => 'Create Receive Part Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);



                    } else {

                        // Create Bom Error
                        $message = [
                            'status' => false,
                            'message' => 'Create Receive Part Fail : [Insert Data Fail]',
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
     * Update ReceivePart API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : receivepart/update
     */
    public function update_post()
    { 

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // ReceivePart Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $bom_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $bom_permission = array_filter($bom_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                    $bom_header = json_decode($this->input->post('data1'), true); 

                    if ($bom_permission[array_keys($bom_permission)[0]]['Created']) {

                        $bom_data['index'] = $bom_header['BOM_Index'];

                        $bom_data['data'] = [
                            'BOM_Name' => $bom_header['Bom_Id'],
                            'BOM_Date' => $bom_header['Bom_Date'],
                            'FG_ITEM_ID' => $bom_header['Grade_ID_FG'],
                            'Bom_Rev_No' => $bom_header['Rev_No'],
                            'Remark' => $bom_header['Bom_Remark'],
                            'Status' => intval($bom_header['Bom_Status']),
                            'Update_Date' => date('Y-m-d H:i:s'),
                            'Update_By' => $bom_token['UserName'],
                            
                        ];

                    

                   // Update ReceivePart Function
                    $bom_output = $this->Bom_Model->update_receivepart($bom_data);

                    if (isset($bom_output) && $bom_output) {


                        $delete_output = $this->Bom_Model->delete_receivepart_item($bom_data);

                        $bom_item = json_decode($this->input->post('data2'), true); 
                        $i = 1;
                        foreach ($bom_item as $value) {
                            
                            $bom_data_item['data'] = [
                                'BOM_ID' => $bom_header['BOM_Index'],
                                'ITEM_Seq' => $i,
                                'ITEM_ID' => $value['Grade_ID'],
                                'ITEM_QTY' => $value['QTY'],
                                'Create_Date' => date('Y-m-d H:i:s'),
                                'Create_By' => $bom_token['UserName'],
                                
                            ];

                            $i = $i+1;

                            $bom_output_item = $this->Bom_Model->insert_receivepart_item($bom_data_item);
                        }
                            // Update ReceivePart Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Grade Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update ReceivePart Error
                        $message = [
                            'status' => false,
                            'message' => 'Update Receive Part Fail : [Update Data Fail]',
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
     * Delete ReceivePart API
     * ---------------------------------
     * @param: ReceivePart_Index
     * ---------------------------------
     * @method : POST
     * @link : receivepart/delete
     */
    public function delete_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // ReceivePart Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $bom_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $bom_permission = array_filter($bom_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($bom_permission[array_keys($bom_permission)[0]]['Deleted']) {

                    $bom_data['index'] = $this->input->post('BOM_ID');

                    // Delete ReceivePart Function
                    $bom_output = $this->Bom_Model->delete_receivepart($bom_data);
                    $bom_output_item = $this->Bom_Model->delete_receivepart_item($bom_data);

                    if (isset($bom_output) && $bom_output) {

                        // Delete ReceivePart Success
                        $message = [
                            'status' => true,
                            'message' => 'Delete Receive Part Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete ReceivePart Error
                        $message = [
                            'status' => false,
                            'message' => 'Delete Receive Part Fail : [Delete Data Fail]',
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
     * Show ReceivePart item All API
     * ---------------------------------
     * @method : GET
     * @link : receivepart/receivepart_item
     */
    public function receivepartitem_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // ReceivePartID Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load ReceivePartID Function
            $Bom_ID = $this->input->get('ReceivePart_ID');

            $output = $this->ReceivePart_Model->select_receivepartitem($Bom_ID);

            if (isset($output) && $output) {

                // Show ReceivePartID All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show ReceivePartItem all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
        }

    }

}
