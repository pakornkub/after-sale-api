<?php defined('BASEPATH') or exit('No direct script access allowed');

class Tag_Model extends MY_Model
{

    /**
     * Tag
     * ---------------------------------
     * @param : null
     */
    public function select_tag($param = [])
    {

        $this->set_db('default');

        $sql = "
            select * from View_TagQR where Rec_ID = ?
        ";

        $query = $this->db->query($sql,$param['Rec_ID']);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Insert Tag
     * ---------------------------------
     * @param : FormData
     */
    public function insert_tag($param = [])
    {
        $this->set_db('default');

        $sql = "

        exec [dbo].[SP_CreateReceiveTag]  ?,?
          
        ";

        return $this->db->query($sql,[$param['Rec_NO'],$param['username']]) ? true : false;
    }


     /**
     * Delete Tag
     * ---------------------------------
     * @param : Tag_Index
     */
    public function delete_tag($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_TagQR', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }


}
