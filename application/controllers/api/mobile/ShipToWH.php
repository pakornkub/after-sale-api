<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class ShipToWH extends REST_Controller
{

    protected $MenuId = 'ShipToWH';

    public function __construct()
    {

        parent::__construct();

        // Load ShipToWH_Model
        $this->load->model('mobile/ShipToWH_Model');

    }

    /**
     * Update ShipToWH Tag API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : ship_to_wh/update
     */
    public function update_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('Items', 'Items', 'trim|required');

        if ($this->form_validation->run() == false) {
            // Form Validation Error
            $message = [
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors(),
            ];

            $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
        } else {

            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // ShipToWH Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $ship_to_wh_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $ship_to_wh_permission = array_filter($ship_to_wh_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($ship_to_wh_permission[array_keys($ship_to_wh_permission)[0]]['Updated']) {

                    $data['items'] = json_decode($this->input->post('Items'), true);

                    $data['user'] = [
                        'Update_By' => $ship_to_wh_token['UserName'],
                        'Update_Date' => date('Y-m-d H:i:s'),
                    ];

                    // Update ShipToWH Function
                    $ship_to_wh_output = $this->ShipToWH_Model->update_ship_to_wh($data);

                    if (isset($ship_to_wh_output) && $ship_to_wh_output) {

                        // Update ShipToWH Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Ship to WH Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update ShipToWH Error
                        $message = [
                            'status' => false,
                            'message' => 'Update Ship to WH Fail : [Update Data Fail]',
                        ];

                        $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

                    }

                } else {
                    // Permission Error
                    $message = [
                        'status' => false,
                        'message' => 'You don’t currently have permission to Update',
                    ];

                    $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
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

}
