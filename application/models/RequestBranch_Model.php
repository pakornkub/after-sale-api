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
        select Tb_RequestBranch.*,CONVERT(varchar, dbo.Tb_RequestBranch.RequestBranch_Date, 103) AS Date1, 
        dbo.ms_RequestType.DESCRIPTION, dbo.ms_Location.Location_ID, dbo.ms_Location.Location 
        from Tb_RequestBranch
        INNER JOIN ms_RequestType ON dbo.ms_RequestType.RequestType_ID = Tb_RequestBranch.RequestBranch_type 
        LEFT OUTER JOIN dbo.ms_Location ON dbo.ms_Location.Location_ID = Tb_RequestBranch.Plan_Team
        where Tb_RequestBranch.Status <> -1
        order by RequestBranch_ID DESC

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
    public function delete_requestbranch($param = [])
    {
        // $this->set_db('default');

        // $sql = "

        // exec [dbo].[SP_CancelWithdraw]  ?,?
          
        // ";

        // return $this->db->query($sql,[$param['RequestBranch_No'],$param['username']]) ? true : false;



        $this->set_db('default');

        return ($this->db->update('Tb_RequestBranch', $param['data'], ['RequestBranch_NO'=> $param['RequestBranch_No']])) ? true : false/*$this->db->error()*/;

    }
   
  
         /**
     * Delete requestbranch Item
     * ---------------------------------
     * @param : requestbranch_ID
     */
    public function delete_requestbranch_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_RequestBranchItem', ['requestbranch_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

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
        
	    select Tb_RequestBranchItem.ITEM_ID as [key],ms_Item.ITEM_ID as ITEM_ID,ms_Item.ITEM_CODE as ITEM_CODE,ms_Item.ITEM_DESCRIPTION as ITEM_DESCRIPTION,
		ms_ProductType.Product_DESCRIPTION as Product_DESCRIPTION,Tb_RequestBranchItem.QTY as QTY,0 as Qty_Action  
		from Tb_RequestBranchItem
		inner join ms_Item on ms_Item.ITEM_ID = Tb_RequestBranchItem.ITEM_ID
		inner join ms_ProductType on ms_Item.Product_ID = ms_ProductType.Product_ID
		where RequestBranch_ID = '$param' 
            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }
    

    /**
     * RequestBranchItem
     * ---------------------------------
     * @param : null
     */
    public function select_quotation_requestbranch()
    {

        $this->set_db('default');

        $sql = "
        select Tb_RequestBranch.*,ms_Location.Location
		from Tb_RequestBranch
		LEFT JOIN ms_Location on ms_Location.Location_ID = Tb_RequestBranch.Plan_Team
		where Status = '1'

        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

}