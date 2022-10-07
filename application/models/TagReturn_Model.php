<?php defined('BASEPATH') or exit('No direct script access allowed');

class TagReturn_Model extends MY_Model
{

    /**
     * Receive Status
     * ---------------------------------
     * @param : null
     */
    public function select_receivestatus($param = [])
    {

        $this->set_db('default');

        $sql = "
            select * from Tb_Receive where Rec_ID = ?
        ";

        $query = $this->db->query($sql,$param['Rec_ID']);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    } 
    /**
     * Tag
     * ---------------------------------
     * @param : null
     */
    public function select_tag($param = [])
    {

        $this->set_db('default');

        $sql = "
            select * from View_TagQR where Rec_ID = ? order by QR_NO ASC
        ";

        $query = $this->db->query($sql,$param['Rec_ID']);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Insert Tag Return
     * ---------------------------------
     * @param : FormData
     */
    public function insert_tag($param = [])
    {
        $this->set_db('default');

        $sql = "

        exec [dbo].[SP_CreateReturnTag]  ?,?
          
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

        $this->db->update('Tb_Receive', $param['data1'], ['Rec_ID'=> $param['index']]) ? true : false;
        
        return ($this->db->update('Tb_TagQR', $param['data'], ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     



}
