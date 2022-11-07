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

        return ($this->db->update('Tb_Job', $param['data'], ['JOB_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete SplitPart
     * ---------------------------------
     * @param : SplitPart_Index
     */
    public function delete_splitpart($param)
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_Job', ['JOB_ID'=> $param])) ? true : false/*$this->db->error()*/;

    }
         /**
     * Delete SplitPart Item
     * ---------------------------------
     * @param : SplitPart_ID
     */
    public function delete_splitpart_item($param)
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_JobItem', ['job_ID'=> $param])) ? true : false/*$this->db->error()*/;

    }

         /**
     * Delete ReceivePart
     * ---------------------------------
     * @param : ReceivePart_Index
     */
    public function delete_receivepart($param)
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_Receive', ['Rec_ID'=> $param])) ? true : false/*$this->db->error()*/;

    }
         /**
     * Delete ReceivePart Item
     * ---------------------------------
     * @param : ReceivePart_ID
     */
    public function delete_receivepart_item($param)
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_ReceiveItem', ['Rec_ID'=> $param])) ? true : false/*$this->db->error()*/;

    }

        /**
     * Update StockBalance
     * ---------------------------------
     * @param : QR_NO
     */
    public function update_stockbalance($param = [])
    {
        $this->set_db('default');
        
        return ($this->db->update('Tb_StockBalance', $param['StockBalance'], ['QR_NO'=> $param['QR_NO']])) ? true : false/*$this->db->error()*/;

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
        select Tb_JobItem.JobItem_ID as [key],Tb_JobItem.SKUMapping_ID,Tb_JobItem.Rec_NO,Tb_JobItem.QR_NO,Tb_JobItem.FG_ITEM_ID as Grade_ID_FG,SKU1.ITEM_CODE as Grade_Name_FG,
        SKU1.ITEM_DESCRIPTION as Grade_DESCRIPTION_FG,Tb_JobItem.Lot_No,Tb_JobItem.FG_Qty as QTY_FG,
        Tb_JobItem.SP_ITEM_ID as Grade_ID_SP,SKU2.ITEM_CODE as Grade_Name_SP,SKU2.ITEM_DESCRIPTION as Grade_DESCRIPTION_SP,Tb_JobItem.SP_Qty  as QTY_SP
        from Tb_JobItem
        LEFT JOIN ms_Item SKU1 on Tb_JobItem.FG_ITEM_ID = SKU1.ITEM_ID
        LEFT JOIN ms_Item SKU2 on Tb_JobItem.SP_ITEM_ID = SKU2.ITEM_ID
        where Job_ID = '$param'
            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }


    /**
     * SKU Mapping
     * ---------------------------------
     * @param : null
     */
    public function select_skumapping($param)
    {

        $this->set_db('default');

        $sql = "
        select SKUMapping_ID,SP_ITEM_ID,QTY,ms_Item.ITEM_CODE,ms_Item.ITEM_DESCRIPTION,Product_DESCRIPTION
        from ms_SKUMapping
        inner join ms_Item on ms_Item.ITEM_ID = ms_SKUMapping.SP_ITEM_ID
        inner join ms_ProductType on ms_Item.Product_ID = ms_ProductType.Product_ID
        where FG_ITEM_ID = '$param' and ms_SKUMapping.Status <> -1
        ";

        $query = $this->db->query($sql,$param);

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
}