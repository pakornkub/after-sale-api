<?php defined('BASEPATH') or exit('No direct script access allowed');

class RequestType_Model extends MY_Model
{

    /**
     * Menu Type
     * ---------------------------------
     * @param : null
     */
    public function select_request_type()
    {

        $this->set_db('default');

        $sql = "
        select * from ms_RequestType
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
