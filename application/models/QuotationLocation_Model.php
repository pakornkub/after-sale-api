<?php defined('BASEPATH') or exit('No direct script access allowed');

class QuotationLocation_Model extends MY_Model
{

    /**
     * receive no
     * ---------------------------------
     * @param : null
     */
    public function select_quotation_location($param)
    {

        $this->set_db('default');

        $sql = "
        select * from View_Quotation where Location = '$param'
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
