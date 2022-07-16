<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class CheckStock extends REST_Controller
{

    protected $MenuId = 'CheckStock';

    public function __construct()
    {

        parent::__construct();

        // Load CheckStock_Model
        $this->load->model('mobile/CheckStock_Model');

    }

    /**
     * Show CheckStock API
     * ---------------------------------
     * @method : GET
     * @link : check_stock/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // CheckStock Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load CheckStock Function
            $output = $this->CheckStock_Model->select_check_stock($this->input->get('Tag_ID'));

            if (isset($output) && $output) {

                // Show CheckStock Success
                $message = [
                    'status' => true,
                    'data' => $output[0],
                    'message' => 'Show item successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show CheckStock Error
                $message = [
                    'status' => false,
                    'message' => 'Item data was not found in the database',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

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
