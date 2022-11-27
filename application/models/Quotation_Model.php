<?php defined('BASEPATH') or exit('No direct script access allowed');

class Quotation_Model extends MY_Model
{

    /**
     * Menu Type
     * ---------------------------------
     * @param : null
     */
    public function select_quotation()
    {

        $this->set_db('default');

        $sql = "
            select * from View_Quotation
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
