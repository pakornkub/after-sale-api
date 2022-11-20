<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class RequestNo extends REST_Controller
{

    protected $MenuId = 'RequestNo';

    public function __construct()
    {

        parent::__construct();

        // Load RequestNo
        $this->load->model('RequestNo_Model');

    }

    /**
     * Show RequestNo All API
     * ---------------------------------
     * @method : GET
     * @link : RequestNo/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // RequestNo Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load RequestNo Function
            $type_data = $this->input->get('type');

            $output = $this->RequestNo_Model->select_request_no($type_data);

            if (isset($output) && $output) {

                // Show RequestNo All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show RequestNo all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show RequestNo All Error
                $message = [
                    'status' => false,
                    'message' => 'RequestNo data was not found in the database',
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
            'message' => 'Show RequestNo all successful',
        ];

        $this->response($message, REST_Controller::HTTP_OK);

    }
}