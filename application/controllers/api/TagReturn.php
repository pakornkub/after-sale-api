<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class TagReturn extends REST_Controller
{

    protected $MenuId = 'ReceiveReturn';

    public function __construct()
    {

        parent::__construct();

        // Load TagReturn_Model
        $this->load->model('TagReturn_Model');
        $this->load->model('Auth_Model');

    }

    /**
     * Show Tag All API
     * ---------------------------------
     * @method : GET
     * @link : tagreturn/index
     */
    public function index_get()
    {

        

    }

    /**
     * Show Receive status API
     * ---------------------------------
     * @method : POST
     * @link : tagreturn/selectreceivestatus
     */
    public function selectreceivestatus_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Tag Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        //if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load Tag Function
            

            $tag_data = [
                'Rec_ID' => $this->input->post('Rec_ID'),
               
            ];

            $tag_output = $this->TagReturn_Model->select_receivestatus($tag_data);

            if (isset($tag_output) && $tag_output) {

                // Show Tag All Success
                $message = [
                    'status' => true,
                    'data' => $tag_output,
                    'message' => 'Show Receive Status successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }

        // } else {
        //     // Validate Error
        //     $message = [
        //         'status' => false,
        //         'message' => $is_valid_token['message'],
        //     ];

        //     $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        // }
    }

    /**
     * Show Tag All API
     * ---------------------------------
     * @method : POST
     * @link : tagreturn/select
     */
    public function select_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Tag Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        //if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load Tag Function
            

            $tag_data = [
                'Rec_ID' => $this->input->post('Rec_ID'),
               
            ];

            $tag_output = $this->TagReturn_Model->select_tag($tag_data);

            if (isset($tag_output) && $tag_output) {

                // Show Tag All Success
                $message = [
                    'status' => true,
                    'data' => $tag_output,
                    'message' => 'Show tag all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }

        // } else {
        //     // Validate Error
        //     $message = [
        //         'status' => false,
        //         'message' => $is_valid_token['message'],
        //     ];

        //     $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        // }
    }

    /**
     * Create Tag API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : tagreturn/create
     */
    public function create_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        
            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // Tag Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $tag_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $tag_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $tag_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($tag_permission[array_keys($tag_permission)[0]]['Created']) {

                    
                    $tag_data = [
                        'Rec_NO' => $this->input->post('Rec_NO'),
                        'username' => $tag_token['UserName'],
                       
                    ];

                    // Create Tag Function
                    $tag_output = $this->TagReturn_Model->insert_tag($tag_data);

                    if (isset($tag_output) && $tag_output) {

                        // Create Tag Success
                        $message = [
                            'status' => true,
                            'message' => 'Create Tag Return Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Create Tag Error
                        $message = [
                            'status' => false,
                            'message' => 'Create Tag Fail : [Insert Data Fail]',
                        ];

                        $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

                    }

                } else {
                    // Permission Error
                    $message = [
                        'status' => false,
                        'message' => 'You don’t currently have permission to Create',
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
     * Delete Tag API
     * ---------------------------------
     * @param: Tag_Index
     * ---------------------------------
     * @method : POST
     * @link : tagreturn/delete
     */
    public function delete_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // Tag Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $tag_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $tag_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $tag_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($tag_permission[array_keys($tag_permission)[0]]['Deleted']) {
                    

                    $tag_data['index'] = $this->input->post('Rec_ID');

                    $tag_data['data'] = [
                        'Tag_Status' => -1,
                        'Update_Date' => date('Y-m-d H:i:s'),
                        'Update_By' => $tag_token['UserName'],
                        
                    ];

                    $tag_data['data1'] = [
                        'status' =>  1,
                        'Update_Date' => date('Y-m-d H:i:s'),
                        'Update_By' => $tag_token['UserName'],
                        
                    ];

                    // Delete Tag Function
                    $tag_output = $this->TagReturn_Model->delete_tag($tag_data);

                    if (isset($tag_output) && $tag_output) {

                        // Delete Tag Success
                        $message = [
                            'status' => true,
                            'message' => 'Delete Tag Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete Tag Error
                        $message = [
                            'status' => false,
                            'message' => 'Delete Tag Fail : [Delete Data Fail]',
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
     * Receive Auto API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : tag/receive_auto_return
     */
    public function receive_auto_return_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        
            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // Tag Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $tag_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $tag_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $tag_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($tag_permission[array_keys($tag_permission)[0]]['Approved1']) {

                    
                    $receive_data = [
                        'Rec_ID' => $this->input->post('Rec_ID'),
                        'username' => $tag_token['UserName'],
                       
                    ];

                    // Receive Auto Function
                    $receive_output = $this->TagReturn_Model->insert_receive_auto($receive_data);

                    if (isset($receive_output) && $receive_output) {

                        // Receive Auto Success
                        $message = [
                            'status' => true,
                            'message' => 'Receive Auto Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Receive Auto Error
                        $message = [
                            'status' => false,
                            'message' => 'Receive Auto Fail : [Insert Data Fail]',
                        ];

                        $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

                    }

                } else {
                    // Permission Error
                    $message = [
                        'status' => false,
                        'message' => 'You don’t currently have permission to Receive Auto (Approved1)',
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
