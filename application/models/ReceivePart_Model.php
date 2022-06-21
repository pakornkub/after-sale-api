<?php defined('BASEPATH') or exit('No direct script access allowed');

class ReceivePart_Model extends MY_Model
{

    /**
     * ReceivePart
     * ---------------------------------
     * @param : null
     */
    public function select_receivepart()
    {

        $this->set_db('default');

        $sql = "
        select BOM_ID,BOM_Name,CONVERT(varchar,BOM_Date,103) as BOM_Date1,BOM_Date,ms_Item.ITEM_ID,ms_Item.ITEM_CODE,Bom_Rev_No,
        CASE WHEN Bom_Rev_No <   10 THEN '00'+ CONVERT(varchar(2),Bom_Rev_No)
            WHEN Bom_Rev_No >=  10 THEN '0'+ CONVERT(varchar(2),Bom_Rev_No)
            WHEN Bom_Rev_No >= 100 THEN CONVERT(varchar(2),Bom_Rev_No)	
        ELSE 'No' END as Rev_No,Remark,ms_BOM.Status,
        ms_BOM.Create_By,CONVERT(varchar,ms_BOM.Create_Date,103) as Add_Date
        from ms_BOM
        inner join ms_Item on ms_BOM.FG_ITEM_ID = ms_Item.ITEM_ID
        order by ms_BOM.Create_Date DESC

        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Insert ReceivePart
     * ---------------------------------
     * @param : FormData
     */
    public function insert_receivepart($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('ms_BOM', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

    /**
     * Insert ReceivePart Item
     * ---------------------------------
     * @param : FormData
     */
    public function insert_receivepart_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('ms_BOM_Item', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

     /**
     * Update ReceivePart
     * ---------------------------------
     * @param : FormData
     */
    public function update_receivepart($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('ms_BOM', $param['data'], ['BOM_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete ReceivePart
     * ---------------------------------
     * @param : ReceivePart_Index
     */
    public function delete_receivepart($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('ms_BOM', ['BOM_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }
         /**
     * Delete ReceivePart Item
     * ---------------------------------
     * @param : ReceivePart_ID
     */
    public function delete_receivepart_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('ms_BOM_Item', ['BOM_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }



    /**
     * ReceivePartItem
     * ---------------------------------
     * @param : null
     */
    public function select_receivepartitem($param)
    {

        $this->set_db('default');

        $sql = "
            select ms_BOM_Item.ITEM_Seq as key_index,ms_BOM_Item.ITEM_ID as Grade_ID,ms_Item.ITEM_CODE as Grade_Name,ITEM_QTY as QTY
            from ms_BOM_Item 
            inner join ms_Item on ms_BOM_Item.ITEM_ID = ms_Item.ITEM_ID
            where BOM_ID = '$param'
            order by ITEM_Seq
            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

}