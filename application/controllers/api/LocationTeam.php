<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class LocationTeam extends REST_Controller
{

    protected $MenuId = 'LocationTeam';

    public function __construct()
    {

        parent::__construct();

        // Load LocationTeam
        $this->load->model('LocationTeam_Model');

    }

    /**
     * Show LocationTeam All API
     * ---------------------------------
     * @method : GET
     * @link : LocationTeam/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // LocationTeam Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load LocationTeam Function
            $type_data = $this->input->get('type');

            $output = $this->LocationTeam_Model->select_location_team($type_data);

            if (isset($output) && $output) {

                // Show LocationTeam All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show LocationTeam all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show LocationTeam All Error
                $message = [
                    'status' => false,
                    'message' => 'LocationTeam data was not found in the database',
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
            'message' => 'Show LocationTeam all successful',
        ];

        $this->response($message, REST_Controller::HTTP_OK);

    }
}