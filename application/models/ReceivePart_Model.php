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
        select * from view_Receive where Rec_type = '1' order by Rec_ID DESC

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

        return ($this->db->insert('Tb_ReceiveItem', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

     /**
     * Update ReceivePart
     * ---------------------------------
     * @param : FormData
     */
    public function update_receivepart($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('Tb_Receive', $param['data'], ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete ReceivePart
     * ---------------------------------
     * @param : ReceivePart_Index
     */
    public function delete_receivepart($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_Receive', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }
         /**
     * Delete ReceivePart Item
     * ---------------------------------
     * @param : ReceivePart_ID
     */
    public function delete_receivepart_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_ReceiveItem', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

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
            select Tb_ReceiveItem.RecItem_ID as key_index,Tb_ReceiveItem.Item_ID as Grade_ID,ms_Item.ITEM_CODE as Grade_Name,Tb_ReceiveItem.Lot_No,Tb_ReceiveItem.Qty as QTY
            from Tb_ReceiveItem
            inner join ms_Item on Tb_ReceiveItem.Item_ID = ms_Item.ITEM_ID
            where Rec_ID = '$param'
            order by RecItem_ID

            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

}