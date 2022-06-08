<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Grade extends REST_Controller
{

    protected $MenuId = 'Grade';

    public function __construct()
    {

        parent::__construct();

        // Load Grade_Model
        $this->load->model('Grade_Model');

    }

    /**
     * Show Grade All API
     * ---------------------------------
     * @method : GET
     * @link : grade/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Grade Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load Grade Function
            $output = $this->Grade_Model->select_grade();

            if (isset($output) && $output) {

                // Show Grade All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show grade all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show Grade All Error
                $message = [
                    'status' => false,
                    'message' => 'Grade data was not found in the database',
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
     * Create Grade API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : grade/create
     */
    public function create_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('Grade_Id', 'Grade_Id', 'trim|required');
        $this->form_validation->set_rules('Grade_Description', 'Grade_Description', 'trim|required');
        $this->form_validation->set_rules('Product_Type', 'Product_Type', 'trim|required');
        $this->form_validation->set_rules('Grade_Unit', 'Grade_Unit', 'trim|required');
        $this->form_validation->set_rules('Grade_Status', 'Grade_Status', 'trim|required');

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

            // Grade Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $grade_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $grade_permission = array_filter($grade_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($grade_permission[array_keys($grade_permission)[0]]['Created']) {

                    $grade_data['data'] = [
                        'ITEM_CODE' => $this->input->post('Grade_Id'),
                        'ITEM_DESCRIPTION' => $this->input->post('Grade_Description'),
                        'Product_ID' => intval($this->input->post('Product_Type')),
                        'Unit' => $this->input->post('Grade_Unit'),
                        'Status' => intval($this->input->post('Grade_Status')),
                        'Create_Date' => date('Y-m-d H:i:s'),
                        'Create_By' => $grade_token['UserName'],
                        'Update_Date' => null,
                        'Update_By' => null,
                    ];

                    // Create Grade Function
                    $grade_output = $this->Grade_Model->insert_grade($grade_data);

                    if (isset($grade_output) && $grade_output) {

                        // Create Grade Success
                        $message = [
                            'status' => true,
                            'message' => 'Create Grade Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Create Grade Error
                        $message = [
                            'status' => false,
                            'message' => 'Create Grade Fail : [Insert Data Fail]',
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

    }

}
