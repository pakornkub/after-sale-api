<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class MKT_ExportPending extends REST_Controller {

    public function __construct(){

        parent::__construct();

        // Load MKT_ExportPending_Model
        $this->load->model('MKT_ExportPending_Model');

    }

    /**
     * โครงสร้างการทำงาน API
     * - form_validation (ในกรณีมีการ ส่งค่า input parameter มา)
     * - Get Data from Database
     * - generateToken (เฉพาะตอน Login เช้าใช้งาน)
     * - validateToken (ทุกครั้งที่มีการเรียกใช้ API เพื่อตรวจสอบสถานะ Login และ สิทธิ์การเข้าใช้)
     */

    /**  
     * Get Export Pending API
     * ---------------------------------
     * @method : POST 
     * @link : mkt/get_export_pending
     */
    public function get_export_pending_post(){

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering  (https://codeigniter.com/userguide3/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation (https://codeigniter.com/userguide3/libraries/form_validation.html)
        $this->form_validation->set_rules('startDate', 'startDate', 'trim');
        $this->form_validation->set_rules('endDate', 'endDate', 'trim');
        $this->form_validation->set_rules('priority', 'priority', 'trim');

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
                "priority"  => $this->input->post('priority'),
            ];

            // Select Export Pending Function
            $result_output = $this->MKT_ExportPending_Model->select_export_pending($form_data);

            if(isset($result_output) && $result_output) {

                // Get Export Pending Success
                $message = [
                    'status'    => TRUE,
                    'data'      => $result_output,
                    'message'   => 'Get Export Pending Successful'
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
            else{

                // Get Export Pending Error
                $message = [
                    'status'    => FALSE,
                    'message'   => 'Export Pending not in Database : [Data Not Found]'
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
        }

    }

}