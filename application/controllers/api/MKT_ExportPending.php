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
     * @method : GET 
     * @link : mkt/get_export_pending
     */
    public function get_export_pending_get(){

        header("Access-Controll-Allow-Origin: *");

        // Select Export Pending Function
        $result_output = $this->MKT_ExportPending_Model->select_export_pending();

        if(isset($spec_output) && $spec_output) {

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