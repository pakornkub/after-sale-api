<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class RequestSale extends REST_Controller
{

    protected $MenuId = 'RequestSale';

    public function __construct()
    {

        parent::__construct();

        // Load RequestSale
        $this->load->model('RequestSale_Model');

    }

    /**
     * Show RequestSale All API
     * ---------------------------------
     * @method : GET
     * @link : requestsale/index
     */
    public function index_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // RequestSale Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load RequestSale Function
            $output = $this->RequestSale_Model->select_requestsale();

            if (isset($output) && $output) {

                // Show RequestSale All Success
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
     * Create Request Sale API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : requestsale/create
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


                $request_header = json_decode($this->input->post('data1'), true); 

                if ($request_permission[array_keys($request_permission)[0]]['Created']) {



                    $request_no_output = json_decode(json_encode($this->RequestSale_Model->select_request_no($request_header['Request_Type'])), true);
                    $request_no = $request_no_output[array_keys($request_no_output)[0]]['RequestNo'];

                    if (isset($request_no) && $request_no) {

                    
                        $request_data['data'] = [
                            'Withdraw_No' => $request_no,
                            'Withdraw_Date' => $request_header['Request_Date'],
                            'Withdraw_type' => $request_header['Request_Type'],
                            'Quotation_No' => $request_header['Quotation_No'],
                            'Customer_Name' => $request_header['Customer_Name'],
                            'User_Request' => $request_header['User'],
                            'Plan_Team' => $request_header['Plan_Team'],
                            'Ref_No1' => null,
                            'Remark' => (isset($request_header['Request_Remark']) && $request_header['Request_Remark']) ? $request_header['Request_Remark'] : null,
                            'Stock_By' => null,
                            'BOM_ID' => null,
                            'status' => '1',
                            'Create_Date' => date('Y-m-d H:i:s'),
                            'Create_By' => $request_token['UserName'],
                            'Update_Date' => null,
                            'Update_By' => null,
                            
                        ];

    
                        // Create request Function
                        $request_output = $this->RequestSale_Model->insert_requestsale($request_data);

    
    
    
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
    
                                $request_output_item = $this->RequestSale_Model->insert_requestsale_item($request_data_item);
                                $update_stockbal = $this->RequestSale_Model->reserve_stockbalance($request_update_bal);
    
                            }
                            
    
                            $message = [
                                'status' => true,
                                'message' => 'Create Request Sale Successful',
                            ];
    
                            $this->response($message, REST_Controller::HTTP_OK);
    
    
    
                        } else {
    
                            // Create Request Sale Error
                            $message = [
                                'status' => false,
                                'message' => 'Create Request Sale Fail : [Insert Data Fail]',
                            ];
    
                            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    
                        }

                    }else{
                            // Create Request NO Error
                            $message = [
                                'status' => false,
                                'message' => 'Request Sale Fail',
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
     * Update RequestSale API
     * ---------------------------------
     * @param: FormData
     * ---------------------------------
     * @method : POST
     * @link : requestsale/update
     */
    public function update_post()
    { 

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // RequestSale Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $request_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $request_permission = array_filter($request_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                    $request_header = json_decode($this->input->post('data1'), true); 

                    if ($request_permission[array_keys($request_permission)[0]]['Created']) {

                        $request_data['index'] = $request_header['Split_Index'];
                        
                        $request_data['data'] = [
                            'JOB_Date' => $request_header['Split_Date'],
                            'JobType_ID' => '1',
                            'Ref_DocNo_1' => (isset($request_header['Ref_No1']) && $request_header['Ref_No1']) ? $request_header['Ref_No1'] : null,
                            'Ref_DocNo_2' => (isset($request_header['Ref_No2']) && $request_header['Ref_No2']) ? $request_header['Ref_No2'] : null,
                            'Remark' => (isset($request_header['Split_Remark']) && $request_header['Split_Remark']) ? $request_header['Split_Remark'] : null,
                            'Update_Date' => date('Y-m-d H:i:s'),
                            'Update_By' => $request_token['UserName'],
                            
                        ];

                    
                   // Update RequestSale Function
                    $request_output = $this->RequestSale_Model->update_requestsale($request_data);



                    if (isset($request_output) && $request_output) {

                        //ค้นหา QR CODE จาก tb_jobitem
                        $output_qr = json_decode(json_encode($this->RequestSale_Model->select_qr_no($request_data['index'])), true);
                        $qr_no = $output_qr[array_keys($output_qr)[0]]['QR_NO'];
                        

                        $request_update_bal['QR_NO'] = $qr_no;
                        $request_update_bal['username'] = $request_token['UserName'];
    
                        $unreserve_stockbal = $this->RequestSale_Model->unreserve_stockbalance($request_update_bal);


                        $delete_output = $this->RequestSale_Model->delete_requestsale_item($request_data['index']);

                        $request_item = json_decode($this->input->post('data2'), true); 
                        
                        foreach ($request_item as $value) {
                            
                            $request_data_item['data'] = [
                                'Job_ID' => $request_header['Split_Index'],
                                'SKUMapping_ID' => $value['SKUMapping_ID'],
                                'Rec_NO' => $value['Rec_NO'],
                                'QR_NO' => $value['QR_NO'],
                                'FG_ITEM_ID' => $value['Grade_ID_FG'],
                                'Lot_No' => (isset($value['Lot_No']) && $value['Lot_No']) ? $value['Lot_No'] : null,
                                'FG_Qty' => $value['QTY_FG'],
                                'SP_ITEM_ID' => $value['Grade_ID_SP'],
                                'SP_Qty' => $value['QTY_SP'],
                                'Create_Date' => date('Y-m-d H:i:s'),
                                'Create_By' => $request_token['UserName'],
                                
                            ];

                            $request_update_bal['QR_NO'] = $value['QR_NO'];
                            $request_update_bal['username'] = $request_token['UserName'];

                            $request_output_item = $this->RequestSale_Model->insert_requestsale_item($request_data_item);
                            $update_stockbal = $this->RequestSale_Model->reserve_stockbalance($request_update_bal);

                        }
                            // Update RequestSale Success
                        $message = [
                            'status' => true,
                            'message' => 'Update Request Sale Successful',
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Update RequestSale Error
                        $message = [
                            'status' => false,
                            'message' => 'Update Request Sale Fail : [Update Data Fail]',
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
     * Delete RequestSale API
     * ---------------------------------
     * @param: RequestSale_Index
     * ---------------------------------
     * @method : POST
     * @link : requestsale/delete
     */
    public function delete_post()
    {

        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);


            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // RequestSale Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {

                $request_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $request_permission = array_filter($request_token['permission'], function ($permission) {
                    return $permission['MenuId'] == $this->MenuId;
                });

                if ($request_permission[array_keys($request_permission)[0]]['Deleted']) {

                    // $request_data['index'] = $this->input->post('Split_Index');

                    $JOB_ID = json_decode($this->input->post('data1'), true); 
                    $REC_ID = json_decode($this->input->post('data2'), true);
                    $QR_NO = json_decode($this->input->post('data3'), true);  

                    // Delete RequestSale Function
                    $request_output = $this->RequestSale_Model->delete_requestsale($JOB_ID);
                    $request_output_item = $this->RequestSale_Model->delete_requestsale_item($JOB_ID);

                    // unreserve StockBalance

                    $request_update_bal['QR_NO'] = $QR_NO;
                    $request_update_bal['username'] = $request_token['UserName'];

                    $unreserve_stockbal = $this->RequestSale_Model->unreserve_stockbalance($request_update_bal);



                    if ((isset($request_output) && $request_output)  {

                        // Delete RequestSale Success
                        $message = [
                            'status' => true,
                            'message' => $request_output,
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    } else {

                        // Delete RequestSale Error
                        $message = [
                            'status' => false,
                            'message' => 'Delete Request Sale Fail : [Delete Data Fail]',
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
     * Show RequestSale item All API
     * ---------------------------------
     * @method : GET
     * @link : requestsale/requestsale_item
     */
    public function requestsaleitem_get()
    {

        header("Access-Control-Allow-Origin: *");

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // RequestSaleID Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if (isset($is_valid_token) && boolval($is_valid_token['status']) === true) {
            // Load RequestSaleID Function
            $Request_ID = $this->input->get('RequestSale_ID');

            $output = $this->RequestSale_Model->select_requestsaleitem($Request_ID);

            if (isset($output) && $output) {

                // Show RequestSaleID All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show RequestSaleItem all successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
        }

    }

}
