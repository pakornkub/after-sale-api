<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class SKUMapping extends REST_Controller
{

    protected $MenuId = 'MaterialMapping';

    public function __construct()
    {

        parent::__construct();

        // Load SKUMapping
        $this->load->model('SKUMapping_Model');
        $this->load->model('Auth_Model');

    }

    /**
     * Show SKUMapping All API
     * ---------------------------------
     * @method : GET
     * @link : skumapping/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // SKUMapping Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load SKUMapping Function
            $output = $this->SKUMapping_Model->select_skumapping();

            if (isset($output) && $output) {

                // Show SKUMapping All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show SKUMapping all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show SKUMapping All Error
                // $message = [
                //     'status' => false,
                //     'message' => 'SKUMapping data was not found in the database',
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
     * Create SKUMapping API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : skumapping/create
     */
    public function create_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        
            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // SKUMapping Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $skumapping_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $skumapping_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $skumapping_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });


                $skumapping_header = json_decode($this->input->post('data1'), true); 

                if ($skumapping_permission[array_keys($skumapping_permission)[0]]['Created']) {

                    $skumapping_data['data'] = [
                        'SKUMapping_Date' => $skumapping_header['SKUMapping_Date'],
                        'FG_ITEM_ID' => $skumapping_header['Grade_ID_FG'],
                        'QTY' => $skumapping_header['SKUMapping_QTY'],
                        'SP_ITEM_ID' => $skumapping_header['Grade_ID_SP'],
                        'Status' => intval($skumapping_header['SKUMapping_Status']),
                        'Create_Date' => date('Y-m-d H:i:s'),
                        'Create_By' => $skumapping_token['UserName'],
                        'Update_Date' => null,
                        'Update_By' => null,
                        
                    ];

                    // Create SKUMapping Function
                    $skumapping_output = $this->SKUMapping_Model->insert_skumapping($skumapping_data);



                    if (isset($skumapping_output) && $skumapping_output) {

                        // Create SKUMapping Success

                        $message = [
                            'status' => true,
                            'message' => 'Create SKUMapping Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);



                    } else {

                        // Create SKUMapping Error
                        $message = [
                            'status' => false,
                            'message' => 'Create SKUMapping Fail : [Insert Data Fail]',
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
     * Update SKUMapping API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : skumapping/update
     */
    public function update_post()
    { 

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // SKUMapping Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $skumapping_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $skumapping_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $skumapping_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                    $skumapping_header = json_decode($this->input->post('data1'), true); 

                    if ($skumapping_permission[array_keys($skumapping_permission)[0]]['Updated']) {

                        $skumapping_data['index'] = $skumapping_header['SKUMapping_Index'];

                        
                        $skumapping_data['data'] = [
                            'SKUMapping_Date' => $skumapping_header['SKUMapping_Date'],
                            'FG_ITEM_ID' => $skumapping_header['Grade_ID_FG'],
                            'QTY' => $skumapping_header['SKUMapping_QTY'],
                            'SP_ITEM_ID' => $skumapping_header['Grade_ID_SP'],
                            'Status' => intval($skumapping_header['SKUMapping_Status']),
                            'Update_Date' => date('Y-m-d H:i:s'),
                            'Update_By' => $skumapping_token['UserName'],
                            
                        ];

                    

                    // // Update skumapping Function
                    $skumapping_output = $this->SKUMapping_Model->update_skumapping($skumapping_data);

                    if (isset($skumapping_output) && $skumapping_output) {

                        // Update skumapping Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Grade Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update SKUMapping Error
                        $message = [
                            'status' => false,
                            'message' => 'Update SKUMapping Fail : [Update Data Fail]',
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
     * Delete SKUMapping API
     * ---------------------------------
     * @param: SKUMapping_Index
     * ---------------------------------
     * @method : POST
     * @link : skumapping/delete
     */
    public function delete_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // SKUMapping Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $skumapping_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $check_permission = [
                    'username' => $skumapping_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $skumapping_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($skumapping_permission[array_keys($skumapping_permission)[0]]['Deleted']) {

                    $skumapping_data['index'] = $this->input->post('SKUMapping_ID');

                    // Delete skumapping Function
                    $skumapping_output = $this->SKUMapping_Model->delete_skumapping($skumapping_data);

                    if (isset($skumapping_output) && $skumapping_output) {

                        // Delete skumapping Success
                        $message = [
                            'status' => true,
                            'message' => 'Delete SKUMapping Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete skumapping Error
                        $message = [
                            'status' => false,
                            'message' => 'Delete SKUMapping Fail : [Delete Data Fail]',
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

}
