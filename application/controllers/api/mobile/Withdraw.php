<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Withdraw extends REST_Controller
{

    protected $MenuId = 'WithdrawMobile';

    public function __construct()
    {

        parent::__construct();

        // Load Withdraw_Model
        $this->load->model('mobile/Withdraw_Model');
        $this->load->model('Auth_Model');

    }

    /**
     * Update Withdraw API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : withdraw/update
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

            // Withdraw Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $withdraw_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $withdraw_token['UserName'],
                  ];
                  $permission_output = $this->Auth_Model->select_permission_new($check_permission);
          
                $withdraw_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($withdraw_permission[array_keys($withdraw_permission)[0]]['Created']) {

                    $data['items'] = json_decode($this->input->post('Items'), true);

                    $data['user'] = [
                        'Create_By' => $withdraw_token['UserName'],
                        'Create_Date' => date('Y-m-d H:i:s'),
                    ];

                    // Update Withdraw Function
                    $withdraw_output = $this->Withdraw_Model->update_withdraw($data);

                    if (isset($withdraw_output) && $withdraw_output) {

                        // Update Withdraw Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Withdraw Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update Withdraw Error
                        $message = [
                            'status' => false,
                            'message' => 'Update Withdraw Fail : [Update Data Fail]',
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
