<?php defined('BASEPATH') or exit('No direct script access allowed');

class StockMonitor_Model extends MY_Model
{

    
    /**
     * Stock Group
     * ---------------------------------
     * @param : null
     */
    public function select_stockgroup($param)
    {

        $this->set_db('default');

        $sql = "
        select * from View_Stock_Group $param

            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

    /**
     * Stock Detail
     * ---------------------------------
     * @param : null
     */
    public function select_stockdetail($param)
    {

        $this->set_db('default');

        $sql = "
        select * from View_Stock_Detail $param

            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }
    
}

