<?php defined('BASEPATH') or exit('No direct script access allowed');

class LocationTeam_Model extends MY_Model
{

    /**
     * Request no
     * ---------------------------------
     * @param : null
     */
    public function select_location_team($param)
    {

        $this->set_db('default');

        $sql = "
        select * from ms_Location where Unit =  '$param'
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
