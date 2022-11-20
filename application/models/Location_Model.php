<?php defined('BASEPATH') or exit('No direct script access allowed');

class Location_Model extends MY_Model
{

    /**
     * Menu Type
     * ---------------------------------
     * @param : null
     */
    public function select_location()
    {

        $this->set_db('default');

        $sql = "
            select * from ms_Location
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
