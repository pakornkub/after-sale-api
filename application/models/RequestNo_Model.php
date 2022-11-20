<?php defined('BASEPATH') or exit('No direct script access allowed');

class RequestNo_Model extends MY_Model
{

    /**
     * Request no
     * ---------------------------------
     * @param : null
     */
    public function select_request_no($param)
    {

        $this->set_db('default');

        $sql = "
        select dbo.[fnGetRqDocNo] ('$param') as RequestNo
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
