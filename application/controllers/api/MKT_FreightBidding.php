<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class MKT_FreightBidding extends REST_Controller {

    public function __construct(){

        parent::__construct();

        // Load MKT_FreightBidding_Model
        $this->load->model('MKT_FreightBidding_Model');

    }

    /**
     * โครงสร้างการทำงาน API
     * - form_validation (ในกรณีมีการ ส่งค่า input parameter มา)
     * - Get Data from Database
     * - generateToken (เฉพาะตอน Login เช้าใช้งาน)
     * - validateToken (ทุกครั้งที่มีการเรียกใช้ API เพื่อตรวจสอบสถานะ Login และ สิทธิ์การเข้าใช้)
     */

    /**  
     * Get Port Country API
     * ---------------------------------
     * @method : GET 
     * @link : mkt/get_port_country
     */
    public function get_port_country_get(){

        header("Access-Control-Allow-Origin: *");

        // Select Port Function
        $result_output = $this->MKT_FreightBidding_Model->select_port_country();

        if(isset($result_output) && $result_output) {

            // Get Port Country Success
            $message = [
                'status'    => TRUE,
                'data'      => $result_output,
                'message'   => 'Get Port Country Successful'
            ];

            $this->response($message, REST_Controller::HTTP_OK);

        }
        else{

            // Get Port Country Error
            $message = [
                'status'    => FALSE,
                'message'   => 'Port Country not in Database : [Data Not Found]'
            ];

            $this->response($message, REST_Controller::HTTP_OK);

        }

    }

    /**  
     * Get Vessel API
     * ---------------------------------
     * @method : GET 
     * @link : mkt/get_vessel
     */
    public function get_vessel_get(){

        header("Access-Control-Allow-Origin: *");

        // Select Vessel Function
        $result_output = $this->MKT_FreightBidding_Model->select_vessel();

        if(isset($result_output) && $result_output) {

            // Get Vessel Success
            $message = [
                'status'    => TRUE,
                'data'      => $result_output,
                'message'   => 'Get Vessel Successful'
            ];

            $this->response($message, REST_Controller::HTTP_OK);

        }
        else{

            // Get Vessel Error
            $message = [
                'status'    => FALSE,
                'message'   => 'Vessel not in Database : [Data Not Found]'
            ];

            $this->response($message, REST_Controller::HTTP_OK);

        }

    }

    /**  
     * Get Freight Bidding API
     * ---------------------------------
     * @method : POST 
     * @link : mkt/get_freight_bidding
     */
    public function get_freight_bidding_post(){

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('startDate', 'startDate', 'trim');
        $this->form_validation->set_rules('endDate', 'endDate', 'trim');

        if ($this->form_validation->run() == FALSE)
        {
            // Form Validation Error
            $message = [
                'status'    => FALSE,
                'error'     => $this->form_validation->error_array(),
                'message'   => validation_errors()
            ];

            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        else
        {
            $form_data = [
                "startDate" => $this->input->post('startDate'), 
                "endDate"   => $this->input->post('endDate'),
            ];

            // Select Freight Bidding Function
            $result_output = $this->MKT_FreightBidding_Model->select_freight_bidding($form_data);

            if(isset($result_output) && $result_output) {

                // Get Freight Bidding Success
                $message = [
                    'status'    => TRUE,
                    'data'      => $result_output,
                    'message'   => 'Get Freight Bidding Successful'
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
            else{

                // Get Freight Bidding Error
                $message = [
                    'status'    => FALSE,
                    'message'   => 'Freight Bidding not in Database : [Data Not Found]'
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
        }

    }

    /**  
     * Save Freight Bidding API
     * ---------------------------------
     * @method : POST 
     * @link : pc/save_freight_bidding
     */
    public function save_freight_bidding_post(){

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('Quotation_No', 'Quotation_No', 'trim|required');
        $this->form_validation->set_rules('PortCountry_Index', 'PortCountry_Index', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            // Form Validation Error
            $message = [
                'status'    => FALSE,
                'error'     => $this->form_validation->error_array(),
                'message'   => validation_errors()
            ];

            $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        else
        {   
            $param_quotation_no = [
                'PortCountry_Index' => $this->input->post('PortCountry_Index'),
                'Quotation_No'=> $this->input->post('Quotation_No')
            ];

            $result_quotation_no = $this->MKT_FreightBidding_Model->select_quotation_no($param_quotation_no);

            if(isset($result_quotation_no) && $result_quotation_no && !$this->input->post('FreightBidding_Index')) {

                // Check Quotation No Fail
                $message = [
                    'status'    => FALSE,
                    'message'   => 'Check Quotation No Fail : [Data Duplicate]'
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
            else{

                $form_data['FreightBidding_Index'] = $this->input->post('FreightBidding_Index') ? $this->input->post('FreightBidding_Index') : null;

                $form_data['data_header'] = [
                    'PortCountry_Index' => $this->input->post('PortCountry_Index'),
                    'Transit'           => $this->input->post('Transit'),
                    'Time_Day'          => $this->input->post('Time_Day'),
                    'Valid_Until'       => $this->input->post('Valid_Until') ? $this->input->post('Valid_Until') : null,
                    'Quoter'            => $this->input->post('Quoter'),
                    'Quotation'         => $this->input->post('Quotation'),
                    'Quotation_No'      => $this->input->post('Quotation_No'),
                    'Vessel_Index'      => $this->input->post('Vessel_Index'),
                    'Status'            => $form_data['FreightBidding_Index'] ? 4 : 1, //? 1 = new, 4 = edit
                    'Ref_Index'         => $form_data['FreightBidding_Index'],
                    'Rev'               => $this->input->post('Status') ? intval($this->input->post('Rev')) + 1 : 0,
                    'add_by'            => null,
                    'add_date'          => date('Y-m-d H:i:s'),
                    'cancel_by'         => null,
                    'cancel_date'       => null
                ];

                $con_num=1;
                $con_count=0;

                while($con_num <= 2)
                {
                    $con = '_con'.$con_num;
                
                    $form_data['data_detail'.$con] = [
                        'Con_Type'              => $this->input->post('Con_Type'.$con),
                        'Freight'               => floatval($this->input->post('Freight'.$con)),
                        'LSS'                   => floatval($this->input->post('LSS'.$con)),
                        'WSC'                   => floatval($this->input->post('WSC'.$con)),
                        'THC'                   => floatval($this->input->post('THC'.$con)),
                        'BL'                    => floatval($this->input->post('BL'.$con)),
                        'Seal'                  => floatval($this->input->post('Seal'.$con)),
                        'Space'                 => floatval($this->input->post('Space'.$con)),
                        'Credit_Term'           => floatval($this->input->post('Credit_Term'.$con)),
                        'DEM'                   => floatval($this->input->post('DEM'.$con)),
                        'DET'                   => floatval($this->input->post('DET'.$con)),
                        'STO'                   => floatval($this->input->post('STO'.$con)),
                        'Status'                => $form_data['FreightBidding_Index'] ? 4 : 1, //? 1 = new, 4 = edit,
                        'add_by'                => null,
                        'add_date'              => date('Y-m-d H:i:s'),
                        'cancel_by'             => null,
                        'cancel_date'           => null
                    ];

                    $con_num++;
                }

                // Insert Freight Bidding Function
                $insert_output = $this->MKT_FreightBidding_Model->insert_freight_bidding($form_data);

                if(isset($insert_output) && $insert_output) {

                    $result_output = [
                        'FreightBidding_Index_New'          => $insert_output,
                        'Rev'                               => $this->input->post('Status') ? intval($this->input->post('Rev')) + 1 : 0,
                        'Status'                            => $form_data['FreightBidding_Index'] ? 4 : 1,
                        'add_by'                            => null,
                        'add_date'                          => date('Y-m-d H:i:s'),
                    ];

                    // Save Freight Bidding Success
                    $message = [
                        'status'    => TRUE,
                        'data'      => $result_output,
                        'message'   => 'Save Freight Bidding Successful'
                    ];

                    $this->response($message, REST_Controller::HTTP_OK);

                }
                else{

                    // Save Freight Bidding Error
                    $message = [
                        'status'    => FALSE,
                        'message'   => 'Save Freight Bidding Fail : [Insert Data Fail]'
                    ];

                    $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

                }
            }
           
        }

    }


}