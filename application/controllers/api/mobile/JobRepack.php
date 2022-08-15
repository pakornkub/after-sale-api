<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class JobRepack extends REST_Controller
{

    protected $MenuId = 'JobRepackMobile';

    public function __construct()
    {

        parent::__construct();

        // Load JobRepack_Model
        $this->load->model('mobile/JobRepack_Model');

    }

    /**
     * Show JobRepack All API
     * ---------------------------------
     * @method : GET
     * @link : job_repack/index
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
            $output = $this->JobRepack_Model->select_job_repack();

            if (isset($output) && $output) {

                // Show JobRepack All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show job repack all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show JobRepack All Error
                $message = [
                    'status' => false,
                    'message' => 'Job Repack data was not found in the database',
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
     * Update JobRepack API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : job_repack/update
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

            // JobRepack Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $job_repack_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $job_repack_permission = array_filter($job_repack_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if (count($job_repack_permission) > 0 && $job_repack_permission[array_keys($job_repack_permission)[0]]['Updated']) {

                    $job_repack_data['where'] = [
                        'JOB_ID' => $this->input->post('JOB_ID'),
                    ];

                    $job_repack_data['data'] = [
                        'JOB_STATUS' => 9,
                        'Update_By' => $job_repack_token['UserName'],
                        'Update_Date' => date('Y-m-d H:i:s'),
                    ];

                    // Update JobRepack Function
                    $job_repack_output = $this->JobRepack_Model->update_job_repack($job_repack_data);

                    if (isset($job_repack_output) && $job_repack_output) {

                        // Update JobRepack Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Job Repack Successful',
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

    }

    /**
     * Show JobRepack BOM API
     * ---------------------------------
     * @method : GET
     * @link : job_repack/bom
     */
    public function bom_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // JobRepack Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load JobRepack Function
            $output = $this->JobRepack_Model->select_job_repack_bom($this->input->get('JOB_ID'));

            if (isset($output) && $output) {

                // Show JobRepack BOM Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show job repack bom successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show JobRepack BOM Error
                $message = [
                    'status' => false,
                    'message' => 'Job Repack BOM data was not found in the database',
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
     * Exec JobRepack Item API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : job_repack/exec_item
     */
    public function exec_item_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('JOB_ID', 'JOB_ID', 'trim|required');
        //$this->form_validation->set_rules('QR_NO', 'QR_NO', 'trim|required');
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

            // JobRepack Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $job_repack_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $job_repack_permission = array_filter($job_repack_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if (count($job_repack_permission) > 0 && $job_repack_permission[array_keys($job_repack_permission)[0]]['Created']) {


                    $Item_Code = $this->input->post('Item_Code') ? $this->input->post('Item_Code') : null;

                    if($Item_Code)
                    {
                        $tag_data = [
                            'JOB_ID' => $this->input->post('JOB_ID'),
                            'QR_NO' => null,
                            'Tag_ID' => null,
                            'Item_Code' => $Item_Code,
                            'Username' => $job_repack_token['UserName'],
                        ];
                    }
                    else
                    {
                        $tag_data = [
                            'JOB_ID' => $this->input->post('JOB_ID'),
                            'QR_NO' => $this->input->post('QR_NO'),
                            'Tag_ID' => $this->input->post('Tag_ID'),
                            'Item_Code' => null,
                            'Username' => $job_repack_token['UserName'],
                        ];
                    }

                    // Exec JobRepack Item Function
                    $job_repack_output = $this->JobRepack_Model->exec_job_repack_item($tag_data);

                    if (isset($job_repack_output) && $job_repack_output) {

                        if (boolval($job_repack_output[0]['Result_status']) === true) {

                            // Exec JobRepack Item Success
                            $message = [
                                'status' => true,
                                'message' => $job_repack_output[0]['Result_Desc'],
                            ];

                            $this->response($message, REST_Controller::HTTP_OK);
                        } else {
                            // Exec JobRepack Item Error Condition
                            $message = [
                                'status' => false,
                                'message' => $job_repack_output[0]['Result_Desc'],
                            ];

                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }

                    } else {

                        // Exec JobRepack Item Error
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
                        'message' => 'You don’t currently have permission to Created',
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
     * Exec JobRepack Transaction API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : job_repack/exec_transaction
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

            // JobRepack Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $job_repack_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $job_repack_permission = array_filter($job_repack_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if (count($job_repack_permission) > 0 && $job_repack_permission[array_keys($job_repack_permission)[0]]['Created']) {

                    $tag_data = [
                        'JOB_ID' => intval($this->input->post('JOB_ID')),
                        'QR_NO_BOX' => $this->input->post('QR_NO_BOX'),
                        'Username' => $job_repack_token['UserName'],
                    ];

                    // Exec JobRepack Transaction Function
                    $job_repack_output = $this->JobRepack_Model->exec_job_repack_transaction($tag_data);

                    if (isset($job_repack_output) && $job_repack_output) {

                        if (boolval($job_repack_output[0]['Result_status']) === true) {

                            // Exec JobRepack Transaction Success
                            $message = [
                                'status' => true,
                                'message' => $job_repack_output[0]['Result_Desc'],
                            ];

                            $this->response($message, REST_Controller::HTTP_OK);
                        } else {
                            // Exec JobRepack Transaction Error Condition
                            $message = [
                                'status' => false,
                                'message' => $job_repack_output[0]['Result_Desc'],
                            ];

                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }

                    } else {

                        // Exec JobRepack Transaction Error
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
                        'message' => 'You don’t currently have permission to Created',
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
