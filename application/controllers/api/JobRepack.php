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
                    'message' => 'Show Job Part all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show Job Part All Error
                // $message = [
                //     'status' => false,
                //     'message' => 'Job Part data was not found in the database',
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
     * Create Job Repack API
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

            // Job Repack Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $job_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $job_permission = array_filter($job_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });


                $job_header = json_decode($this->input->post('data1'), true); 

                if ($job_permission[array_keys($job_permission)[0]]['Created']) {

                    $job_data['data'] = [
                        'JOB_No' => $job_header['Job_No'],
                        'JOB_Date' => $job_header['Job_Date'],
                        'BOM_ID' => $job_header['Bom_ID'],
                        'JobType_ID' => $job_header['Job_Type'],
                        'FG_ITEM_ID' => $job_header['Grade_ID_FG'],
                        'JOB_STATUS' => 1,
                        'JOB_QTY' => $job_header['QTY_Use'],
                        'Create_Date' => date('Y-m-d H:i:s'),
                        'Create_By' => $job_token['UserName'],
                        'Update_Date' => null,
                        'Update_By' => null,
                        
                    ];

                    // Create job Function
                    $job_output = $this->JobRepack_Model->insert_jobrepack($job_data);



                    if (isset($job_output) && $job_output) {

                        //Create Item Success
                        $job_item = json_decode($this->input->post('data2'), true); 
                        
                        foreach ($job_item as $value) {
                            
                            $job_data_item['data'] = [
                                'Job_ID' => $job_output,
                                'Item_ID' => $value['Grade_ID'],
                                'Qty' => $value['QTY'],
                                'TotalQty' => $value['ToTal_Use'],
                                'Create_Date' => date('Y-m-d H:i:s'),
                                'Create_By' => $job_token['UserName'],
                                
                            ];


                            $job_output_item = $this->JobRepack_Model->insert_jobrepack_item($job_data_item);

                        }
                        

                        $message = [
                            'status' => true,
                            'message' => 'Create job Repack Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);



                    } else {

                        // Create job Error
                        $message = [
                            'status' => false,
                            'message' => 'Create job Repack Fail : [Insert Data Fail]',
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
