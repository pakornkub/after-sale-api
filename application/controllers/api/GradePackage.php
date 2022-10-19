<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class GradePackage extends REST_Controller
{

    protected $GradePackageId = 'GradePackage';

    public function __construct()
    {

        parent::__construct();

        // Load GradePackage
        $this->load->model('GradePackage_Model');

    }

    /**
     * Show GradePackage All API
     * ---------------------------------
     * @method : GET
     * @link : GradePackage/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // GradePackage Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load GradePackage Function
            $output = $this->GradePackage_Model->select_grade_package();

            if (isset($output) && $output) {

                // Show GradePackage All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show GradePackage all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show GradePackage All Error
                // $message = [
                //     'status' => false,
                //     'message' => 'GradePackage data was not found in the database',
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
