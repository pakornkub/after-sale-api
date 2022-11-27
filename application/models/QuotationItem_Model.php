<?php defined('BASEPATH') or exit('No direct script access allowed');

class QuotationItem_Model extends MY_Model
{

    /**
     * receive no
     * ---------------------------------
     * @param : null
     */
    public function select_quotation_item($param)
    {

        $this->set_db('default');

        $sql = "
        select * from View_ItemForQuotation where Withdraw_No = '$param'
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
