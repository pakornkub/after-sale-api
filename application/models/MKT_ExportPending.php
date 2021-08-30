<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MKT_ExportPending_Model extends MY_Model {

    /**
     * Select Export Pending
     * ---------------------------------
     * @param : {array} null
     */
    public function select_export_pending($param = []){

        $this->set_db('default');

        $sql = "

            select * from tb_ExportPending order by ExportPending_Index 
           
        ";

        $query = $this->db->query($sql);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }

}