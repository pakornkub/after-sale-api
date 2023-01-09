<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class GradeAll extends REST_Controller
{

    protected $GradeAllId = 'GradeAll';

    public function __construct()
    {

        parent::__construct();

        // Load GradeAll
        $this->load->model('GradeAll_Model');

    }

    /**
     * Show GradeAll All API
     * ---------------------------------
     * @method : GET
     * @link : GradeAll/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // GradeAll Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load GradeAll Function
            $output = $this->GradeAll_Model->select_grade_all();

            if (isset($output) && $output) {

                // Show GradeAll All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Grade all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show Grade All Error
                // $message = [
                //     'status' => false,
                //     'message' => 'GradeAll data was not found in the database',
                // ];

                //$this->response($message, REST_Controller::HTTP_NOT_FOUND);

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
