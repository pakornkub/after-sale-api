<?php defined('BASEPATH') or exit('No direct script access allowed');

class Group_Model extends MY_Model
{

    /**
     * Group
     * ---------------------------------
     * @param : null
     */
    public function select_group()
    {

        $this->set_db('auth');

        $sql = "
           select * from se_Group
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Insert Group
     * ---------------------------------
     * @param : FormData
     */
    public function insert_group($param = [])
    {
        $this->set_db('auth');

        return ($this->db->insert('se_Group', $param['data'])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Update Group
     * ---------------------------------
     * @param : FormData
     */
    public function update_group($param = [])
    {
        $this->set_db('auth');

        return ($this->db->update('se_Group', $param['data'], ['Group_Index'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete Group
     * ---------------------------------
     * @param : Group_Index
     */
    public function delete_group($param = [])
    {
        $this->set_db('auth');

        return ($this->db->delete('se_Group', ['Group_Index'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }


}
