<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Reprint extends REST_Controller
{

    protected $MenuId = 'Reprint';

    public function __construct()
    {

        parent::__construct();

        // Load Reprint
        $this->load->model('Reprint_Model');

    }



    /**
     * Show Stock Detail API
     * ---------------------------------
     * @method : POST
     * @link : reprint/qrcode
     */
    public function qrcode_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        $is_valid_token = $this->authorization_token->validateToken();
        
            $Qrcode = $this->input->post('Qrcode');

            // $tag_data = [
            //     'Rec_ID' => $this->input->post('Rec_ID'),
               
            // ];

            $output = $this->Reprint_Model->select_qrcode($Qrcode);

            if (isset($output) && $output) {

                // Show Detail All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show QR Code successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
    }

   

}

    

