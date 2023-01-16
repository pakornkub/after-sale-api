<?php defined('BASEPATH') or exit('No direct script access allowed');

class ReceiveSale_Model extends MY_Model
{

    /**
     * ReceiveSale
     * ---------------------------------
     * @param : null
     */
    public function select_receivesale()
    {

        $this->set_db('default');

        $sql = "
        select * from view_Receive_Sale where Rec_type in ('3','5','6') order by Rec_NO DESC

        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }




     /**
     * Update ReceiveSale
     * ---------------------------------
     * @param : FormData
     */
    public function update_receivesale($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('Tb_Receive', $param['data'], ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete ReceiveSale
     * ---------------------------------
     * @param : ReceiveSale_Index
     */
    public function delete_receivesale($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_Receive', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }
         /**
     * Delete ReceiveSale Item
     * ---------------------------------
     * @param : ReceiveSale_ID
     */
    public function delete_receivesale_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_ReceiveItem', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }



    /**
     * ReceiveSaleItem
     * ---------------------------------
     * @param : null
     */
    public function select_receivesaleitem($param)
    {

        $this->set_db('default');

        $sql = "
            select Tb_ReceiveItem.RecItem_ID as [key],Tb_ReceiveItem.Item_ID ,
            ms_Item.ITEM_CODE,ms_Item.ITEM_DESCRIPTION,Tb_ReceiveItem.Lot_No,Tb_ReceiveItem.Qty as QTY,ms_ProductType.Product_DESCRIPTION,QR_Code
            from Tb_ReceiveItem
            inner join ms_Item on Tb_ReceiveItem.Item_ID = ms_Item.ITEM_ID
            inner join ms_ProductType on ms_Item.Product_ID = ms_ProductType.Product_ID
            where Rec_ID = '$param'
            order by RecItem_ID

            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

}