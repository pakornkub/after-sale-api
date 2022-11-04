<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class SplitPart extends REST_Controller
{

    protected $MenuId = 'SplitPart';

    public function __construct()
    {

        parent::__construct();

        // Load SplitPart
        $this->load->model('SplitPart_Model');

    }

    /**
     * Show SplitPart All API
     * ---------------------------------
     * @method : GET
     * @link : splitpart/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // SplitPart Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load SplitPart Function
            $output = $this->SplitPart_Model->select_splitpart();

            if (isset($output) && $output) {

                // Show SplitPart All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Split Part all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            } else {

                // Show Split Part All Error
                // $message = [
                //     'status' => false,
                //     'message' => 'Split Part data was not found in the database',
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
     * Create Split Part API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : splitpart/create
     */
    public function create_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        
            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // Split Part Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $split_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $split_permission = array_filter($split_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });


                $split_header = json_decode($this->input->post('data1'), true); 

                if ($split_permission[array_keys($split_permission)[0]]['Created']) {



                    $split_no_output = json_decode(json_encode($this->SplitPart_Model->select_split_no()), true);
                    $split_no = $split_no_output[array_keys($split_no_output)[0]]['jobNo'];

                    if (isset($split_no) && $split_no) {

                    
                        $split_data['data'] = [
                            'JOB_No' => $split_no,
                            'JOB_Date' => $split_header['Split_Date'],
                            'JobType_ID' => '1',
                            'Ref_DocNo_1' => (isset($split_header['Ref_No1']) && $split_header['Ref_No1']) ? $split_header['Ref_No1'] : null,
                            'Ref_DocNo_2' => (isset($split_header['Ref_No2']) && $split_header['Ref_No2']) ? $split_header['Ref_No2'] : null,
                            'Remark' => (isset($split_header['Split_Remark']) && $split_header['Split_Remark']) ? $split_header['Split_Remark'] : null,
                            'JOB_STATUS' => '1',
                            'Create_Date' => date('Y-m-d H:i:s'),
                            'Create_By' => $split_token['UserName'],
                            'Update_Date' => null,
                            'Update_By' => null,
                            
                        ];

    
                        // Create split Function
                        $split_output = $this->SplitPart_Model->insert_splitpart($split_data);
    
    
    
                        if (isset($split_output) && $split_output) {
    
                            //Create Item Success
                            $split_item = json_decode($this->input->post('data2'), true); 
                            
                            foreach ($split_item as $value) {
                                
                                $split_data_item['data'] = [
                                    'Job_ID' => $split_output,
                                    'SKUMapping_ID' => $value['SKUMapping_ID'],
                                    'Rec_NO' => $value['Rec_No'],
                                    'QR_NO' => $value['QR_NO'],
                                    'FG_ITEM_ID' => $value['Grade_ID_FG'],
                                    'Lot_No' => (isset($value['Lot_No']) && $value['Lot_No']) ? $value['Lot_No'] : null,
                                    'FG_Qty' => $value['QTY_FG'],
                                    'SP_ITEM_ID' => $value['Grade_ID_SP'],
                                    'SP_Qty' => $value['QTY_SP'],
                                    'Create_Date' => date('Y-m-d H:i:s'),
                                    'Create_By' => $split_token['UserName'],
                                    
                                ];
    
                                $split_output_item = $this->SplitPart_Model->insert_splitpart_item($split_data_item);
    
                            }
                            
    
                            $message = [
                                'status' => true,
                                'message' => 'Create Split Part Successful',
                            ];
    
                            $this->response($message, REST_Controller::HTTP_OK);
    
    
    
                        } else {
    
                            // Create split Error
                            $message = [
                                'status' => false,
                                'message' => 'Create Split Part Fail : [Insert Data Fail]',
                            ];
    
                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    
                        }
                    }else{
                            // Create Split NO Error
                            $message = [
                                'status' => false,
                                'message' => 'Split No Fail',
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
     * Update SplitPart API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : splitpart/update
     */
    public function update_post()
    { 

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // SplitPart Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $split_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $split_permission = array_filter($split_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                    $split_header = json_decode($this->input->post('data1'), true); 

                    if ($split_permission[array_keys($split_permission)[0]]['Created']) {

                        $split_data['index'] = $split_header['Split_Index'];
                        
                        $split_data['data'] = [
                            'JOB_Date' => $split_header['Split_Date'],
                            'JobType_ID' => '1',
                            'Ref_DocNo_1' => (isset($split_header['Ref_No1']) && $split_header['Ref_No1']) ? $split_header['Ref_No1'] : null,
                            'Ref_DocNo_2' => (isset($split_header['Ref_No2']) && $split_header['Ref_No2']) ? $split_header['Ref_No2'] : null,
                            'Remark' => (isset($split_header['Split_Remark']) && $split_header['Split_Remark']) ? $split_header['Split_Remark'] : null,
                            'Update_Date' => date('Y-m-d H:i:s'),
                            'Update_By' => $split_token['UserName'],
                            
                        ];

                    
                   // Update SplitPart Function
                    $split_output = $this->SplitPart_Model->update_splitpart($split_data);

                    if (isset($split_output) && $split_output) {


                        $delete_output = $this->SplitPart_Model->delete_splitpart_item($split_data);

                        $split_item = json_decode($this->input->post('data2'), true); 
                        
                        foreach ($split_item as $value) {
                            
                            $split_data_item['data'] = [
                                'Job_ID' => $split_header['Split_Index'],
                                'SKUMapping_ID' => $value['SKUMapping_ID'],
                                'Rec_NO' => $value['Rec_NO'],
                                'QR_NO' => $value['QR_NO'],
                                'FG_ITEM_ID' => $value['Grade_ID_FG'],
                                'Lot_No' => (isset($value['Lot_No']) && $value['Lot_No']) ? $value['Lot_No'] : null,
                                'FG_Qty' => $value['QTY_FG'],
                                'SP_ITEM_ID' => $value['Grade_ID_SP'],
                                'SP_Qty' => $value['QTY_SP'],
                                'Create_Date' => date('Y-m-d H:i:s'),
                                'Create_By' => $split_token['UserName'],
                                
                            ];

                            $split_output_item = $this->SplitPart_Model->insert_splitpart_item($split_data_item);
                        }
                            // Update SplitPart Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Split Part Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update SplitPart Error
                        $message = [
                            'status' => false,
                            'message' => 'Update Split Part Fail : [Update Data Fail]',
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
     * Delete SplitPart API
     * ---------------------------------
     * @param: SplitPart_Index
     * ---------------------------------
     * @method : POST
     * @link : splitpart/delete
     */
    public function delete_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // SplitPart Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $split_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $split_permission = array_filter($split_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($split_permission[array_keys($split_permission)[0]]['Deleted']) {

                    $split_data['index'] = $this->input->post('Split_Index');

                    // Delete SplitPart Function
                    $split_output = $this->SplitPart_Model->delete_splitpart($split_data);
                    $split_output_item = $this->SplitPart_Model->delete_splitpart_item($split_data);

                    if (isset($split_output) && $split_output) {

                        // Delete SplitPart Success
                        $message = [
                            'status' => true,
                            'message' => $split_output,
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete SplitPart Error
                        $message = [
                            'status' => false,
                            'message' => 'Delete Split Part Fail : [Delete Data Fail]',
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
     * Show SplitPart item All API
     * ---------------------------------
     * @method : GET
     * @link : splitpart/splitpart_item
     */
    public function splitpartitem_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // SplitPartID Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load SplitPartID Function
            $Split_ID = $this->input->get('SplitPart_ID');

            $output = $this->SplitPart_Model->select_splitpartitem($Split_ID);

            if (isset($output) && $output) {

                // Show SplitPartID All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show SplitPartItem all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
        }

    }
    /**
     * Show SKU Mapping API
     * ---------------------------------
     * @method : POST
     * @link : splitpart/skumapping
     */
    public function skumapping_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        $is_valid_token = $this->authorization_token->validateToken();
        
            $ITEM_ID = $this->input->post('ITEM_ID');

            // $tag_data = [
            //     'Rec_ID' => $this->input->post('Rec_ID'),
               
            // ];

            $output = $this->SplitPart_Model->select_skumapping($ITEM_ID);

            if (isset($output) && $output) {

                // Show Detail All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show SKU Mapping successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
    }

}
