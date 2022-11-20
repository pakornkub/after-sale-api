<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class RequestType extends REST_Controller
{

    protected $RequestTypeId = 'RequestType';

    public function __construct()
    {

        parent::__construct();

        // Load RequestType
        $this->load->model('RequestType_Model');

    }

    /**
     * Show RequestType All API
     * ---------------------------------
     * @method : GET
     * @link : RequestType/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // RequestType Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load RequestType Function
            $output = $this->RequestType_Model->select_request_type();

            if (isset($output) && $output) {

                // Show RequestType All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Request Type all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show RequestType All Error
                $message = [
                    'status' => false,
                    'message' => 'RequestType data was not found in the database',
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
