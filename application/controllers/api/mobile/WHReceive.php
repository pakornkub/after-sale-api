<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class WHReceive extends REST_Controller
{

    protected $MenuId = 'WHReceiveMobile';

    public function __construct()
    {

        parent::__construct();

        // Load WHReceive_Model
        $this->load->model('mobile/WHReceive_Model');
        $this->load->model('Auth_Model');

    }

    /**
     * Update WHReceive Tag API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : wh_receive/update
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

            // WHReceive Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $wh_receive_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $wh_receive_token['UserName'],
                  ];
                  $permission_output = $this->Auth_Model->select_permission_new($check_permission);
          
                $wh_receive_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($wh_receive_permission[array_keys($wh_receive_permission)[0]]['Created']) {

                    $data['items'] = json_decode($this->input->post('Items'), true);

                    $data['user'] = [
                        'Create_By' => $wh_receive_token['UserName'],
                        'Create_Date' => date('Y-m-d H:i:s'),
                    ];

                    // Update WHReceive Function
                    $wh_receive_output = $this->WHReceive_Model->update_wh_receive($data);

                    if (isset($wh_receive_output) && $wh_receive_output) {

                        // Update WHReceive Success
                        $message = [
                            'status' => true,
                            'message' => 'Update WH Receive Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update WHReceive Error
                        $message = [
                            'status' => false,
                            'message' => 'Update WH Receive Fail : [Update Data Fail]',
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
