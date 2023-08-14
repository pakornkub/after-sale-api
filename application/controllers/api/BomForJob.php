<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class BomForJob extends REST_Controller
{

    protected $MenuId = 'JobRepack';

    public function __construct()
    {

        parent::__construct();

        // Load BomForJob
        $this->load->model('BomForJob_Model');

    }

    /**
     * Show BomForJob All API
     * ---------------------------------
     * @method : GET
     * @link : BomForJob/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // BomForJob Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load BomForJob Function
            $output = $this->BomForJob_Model->select_bom();

            if (isset($output) && $output) {

                // Show BomForJob All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Bom all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show BomForJob All Error
                $message = [
                    'status' => false,
                    'message' => 'Bom data was not found in the database',
                ];

                //$this->response($message, REST_Controller::HTTP_NOT_FOUND);

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
     * Show Bom All API
     * ---------------------------------
     * @method : POST
     * @link : BomForJob/selectgrade
     */
    public function selectgrade_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Bom Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        //if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load Bom Function
            

            $grade_data = [
                'DATE' => $this->input->post('DATE'),
               
            ];

            $bom_output = $this->BomForJob_Model->select_gradeplan($grade_data);

            if (isset($bom_output) && $bom_output) {

                // Show Bom All Success
                $message = [
                    'status' => true,
                    'data' => $bom_output,
                    'message' => 'Show Grade all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }

        // } else {
        //     // Validate Error
        //     $message = [
        //         'status' => false,
        //         'message' => $is_valid_token['message'],
        //     ];

        //     $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        // }
    }



    /**
     * Show Bom All API
     * ---------------------------------
     * @method : POST
     * @link : BomForJob/selectrev
     */
    public function selectrev_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Bom Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        //if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load Bom Function
            

            $grade_data = [
                'GRADE_ID' => $this->input->post('GRADE_ID'),
               
            ];

            $bom_output = $this->BomForJob_Model->select_bomrev($grade_data);

            if (isset($bom_output) && $bom_output) {

                // Show Bom All Success
                $message = [
                    'status' => true,
                    'data' => $bom_output,
                    'message' => 'Show bom rev all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }

        // } else {
        //     // Validate Error
        //     $message = [
        //         'status' => false,
        //         'message' => $is_valid_token['message'],
        //     ];

        //     $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        // }
    }


    /**
     * Show Bom All API
     * ---------------------------------
     * @method : POST
     * @link : BomForJob/selectitem
     */
    public function selectitem_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Bom Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        //if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load Bom Function
            

            $grade_data = [
                'BOM_ID' => $this->input->post('BOM_ID'),
               
            ];

            $bom_output = $this->BomForJob_Model->select_bomitem($grade_data);

            if (isset($bom_output) && $bom_output) {

                // Show Bom All Success
                $message = [
                    'status' => true,
                    'data' => $bom_output,
                    'message' => 'Show bom item all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }

        // } else {
        //     // Validate Error
        //     $message = [
        //         'status' => false,
        //         'message' => $is_valid_token['message'],
        //     ];

        //     $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        // }
    }

    /**
     * Show Bom Iten API
     * ---------------------------------
     * @method : POST
     * @link : BomForJob/selectitem_v1
     */
    public function selectitem_v1_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Bom Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        //if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load Bom Function
            

            $grade_data = [
                'BOM_ID' => $this->input->post('BOM_ID'),
               
            ];

            $bom_output = $this->BomForJob_Model->select_bomitem_v1($grade_data);

            if (isset($bom_output) && $bom_output) {

                // Show Bom All Success
                $message = [
                    'status' => true,
                    'data' => $bom_output,
                    'message' => 'Show bom item all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }

        // } else {
        //     // Validate Error
        //     $message = [
        //         'status' => false,
        //         'message' => $is_valid_token['message'],
        //     ];

        //     $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        // }
    }
}
