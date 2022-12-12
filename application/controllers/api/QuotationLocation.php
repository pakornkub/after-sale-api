<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class QuotationLocation extends REST_Controller
{

    protected $MenuId = 'QuotationLocation';

    public function __construct()
    {

        parent::__construct();

        // Load QuotationLocation
        $this->load->model('QuotationLocation_Model');

    }

    /**
     * Show QuotationLocation All API
     * ---------------------------------
     * @method : GET
     * @link : QuotationLocation/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // QuotationLocation Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load QuotationLocation Function
            $location = $this->input->get('location');

            $output = $this->QuotationLocation_Model->select_quotation_location($location);

            if (isset($output) && $output) {

                // Show QuotationLocation All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show QuotationLocation all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show QuotationLocation All Error
                $message = [
                    'status' => false,
                    'message' => 'QuotationLocation data was not found in the database',
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
            'message' => 'Show QuotationLocation all successful',
        ];

        $this->response($message, REST_Controller::HTTP_OK);

    }
}