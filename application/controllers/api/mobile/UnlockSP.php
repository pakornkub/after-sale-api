<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class UnlockSP extends REST_Controller
{

    protected $MenuId = 'UnlockSP';

    public function __construct()
    {

        parent::__construct();

        // Load UnlockSP_Model
        $this->load->model('mobile/UnlockSP_Model');

    }

    /**
     * Show UnlockSP All API
     * ---------------------------------
     * @method : GET
     * @link : unlock_sp/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // UnlockSP Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load UnlockSP Function
            $output = $this->UnlockSP_Model->select_unlock_sp();

            if (isset($output) && $output) {

                // Show UnlockSP All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show unlock sp all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show UnlockSP All Error
                $message = [
                    'status' => false,
                    'message' => 'Unlock SP data was not found in the database',
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
     * Update UnlockSP API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : unlock_sp/update
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

            // UnlockSP Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $unlock_sp_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $unlock_sp_permission = array_filter($unlock_sp_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($unlock_sp_permission[array_keys($unlock_sp_permission)[0]]['Updated']) {

                    $unlock_sp_data['where'] = [
                        'Rec_ID' =>  $this->input->post('Rec_ID')
                    ];

                    $unlock_sp_data['data'] = [
                        'status' => 9,
                        'Update_By' => $unlock_sp_token['UserName'],
                        'Update_Date' => date('Y-m-d H:i:s'),
                    ];

                    // Update UnlockSP Function
                    $unlock_sp_output = $this->UnlockSP_Model->update_unlock_sp($unlock_sp_data);

                    if (isset($unlock_sp_output) && $unlock_sp_output) {

                        // Update UnlockSP Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Unlock SP Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update UnlockSP Error
                        $message = [
                            'status' => false,
                            'message' => 'Update Unlock SP Fail : [Update Data Fail]',
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
     * Update UnlockSP Tag API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : unlock_sp/update_tag
     */
    public function update_tag_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
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

            // UnlockSP Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $unlock_sp_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $unlock_sp_permission = array_filter($unlock_sp_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($unlock_sp_permission[array_keys($unlock_sp_permission)[0]]['Updated']) {

                    $unlock_sp_data['where'] = [
                        'QR_NO' => $this->input->post('QR_NO'),
                        'Tag_ID' => $this->input->post('Tag_ID'),
                    ]; 

                    $unlock_sp_data['data'] = [
                        'Tag_Status' => 9,
                        'ItemStatus_ID' => 2,
                        'Update_By' => $unlock_sp_token['UserName'],
                        'Update_Date' => date('Y-m-d H:i:s'),
                    ];

                    // Check UnlockSP Tag Status Complete 
                    if(!$this->UnlockSP_Model->select_unlock_sp_tag_complete($unlock_sp_data['where']))
                    {
                        // Update UnlockSP Function
                        $unlock_sp_output = $this->UnlockSP_Model->update_unlock_sp_tag($unlock_sp_data);

                        if (isset($unlock_sp_output) && $unlock_sp_output) {

                            // Update UnlockSP Success
                            $message = [
                                'status' => true,
                                'message' => 'Update Unlock SP Successful',
                            ];

                            $this->response($message, REST_Controller::HTTP_OK);

                        } else {

                            // Update UnlockSP Error
                            $message = [
                                'status' => false,
                                'message' => 'Update Unlock SP Fail : [Update Data Fail]',
                            ];

                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

                        }

                    }
                    else
                    {
                         // Check UnlockSP Tag Status Complete Error
                         $message = [
                            'status' => false,
                            'message' => 'QR has been scanned in This Order',
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
