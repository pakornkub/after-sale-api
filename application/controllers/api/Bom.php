<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Bom extends REST_Controller
{

    protected $MenuId = 'BOM';

    public function __construct()
    {

        parent::__construct();

        // Load Bom
        $this->load->model('Bom_Model');

    }

    /**
     * Show Bom All API
     * ---------------------------------
     * @method : GET
     * @link : bom/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // Bom Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load Bom Function
            $output = $this->Bom_Model->select_bom();

            if (isset($output) && $output) {

                // Show Bom All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Bom all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show Bom All Error
                // $message = [
                //     'status' => false,
                //     'message' => 'Bom data was not found in the database',
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
     * Create Bom API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : bom/create
     */
    public function create_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        
            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // Bom Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $bom_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $bom_permission = array_filter($bom_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });


                $bom_header = json_decode($this->input->post('data1'), true); 

                if ($bom_permission[array_keys($bom_permission)[0]]['Created']) {

                    $bom_data['data'] = [
                        'BOM_Name' => $bom_header['Bom_Id'],
                        'BOM_Date' => $bom_header['Bom_Date'],
                        'FG_ITEM_ID' => $bom_header['Grade_ID_FG'],
                        'Bom_Rev_No' => $bom_header['Rev_No'],
                        'Remark' => $bom_header['Bom_Remark'],
                        'Status' => intval($bom_header['Bom_Status']),
                        'Create_Date' => date('Y-m-d H:i:s'),
                        'Create_By' => $bom_token['UserName'],
                        'Update_Date' => null,
                        'Update_By' => null,
                        
                    ];

                    // Create bom Function
                    $bom_output = $this->Bom_Model->insert_bom($bom_data);



                    if (isset($bom_output) && $bom_output) {

                        // Create Bom Success
                        $bom_item = json_decode($this->input->post('data2'), true); 
                        $i = 1;
                        foreach ($bom_item as $value) {
                            
                            $bom_data_item['data'] = [
                                'BOM_ID' => $bom_output,
                                'ITEM_Seq' => $i,
                                'ITEM_ID' => $value['Grade_ID'],
                                'ITEM_QTY' => $value['QTY'],
                                'Create_Date' => date('Y-m-d H:i:s'),
                                'Create_By' => $bom_token['UserName'],
                                
                            ];

                            $i = $i+1;

                            $bom_output_item = $this->Bom_Model->insert_bom_item($bom_data_item);

                        }
                        

                        $message = [
                            'status' => true,
                            'message' => 'Create Bom Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);



                    } else {

                        // Create Bom Error
                        $message = [
                            'status' => false,
                            'message' => 'Create Bom Fail : [Insert Data Fail]',
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
     * Update Bom API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : bom/update
     */
    public function update_post()
    { 

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // Bom Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $bom_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $bom_permission = array_filter($bom_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                    $bom_header = json_decode($this->input->post('data1'), true); 

                    if ($bom_permission[array_keys($bom_permission)[0]]['Created']) {

                        $bom_data['index'] = $bom_header['BOM_Index'];

                        $bom_data['data'] = [
                            'BOM_Name' => $bom_header['Bom_Id'],
                            'BOM_Date' => $bom_header['Bom_Date'],
                            'FG_ITEM_ID' => $bom_header['Grade_ID_FG'],
                            'Bom_Rev_No' => $bom_header['Rev_No'],
                            'Remark' => $bom_header['Bom_Remark'],
                            'Status' => intval($bom_header['Bom_Status']),
                            'Update_Date' => date('Y-m-d H:i:s'),
                            'Update_By' => $bom_token['UserName'],
                            
                        ];

                    

                    // // Update Bom Function
                    $bom_output = $this->Bom_Model->update_bom($bom_data);

                    if (isset($bom_output) && $bom_output) {


                        $delete_output = $this->Bom_Model->delete_bom_item($bom_data);

                        $bom_item = json_decode($this->input->post('data2'), true); 
                        $i = 1;
                        foreach ($bom_item as $value) {
                            
                            $bom_data_item['data'] = [
                                'BOM_ID' => $bom_header['BOM_Index'],
                                'ITEM_Seq' => $i,
                                'ITEM_ID' => $value['Grade_ID'],
                                'ITEM_QTY' => $value['QTY'],
                                'Create_Date' => date('Y-m-d H:i:s'),
                                'Create_By' => $bom_token['UserName'],
                                
                            ];

                            $i = $i+1;

                            $bom_output_item = $this->Bom_Model->insert_bom_item($bom_data_item);
                        }
                            // Update Bom Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Grade Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update Bom Error
                        $message = [
                            'status' => false,
                            'message' => 'Update Bom Fail : [Update Data Fail]',
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

    /**
     * Delete Bom API
     * ---------------------------------
     * @param: Bom_Index
     * ---------------------------------
     * @method : POST
     * @link : bom/delete
     */
    public function delete_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // Bom Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $bom_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $bom_permission = array_filter($bom_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($bom_permission[array_keys($bom_permission)[0]]['Deleted']) {

                    $bom_data['index'] = $this->input->post('BOM_ID');

                    // Delete bom Function
                    $bom_output = $this->Bom_Model->delete_bom($bom_data);
                    $bom_output_item = $this->Bom_Model->delete_bom_item($bom_data);

                    if (isset($bom_output) && $bom_output) {

                        // Delete bom Success
                        $message = [
                            'status' => true,
                            'message' => 'Delete Bom Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete bom Error
                        $message = [
                            'status' => false,
                            'message' => 'Delete Bom Fail : [Delete Data Fail]',
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
     * Show Bom item All API
     * ---------------------------------
     * @method : GET
     * @link : bom/bom_item
     */
    public function bomitem_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // BomID Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load BomID Function
            $Bom_ID = $this->input->get('Bom_ID');

            $output = $this->Bom_Model->select_BomItem($Bom_ID);

            if (isset($output) && $output) {

                // Show BomID All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show BomItem all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
        }

    }

}
