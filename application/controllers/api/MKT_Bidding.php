<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class MKT_Bidding extends REST_Controller {

    public function __construct(){

        parent::__construct();

        // Load MKT_Bidding_Model
        $this->load->model('MKT_Bidding_Model');

    }

    /**
     * โครงสร้างการทำงาน API
     * - form_validation (ในกรณีมีการ ส่งค่า input parameter มา)
     * - Get Data from Database
     * - generateToken (เฉพาะตอน Login เช้าใช้งาน)
     * - validateToken (ทุกครั้งที่มีการเรียกใช้ API เพื่อตรวจสอบสถานะ Login และ สิทธิ์การเข้าใช้)
     */

    /**  
     * Get Port API
     * ---------------------------------
     * @method : GET 
     * @link : mkt/get_port
     */
    public function get_port_get(){

        header("Access-Control-Allow-Origin: *");

        // Select Port Function
        $result_output = $this->MKT_Bidding_Model->select_port();

        if(isset($result_output) && $result_output) {

            // Get Export Pending Success
            $message = [
                'status'    => TRUE,
                'data'      => $result_output,
                'message'   => 'Get Port Successful'
            ];

            $this->response($message, REST_Controller::HTTP_OK);

        }
        else{

            // Get Export Pending Error
            $message = [
                'status'    => FALSE,
                'message'   => 'Port not in Database : [Data Not Found]'
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

        // Select Port Function
        $result_output = $this->MKT_Bidding_Model->select_vessel();

        if(isset($result_output) && $result_output) {

            // Get Export Pending Success
            $message = [
                'status'    => TRUE,
                'data'      => $result_output,
                'message'   => 'Get Vessel Successful'
            ];

            $this->response($message, REST_Controller::HTTP_OK);

        }
        else{

            // Get Export Pending Error
            $message = [
                'status'    => FALSE,
                'message'   => 'Vessel not in Database : [Data Not Found]'
            ];

            $this->response($message, REST_Controller::HTTP_OK);

        }

    }

    /**  
     * Get Sea Freight API
     * ---------------------------------
     * @method : POST 
     * @link : mkt/get_sea_freight
     */
    public function get_sea_freight_post(){

        header("Access-Control-Allow-Origin: *");

        // Select Port Function
        $result_output = $this->MKT_Bidding_Model->select_sea_freight();

        if(isset($result_output) && $result_output) {

            // Get Export Pending Success
            $message = [
                'status'    => TRUE,
                'data'      => $result_output,
                'message'   => 'Get Sea Freight Successful'
            ];

            $this->response($message, REST_Controller::HTTP_OK);

        }
        else{

            // Get Export Pending Error
            $message = [
                'status'    => FALSE,
                'message'   => 'Sea Freight not in Database : [Data Not Found]'
            ];

            $this->response($message, REST_Controller::HTTP_OK);

        }

    }


    /**  
     * Save Bidding API
     * ---------------------------------
     * @method : POST 
     * @link : pc/save_bidding
     */
    public function save_bidding_post(){

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('Port', 'Port', 'trim|required');

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
                    $form_data['SeaFreight_Index'] = $this->input->post('SeaFreight_Index') ? $this->input->post('SeaFreight_Index') : null;

                    $form_data['data_header'] = [
                        'PortCountry_Index' => $this->input->post('Port'),
                        'Transit'           => $this->input->post('Transit'),
                        'Time_Day'          => $this->input->post('Time_Day'),
                        'Valid_Until'       => $this->input->post('Valid_Until'),
                        'Quoter'            => $this->input->post('Quoter'),
                        'Quotation'         => $this->input->post('Quotation'),
                        'Quotation_No'      => $this->input->post('Quotation_No'),
                        'Vessel_Index'      => $this->input->post('Vessel'),
                        'Status'            => $form_data['SeaFreight_Index'] ? 4 : 1, //? 1 = new, 4 = edit
                        'Ref_Index'         => $form_data['SeaFreight_Index'],
                        'add_by'            => null,
                        'add_date'          => date('Y-m-d H:i:s'),
                        'cancel_by'         => null,
                        'cancel_date'       => null
                    ];

                    $form_data['SeaFreight_Index_New'] = $this->MKT_Bidding_Model->insert_sea_freight($form_data);

                    $con_num=1;
                    $con_count=0;

                    if($form_data['SeaFreight_Index_New'])
                    {
                    
                        while($con_num <= 2)
                        {
                            $con = '_con'.$con_num;
                        
                            $form_data['data_detail'] = [
                                'SeaFreight_Index'  => $form_data['SeaFreight_Index_New'],
                                'Con_Type'          => $this->input->post('Con_Type'.$con),
                                'Freight'           => floatval($this->input->post('Freight'.$con)),
                                'LSS'               => floatval($this->input->post('LSS'.$con)),
                                'WSC'               => floatval($this->input->post('WSC'.$con)),
                                'THC'               => floatval($this->input->post('THC'.$con)),
                                'BL'                => floatval($this->input->post('BL'.$con)),
                                'Seal'              => floatval($this->input->post('Seal'.$con)),
                                'Space'             => floatval($this->input->post('Space'.$con)),
                                'Credit_Term'       => floatval($this->input->post('Credit_Term'.$con)),
                                'DEM'               => floatval($this->input->post('DEM'.$con)),
                                'DET'               => floatval($this->input->post('DET'.$con)),
                                'STO'               => floatval($this->input->post('STO'.$con)),
                                'Status'            => $form_data['SeaFreight_Index'] ? 4 : 1, //? 1 = new, 4 = edit,
                                'add_by'            => null,
                                'add_date'          => date('Y-m-d H:i:s'),
                                'cancel_by'         => null,
                                'cancel_date'       => null
                            ];

                            $con_count += $this->MKT_Bidding_Model->insert_sea_freight_item($form_data);

                            $con_num++;
                        }

                    }

                    $message = [
                        'status'    => TRUE,
                        'data'      => $form_data['SeaFreight_Index_New'],
                        'message'   => 'Save Product Spec Successful'
                    ];

                    $this->response($message, REST_Controller::HTTP_OK);

                   




                
                    
            

           
        }

    }


}