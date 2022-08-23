<?php defined('BASEPATH') or exit('No direct script access allowed');

class GradeSP_Model extends MY_Model
{

    /**
     * Menu Type
     * ---------------------------------
     * @param : null
     */
    public function select_grade_sp()
    {

        $this->set_db('default');

        $sql = "
            select * from ms_Item where Product_ID in ('2','3') and Status = '1'
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
