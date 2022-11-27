<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Quotation extends REST_Controller
{

    protected $MenuId = 'Quotation';

    public function __construct()
    {

        parent::__construct();

        // Load Quotation
        $this->load->model('Quotation_Model');

    }

    /**f
     * Show Quotation All API
     * ---------------------------------
     * @method : GET
     * @link : Quotation/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Quotation Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load Quotation Function
            $output = $this->Quotation_Model->select_quotation();

            if (isset($output) && $output) {

                // Show Quotation All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Quotation all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show Quotation All Error
                $message = [
                    'status' => false,
                    'message' => 'Quotation data was not found in the database',
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
