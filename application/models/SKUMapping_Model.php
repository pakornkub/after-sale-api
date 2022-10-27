<?php defined('BASEPATH') or exit('No direct script access allowed');

class SKUMapping_Model extends MY_Model
{

    /**
     * SKUMapping
     * ---------------------------------
     * @param : null
     */
    public function select_skumapping()
    {

        $this->set_db('default');

        $sql = "
        select SKUMapping_ID,CONVERT(varchar,SKUMapping_Date,103) as SKUMapping_Date1,SKUMapping_Date,ms_Item.ITEM_ID,ms_Item.ITEM_CODE,Remark,ms_SKUMapping.Status,QTY,
        ms_SKUMapping.Create_By,CONVERT(varchar,ms_SKUMapping.Create_Date,103) as Add_Date,SP_ITEM_ID,sp.ITEM_CODE as ITEM_CODE_SP
        from ms_SKUMapping
        left join ms_Item on ms_SKUMapping.FG_ITEM_ID = ms_Item.ITEM_ID
        left join ms_Item sp on ms_SKUMapping.SP_ITEM_ID = sp.ITEM_ID
        order by ms_SKUMapping.Create_Date DESC



        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Insert SKUMapping
     * ---------------------------------
     * @param : FormData
     */
    public function insert_skumapping($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('ms_SKUMapping', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

   

     /**
     * Update SKUMapping
     * ---------------------------------
     * @param : FormData
     */
    public function update_skumapping($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('ms_SKUMapping', $param['data'], ['BOM_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete SKUMapping
     * ---------------------------------
     * @param : SKUMapping_Index
     */
    public function delete_skumapping($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('ms_SKUMapping', ['BOM_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }
   





}