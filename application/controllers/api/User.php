<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller
{

    public function __construct()
    {

        parent::__construct();

        // Load User_Model
        $this->load->model('User_Model');

    }

    /**
     * Show User All API
     * ---------------------------------
     * @method : GET
     * @link : user/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // User Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load User Function
            $output = $this->User_Model->select_user();

            if (isset($output) && $output) {

                // Show User All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show user all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show User All Error
                $message = [
                    'status' => false,
                    'message' => 'User data was not found in the database',
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
