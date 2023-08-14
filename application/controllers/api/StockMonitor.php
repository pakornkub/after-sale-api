<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class StockMonitor extends REST_Controller
{

    protected $MenuId = 'StockMonitor';

    public function __construct()
    {

        parent::__construct();

        // Load StockMonitor
        $this->load->model('StockMonitor_Model');

    }

    /**
     * Show Stock Group API
     * ---------------------------------
     * @method : POST
     * @link : stockmonitor/stockgroup
     */
    public function stockgroup_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        $is_valid_token = $this->authorization_token->validateToken();
        
            $Filter = $this->input->post('Filter');

            // $tag_data = [
            //     'Rec_ID' => $this->input->post('Rec_ID'),
               
            // ];

            $output = $this->StockMonitor_Model->select_stockgroup($Filter);

            if (isset($output) && $output) {

                // Show Group All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Stock Group successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
    }

    /**
     * Show Stock Detail API
     * ---------------------------------
     * @method : POST
     * @link : stockmonitor/stockdetail
     */
    public function stockdetail_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        $is_valid_token = $this->authorization_token->validateToken();
        
            $Filter = $this->input->post('Filter');

            // $tag_data = [
            //     'Rec_ID' => $this->input->post('Rec_ID'),
               
            // ];

            $output = $this->StockMonitor_Model->select_stockdetail($Filter);

            if (isset($output) && $output) {

                // Show Detail All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Stock Detail successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
    }

    /**
     * Show Stock WH API
     * ---------------------------------
     * @method : POST
     * @link : stockmonitor/stockwh
     */
    public function stockwh_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        $is_valid_token = $this->authorization_token->validateToken();
        
            $Filter = $this->input->post('Filter');

            // $tag_data = [
            //     'Rec_ID' => $this->input->post('Rec_ID'),
               
            // ];

            $output = $this->StockMonitor_Model->select_stockwh($Filter);

            if (isset($output) && $output) {

                // Show Detail All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Stock WH successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
    }

    /**
     * Show Stock WH API
     * ---------------------------------
     * @method : POST
     * @link : stockmonitor/stockwhheader
     */
    public function stockwhheader_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        $is_valid_token = $this->authorization_token->validateToken();
        
            $Filter = $this->input->post('Filter');

            // $tag_data = [
            //     'Rec_ID' => $this->input->post('Rec_ID'),
               
            // ];

            $output = $this->StockMonitor_Model->select_stockwhheader($Filter);

            if (isset($output) && $output) {

                // Show Detail All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Stock WH Header successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
    }

    /**
     * Show Report Issue API
     * ---------------------------------
     * @method : POST
     * @link : stockmonitor/reportissue
     */
    public function reportissue_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        $is_valid_token = $this->authorization_token->validateToken();
        
            $Filter = $this->input->post('Filter');

            // $tag_data = [
            //     'Rec_ID' => $this->input->post('Rec_ID'),
               
            // ];

            $output = $this->StockMonitor_Model->select_reportissue($Filter);

            if (isset($output) && $output) {

                // Show Detail All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show report issue successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
    }


    /**
     * Show Stock Grade API
     * ---------------------------------
     * @method : POST
     * @link : stockmonitor/stockgrade
     */
    public function stockgrade_post()
    {
        header("Access-Control-Allow-Origin: *");

        $_POST = $this->security->xss_clean($_POST);

        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        $is_valid_token = $this->authorization_token->validateToken();
        
            $Filter = $this->input->post('Filter');

            // $tag_data = [
            //     'Rec_ID' => $this->input->post('Rec_ID'),
               
            // ];

            $output = $this->StockMonitor_Model->select_stockgrade($Filter);

            if (isset($output) && $output) {

                // Show Detail All Success
                $message = [
                    'status' => true,
                    'data' => $output,
                    'message' => 'Show Grade successful',
                ];

                $this->response($message, REST_Controller::HTTP_OK);

            }
    }

}

    

