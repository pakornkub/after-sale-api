<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Tag extends REST_Controller
{

    protected $MenuId = 'Tag';

    public function __construct()
    {

        parent::__construct();

        // Load Tag_Model
        $this->load->model('Tag_Model');

    }

    /**
     * Show Tag All API
     * ---------------------------------
     * @method : GET
     * @link : tag/index
     */
    public function index_get()
    {

        

    }

    /**
     * Show Receive status API
     * ---------------------------------
     * @method : POST
     * @link : tag/selectreceivestatus
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

            $tag_output = $this->Tag_Model->select_receivestatus($tag_data);

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
     * @link : tag/select
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

            $tag_output = $this->Tag_Model->select_tag($tag_data);

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
     * @link : tag/create
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
                $tag_permission = array_filter($tag_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($tag_permission[array_keys($tag_permission)[0]]['Created']) {

                    
                    $tag_data = [
                        'Rec_NO' => $this->input->post('Rec_NO'),
                        'username' => $tag_token['UserName'],
                       
                    ];

                    // Create Tag Function
                    $tag_output = $this->Tag_Model->insert_tag($tag_data);

                    if (isset($tag_output) && $tag_output) {

                        // Create Tag Success
                        $message = [
                            'status' => true,
                            'message' => 'Create Tag Successful',
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

    

    /**
     * Delete Tag API
     * ---------------------------------
     * @param: Tag_Index
     * ---------------------------------
     * @method : POST
     * @link : tag/delete
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
                $tag_permission = array_filter($tag_token['permission'], function ($permission) {
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
                    $tag_output = $this->Tag_Model->delete_tag($tag_data);

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

    /**
     * Create Tag API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : tag/createreturn
     */
    public function createreturn_post()
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
                $tag_permission = array_filter($tag_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($tag_permission[array_keys($tag_permission)[0]]['Created']) {

                    
                    $tag_data = [
                        'Rec_NO' => $this->input->post('Rec_NO'),
                        'username' => $tag_token['UserName'],
                       
                    ];

                    // Create Tag Function
                    $tag_output = $this->Tag_Model->insert_tagreturn($tag_data);

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
