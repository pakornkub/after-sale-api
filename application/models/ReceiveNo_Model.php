<?php defined('BASEPATH') or exit('No direct script access allowed');

class ReceiveNo_Model extends MY_Model
{

    /**
     * receive no
     * ---------------------------------
     * @param : null
     */
    public function select_receive_no($param)
    {

        $this->set_db('default');

        $sql = "
        select dbo.[fnGetRcDocNo] ('$param') as ReceiveNo
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
