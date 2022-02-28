<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller
{

    public function __construct()
    {

        parent::__construct();

        // Load User_Model
        $this->load->model('User_Model');

    }

    /**
     * โครงสร้างการทำงาน API
     * - form_validation (ในกรณีมีการ ส่งค่า input parameter มา)
     * - Get Data from Database
     * - generateToken (เฉพาะตอน Login เช้าใช้งาน)
     * - validateToken (ทุกครั้งที่มีการเรียกใช้ API เพื่อตรวจสอบสถานะ Login และ สิทธิ์การเข้าใช้)
     */

    /**
     * Login API
     * ---------------------------------
     * @param: username
     * @param: password
     * ---------------------------------
     * @method : POST
     * @link : user/login
     */
    public function login_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('username', 'username', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'trim|required');

        if ($this->form_validation->run() == false) {
            // Form Validation Error
            $message = [
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors(),
            ];

            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        } else {
            $login_data = [
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
            ];

            // Load Login Function
            $login_output = $this->User_Model->select_login($login_data);

            if (isset($login_output) && $login_output) {
                // Load Authorization Token Library
                $this->load->library('Authorization_Token');

                // Get Permission
                //$permission_output = $this->User_Model->select_permission($login_data);

                // Generate Token
                $token_data = [
                    'id' => $login_output[0]['Id'], //Recommend for Token
                    'UserName' => $login_output[0]['UserName'],
                    'FirstName' => $login_output[0]['FirstName'],
                    'LastName' => $login_output[0]['LastName'],
                    //'permission' => (isset($permission_output) && $permission_output) ? $permission_output : null,
                    'time' => time(), //Recommend for Token
                ];

                $user_token = $this->authorization_token->generateToken($token_data);

                //! print_r($this->authorization_token->userData());
                //! print_r($this->authorization_token->validateToken());
                //! exit();

                $return_data = [
                    'id' => $login_output[0]['Id'],
                    'UserName' => $login_output[0]['UserName'],
                    'FirstName' => $login_output[0]['FirstName'],
                    'LastName' => $login_output[0]['LastName'],
                    //'permission' => (isset($permission_output) && $permission_output) ? $permission_output : null,
                    'token' => $user_token,
                ];

                // Login Success
                $message = [
                    'status' => true,
                    'data' => $return_data,
                    'message' => 'User Login Successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                // Login Error
                $message = [
                    'status' => false,
                    'message' => 'Invalid Username or Password',
                ];

                $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
            }
        }

    }

    /**
     * Validate User Token API
     * ---------------------------------
     * @method : GET
     * @link : user/validate_user_token
     */
    public function validate_user_token_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // User Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Validate User Token Success
            $message = [
                'status' => true,
                'message' => 'Validate Token Successful',
            ];

            $this->response($message, REST_Controller::HTTP_OK);
        } else {
            // Validate User Token Error
            $message = [
                'status' => false,
                'message' => $is_valid_token['message'],
            ];

            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        }

    }

    /**
     * List All User API
     * ---------------------------------
     * @method : GET
     * @link : user/list_all_user
     */
    public function list_all_user_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // User Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load List All User Function
            $output = $this->User_Model->select_list_all_user();

            if (isset($output) && $output) {

                // List All User Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'List All User Successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // List All User Error
                $message = [
                    'status' => false,
                    'message' => 'User not in Database',
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
     * List All User API
     * ---------------------------------
     * @method : GET
     * @link : user/list_all_user
     */
    public function list_all_user_test_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load List All User Function
        $output = $this->User_Model->select_list_all_user();

        if (isset($output) && $output) {

            // List All User Success
            $message = [
                'status' => true,
                'data' => $output,
                'message' => 'List All User Successful',
            ];

            $this->response($message, REST_Controller::HTTP_OK);

        } else {

            // List All User Error
            $message = [
                'status' => false,
                'message' => 'User not in Database',
            ];

            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);

        }

    }

}
