<?php defined('BASEPATH') or exit('No direct script access allowed');

class ProductType_Model extends MY_Model
{

    /**
     * Menu Type
     * ---------------------------------
     * @param : null
     */
    public function select_producttype()
    {

        $this->set_db('default');

        $sql = "
            select * from ms_ProductType
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
