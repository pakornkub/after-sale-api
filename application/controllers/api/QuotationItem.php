<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class QuotationItem extends REST_Controller
{

    protected $MenuId = 'QuotationItem';

    public function __construct()
    {

        parent::__construct();

        // Load QuotationItem
        $this->load->model('QuotationItem_Model');

    }

    /**
     * Show QuotationItem All API
     * ---------------------------------
     * @method : GET
     * @link : QuotationItem/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // QuotationItem Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load QuotationItem Function
            $withdraw_data = $this->input->get('withdraw');

            $output = $this->QuotationItem_Model->select_quotation_item($withdraw_data);

            if (isset($output) && $output) {

                // Show QuotationItem All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show QuotationItem all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show QuotationItem All Error
                $message = [
                    'status' => false,
                    'message' => 'QuotationItem data was not found in the database',
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
            'message' => 'Show QuotationItem all successful',
        ];

        $this->response($message, REST_Controller::HTTP_OK);

    }
}