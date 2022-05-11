<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Menu extends REST_Controller
{

    protected $MenuId = 'Menu';

    public function __construct()
    {

        parent::__construct();

        // Load Menu_Model
        $this->load->model('Menu_Model');

    }

    /**
     * Show Menu All API
     * ---------------------------------
     * @method : GET
     * @link : menu/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Menu Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load Menu Function
            $output = $this->Menu_Model->select_menu();

            if (isset($output) && $output) {

                // Show Menu All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show menu all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show Menu All Error
                $message = [
                    'status' => false,
                    'message' => 'Menu data was not found in the database',
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
     * Create Menu API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : menu/create
     */
    public function create_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('Id', 'Id', 'trim|required');
        $this->form_validation->set_rules('IsUse', 'IsUse', 'trim|required');

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

            // Menu Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $menu_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $menu_permission = array_filter($menu_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($menu_permission[array_keys($menu_permission)[0]]['Created']) {

                    $menu_data['data'] = [
                        'Id' => $this->input->post('Id'),
                        'Name' => $this->input->post('Name'),
                        'Des' => $this->input->post('Des'),
                        'IsUse' => intval($this->input->post('IsUse')),
                        'AddBy' => $menu_token['UserName'],
                        'AddDate' => date('Y-m-d H:i:s'),
                        'UpdateBy' => null,
                        'UpdateDate' => null,
                        'CancelBy' => null,
                        'CancelDate' => null,
                    ];

                    // Create Menu Function
                    $menu_output = $this->Menu_Model->insert_menu($menu_data);

                    if (isset($menu_output) && $menu_output) {

                        // Create Menu Success
                        $message = [
                            'status' => true,
                            'message' => 'Create Menu Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Create Menu Error
                        $message = [
                            'status' => false,
                            'message' => 'Create Menu Fail : [Insert Data Fail]',
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

    /**
     * Update Menu API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : menu/update
     */
    public function update_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('Id', 'Id', 'trim|required');
        $this->form_validation->set_rules('IsUse', 'IsUse', 'trim|required');

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

            // Menu Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $menu_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $menu_permission = array_filter($menu_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($menu_permission[array_keys($menu_permission)[0]]['Updated']) {

                    $menu_data['index'] = $this->input->post('Menu_Index');

                    $menu_data['data'] = [
                        'Id' => $this->input->post('Id'),
                        'Name' => $this->input->post('Name'),
                        'Des' => $this->input->post('Des'),
                        'IsUse' => intval($this->input->post('IsUse')),
                        'UpdateBy' => $menu_token['UserName'],
                        'UpdateDate' => date('Y-m-d H:i:s'),
                    ];

                    // Update Menu Function
                    $menu_output = $this->Menu_Model->update_menu($menu_data);

                    if (isset($menu_output) && $menu_output) {

                        // Update Menu Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Menu Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update Menu Error
                        $message = [
                            'status' => false,
                            'message' => 'Update Menu Fail : [Update Data Fail]',
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
     * Delete Menu API
     * ---------------------------------
     * @param: Menu_Index
     * ---------------------------------
     * @method : POST
     * @link : menu/delete
     */
    public function delete_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('Menu_Index', 'Menu_Index', 'trim|required');

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

            // Menu Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $menu_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $menu_permission = array_filter($menu_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($menu_permission[array_keys($menu_permission)[0]]['Deleted']) {

                    $menu_data['index'] = $this->input->post('Menu_Index');

                    // Delete Menu Function
                    $menu_output = $this->Menu_Model->delete_menu($menu_data);

                    if (isset($menu_output) && $menu_output) {

                        // Delete Menu Success
                        $message = [
                            'status' => true,
                            'message' => 'Delete Menu Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete Menu Error
                        $message = [
                            'status' => false,
                            'message' => 'Delete Menu Fail : [Delete Data Fail]',
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

    }

}
