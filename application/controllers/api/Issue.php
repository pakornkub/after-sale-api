<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Issue extends REST_Controller
{

    protected $MenuId = 'Issue';

    public function __construct()
    {

        parent::__construct();

        // Load Issue
        $this->load->model('Issue_Model');

    }

    /**
     * Show Issue All API
     * ---------------------------------
     * @method : GET
     * @link : issue/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Issue Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load Issue Function
            $output = $this->Issue_Model->select_issue();

            if (isset($output) && $output) {

                // Show Issue All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Issue all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show Issue All Error
                // $message = [
                //     'status' => false,
                //     'message' => 'Issue data was not found in the database',
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
     * Create Issue API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : issue/create
     */
    public function create_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        
            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // Transfer Team Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $tf_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $tf_permission = array_filter($tf_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                $TF_Quotation_No = $this->input->post('Quotation_No'); 

                if ($tf_permission[array_keys($tf_permission)[0]]['Created']) {

                    $data['items'] = json_decode($this->input->post('Item'), true);

                    $data['user'] = [
                        'Create_By' => $tf_token['UserName'],
                        'Create_Date' => date('Y-m-d H:i:s'),
                    ];
    
                    $withdraw_output = $this->Issue_Model->insert_issue_item($data);
    
                    if (isset($withdraw_output) && $withdraw_output) {

                        $message = [
                            'status' => true,
                            'message' => 'Create Issue Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);



                    } else {

                        // Create Issue Error
                        $message = [
                            'status' => false,
                            'message' => 'Create Issue Fail : [Insert Data Fail]',
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
     * Show Tag All API
     * ---------------------------------
     * @method : POST
     * @link : issue/stock_bal
     */
    public function stock_bal_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Tag Token Validation
        $is_valid_token = $this->authorization_token->validateToken();


            $qrcode_data = [
                'QR_NO' => $this->input->post('QR_NO'),
                'Location_ID' => $this->input->post('Location_ID'),
               
            ];

            $qrcode_output = $this->Issue_Model->select_stockbal($qrcode_data);

            if (isset($qrcode_output) && $qrcode_output) {

                // Show Tag All Success
                $message = [
                    'status' => true,
                    'data' => $qrcode_output,
                    'message' => 'Show QR CODE successful',
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

    

}
