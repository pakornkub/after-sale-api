<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class User extends REST_Controller {

    public function __construct(){

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
    public function login_post(){

        header("Access-Controll-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('username', 'username', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'trim|required');
        $this->form_validation->set_rules('menu_name', 'menu_name', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            // Form Validation Error
            $message = [
                'status'    => FALSE,
                'error'     => $this->form_validation->error_array(),
                'message'   => validation_errors()
            ];

            $this->response($message, REST_Controller::HTTP_OK);
        }
        else
        {
            $login_data = [
                'username'      => $this->input->post('username'),
                'password'      => $this->input->post('password'),
                'menu_name'     => $this->input->post('menu_name')
            ];

            // Load Login Function
            $login_output = $this->User_Model->select_login($login_data);

            if(isset($login_output) && $login_output)
            {
                // Load Authorization Token Library
                $this->load->library('Authorization_Token');

                // Get Permission
                $permission_output = $this->User_Model->select_permission($login_data);

                // Generate Token
                $token_data = [
                    'id'            => $login_output[0]['EmpCode'], //Recommend for Token
                    'UserName'      => $login_output[0]['UserName'],
                    'Title'         => $login_output[0]['Title'],
                    'FirstName'     => $login_output[0]['FirstName'],
                    'LastName'      => $login_output[0]['LastName'],
                    'Department'    => $login_output[0]['Department'],
                    'Section'       => $login_output[0]['Section'],
                    'Unit'          => $login_output[0]['Unit'],
                    'PositionName'  => $login_output[0]['PositionName'],
                    'permission'    => (isset($permission_output) && $permission_output) ? $permission_output : null,
                    'time'          => time() //Recommend for Token
                ];

                $user_token = $this->authorization_token->generateToken($token_data);

                //! print_r($this->authorization_token->userData()); 
                //! print_r($this->authorization_token->validateToken()); 
                //! exit();
               
                $return_data = [
                    'id'            => $login_output[0]['EmpCode'],
                    'UserName'      => $login_output[0]['UserName'],
                    'Title'         => $login_output[0]['Title'],
                    'FirstName'     => $login_output[0]['FirstName'],
                    'LastName'      => $login_output[0]['LastName'],
                    'Department'    => $login_output[0]['Department'],
                    'Section'       => $login_output[0]['Section'],
                    'Unit'          => $login_output[0]['Unit'],
                    'PositionName'  => $login_output[0]['PositionName'],
                    'permission'    => (isset($permission_output) && $permission_output) ? $permission_output : null,
                    'token'         => $user_token,
                ];

                // Login Success
                $message = [
                    'status'    => TRUE,
                    'data'      => $return_data,
                    'message'   => 'User Login Successful'
                ];

                $this->response($message, REST_Controller::HTTP_OK);
            }
            else
            {
                // Login Error
                $message = [
                    'status'    => FALSE,
                    'message'   => 'Invalid Username or Password'
                ];

                $this->response($message, REST_Controller::HTTP_OK);
            }
        }

    }

    /**  
     * List All User API
     * ---------------------------------
     * @method : GET 
     * @link : user/list_all_user
     */
    public function list_all_user_get(){

        header("Access-Controll-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // User Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if(isset($is_valid_token) && boolval($is_valid_token['status']) === true)
        {
            // Load List All User Function
            $output = $this->User_Model->select_list_all_user();

            if(isset($output) && $output) {

                // List All User Success
                $message = [
                    'status'    => TRUE,
                    'data'      => $output,
                    'message'   => 'List All User Successful'
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
            else{

                // List All User Error
                $message = [
                    'status'    => FALSE,
                    'message'   => 'User not in Database'
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }

        }
        else
        {
            // Validate Error
            $message = [
                'status'    => FALSE,
                'message'   => $is_valid_token['message']
            ];

            $this->response($message, REST_Controller::HTTP_OK);
        }

    }

    /**  
     * Validate User Token API
     * ---------------------------------
     * @method : GET 
     * @link : user/validate_user_token
     */
    public function validate_user_token_get(){

        header("Access-Controll-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // User Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if(isset($is_valid_token) && boolval($is_valid_token['status']) === true)
        {
            // Validate User Token Success
            $message = [
                'status'    => TRUE,
                'message'   => 'Validate Token Successful'
            ];

            $this->response($message, REST_Controller::HTTP_OK);
        }
        else
        {
            // Validate Error
            $message = [
                'status'    => FALSE,
                'message'   => $is_valid_token['message']
            ];

            $this->response($message, REST_Controller::HTTP_OK);
        }

    }
 
}