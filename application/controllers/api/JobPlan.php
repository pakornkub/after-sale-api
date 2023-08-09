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
        $this->load->model('Auth_Model');

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
                    'message' => 'Show JobPlan all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show JobPlan Part All Error
                // $message = [
                //     'status' => false,
                //     'message' => 'JobPlan data was not found in the database',
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
     * Create JobPlan API
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

            // JobPlan Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $jobplan_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $jobplan_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $jobplan_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });


                $jobplan_item = json_decode($this->input->post('data'), true); 

                if ($jobplan_permission[array_keys($jobplan_permission)[0]]['Created']) {

                     //Create Item Success
                     
                        
                     foreach ($jobplan_item as $value) {
                        
                         $plan_data_item['data'] = [
                             'DATE' => $value['DATE'],
                             'FG_ITEM_CODE' => $value['GRADE'],
                             'ITEM_QTY' => $value['QTY'],
                             'Create_Date' => date('Y-m-d H:i:s'),
                             'Create_By' => $jobplan_token['UserName'],
                             
                         ];


                         $jobplan_output_item = $this->JobPlan_Model->insert_jobplan($plan_data_item);

                     }



                    if (isset($jobplan_output_item) && $jobplan_output_item) {

                       
                        

                        $message = [
                            'status' => true,
                            'message' => 'Create JobPlan Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);



                    } else {

                        // Create jobplan Error
                        $message = [
                            'status' => false,
                            'message' => 'Create jobplan Part Fail : [Insert Data Fail]',
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

                $jobplan_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $jobplan_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $jobplan_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                    $jobplan_header = json_decode($this->input->post('data1'), true); 

                    if ($jobplan_permission[array_keys($jobplan_permission)[0]]['Updated']) {

                        $jobplan_data['index'] = $jobplan_header['JobPlan_Index'];

                        $jobplan_data['data'] = [
                            'Rec_type' => '1',
                            'Rec_NO' => $jobplan_header['jobplan_No'],
                            'Update_Date' => date('Y-m-d H:i:s'),
                            'Update_By' => $jobplan_token['UserName'],
                            
                        ];

                    

                   // Update JobPlan Function
                    $jobplane_output = $this->JobPlan_Model->update_jobplan($jobplan_data);

                    if (isset($jobplan_output) && $jobplan_output) {


                            // Update JobPlan Success
                        $message = [
                            'status' => true,
                            'message' => 'Update jobplan Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update JobPlan Error
                        $message = [
                            'status' => false,
                            'message' => 'Update jobplan Fail : [Update Data Fail]',
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

                $jobplan_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $jobplan_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $jobplan_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($jobplan_permission[array_keys($jobplan_permission)[0]]['Deleted']) {

                    $jobplan_data['index'] = $this->input->post('JobPlan_ID');

                    // Delete JobPlan Function
                    $jobplan_output = $this->JobPlan_Model->delete_jobplan($jobplan_data);

                    if (isset($jobplan_output) && $jobplan_output) {

                        // Delete JobPlan Success
                        $message = [
                            'status' => true,
                            'message' => $jobplan_output,
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete JobPlan Error
                        $message = [
                            'status' => false,
                            'message' => 'Delete jobplan Fail : [Delete Data Fail]',
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

 

}
