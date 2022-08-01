<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class ReceiveSP extends REST_Controller
{

    protected $MenuId = 'ReceiveSP';

    public function __construct()
    {

        parent::__construct();

        // Load ReceiveSP_Model
        $this->load->model('mobile/ReceiveSP_Model');

    }

    /**
     * Show ReceiveSP All API
     * ---------------------------------
     * @method : GET
     * @link : receive_sp/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // ReceiveSP Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load ReceiveSP Function
            $output = $this->ReceiveSP_Model->select_receive_sp();

            if (isset($output) && $output) {

                // Show ReceiveSP All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show receive sp all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show ReceiveSP All Error
                $message = [
                    'status' => false,
                    'message' => 'Receive SP data was not found in the database',
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

    /**
     * Update ReceiveSP API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : receive_sp/update
     */
    public function update_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('Rec_ID', 'Rec_ID', 'trim|required');

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

            // ReceiveSP Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $receive_sp_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $receive_sp_permission = array_filter($receive_sp_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($receive_sp_permission[array_keys($receive_sp_permission)[0]]['Updated']) {

                    $receive_sp_data['where'] = [
                        'Rec_ID' =>  $this->input->post('Rec_ID')
                    ];

                    $receive_sp_data['data'] = [
                        'status' => 5,
                        'Update_By' => $receive_sp_token['UserName'],
                        'Update_Date' => date('Y-m-d H:i:s'),
                    ];

                    // Update ReceiveSP Function
                    $receive_sp_output = $this->ReceiveSP_Model->update_receive_sp($receive_sp_data);

                    if (isset($receive_sp_output) && $receive_sp_output) {

                        // Update ReceiveSP Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Receive SP Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update ReceiveSP Error
                        $message = [
                            'status' => false,
                            'message' => 'Update Receive SP Fail : [Update Data Fail]',
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


     /**
     * Show ReceiveSP Item API
     * ---------------------------------
     * @method : GET
     * @link : receive_sp/item
     */
    public function item_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // ReceiveSP Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load ReceiveSP Function
            $output = $this->ReceiveSP_Model->select_receive_sp_item($this->input->get('Rec_ID'));

            if (isset($output) && $output) {

                // Show ReceiveSP All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show receive sp item successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show ReceiveSP All Error
                $message = [
                    'status' => false,
                    'message' => 'Receive SP Item data was not found in the database',
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

     /**
     * Exec ReceiveSP Transaction API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : receive_sp/exec_transaction
     */
    public function exec_transaction_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('Rec_ID', 'Rec_ID', 'trim|required');
        $this->form_validation->set_rules('QR_NO', 'QR_NO', 'trim|required');
        $this->form_validation->set_rules('Tag_ID', 'Tag_ID', 'trim|required');

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

            // ReceiveSP Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $receive_sp_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $receive_sp_permission = array_filter($receive_sp_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($receive_sp_permission[array_keys($receive_sp_permission)[0]]['Created']) {

                    $tag_data = [
                        'Rec_ID' => intval($this->input->post('Rec_ID')),
                        'QR_NO' => $this->input->post('QR_NO'),
                        'Tag_ID' => intval($this->input->post('Tag_ID')),
                        'Username' => $receive_sp_token['UserName'],
                    ];

                    // Exec ReceiveSP Transaction Function
                    $receive_sp_output = $this->ReceiveSP_Model->exec_receive_sp_transaction($tag_data);

                    if (isset($receive_sp_output) && $receive_sp_output) {

                        if(boolval($receive_sp_output[0]['Result_status']) === true)
                        {
                        
                            // Exec ReceiveSP Transaction Success
                            $message = [
                                'status' => true,
                                'message' => $receive_sp_output[0]['Result_Desc'],
                            ];

                            $this->response($message, REST_Controller::HTTP_OK);
                        }
                        else
                        {
                             // Exec ReceiveSP Transaction Error Condition
                             $message = [
                                'status' => false,
                                'message' => $receive_sp_output[0]['Result_Desc'],
                            ];

                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }

                    } else {

                        // Exec ReceiveSP Transaction Error
                        $message = [
                            'status' => false,
                            'message' => 'Exec Transaction Fail : [Exec Data Fail]',
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
