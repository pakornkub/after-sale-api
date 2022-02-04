<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class PC_TrialProductForCustomer extends REST_Controller {

    public function __construct(){

        parent::__construct();

        // Load PC_TrialProductForCustomer_Model
        $this->load->model('PC_TrialProductForCustomer_Model');

    }

    /**
     * โครงสร้างการทำงาน API
     * - form_validation (ในกรณีมีการ ส่งค่า input parameter มา)
     * - Get Data from Database
     * - generateToken (เฉพาะตอน Login เช้าใช้งาน)
     * - validateToken (ทุกครั้งที่มีการเรียกใช้ API เพื่อตรวจสอบสถานะ Login และ สิทธิ์การเข้าใช้)
     */

    /**  
     * Get Product Spec API
     * ---------------------------------
     * @method : POST 
     * @link : pc/get_product_spec
     */
    public function get_product_spec_post(){

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('inputBarcode', 'inputBarcode', 'trim|required');

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
            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // User Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if(isset($is_valid_token) && boolval($is_valid_token['status']) === true)
            {

                $user_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $user_permission = $user_token['permission'][0];

                if($user_permission['Viewer'])
                {

                    $barcode_data = [
                        'inputBarcode' => $this->input->post('inputBarcode'),
                    ];

                    // Select Product Spec Function
                    $spec_output = $this->PC_TrialProductForCustomer_Model->select_product_spec($barcode_data);

                    if(isset($spec_output) && $spec_output) {

                        // Get Product Spec Success
                        $message = [
                            'status'    => TRUE,
                            'data'      => $spec_output,
                            'message'   => 'Get Product Spec Successful'
                        ];
        
                        $this->response($message, REST_Controller::HTTP_OK);
        
                    }
                    else{
        
                        // Get Product Spec Error
                        $message = [
                            'status'    => FALSE,
                            'message'   => 'Product Spec not in Database : [Data Not Found]'
                        ];
        
                        $this->response($message, REST_Controller::HTTP_OK);
        
                    }
                }
                else
                {
                    // Permission Error
                    $message = [
                        'status'    => FALSE,
                        'message'   => 'You don’t currently have permission to Viewer'
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

    }

     /**  
     * Save Product Spec API
     * ---------------------------------
     * @method : POST 
     * @link : pc/save_product_spec
     */
    public function save_product_spec_post(){

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('Pallet_No', 'Pallet_No', 'trim|required');
        $this->form_validation->set_rules('Grade', 'Grade', 'trim|required');
        $this->form_validation->set_rules('Lot_No', 'Lot_No', 'trim|required');

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
            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // User Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if(isset($is_valid_token) && boolval($is_valid_token['status']) === true)
            {

                $user_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $user_permission = $user_token['permission'][0];

                if((!$this->input->post('TrialProductForCustomer_Index') && $user_permission['Input']) || ($this->input->post('TrialProductForCustomer_Index') && $user_permission['Edit']))
                {

                    $form_data['TrialProductForCustomer_Index'] = $this->input->post('TrialProductForCustomer_Index') ? $this->input->post('TrialProductForCustomer_Index') : null;

                    $form_data['data'] = [
                        'Pallet_No'         => $this->input->post('Pallet_No'),
                        'Grade'             => $this->input->post('Grade'),
                        'Lot_No'            => $this->input->post('Lot_No'),
                        'Number'            => $this->input->post('Number'),
                        'Bagout_No'         => $this->input->post('Bagout_No'),
                        'VAE_Qty'           => $this->input->post('VAE_Qty'),
                        'VAE_Package'       => $this->input->post('VAE_Package'),
                        'VAE_Viscosity'     => $this->input->post('VAE_Viscosity'),
                        'VAE_Solid_Content' => $this->input->post('VAE_Solid_Content'),
                        'PC_Qty'            => $this->input->post('PC_Qty'),
                        'PC_Package'        => $this->input->post('VAE_Package'), //? ใช่ตัวเดียวกันกับของ PC
                        'PC_Viscosity'      => $this->input->post('PC_Viscosity'),
                        'PC_Solid_Content'  => $this->input->post('PC_Solid_Content'),
                        'PC_RunningNumber'  => $this->input->post('PC_RunningNumber'),
                        'PC_DrumNumber'     => $this->input->post('PC_DrumNumber'),
                        'Status'            => $form_data['TrialProductForCustomer_Index'] ? 4 : 1, //? 1 = new, 4 = edit
                        'Ref_Index'         => $form_data['TrialProductForCustomer_Index'],
                        'add_by'            => $user_token['UserName'],
                        'add_date'          => date('Y-m-d H:i:s'),
                        'cancel_by'         => null,
                        'cancel_date'       => null,
                        'approve_by'        => null,
                        'approve_date'      => null,
                        'approve2_by'       => null,
                        'approve2_date'     => null,
                    ];

                    // Select List Product Spec Function and Check TrialProductForCustomer_Index
                    $list_spec_output = ($form_data['TrialProductForCustomer_Index'] == null) ? $this->PC_TrialProductForCustomer_Model->select_list_product_spec($form_data) : null;

                    if(isset($list_spec_output) && $list_spec_output)
                    {

                         // Save Product Spec Error
                         $message = [
                            'status'    => FALSE,
                            'message'   => $form_data['data']['Pallet_No'].' have already in database : [Duplicate Data]'
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    }
                    else
                    {
                        // Insert Product Spec Function
                        $insert_output = $this->PC_TrialProductForCustomer_Model->insert_product_spec($form_data);

                        if(isset($insert_output) && $insert_output) {

                            $result_output = [
                                'TrialProductForCustomer_Index_New' => $insert_output,
                                'Status'                            => $form_data['TrialProductForCustomer_Index'] ? 4 : 1,
                                'add_by'                            => $user_token['UserName'],
                                'add_date'                          => date('Y-m-d H:i:s'),
                            ];

                            // Save Product Spec Success
                            $message = [
                                'status'    => TRUE,
                                'data'      => $result_output,
                                'message'   => 'Save Product Spec Successful'
                            ];

                            $this->response($message, REST_Controller::HTTP_OK);

                        }
                        else{

                            // Save Product Spec Error
                            $message = [
                                'status'    => FALSE,
                                'message'   => 'Save Product Spec Fail : [Insert Data Fail]'
                            ];

                            $this->response($message, REST_Controller::HTTP_OK);

                        }

                    }
                    
                }
                else
                {
                    // Permission Error
                    $message = [
                        'status'    => FALSE,
                        'message'   => 'You don’t currently have permission to '.( $user_permission['Input'] ? 'Input' : 'Edit' )
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

    }

     /**  
     * Approve Product Spec API
     * ---------------------------------
     * @method : POST 
     * @link : pc/approve_product_spec
     */
    public function approve_product_spec_post(){

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('TrialProductForCustomer_Index', 'TrialProductForCustomer_Index', 'trim|required');
        $this->form_validation->set_rules('approveStatus', 'approveStatus', 'trim|required');
        
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
            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // User Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if(isset($is_valid_token) && boolval($is_valid_token['status']) === true)
            {

                $user_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $user_permission = $user_token['permission'][0];

                if(($this->input->post('approveStatus') == 3 && $user_permission['Approve1']) || ($this->input->post('approveStatus') == 2 && $user_permission['Approve2']))
                {

                    $approve_data['TrialProductForCustomer_Index'] = $this->input->post('TrialProductForCustomer_Index');

                    if($this->input->post('approveStatus') == 3)
                    {
                        $approve_data['data'] = [          
                            'Status'            => $this->input->post('approveStatus'),
                            'approve_by'        => $user_token['UserName'],
                            'approve_date'      => date('Y-m-d H:i:s'),
                        ];
                    }
                    else
                    {
                        $approve_data['data'] = [          
                            'Status'            => $this->input->post('approveStatus'),
                            'approve2_by'       => $user_token['UserName'],
                            'approve2_date'     => date('Y-m-d H:i:s'),
                        ];
                    }

                    // Update Product Spec Function
                    $update_output = $this->PC_TrialProductForCustomer_Model->update_product_spec($approve_data);

                    if(isset($update_output) && $update_output) {

                        // Approve Product Spec Success
                        $message = [
                            'status'    => TRUE,
                            'data'      => $approve_data['data'],
                            'message'   => 'Approve Product Spec Successful'
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    }
                    else{

                        // Approve Product Spec Error
                        $message = [
                            'status'    => FALSE,
                            'message'   => 'Approve Product Spec Fail : [Update Data Fail]'
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    }
                    
                }
                else
                {
                    // Permission Error
                    $message = [
                        'status'    => FALSE,
                        'message'   => 'You don’t currently have permission to Approve'
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

    }

    /**  
     * Delete Product Spec API
     * ---------------------------------
     * @method : POST 
     * @link : pc/delete_product_spec
     */
    public function delete_product_spec_post(){

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('indexItem', 'indexItem', 'trim|required');
        
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
            // Load Authorization Token Library
            $this->load->library('Authorization_Token');

            // User Token Validation
            $is_valid_token = $this->authorization_token->validateToken();

            if(isset($is_valid_token) && boolval($is_valid_token['status']) === true)
            {

                $user_token = json_decode(json_encode($this->authorization_token->userData()), true);
                $user_permission = $user_token['permission'][0];

                if($user_permission['Deleted'])
                {

                    $delete_data['TrialProductForCustomer_Index'] = $this->input->post('indexItem');

                    $delete_data['data'] = [          
                        'Status'            => -1,
                        'cancel_by'        => $user_token['UserName'],
                        'cancel_date'      => date('Y-m-d H:i:s'),
                    ];

                    // Update Product Spec Function
                    $delete_data = $this->PC_TrialProductForCustomer_Model->update_product_spec($delete_data);

                    if(isset($delete_data) && $delete_data) {

                        // Delete Product Spec Success
                        $message = [
                            'status'    => TRUE,
                            'data'      => $delete_data['data'],
                            'message'   => 'Delete Product Spec Successful'
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    }
                    else{

                        // Delete Product Spec Error
                        $message = [
                            'status'    => FALSE,
                            'message'   => 'Delete Product Spec Fail : [Update Data Fail]'
                        ];

                        $this->response($message, REST_Controller::HTTP_OK);

                    }
                    
                }
                else
                {
                    // Permission Error
                    $message = [
                        'status'    => FALSE,
                        'message'   => 'You don’t currently have permission to Deleted'
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

    }

     /**  
     * List Product Spec API
     * ---------------------------------
     * @method : POST 
     * @link : pc/list_product_spec
     */
    public function list_product_spec_post(){

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);
        
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        // User Token Validation
        $is_valid_token = $this->authorization_token->validateToken();

        if(isset($is_valid_token) && boolval($is_valid_token['status']) === true)
        {

            $user_token = json_decode(json_encode($this->authorization_token->userData()), true);
            $user_permission = $user_token['permission'][0];

            if($user_permission['Viewer'])
            {

                $search_data = [
                    'inputSearch' => $this->input->post('inputSearch'),
                ];

                // Select List Product Spec Function
                $list_output = $this->PC_TrialProductForCustomer_Model->select_list_product_spec($search_data);

                if(isset($list_output) && $list_output) {

                    // List Product Spec Success
                    $message = [
                        'status'    => TRUE,
                        'data'      => $list_output,
                        'message'   => 'Get List Product Spec Successful'
                    ];
    
                    $this->response($message, REST_Controller::HTTP_OK);
    
                }
                else{
    
                    // List Product Spec Error
                    $message = [
                        'status'    => FALSE,
                        'message'   => 'Data Search not in Database : [Data Not Found]'
                    ];
    
                    $this->response($message, REST_Controller::HTTP_OK);
    
                }
            }
            else
            {
                // Permission Error
                $message = [
                    'status'    => FALSE,
                    'message'   => 'You don’t currently have permission to Viewer'
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

}