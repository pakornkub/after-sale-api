<?php defined('BASEPATH') or exit('No direct script access allowed');

class CheckStock_Model extends MY_Model
{

    /**
     * CheckStock
     * ---------------------------------
     * @param : Tag_ID
     */
    public function select_check_stock($Tag_ID)
    {

        $this->set_db('default');

        $sql = "
            select  * 
            from    View_StockBalance
            where   Tag_ID = ?
        ";

        $query = $this->db->query($sql, [$Tag_ID]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
