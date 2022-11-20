<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class GradeSP extends REST_Controller
{

    protected $GradeSPId = 'GradeSP';

    public function __construct()
    {

        parent::__construct();

        // Load GradeSP
        $this->load->model('GradeSP_Model');

    }

    /**
     * Show GradeSP All API
     * ---------------------------------
     * @method : GET
     * @link : GradeSP/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // GradeSP Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load GradeSP Function
            $output = $this->GradeSP_Model->select_grade_sp();

            if (isset($output) && $output) {

                // Show GradeSP All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show GradeSP all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show GradeSP All Error
                $message = [
                    'status' => false,
                    'message' => 'GradeSP data was not found in the database',
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
