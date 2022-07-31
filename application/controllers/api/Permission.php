<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Permission extends REST_Controller
{

    protected $MenuId = 'Permission';

    public function __construct()
    {

        parent::__construct();

        // Load Permission_Model
        $this->load->model('Permission_Model');

    }

    /**
     * Show Permission By Condition API
     * ---------------------------------
     * @method : GET
     * @link : permission/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Permission Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

            $User_Group = $this->input->get('User_Group');
            $User_Group_Value = $this->input->get('User_Group_Value');
            $Platform = $this->input->get('Platform');

            // Load Permission Function
            $output = $User_Group == 'User' ? $this->Permission_Model->select_user_permission($User_Group_Value,$Platform) : $this->Permission_Model->select_group_permission($User_Group_Value,$Platform);

            if (isset($output) && $output) {

                // Show Permission By Condition Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show permission by condition successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show Permission By Condition Error
                $message = [
                    'status' => false,
                    'message' => 'Permission data was not found in the database',
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

}
