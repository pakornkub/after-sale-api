<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class TransferTeam extends REST_Controller
{

    protected $MenuId = 'TransferTeam';

    public function __construct()
    {

        parent::__construct();

        // Load TransferTeam
        $this->load->model('TransferTeam_Model');
        $this->load->model('Auth_Model');

    }

    /**
     * Show TransferTeam All API
     * ---------------------------------
     * @method : GET
     * @link : transferteam/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // TransferTeam Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load TransferTeam Function
            $output = $this->TransferTeam_Model->select_transferteam();

            if (isset($output) && $output) {

                // Show TransferTeam All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show TransferTeam all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show TransferTeam All Error
                // $message = [
                //     'status' => false,
                //     'message' => 'TransferTeam data was not found in the database',
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
     * Create TransferTeam API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : transferteam/create
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
                $check_permission = [
                    'username' => $tf_token['UserName'],
                  ];
                $permission_output = $this->Auth_Model->select_permission_new($check_permission);

                $tf_permission = array_filter($permission_output, function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });


                $TF_Withdraw_No = $this->input->post('Withdraw_No'); 
                $TF_Old_Team = $this->input->post('Old_Team'); 
                $TF_New_Team = $this->input->post('New_Team'); 
                $TF_Quotation_No = $this->input->post('Quotation_No'); 

                if ($tf_permission[array_keys($tf_permission)[0]]['Created']) {
                    
                        // $tf_data['data'] = [
                        //     'Withdraw_No' => $this->input->post('Withdraw_No'),
                        //     'Old_Team' => $this->input->post('Old_Team'),
                        //     'New_Team' => $this->input->post('New_Team'),
                        //     'Quotation_No' => $this->input->post('Quotation_No'),
                        //     'Create_By' => $tf_token['UserName'],
                        // ];

    
                        // Create TransferTeam Function
                        $tf_output = $this->TransferTeam_Model->insert_transferteam($TF_Quotation_No,$TF_Old_Team,$TF_New_Team,$tf_token['UserName']);


                        
    
    
    
                        if (isset($tf_output) && $tf_output) {
    
                            $request_item = json_decode($this->input->post('Item'), true); 
                            
                            foreach ($request_item as $value) {
                                    $tf_data_item['data'] = [
                                    'UniqueKey' => $this->input->post('Quotation_No'),
                                    'Withdraw_No' => $value['Withdraw_No'],
                                    'QR_NO' => $value['QR_NO'],
                                    'ITEM_ID' => $value['ITEM_ID'],
                                    'Qty' => $value['Qty'],
                                    'Create_Date' => date('Y-m-d H:i:s'),
                                    'Create_By' => $tf_token['UserName'],
                                    
                                ];

                                $tf_output_item = $this->TransferTeam_Model->insert_transferteam_item($tf_data_item);
                            }
                            
    
                            $message = [
                                'status' => true,
                                'message' => 'Create Transfer Team Successful',
                            ];
    
                            $this->response($message, REST_Controller::HTTP_OK);
    
    
    
                        } else {
    
                            // Create TransferTeam Error
                            $message = [
                                'status' => false,
                                'message' => 'Create TransferTeam Fail : [Insert Data Fail]',
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

    

}
