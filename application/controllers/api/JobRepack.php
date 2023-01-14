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
                $job_header1 = json_decode($this->input->post('data3'), true); 

                if ($job_permission[array_keys($job_permission)[0]]['Created']) {

                    $job_data['data'] = [
                        'JOB_No' => $job_header['Job_No'],
                        'JOB_Date' => $job_header['Job_Date'],
                        'BOM_ID' => $job_header['Bom_ID'],
                        'JobType_ID' => $job_header['Job_Type'],
                        'FG_ITEM_ID' => $job_header['Grade_ID_FG'],
                        'FG_LOT_NO' => $job_header['Lot_No'],
                        'JOB_STATUS' => 1,
                        'JOB_QTY' => $job_header['QTY_Use'],
                        'JOB_Total_QTY' => $job_header1['totalUseQty'],
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

                        $qr_data = [
                            'JOB_ID' => $job_output,
                            'username' => $tag_token['UserName'],
                           
                        ];

                        $job_output_qr = $this->JobRepack_Model->insert_jobrepack_qr($qr_data);

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

                $job_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $job_permission = array_filter($job_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                    $job_header = json_decode($this->input->post('data1'), true);
                    $job_header1 = json_decode($this->input->post('data3'), true);  

                    if ($job_permission[array_keys($job_permission)[0]]['Created']) {

                        $job_data['index'] = $job_header['Job_Index'];

                        $job_data['data'] = [
                            'JOB_No' => $job_header['Job_No'],
                            'JOB_Date' => $job_header['Job_Date'],
                            'BOM_ID' => $job_header['Bom_ID'],
                            'JobType_ID' => $job_header['Job_Type'],
                            'FG_ITEM_ID' => $job_header['Grade_ID_FG'],
                            'FG_LOT_NO' => $job_header['Lot_No'],
                            'JOB_QTY' => $job_header['QTY_Use'],
                            'Update_Date' => date('Y-m-d H:i:s'),
                            'Update_By' => $job_token['UserName'],
                            
                        ];

                    

                   // Update JobRepack Function
                    $job_output = $this->JobRepack_Model->update_jobrepack($job_data);

                    if (isset($job_output) && $job_output) {


                        $delete_output = $this->JobRepack_Model->delete_jobrepack_item($job_data);

                        $job_item = json_decode($this->input->post('data2'), true); 
                        
                        foreach ($job_item as $value) {
                            
                            $job_data_item['data'] = [
                                'Job_ID' => $job_header['Job_Index'],
                                'Item_ID' => $value['Grade_ID'],
                                'Qty' => $value['QTY'],
                                'TotalQty' => $value['ToTal_Use'],
                                'Create_Date' => date('Y-m-d H:i:s'),
                                'Create_By' => $job_token['UserName'],
                                
                            ];

                            $job_output_item = $this->JobRepack_Model->insert_jobrepack_item($job_data_item);
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
                            'message' => 'Update Job Repack Fail : [Update Data Fail]',
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

                $job_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $job_permission = array_filter($job_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($job_permission[array_keys($job_permission)[0]]['Deleted']) {

                    $job_data['index'] = $this->input->post('Rec_ID');

                    // Delete JobRepack Function
                    $job_output = $this->JobRepack_Model->delete_jobrepack($job_data);
                    $job_output_item = $this->JobRepack_Model->delete_jobrepack_item($job_data);

                    if (isset($job_output) && $job_output) {

                        // Delete JobRepack Success
                        $message = [
                            'status' => true,
                            'message' => $job_output,
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete JobRepack Error
                        $message = [
                            'status' => false,
                            'message' => 'Delete Job Repack Fail : [Delete Data Fail]',
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

    /**
     * Show QR BOX API
     * ---------------------------------
     * @method : POST
     * @link : jobrepack/selectqrbox
     */
    public function selectqrbox_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // qrbox Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        
            $qrbox_data = [
                'JOB_ID' => $this->input->post('JOB_ID'),
               
            ];

            $qrbox_output = $this->JobRepack_Model->select_qrbox($qrbox_data);

            if (isset($qrbox_output) && $qrbox_output) {

                // Show qrbox All Success
                $message = [
                    'status' => true,
                    'data' => $qrbox_output,
                    'message' => 'Show QR Box all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }

    }

    /**
     * Show Withdraw Item API
     * ---------------------------------
     * @method : POST
     * @link : jobrepack/selectwithdrawitem
     */
    public function selectwithdrawitem_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // WI Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        
            $wi_data = [
                'JOB_ID' => $this->input->post('JOB_ID'),
               
            ];

            $wi_output = $this->JobRepack_Model->select_withdrawitem($wi_data);

            if (isset($wi_output) && $wi_output) {

                // Show qrbox All Success
                $message = [
                    'status' => true,
                    'data' => $wi_output,
                    'message' => 'Show Withdraw Item all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }

    }

}