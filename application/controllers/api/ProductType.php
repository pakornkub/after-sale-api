<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class ProductType extends REST_Controller
{

    protected $ProductTypeId = 'ProductType';

    public function __construct()
    {

        parent::__construct();

        // Load ProductType
        $this->load->model('ProductType_Model');

    }

    /**f
     * Show ProductType All API
     * ---------------------------------
     * @method : GET
     * @link : producttype/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // ProductType Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load ProductType Function
            $output = $this->ProductType_Model->select_producttype();

            if (isset($output) && $output) {

                // Show ProductType All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Product Type all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show ProductType All Error
                $message = [
                    'status' => false,
                    'message' => 'Product Type data was not found in the database',
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
