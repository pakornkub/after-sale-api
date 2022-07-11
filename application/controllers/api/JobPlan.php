<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class JobPlan extends REST_Controller
{

    protected $MenuId = 'JobPlan';

    public function __construct()
    {

        parent::__construct();

        // Load JobPlan
        $this->load->model('JobPlan_Model');

    }

    /**
     * Show JobPlan All API
     * ---------------------------------
     * @method : GET
     * @link : jobplan/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // JobPlan Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load JobPlan Function
            $output = $this->JobPlan_Model->select_jobplan();

            if (isset($output) && $output) {

                // Show JobPlan All Success
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
     * @link : jobplan/create
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
                        'Rec_type' => '1',
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
                    $receive_output = $this->JobPlan_Model->insert_jobplan($receive_data);



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


                            $receive_output_item = $this->JobPlan_Model->insert_jobplan_item($receive_data_item);

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
     * Update JobPlan API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : jobplan/update
     */
    public function update_post()
    { 

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // JobPlan Token Validation
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

                    

                   // Update JobPlan Function
                    $receive_output = $this->JobPlan_Model->update_jobplan($receive_data);

                    if (isset($receive_output) && $receive_output) {


                        $delete_output = $this->JobPlan_Model->delete_jobplan_item($receive_data);

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

                            $receive_output_item = $this->JobPlan_Model->insert_jobplan_item($receive_data_item);
                        }
                            // Update JobPlan Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Grade Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update JobPlan Error
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
     * Delete JobPlan API
     * ---------------------------------
     * @param: JobPlan_Index
     * ---------------------------------
     * @method : POST
     * @link : jobplan/delete
     */
    public function delete_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // JobPlan Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $receive_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $receive_permission = array_filter($receive_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($receive_permission[array_keys($receive_permission)[0]]['Deleted']) {

                    $receive_data['index'] = $this->input->post('Rec_ID');

                    // Delete JobPlan Function
                    $receive_output = $this->JobPlan_Model->delete_jobplan($receive_data);
                    $receive_output_item = $this->JobPlan_Model->delete_jobplan_item($receive_data);

                    if (isset($receive_output) && $receive_output) {

                        // Delete JobPlan Success
                        $message = [
                            'status' => true,
                            'message' => $receive_output,
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete JobPlan Error
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
     * Show JobPlan item All API
     * ---------------------------------
     * @method : GET
     * @link : jobplan/jobplan_item
     */
    public function jobplanitem_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // JobPlanID Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load JobPlanID Function
            $Rc_ID = $this->input->get('JobPlan_ID');

            $output = $this->JobPlan_Model->select_jobplanitem($Rc_ID);

            if (isset($output) && $output) {

                // Show JobPlanID All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show JobPlanItem all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
        }

    }

}
