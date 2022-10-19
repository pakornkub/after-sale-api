<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class SplitPart extends REST_Controller
{

    protected $MenuId = 'SplitPart';

    public function __construct()
    {

        parent::__construct();

        // Load SplitPart
        $this->load->model('SplitPart_Model');

    }

    /**
     * Show SplitPart All API
     * ---------------------------------
     * @method : GET
     * @link : splitpart/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // SplitPart Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load SplitPart Function
            $output = $this->SplitPart_Model->select_splitpart();

            if (isset($output) && $output) {

                // Show SplitPart All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Split Part all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show Split Part All Error
                // $message = [
                //     'status' => false,
                //     'message' => 'Split Part data was not found in the database',
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
     * Create Split Part API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : splitpart/create
     */
    public function create_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        
            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // Split Part Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $receive_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $receive_permission = array_filter($receive_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });


                $receive_header = json_decode($this->input->post('data1'), true); 

                if ($receive_permission[array_keys($receive_permission)[0]]['Created']) {



                    $receive_no_output = json_decode(json_encode($this->SplitPart_Model->select_receive_no()), true);
                    $receive_no = $receive_no_output[array_keys($receive_no_output)[0]]['ReceiveNo'];

                    if (isset($receive_no) && $receive_no) {

                    
                        $receive_data['data'] = [
                            'Rec_type' => '4',
                            'Rec_NO' => $receive_no,
                            'Rec_Datetime' => $receive_header['Split_Date'],
                            'status' => '1',
                            'Ref_DocNo_1' => (isset($receive_header['Ref_No1']) && $receive_header['Ref_No1']) ? $receive_header['Ref_No1'] : null,
                            'Ref_DocNo_2' => (isset($receive_header['Ref_No2']) && $receive_header['Ref_No2']) ? $receive_header['Ref_No2'] : null,
                            'Remark' => (isset($receive_header['Split_Remark']) && $receive_header['Split_Remark']) ? $receive_header['Split_Remark'] : null,
                            'Create_Date' => date('Y-m-d H:i:s'),
                            'Create_By' => $receive_token['UserName'],
                            'Update_Date' => null,
                            'Update_By' => null,
                            
                        ];
    
                        // Create receive Function
                        $receive_output = $this->SplitPart_Model->insert_splitpart($receive_data);
    
    
    
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
    
    
                                $receive_output_item = $this->SplitPart_Model->insert_splitpart_item($receive_data_item);
    
                            }
                            
    
                            $message = [
                                'status' => true,
                                'message' => 'Create Split Part Successful',
                            ];
    
                            $this->response($message, REST_Controller::HTTP_OK);
    
    
    
                        } else {
    
                            // Create receive Error
                            $message = [
                                'status' => false,
                                'message' => 'Create Split Part Fail : [Insert Data Fail]',
                            ];
    
                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    
                        }
                    }else{
                            // Create Split NO Error
                            $message = [
                                'status' => false,
                                'message' => 'Split No Fail',
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
     * Update SplitPart API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : splitpart/update
     */
    public function update_post()
    { 

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // SplitPart Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $receive_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $receive_permission = array_filter($receive_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                    $receive_header = json_decode($this->input->post('data1'), true); 

                    if ($receive_permission[array_keys($receive_permission)[0]]['Created']) {

                        $receive_data['index'] = $receive_header['Split_Index'];

                        $receive_data['data'] = [
                            'Rec_type' => '4',
                            'Rec_NO' => $receive_header['Split_No'],
                            'Rec_Datetime' => $receive_header['Split_Date'],
                            'Ref_DocNo_1' => (isset($receive_header['Ref_No1']) && $receive_header['Ref_No1']) ? $receive_header['Ref_No1'] : null,
                            'Ref_DocNo_2' => (isset($receive_header['Ref_No2']) && $receive_header['Ref_No2']) ? $receive_header['Ref_No2'] : null,
                            'Remark' => (isset($receive_header['Split_Remark']) && $receive_header['Split_Remark']) ? $receive_header['Split_Remark'] : null,
                            'Update_Date' => date('Y-m-d H:i:s'),
                            'Update_By' => $receive_token['UserName'],
                            
                        ];

                    

                   // Update SplitPart Function
                    $receive_output = $this->SplitPart_Model->update_splitpart($receive_data);

                    if (isset($receive_output) && $receive_output) {


                        $delete_output = $this->SplitPart_Model->delete_splitpart_item($receive_data);

                        $receive_item = json_decode($this->input->post('data2'), true); 
                        
                        foreach ($receive_item as $value) {
                            
                            $receive_data_item['data'] = [
                                'Rec_ID' => $receive_header['Split_Index'],
                                'Qty' => $value['QTY'],
                                'Item_ID' => $value['Grade_ID'],
                                'Lot_No' => $value['Lot_No'],
                                'ItemStatus_ID' => $value['QTY'],
                                'Create_Date' => date('Y-m-d H:i:s'),
                                'Create_By' => $receive_token['UserName'],
                                
                            ];

                            $receive_output_item = $this->SplitPart_Model->insert_splitpart_item($receive_data_item);
                        }
                            // Update SplitPart Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Split Part Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update SplitPart Error
                        $message = [
                            'status' => false,
                            'message' => 'Update Split Part Fail : [Update Data Fail]',
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
     * Delete SplitPart API
     * ---------------------------------
     * @param: SplitPart_Index
     * ---------------------------------
     * @method : POST
     * @link : splitpart/delete
     */
    public function delete_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // SplitPart Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $receive_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $receive_permission = array_filter($receive_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($receive_permission[array_keys($receive_permission)[0]]['Deleted']) {

                    $receive_data['index'] = $this->input->post('Rec_ID');

                    // Delete SplitPart Function
                    $receive_output = $this->SplitPart_Model->delete_splitpart($receive_data);
                    $receive_output_item = $this->SplitPart_Model->delete_splitpart_item($receive_data);

                    if (isset($receive_output) && $receive_output) {

                        // Delete SplitPart Success
                        $message = [
                            'status' => true,
                            'message' => $receive_output,
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete SplitPart Error
                        $message = [
                            'status' => false,
                            'message' => 'Delete Split Part Fail : [Delete Data Fail]',
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
     * Show SplitPart item All API
     * ---------------------------------
     * @method : GET
     * @link : splitpart/splitpart_item
     */
    public function splitpartitem_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // SplitPartID Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load SplitPartID Function
            $Rc_ID = $this->input->get('SplitPart_ID');

            $output = $this->SplitPart_Model->select_splitpartitem($Rc_ID);

            if (isset($output) && $output) {

                // Show SplitPartID All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show SplitPartItem all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
        }

    }

}
