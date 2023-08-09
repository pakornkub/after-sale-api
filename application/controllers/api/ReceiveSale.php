<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class ReceiveSale extends REST_Controller
{

    protected $MenuId = 'ReceiveSale';

    public function __construct()
    {

        parent::__construct();

        // Load ReceiveSale
        $this->load->model('ReceiveSale_Model');
        $this->load->model('Auth_Model');

    }

    /**
     * Show ReceiveSale All API
     * ---------------------------------
     * @method : GET
     * @link : receivesale/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // ReceiveSale Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load ReceiveSale Function
            $output = $this->ReceiveSale_Model->select_receivesale();

            if (isset($output) && $output) {

                // Show ReceiveSale All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Receive Sale all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show Receive Sale All Error
                // $message = [
                //     'status' => false,
                //     'message' => 'Receive Sale data was not found in the database',
                // ];

                // $this->response($message, REST_Controller::HTTP_NOT_FOUND);

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
     * Update ReceiveSale API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : receivesale/update
     */
    public function update_post()
    { 

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // ReceiveSale Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $receive_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $receive_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $receive_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                    $receive_header = json_decode($this->input->post('data1'), true); 

                    if ($receive_permission[array_keys($receive_permission)[0]]['Created']) {

                        $receive_data['index'] = $receive_header['Receive_Index'];

                        $receive_data['data'] = [
                            'Actual_Team' => $receive_header['Action_Team'],
                            'Update_Date' => date('Y-m-d H:i:s'),
                            'Update_By' => $receive_token['UserName'],
                            
                        ];

                    

                   // Update ReceiveSale Function
                    $receive_output = $this->ReceiveSale_Model->update_receivesale($receive_data);

                    if (isset($receive_output) && $receive_output) {

                            // Update ReceiveSale Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Receive Sale Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update ReceiveSale Error
                        $message = [
                            'status' => false,
                            'message' => 'Update Receive Sale Fail : [Update Data Fail]',
                        ];

                        $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

                    }

                } else {
                    // Permission Error
                    $message = [
                        'status' => false,
                        'message' => 'You don’t currently have permission to Update',
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

    /**
     * Delete ReceiveSale API
     * ---------------------------------
     * @param: ReceiveSale_Index
     * ---------------------------------
     * @method : POST
     * @link : receivesale/delete
     */
    public function delete_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // ReceiveSale Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $receive_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $receive_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $receive_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($receive_permission[array_keys($receive_permission)[0]]['Deleted']) {

                    $receive_data['index'] = $this->input->post('Rec_ID');

                    // Delete ReceiveSale Function
                    $receive_output = $this->ReceiveSale_Model->delete_receivesale($receive_data);
                    $receive_output_item = $this->ReceiveSale_Model->delete_receivesale_item($receive_data);

                    if (isset($receive_output) && $receive_output) {

                        // Delete ReceiveSale Success
                        $message = [
                            'status' => true,
                            'message' => $receive_output,
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete ReceiveSale Error
                        $message = [
                            'status' => false,
                            'message' => 'Delete Receive Sale Fail : [Delete Data Fail]',
                        ];

                        $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

                    }

                } else {
                    // Permission Error
                    $message = [
                        'status' => false,
                        'message' => 'You don’t currently have permission to Delete',
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

        /**
     * Show ReceiveSale item All API
     * ---------------------------------
     * @method : GET
     * @link : receivesale/receivesale_item
     */
    public function receivesaleitem_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // ReceiveSaleID Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load ReceiveSaleID Function
            $Rc_ID = $this->input->get('ReceiveSale_ID');

            $output = $this->ReceiveSale_Model->select_receivesaleitem($Rc_ID);

            if (isset($output) && $output) {

                // Show ReceiveSaleID All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show ReceiveSaleItem all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
        }

    }

}
