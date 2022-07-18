<?php defined('BASEPATH') or exit('No direct script access allowed');

class CountStock_Model extends MY_Model
{

    /**
     * CountStock
     * ---------------------------------
     * @param : null
     */
    public function select_countstock()
    {

        $this->set_db('default');

        $sql = "
        select * from Tb_StockCount

        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }


    /**
     * countstock no
     * ---------------------------------
     * @param : null
     */
    public function select_countstock_no()
    {

        $this->set_db('default');

        $sql = "
        select dbo.[fnGetRcDocNo] ('1') as ReceiveNo
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }


    /**
     * Insert CountStock
     * ---------------------------------
     * @param : FormData
     */
    public function insert_countstock($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_Receive', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

    /**
     * Insert CountStock Item
     * ---------------------------------
     * @param : FormData
     */
    public function insert_countstock_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_ReceiveItem', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

     /**
     * Update CountStock
     * ---------------------------------
     * @param : FormData
     */
    public function update_countstock($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('Tb_Receive', $param['data'], ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete CountStock
     * ---------------------------------
     * @param : CountStock_Index
     */
    public function delete_countstock($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_Receive', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }
         /**
     * Delete CountStock Item
     * ---------------------------------
     * @param : CountStock_ID
     */
    public function delete_countstock_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_ReceiveItem', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }



    /**
     * CountStockItem
     * ---------------------------------
     * @param : null
     */
    public function select_countstocktitem($param)
    {

        $this->set_db('default');

        $sql = "
            select Tb_ReceiveItem.RecItem_ID as [key],Tb_ReceiveItem.Item_ID as Grade_ID,ms_Item.ITEM_CODE as Grade_Name,Tb_ReceiveItem.Lot_No,Tb_ReceiveItem.Qty as QTY
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