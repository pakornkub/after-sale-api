<?php defined('BASEPATH') or exit('No direct script access allowed');

class User_Model extends MY_Model
{

    /**
     * User
     * ---------------------------------
     * @param : null
     */
    public function select_user()
    {

        $this->set_db('auth');

        $sql = "
           select Title+' '+FirstName+' '+LastName as FullName ,* from se_User
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Insert User
     * ---------------------------------
     * @param : FormData
     */
    public function insert_user($param = [])
    {
        $this->set_db('auth');

        return ($this->db->insert('se_User', $param['data'])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Update User
     * ---------------------------------
     * @param : FormData
     */
    public function update_user($param = [])
    {
        $this->set_db('auth');

        return ($this->db->update('se_User', $param['data'], ['User_Index'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete User
     * ---------------------------------
     * @param : User_Index
     */
    public function delete_user($param = [])
    {
        $this->set_db('auth');

        return ($this->db->delete('se_User', ['User_Index'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }


}
