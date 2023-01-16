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
     * Insert RequestSale
     * ---------------------------------
     * @param : FormData
     */
    public function insert_requestsale($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_Withdraw', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

    /**
     * Insert RequestSale Item
     * ---------------------------------
     * @param : FormData
     */
    public function insert_requestsale_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_WithdrawItem', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

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

        exec [dbo].[SP_ReserveItem]  ?,?,?
          
        ";

        return $this->db->query($sql,[$param['QR_NO'],$param['username'],$param['Withdraw_No']]) ? true : false;
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

        return ($this->db->update('Tb_Withdraw', $param['data'], ['Withdraw_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }


     /**
     * Delete RequestSale
     * ---------------------------------
     * @param : RequestSale_Index
     */
    public function delete_requestsale($param)
    {
        $this->set_db('default');

        $sql = "

        exec [dbo].[SP_CancelWithdraw]  ?,?
          
        ";

        return $this->db->query($sql,[$param['Withdraw_No'],$param['username']]) ? true : false;

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
        
            select Tb_WithdrawItem.QR_NO as [key],View_Stock_Detail.* 
            from Tb_WithdrawItem
            Left join View_Stock_Detail on View_Stock_Detail.QR_NO = Tb_WithdrawItem.QR_NO
            where Tb_WithdrawItem.Withdraw_ID = '$param' and View_Stock_Detail.Location_ID = '1'
            
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