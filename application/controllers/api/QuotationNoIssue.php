<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class QuotationNoIssue extends REST_Controller
{

    protected $QuotationNoIssueId = 'QuotationNoIssue';

    public function __construct()
    {

        parent::__construct();

        // Load QuotationNoIssue
        $this->load->model('QuotationNoIssue_Model');

    }

    /**
     * Show QuotationNoIssue All API
     * ---------------------------------
     * @method : GET
     * @link : QuotationNoIssue/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // QuotationNoIssue Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load QuotationNoIssue Function
            $output = $this->QuotationNoIssue_Model->select_quotationno_issue();

            if (isset($output) && $output) {

                // Show QuotationNoIssue All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show QuotationNo Issue all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show QuotationNoIssue All Error
                $message = [
                    'status' => false,
                    'message' => 'QuotationNo Issue data was not found in the database',
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
