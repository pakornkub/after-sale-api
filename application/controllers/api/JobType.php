<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class JobType extends REST_Controller
{

    protected $JobTypeId = 'JobType';

    public function __construct()
    {

        parent::__construct();

        // Load JobType
        $this->load->model('JobType_Model');

    }

    /**
     * Show JobType All API
     * ---------------------------------
     * @method : GET
     * @link : JobType/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // JobType Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load JobType Function
            $output = $this->JobType_Model->select_job_type();

            if (isset($output) && $output) {

                // Show JobType All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Job Type all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show JobType All Error
                $message = [
                    'status' => false,
                    'message' => 'JobType data was not found in the database',
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
