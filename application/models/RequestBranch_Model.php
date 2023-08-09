<?php defined('BASEPATH') or exit('No direct script access allowed');

class RequestBranch_Model extends MY_Model
{

    /**
     * RequestBranch
     * ---------------------------------
     * @param : null
     */
    public function select_requestbranch()
    {

        $this->set_db('default');

        $sql = "
        select * from View_Request where Withdraw_type in ('1','2') order by Withdraw_ID DESC

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
    public function select_request_no($param)
    {

        $this->set_db('default');

        $sql = "
        select dbo.[fnGetRqDocNo] ('$param') as RequestNo
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
     * Insert RequestBranch
     * ---------------------------------
     * @param : FormData
     */
    public function insert_requestbranch($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_RequestBranch', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

    /**
     * Insert RequestBranch Item
     * ---------------------------------
     * @param : FormData
     */
    public function insert_requestbranch_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_RequestBranchItem', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }


     /**
     * Update RequestBranch
     * ---------------------------------
     * @param : FormData
     */
    public function update_requestbranch($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('Tb_RequestBranch', $param['data'], ['RequestBranch_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }


     /**
     * Delete RequestBranch
     * ---------------------------------
     * @param : RequestBranch_Index
     */
    public function delete_requestbranch($param)
    {
        $this->set_db('default');

        $sql = "

        exec [dbo].[SP_CancelWithdraw]  ?,?
          
        ";

        return $this->db->query($sql,[$param['RequestBranch_No'],$param['username']]) ? true : false;

    }
         /**
     * Delete RequestBranch Item
     * ---------------------------------
     * @param : RequestBranch_ID
     */
    public function delete_requestbranch_item($param)
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
     * RequestBranchItem
     * ---------------------------------
     * @param : null
     */
    public function select_requestbranchitem($param)
    {

        $this->set_db('default');

        // $sql = "
        
        //     select Tb_WithdrawItem.QR_NO as [key],Tb_WithdrawItem.Qty as QTY,Stock.Location,Stock.Product_DESCRIPTION,Stock.ITEM_CODE,Stock.ITEM_DESCRIPTION,Stock.QR_NO,Stock.ReserveQTY,Stock.Unit,Stock.Status_desc,
        //     Stock.Product_ID,Stock.ITEM_ID,Stock.LOT,Stock.Ref_No
        //     from Tb_WithdrawItem
        //     Left join View_Stock_Detail Stock on Stock.QR_NO = Tb_WithdrawItem.QR_NO
        //     where Tb_WithdrawItem.Withdraw_ID = '$param' and Stock.Location_ID = '1'
            
        // ";
        $sql = "
        
	    select Tb_WithdrawItem.QR_NO as [key],Tb_WithdrawItem.Qty as QTY,Stock.Location,Stock.Product_DESCRIPTION,Stock.ITEM_CODE,Stock.ITEM_DESCRIPTION,Stock.QR_NO,Stock.ReserveQTY,Stock.Unit,Stock.Status_desc,
            Stock.Product_ID,Stock.ITEM_ID,Stock.LOT,Stock.Ref_No
            from Tb_WithdrawItem
            CROSS APPLY (select TOP 1 Stock.Location,Stock.Product_DESCRIPTION,Stock.ITEM_CODE,Stock.ITEM_DESCRIPTION,Stock.QR_NO,Stock.ReserveQTY,Stock.Unit,Stock.Status_desc,
                         Stock.Product_ID,Stock.ITEM_ID,Stock.LOT,Stock.Ref_No 
			from View_Stock_Detail Stock where Stock.Location_ID = '1' and Stock.QR_NO = Tb_WithdrawItem.QR_NO order by ReserveQTY DESC) Stock
                        where Tb_WithdrawItem.Withdraw_ID = '$param' 
            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }
    
    /**
     * Confirm Request
     * ---------------------------------
     * @param : FormData
     */
    public function confirm_request($param = [])
    {
        $this->set_db('default');

        $sql = "
        exec [dbo].[SP_WithdrawAutoReceive_ALL]  ?,?
          
        ";

        return $this->db->query($sql,[$param['Withdraw_ID'],$param['username']]) ? true : false;
    }

}