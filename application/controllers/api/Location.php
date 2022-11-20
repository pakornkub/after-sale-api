<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Location extends REST_Controller
{

    protected $LocationId = 'Location';

    public function __construct()
    {

        parent::__construct();

        // Load Location
        $this->load->model('Location_Model');

    }

    /**f
     * Show Location All API
     * ---------------------------------
     * @method : GET
     * @link : location/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Location Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load Location Function
            $output = $this->Location_Model->select_location();

            if (isset($output) && $output) {

                // Show Location All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Location all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show Location All Error
                $message = [
                    'status' => false,
                    'message' => 'Location data was not found in the database',
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
