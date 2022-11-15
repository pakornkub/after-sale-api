<?php defined('BASEPATH') or exit('No direct script access allowed');

class RequestSale_Model extends MY_Model
{

    /**
     * RequestSale
     * ---------------------------------
     * @param : null
     */
    public function select_requestsale()
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
     * QR Code
     * ---------------------------------
     * @param : null
     */
    public function select_qr_no($param)
    {

        $this->set_db('default');

        $sql = "
        select QR_NO from Tb_JobItem where Job_ID = $param
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }


    /**
     * Insert RequestSale
     * ---------------------------------
     * @param : FormData
     */
    public function insert_requestsale($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_Job', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

    /**
     * Insert RequestSale Item
     * ---------------------------------
     * @param : FormData
     */
    public function insert_requestsale_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_JobItem', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

       /**
     * Reserve Stock Balance
     * ---------------------------------
     * @param : FormData
     */
    public function reserve_stockbalance($param = [])
    {
        $this->set_db('default');

        $sql = "

        exec [dbo].[SP_CreateSplitOrder]  ?,?
          
        ";

        return $this->db->query($sql,[$param['QR_NO'],$param['username']]) ? true : false;
    }

       /**
     * Unreserve Stock Balance (cancel reserve)
     * ---------------------------------
     * @param : FormData
     */
    public function unreserve_stockbalance($param = [])
    {
        $this->set_db('default');

        $sql = "

        exec [dbo].[SP_CancelSplitOrder]  ?,?
          
        ";

        return $this->db->query($sql,[$param['QR_NO'],$param['username']]) ? true : false;
    }

     /**
     * Update RequestSale
     * ---------------------------------
     * @param : FormData
     */
    public function update_requestsale($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('Tb_Job', $param['data'], ['JOB_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }


     /**
     * Delete RequestSale
     * ---------------------------------
     * @param : RequestSale_Index
     */
    public function delete_requestsale($param)
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_Job', ['JOB_ID'=> $param])) ? true : false/*$this->db->error()*/;

    }
         /**
     * Delete RequestSale Item
     * ---------------------------------
     * @param : RequestSale_ID
     */
    public function delete_requestsale_item($param)
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_JobItem', ['job_ID'=> $param])) ? true : false/*$this->db->error()*/;

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
     * RequestSaleItem
     * ---------------------------------
     * @param : null
     */
    public function select_requestsaleitem($param)
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


}