<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_Model extends MY_Model {

    /**
     * User
     * ---------------------------------
     * @param : null
     */
    public function select_user(){

        $this->set_db('default');

        $sql = "
           select * from se_User
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}