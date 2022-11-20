<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class BomID extends REST_Controller
{

    protected $MenuId = 'BomID';

    public function __construct()
    {

        parent::__construct();

        // Load BomID_Model
        $this->load->model('BomID_Model');

    }

    /**
     * Show BomID All API
     * ---------------------------------
     * @method : GET
     * @link : BomID/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // BomID Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load BomID Function
            $grade_data = $this->input->get('Grade_Id');

            $output = $this->BomID_Model->select_BomID($grade_data);

            if (isset($output) && $output) {

                // Show BomID All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show BomID all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show BomID All Error
                $message = [
                    'status' => false,
                    'message' => 'BomID data was not found in the database',
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

    public function show($a)
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        $message = [
            'status' => true,
            'data' => $a,
            'message' => 'Show BomID all successful',
        ];

        $this->response($message, REST_Controller::HTTP_OK);

    }
}