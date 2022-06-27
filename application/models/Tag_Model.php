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
            select TAG.Tag_ID as key_index,TAG.QR_NO as QR_Code,ms_Item.ITEM_CODE as Grade_ID,ms_Item.ITEM_DESCRIPTION as Grade_Name,RCI.Lot_No,TAG.Qty as QTY,Tag_Status,TAG.ItemStatus_ID as Item_Status
            from Tb_TagQR TAG
            inner join Tb_ReceiveItem RCI on TAG.RecItem_ID = RCI.RecItem_ID
            inner join ms_Item on RCI.Item_ID = ms_Item.ITEM_ID
            where TAG.Rec_ID = ? and Tag_Status <> -1

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
