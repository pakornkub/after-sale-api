<?php defined('BASEPATH') or exit('No direct script access allowed');

class Grade_Model extends MY_Model
{

    /**
     * Grade
     * ---------------------------------
     * @param : null
     */
    public function select_grade()
    {

        $this->set_db('default');

        $sql = "
        select ms_Item.*,CONVERT(varchar,ms_Item.Create_Date,103) as Add_Date,ms_ProductType.Product_DESCRIPTION from ms_Item
        inner join ms_ProductType on ms_Item.Product_ID = ms_ProductType.Product_ID
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Insert Grade
     * ---------------------------------
     * @param : FormData
     */
    public function insert_grade($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('ms_Item', $param['data'])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Update Grade
     * ---------------------------------
     * @param : FormData
     */
    public function update_grade($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('ms_Item', $param['data'], ['ITEM_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete Grade
     * ---------------------------------
     * @param : Grade_Index
     */
    public function delete_grade($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('ms_Item', ['ITEM_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

    /**
     * CheckGrade
     * ---------------------------------
     * @param : null
     */             
    public function check_grade($param = [])
    {
        $this->set_db('default');

        $sql = "

        select ITEM_CODE from ms_Item where ITEM_CODE = ?
            
        ";

        $query = $this->db->query($sql,$param['data']['ITEM_CODE']);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

}
