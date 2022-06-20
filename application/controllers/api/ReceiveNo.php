<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class ReceiveNo extends REST_Controller
{

    protected $ReceiveNoId = 'ReceiveNo';

    public function __construct()
    {

        parent::__construct();

        // Load ReceiveNo
        $this->load->model('ReceiveNo_Model');

    }

    /**
     * Show ReceiveNo All API
     * ---------------------------------
     * @method : GET
     * @link : ReceiveNo/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // ReceiveNo Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load ReceiveNo Function
            $output = $this->ReceiveNo_Model->select_receive_no();

            if (isset($output) && $output) {

                // Show ReceiveNo All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Receive No all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show ReceiveNo All Error
                $message = [
                    'status' => false,
                    'message' => 'Receive No data was not found in the database',
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
