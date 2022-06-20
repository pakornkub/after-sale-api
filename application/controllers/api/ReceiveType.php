<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class ReceiveType extends REST_Controller
{

    protected $ReceiveTypeId = 'ReceiveType';

    public function __construct()
    {

        parent::__construct();

        // Load ReceiveType
        $this->load->model('ReceiveType_Model');

    }

    /**
     * Show ReceiveType All API
     * ---------------------------------
     * @method : GET
     * @link : ReceiveType/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // ReceiveType Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load ReceiveType Function
            $output = $this->ReceiveType_Model->select_receive_type();

            if (isset($output) && $output) {

                // Show ReceiveType All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Receive Type all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show ReceiveType All Error
                $message = [
                    'status' => false,
                    'message' => 'ReceiveType data was not found in the database',
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
