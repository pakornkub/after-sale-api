<?php defined('BASEPATH') or exit('No direct script access allowed');

class Bom_Model extends MY_Model
{

    /**
     * Bom
     * ---------------------------------
     * @param : null
     */
    public function select_bom()
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
     * Insert Bom
     * ---------------------------------
     * @param : FormData
     */
    public function insert_bom($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('ms_BOM', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

     /**
     * Update Bom
     * ---------------------------------
     * @param : FormData
     */
    public function update_bom($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('ms_Item', $param['data'], ['ITEM_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete Bom
     * ---------------------------------
     * @param : Bom_Index
     */
    public function delete_bom($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('ms_Item', ['ITEM_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }


}
