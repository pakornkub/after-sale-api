<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class RequestNoIssue extends REST_Controller
{

    protected $RequestNoIssueId = 'RequestNoIssue';

    public function __construct()
    {

        parent::__construct();

        // Load RequestNoIssue
        $this->load->model('RequestNoIssue_Model');

    }

    /**
     * Show RequestNoIssue All API
     * ---------------------------------
     * @method : GET
     * @link : RequestNoIssue/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // RequestNoIssue Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load RequestNoIssue Function
            $output = $this->RequestNoIssue_Model->select_requestno_issue();

            if (isset($output) && $output) {

                // Show RequestNoIssue All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show RequestNo Issue all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show RequestNoIssue All Error
                $message = [
                    'status' => false,
                    'message' => 'RequestNo Issue data was not found in the database',
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
