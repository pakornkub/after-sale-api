<?php defined('BASEPATH') or exit('No direct script access allowed');

class Menu_Model extends MY_Model
{

    /**
     * Menu
     * ---------------------------------
     * @param : null
     */
    public function select_menu()
    {

        $this->set_db('default');

        $sql = "
           select * from se_Menu
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Insert Menu
     * ---------------------------------
     * @param : FormData
     */
    public function insert_menu($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('se_Menu', $param['data'])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Update Menu
     * ---------------------------------
     * @param : FormData
     */
    public function update_menu($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('se_Menu', $param['data'], ['Menu_Index'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete Menu
     * ---------------------------------
     * @param : Menu_Index
     */
    public function delete_menu($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('se_Menu', ['Menu_Index'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }


}
