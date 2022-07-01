<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class JobRepack extends REST_Controller
{

    protected $MenuId = 'JobRepack';

    public function __construct()
    {

        parent::__construct();

        // Load JobRepack
        $this->load->model('JobRepack_Model');
        
    }

    /**
     * Show JobRepack All API
     * ---------------------------------
     * @method : GET
     * @link : jobrepack/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // JobRepack Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load JobRepack Function
            $output = $this->JobRepack_Model->select_jobrepack();

            if (isset($output) && $output) {

                // Show JobRepack All Success
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
     * @link : jobrepack/create
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

                    $receive_data['data'] = [
                        'Rec_type' => $receive_header['Receive_Type'],
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
                    $receive_output = $this->JobRepack_Model->insert_jobrepack($receive_data);



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


                            $receive_output_item = $this->JobRepack_Model->insert_jobrepack_item($receive_data_item);

                        }
                        

                        $message = [
                            'status' => true,
                            'message' => 'Create Receive Part Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);



                    } else {

                        // Create receive Error
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
     * Update JobRepack API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : jobrepack/update
     */
    public function update_post()
    { 

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // JobRepack Token Validation
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
                            'Rec_type' => $receive_header['Receive_Type'],
                            'Rec_NO' => $receive_header['Receive_No'],
                            'Rec_Datetime' => $receive_header['Receive_Date'],
                            'Ref_DocNo_1' => (isset($receive_header['Ref_No1']) && $receive_header['Ref_No1']) ? $receive_header['Ref_No1'] : null,
                            'Ref_DocNo_2' => (isset($receive_header['Ref_No2']) && $receive_header['Ref_No2']) ? $receive_header['Ref_No2'] : null,
                            'Remark' => (isset($receive_header['Receive_Remark']) && $receive_header['Receive_Remark']) ? $receive_header['Receive_Remark'] : null,
                            'Update_Date' => date('Y-m-d H:i:s'),
                            'Update_By' => $receive_token['UserName'],
                            
                        ];

                    

                   // Update JobRepack Function
                    $receive_output = $this->JobRepack_Model->update_jobrepack($receive_data);

                    if (isset($receive_output) && $receive_output) {


                        $delete_output = $this->JobRepack_Model->delete_jobrepack_item($receive_data);

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

                            $receive_output_item = $this->JobRepack_Model->insert_jobrepack_item($receive_data_item);
                        }
                            // Update JobRepack Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Grade Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update JobRepack Error
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
     * Delete JobRepack API
     * ---------------------------------
     * @param: JobRepack_Index
     * ---------------------------------
     * @method : POST
     * @link : jobrepack/delete
     */
    public function delete_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // JobRepack Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $receive_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $receive_permission = array_filter($receive_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($receive_permission[array_keys($receive_permission)[0]]['Deleted']) {

                    $receive_data['index'] = $this->input->post('Rec_ID');

                    // Delete JobRepack Function
                    $receive_output = $this->JobRepack_Model->delete_jobrepack($receive_data);
                    $receive_output_item = $this->JobRepack_Model->delete_jobrepack_item($receive_data);

                    if (isset($receive_output) && $receive_output) {

                        // Delete JobRepack Success
                        $message = [
                            'status' => true,
                            'message' => $receive_output,
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete JobRepack Error
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
     * Show JobRepack item All API
     * ---------------------------------
     * @method : GET
     * @link : jobrepack/jobrepack_item
     */
    public function jobrepackitem_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // JobRepackID Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load JobRepackID Function
            $Rc_ID = $this->input->get('JobRepack_ID');

            $output = $this->JobRepack_Model->select_jobrepackitem($Rc_ID);

            if (isset($output) && $output) {

                // Show JobRepackID All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show JobRepackItem all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
        }

    }

}
