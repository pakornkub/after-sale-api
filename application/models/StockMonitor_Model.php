<?php defined('BASEPATH') or exit('No direct script access allowed');

class StockMonitor_Model extends MY_Model
{

    
    /**
     * Stock Group
     * ---------------------------------
     * @param : null
     */
    public function select_stockgroup($param)
    {

        $this->set_db('default');

        $sql = "
        select * from View_Stock_Group $param

            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

    /**
     * Stock Detail
     * ---------------------------------
     * @param : null
     */
    public function select_stockdetail($param)
    {

        $this->set_db('default');

        $sql = "
        select QR_NO as [key],* from View_Stock_Detail $param order by QR_NO

            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

    /**
     * Stock WH Header
     * ---------------------------------
     * @param : null
     */
    public function select_stockwhheader($param)
    {

        $this->set_db('default');

        $sql = "select *,CONVERT(varchar, Pack_Date, 103) AS Production_Date FROM [GetQRBoxApprove] ('$param')";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

    /**
     * Stock WH
     * ---------------------------------
     * @param : null
     */
    public function select_stockwh($param)
    {

        $this->set_db('default');

        $sql = "select * from (select * from GetQRHistoryTrans('$param') ) Trans
                where Trans.Transaction_Type != 'Receive'";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

    /**
     * Report Issue
     * ---------------------------------
     * @param : null
     */
    public function select_reportissue($param)
    {

        $this->set_db('default');

        $sql = "
        select CONVERT(varchar, Request_Date, 103) AS Request_Date1,CONVERT(varchar, Issue_Date, 103) AS Issue_Date1,* from View_Report_Issue $param
   
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }


    /**
     * Stock Grade
     * ---------------------------------
     * @param : null
     */
    public function select_stockgrade($param)
    {

        $this->set_db('default');

        $sql = "
        select ms_Item.ITEM_ID as [key],ms_Item.ITEM_ID as ITEM_ID,ms_Item.ITEM_CODE as ITEM_CODE,ms_Item.ITEM_DESCRIPTION as ITEM_DESCRIPTION,
		ms_ProductType.Product_DESCRIPTION as Product_DESCRIPTION,1 as QTY
        from ms_Item
        inner join ms_ProductType on ms_Item.Product_ID = ms_ProductType.Product_ID $param

            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }
    
}

