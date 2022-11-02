<?php defined('BASEPATH') or exit('No direct script access allowed');

class SplitPart_Model extends MY_Model
{

    /**
     * SplitPart
     * ---------------------------------
     * @param : null
     */
    public function select_splitpart()
    {

        $this->set_db('default');

        $sql = "
        select * from View_SplitPart order by JOB_ID DESC

        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }


    /**
     * receive no
     * ---------------------------------
     * @param : null
     */
    public function select_split_no()
    {

        $this->set_db('default');

        $sql = "
        select dbo.[fnGetJobDocNo] ('1') as jobNo
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }


    /**
     * Insert SplitPart
     * ---------------------------------
     * @param : FormData
     */
    public function insert_splitpart($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_Job', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

    /**
     * Insert SplitPart Item
     * ---------------------------------
     * @param : FormData
     */
    public function insert_splitpart_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_JobItem', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

     /**
     * Update SplitPart
     * ---------------------------------
     * @param : FormData
     */
    public function update_splitpart($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('Tb_Receive', $param['data'], ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete SplitPart
     * ---------------------------------
     * @param : SplitPart_Index
     */
    public function delete_splitpart($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_Receive', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }
         /**
     * Delete SplitPart Item
     * ---------------------------------
     * @param : SplitPart_ID
     */
    public function delete_splitpart_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_ReceiveItem', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }



    /**
     * SplitPartItem
     * ---------------------------------
     * @param : null
     */
    public function select_splitpartitem($param)
    {

        $this->set_db('default');

        $sql = "
            select Tb_ReceiveItem.RecItem_ID as [key],Tb_ReceiveItem.Item_ID as Grade_ID,
            ms_Item.ITEM_CODE as Grade_Name,Tb_ReceiveItem.Lot_No,Tb_ReceiveItem.Qty as QTY,ms_ProductType.Product_DESCRIPTION  as Type
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


    /**
     * Bom Mapping
     * ---------------------------------
     * @param : null
     */
    public function select_bommapping($param)
    {

        $this->set_db('default');

        $sql = "
        select BOM_ID,SP_ITEM_ID,QTY,ms_Item.ITEM_CODE,ms_Item.ITEM_DESCRIPTION,Product_DESCRIPTION
        from ms_BOM
        inner join ms_Item on ms_Item.ITEM_ID = ms_BOM.SP_ITEM_ID
        inner join ms_ProductType on ms_Item.Product_ID = ms_ProductType.Product_ID
        where FG_ITEM_ID = '$param'

            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }
}