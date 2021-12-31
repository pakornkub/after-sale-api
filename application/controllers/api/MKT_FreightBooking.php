<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class MKT_FreightBooking extends REST_Controller {

    public function __construct(){

        parent::__construct();

        // Load MKT_FreightBooking_Model
        $this->load->model('MKT_FreightBooking_Model');

    }

    /**
     * โครงสร้างการทำงาน API
     * - form_validation (ในกรณีมีการ ส่งค่า input parameter มา)
     * - Get Data from Database
     * - generateToken (เฉพาะตอน Login เช้าใช้งาน)
     * - validateToken (ทุกครั้งที่มีการเรียกใช้ API เพื่อตรวจสอบสถานะ Login และ สิทธิ์การเข้าใช้)
     */

    /**  
     * Get Shipment Status API
     * ---------------------------------
     * @method : GET 
     * @link : mkt/get_shipment_status
     */
    public function get_shipment_status_get(){

        header("Access-Control-Allow-Origin: *");

        // Select Shipment Status Function
        $result_output = $this->MKT_FreightBooking_Model->select_shipment_status();

        if(isset($result_output) && $result_output) {

            // Get Shipment Status Success
            $message = [
                'status'    => TRUE,
                'data'      => $result_output,
                'message'   => 'Get Shipment Status Successful'
            ];

            $this->response($message, REST_Controller::HTTP_OK);

        }
        else{

            // Get Shipment Status Error
            $message = [
                'status'    => FALSE,
                'message'   => 'Shipment Status not in Database : [Data Not Found]'
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
        $this->form_validation->set_rules('Port', 'Port', 'trim');
        $this->form_validation->set_rules('Country', 'Country', 'trim');

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
                "Port"      => $this->input->post('Port'), 
                "Country"   => $this->input->post('Country'),
            ];

            // Select Freight Bidding Function
            $result_output = $this->MKT_FreightBooking_Model->select_freight_bidding($form_data);

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
     * Save Freight Booking API
     * ---------------------------------
     * @method : POST 
     * @link : pc/save_freight_booking
     */
    public function save_freight_booking_post(){

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('Shipped_By', 'Shipped_By', 'trim|required');
        $this->form_validation->set_rules('Vessel_Name', 'Vessel_Name', 'trim|required');
        $this->form_validation->set_rules('Booking_No', 'Booking_No', 'trim|required');

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
            $form_data['FreightBooking_Index'] = $this->input->post('FreightBooking_Index') ? $this->input->post('FreightBooking_Index') : null;

            $form_data['data'] = [
                'FreightBidding_Index'  => $this->input->post('FreightBidding_Index'),
                'ExportPending_Index'   => $this->input->post('ExportPending_Index'),
                'Shipped_By'            => $this->input->post('Shipped_By'),
                'Vessel_Name'           => $this->input->post('Vessel_Name'),
                'Booking_No'            => $this->input->post('Booking_No'),
                'BL_On_Board'           => $this->input->post('BL_On_Board'),
                'ETD_BKK_LCB'           => $this->input->post('ETD_BKK_LCB'),
                'ETA_DEAST'             => $this->input->post('ETA_DEAST'),
                'Stuffing_Date'         => $this->input->post('Stuffing_Date'),
                'CY_Date'               => $this->input->post('CY_Date'),
                'CY_Location'           => $this->input->post('CY_Location'),
                'CY_RT_Date'            => $this->input->post('CY_RT_Date'),
                'CY_RT_Location'        => $this->input->post('CY_RT_Location'),
                'Closing_Date'          => $this->input->post('Closing_Date'),
                'Special_Condition'     => $this->input->post('Special_Condition'),
                'Remark'                => $this->input->post('Remark'),
                'Con_20'                => $this->input->post('Con_20'),
                'Con_40'                => $this->input->post('Con_40'),
                'SH_CO'                 => $this->input->post('SH_CO'),
                'Quotation_No'          => $this->input->post('Quotation_No'),
                'Status'                => $form_data['FreightBooking_Index'] ? 4 : 1, //? 1 = new, 4 = edit
                'Ref_Index'             => $form_data['FreightBooking_Index'],
                'Rev'                   => $this->input->post('Status') ? intval($this->input->post('Rev')) + 1 : 0,
                'add_by'                => null,
                'add_date'              => date('Y-m-d H:i:s'),
                'cancel_by'             => null,
                'cancel_date'           => null,
            ];

            // Insert Freight Booking Function
            $insert_output = $this->MKT_FreightBooking_Model->insert_freight_booking($form_data);

            if(isset($insert_output) && $insert_output) {

                $result_output = [
                    'FreightBooking_Index_New'          => $insert_output,
                    'Rev'                               => $this->input->post('Status') ? intval($this->input->post('Rev')) + 1 : 0,
                    'Status'                            => $form_data['FreightBooking_Index'] ? 4 : 1,
                    'add_by'                            => null,
                    'add_date'                          => date('Y-m-d H:i:s'),
                ];

                // Save Freight Booking Success
                $message = [
                    'status'    => TRUE,
                    'data'      => $result_output,
                    'message'   => 'Save Freight Booking Successful'
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
            else{

                // Save Freight Booking Error
                $message = [
                    'status'    => FALSE,
                    'message'   => 'Save Freight Booking Fail : [Insert Data Fail]'
                ];

                $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

            }
           
        }

    }    

}