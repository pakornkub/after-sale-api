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

            // Request Sale Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $request_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $request_permission = array_filter($request_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });


                // $TF_Withdraw_No = json_decode($this->input->post('Withdraw_No'), true); 
                // $TF_Old_Team = json_decode($this->input->post('Old_Team'), true); 
                // $TF_New_Team = json_decode($this->input->post('New_Team'), true); 
                // $TF_Quotation_No = json_decode($this->input->post('Quotation_No'), true); 

                if ($request_permission[array_keys($request_permission)[0]]['Created']) {
                    
                        $tf_data['data'] = [
                            'Withdraw_No' => $this->input->post('Withdraw_No'),
                            'Old_Team' => $this->input->post('Old_Team'),
                            'New_Team' => $this->input->post('New_Team'),
                            'Quotation_No' => $this->input->post('Quotation_No'),
                            'Create_By' => $request_token['UserName'],
                        ];

    
                        // Create TransferTeam Function
                        $request_output = $this->TransferTeam_Model->insert_transferteam($tf_data);

    
    
    
                        if (isset($request_output) && $request_output) {
    
                            //Create Item Success
                            $request_item = json_decode($this->input->post('data2'), true); 
                            
                            foreach ($request_item as $value) {
                                
                                $request_data_item['data'] = [
                                    'Withdraw_ID' => $request_output,
                                    'W_Datetime' => date('Y-m-d H:i:s'),
                                    'QR_NO' => $value['QR_NO'],
                                    'ITEM_ID' => $value['ITEM_ID'],
                                    'Status' => '1',
                                    'Qty' => $value['QTY'],
                                    'Create_Date' => date('Y-m-d H:i:s'),
                                    'Create_By' => $request_token['UserName'],
                                    
                                ];

                                $request_update_bal['QR_NO'] = $value['QR_NO'];
                                $request_update_bal['username'] = $request_token['UserName'];
    
                                $request_output_item = $this->TransferTeam_Model->insert_transferteam_item($request_data_item);
    
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
