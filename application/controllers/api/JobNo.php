<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class JobNo extends REST_Controller
{

    protected $MenuId = 'JobNo';

    public function __construct()
    {

        parent::__construct();

        // Load JobNo
        $this->load->model('JobNo_Model');

    }

    /**
     * Show JobNo All API
     * ---------------------------------
     * @method : GET
     * @link : JobNo/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // JobNo Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load JobNo Function
            $type_data = $this->input->get('type');

            $output = $this->JobNo_Model->select_Job_no($type_data);

            if (isset($output) && $output) {

                // Show JobNo All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show JobNo all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show JobNo All Error
                $message = [
                    'status' => false,
                    'message' => 'JobNo data was not found in the database',
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
            'message' => 'Show JobNo all successful',
        ];

        $this->response($message, REST_Controller::HTTP_OK);

    }
}