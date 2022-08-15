<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class JobRecheck extends REST_Controller
{

    protected $MenuId = 'JobRecheckMobile';

    public function __construct()
    {

        parent::__construct();

        // Load JobRecheck_Model
        $this->load->model('mobile/JobRecheck_Model');

    }

    /**
     * Show JobRecheck All API
     * ---------------------------------
     * @method : GET
     * @link : job_recheck/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // JobRecheck Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load JobRecheck Function
            $output = $this->JobRecheck_Model->select_job_recheck();

            if (isset($output) && $output) {

                // Show JobRecheck All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show job recheck all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show JobRecheck All Error
                $message = [
                    'status' => false,
                    'message' => 'Job Recheck data was not found in the database',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

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
     * Update JobRecheck API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : job_recheck/update
     */
    public function update_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('JOB_ID', 'JOB_ID', 'trim|required');

        if ($this->form_validation->run() == false) {
            // Form Validation Error
            $message = [
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors(),
            ];

            $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
        } else {

            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // JobRecheck Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $job_recheck_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $job_recheck_permission = array_filter($job_recheck_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($job_recheck_permission[array_keys($job_recheck_permission)[0]]['Updated']) {

                    $job_recheck_data['where'] = [
                        'JOB_ID' =>  $this->input->post('JOB_ID')
                    ];

                    $job_recheck_data['data'] = [
                        'JOB_STATUS' => 9,
                        'Update_By' => $job_recheck_token['UserName'],
                        'Update_Date' => date('Y-m-d H:i:s'),
                    ];

                    // Update JobRecheck Function
                    $job_recheck_output = $this->JobRecheck_Model->update_job_recheck($job_recheck_data);

                    if (isset($job_recheck_output) && $job_recheck_output) {

                        // Update JobRecheck Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Job Recheck Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update JobRecheck Error
                        $message = [
                            'status' => false,
                            'message' => 'Update Job Recheck Fail : [Update Data Fail]',
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

    }


     /**
     * Show JobRecheck BOM API
     * ---------------------------------
     * @method : GET
     * @link : job_recheck/bom
     */
    public function bom_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // JobRecheck Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load JobRecheck Function
            $output = $this->JobRecheck_Model->select_job_recheck_bom($this->input->get('JOB_ID'));

            if (isset($output) && $output) {

                // Show JobRecheck BOM Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show job recheck bom successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show JobRecheck BOM Error
                $message = [
                    'status' => false,
                    'message' => 'Job Recheck BOM data was not found in the database',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

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
     * Exec JobRecheck Item API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : job_recheck/exec_item
     */
    public function exec_item_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('JOB_ID', 'JOB_ID', 'trim|required');
        $this->form_validation->set_rules('QR_NO', 'QR_NO', 'trim|required');
        //$this->form_validation->set_rules('Tag_ID', 'Tag_ID', 'trim|required');

        if ($this->form_validation->run() == false) {
            // Form Validation Error
            $message = [
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors(),
            ];

            $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
        } else {

            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // JobRecheck Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $job_recheck_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $job_recheck_permission = array_filter($job_recheck_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($job_recheck_permission[array_keys($job_recheck_permission)[0]]['Created']) {

                    $tag_data = [
                        'JOB_ID' => $this->input->post('JOB_ID'),
                        'QR_NO' => $this->input->post('QR_NO'),
                        'Tag_ID' => '',
                        'Username' => $job_recheck_token['UserName'],
                    ];

                    // Exec JobRecheck Item Function
                    $job_recheck_output = $this->JobRecheck_Model->exec_job_recheck_item($tag_data);

                    if (isset($job_recheck_output) && $job_recheck_output) {

                        if(boolval($job_recheck_output[0]['Result_status']) === true)
                        {
                        
                            // Exec JobRecheck Item Success
                            $message = [
                                'status' => true,
                                'message' => $job_recheck_output[0]['Result_Desc'],
                            ];

                            $this->response($message, REST_Controller::HTTP_OK);
                        }
                        else
                        {
                             // Exec JobRecheck Item Error Condition
                             $message = [
                                'status' => false,
                                'message' => $job_recheck_output[0]['Result_Desc'],
                            ];

                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }

                    } else {

                        // Exec JobRecheck Item Error
                        $message = [
                            'status' => false,
                            'message' => 'Exec Item Fail : [Exec Data Fail]',
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

    }

     /**
     * Exec JobRecheck Transaction API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : job_recheck/exec_transaction
     */
    public function exec_transaction_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('JOB_ID', 'JOB_ID', 'trim|required');
        $this->form_validation->set_rules('QR_NO_BOX', 'QR_NO_BOX', 'trim|required');

        if ($this->form_validation->run() == false) {
            // Form Validation Error
            $message = [
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors(),
            ];

            $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
        } else {

            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // JobRecheck Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $job_recheck_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $job_recheck_permission = array_filter($job_recheck_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($job_recheck_permission[array_keys($job_recheck_permission)[0]]['Created']) {

                    $tag_data = [
                        'JOB_ID' => intval($this->input->post('JOB_ID')),
                        'QR_NO_BOX' => $this->input->post('QR_NO_BOX'),
                        'Username' => $job_recheck_token['UserName'],
                    ];

                    // Exec JobRecheck Transaction Function
                    $job_recheck_output = $this->JobRecheck_Model->exec_job_recheck_transaction($tag_data);

                    if (isset($job_recheck_output) && $job_recheck_output) {

                        if(boolval($job_recheck_output[0]['Result_status']) === true)
                        {
                        
                            // Exec JobRecheck Transaction Success
                            $message = [
                                'status' => true,
                                'message' => $job_recheck_output[0]['Result_Desc'],
                            ];

                            $this->response($message, REST_Controller::HTTP_OK);
                        }
                        else
                        {
                             // Exec JobRecheck Transaction Error Condition
                             $message = [
                                'status' => false,
                                'message' => $job_recheck_output[0]['Result_Desc'],
                            ];

                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }

                    } else {

                        // Exec JobRecheck Transaction Error
                        $message = [
                            'status' => false,
                            'message' => 'Exec Transaction Fail : [Exec Data Fail]',
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

    }

 

}
