<?php defined('BASEPATH') or exit('No direct script access allowed');

class ReceiveNo_Model extends MY_Model
{

    /**
     * Menu Type
     * ---------------------------------
     * @param : null
     */
    public function select_receive_no()
    {

        $this->set_db('default');

        $sql = "
        select dbo.[fnGetRcDocNo] (  'SP' ) as ReceiveNo
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
