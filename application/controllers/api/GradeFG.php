<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class GradeFG extends REST_Controller
{

    protected $GradeFGId = 'GradeFG';

    public function __construct()
    {

        parent::__construct();

        // Load GradeFG
        $this->load->model('GradeFG_Model');

    }

    /**
     * Show GradeFG All API
     * ---------------------------------
     * @method : GET
     * @link : GradeFG/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // GradeFG Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load GradeFG Function
            $output = $this->GradeFG_Model->select_grade_fg();

            if (isset($output) && $output) {

                // Show GradeFG All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show GradeFG all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show GradeFG All Error
                $message = [
                    'status' => false,
                    'message' => 'GradeFG data was not found in the database',
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
