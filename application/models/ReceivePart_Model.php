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
        select Tb_Receive.*,CONVERT(varchar,Rec_Datetime,103) as Date1,ms_ReceiveType.DESCRIPTION
        from Tb_Receive
        inner join ms_ReceiveType on Tb_Receive.Rec_type = ms_ReceiveType.ReceiptType_ID

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

        return ($this->db->insert('Tb_Receive', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

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