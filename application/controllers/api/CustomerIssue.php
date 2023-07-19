<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class CustomerIssue extends REST_Controller
{

    protected $CustomerIssueId = 'CustomerIssue';

    public function __construct()
    {

        parent::__construct();

        // Load CustomerIssue
        $this->load->model('CustomerIssue_Model');

    }

    /**
     * Show CustomerIssue All API
     * ---------------------------------
     * @method : GET
     * @link : CustomerIssue/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // CustomerIssue Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load CustomerIssue Function
            $output = $this->CustomerIssue_Model->select_customer_issue();

            if (isset($output) && $output) {

                // Show CustomerIssue All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Customer Issue all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show CustomerIssue All Error
                $message = [
                    'status' => false,
                    'message' => 'Customer Issue data was not found in the database',
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
