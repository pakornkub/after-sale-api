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
        select * from view_StockCount order by CountStock_ID DESC

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
        select [dbo].[fnGetCountStockDocNo] () as CountStockNo
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

        return ($this->db->insert('Tb_StockCount', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

    /**
     * Insert CountStock Item
     * ---------------------------------
     * @param : FormData
     */
    public function insert_countstock_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_StockCount_Balance', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

     /**
     * Update CountStock
     * ---------------------------------
     * @param : FormData
     */
    public function update_countstock($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('Tb_StockCount', $param['data'], ['CountStock_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete CountStock
     * ---------------------------------
     * @param : CountStock_Index
     */
    public function delete_countstock($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_StockCount', ['CountStock_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }
         /**
     * Delete CountStock Item
     * ---------------------------------
     * @param : CountStock_ID
     */
    public function delete_countstock_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_StockCount_Balance', ['CountStock_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }



    /**
     * CountStockItem
     * ---------------------------------
     * @param : null
     */             
    public function select_countstockitem($param = [])
    {

        $this->set_db('default');

        $sql = "
        select CBL_ID as [key],Location_ID,Location,ITEM_ID,ITEM_CODE,ITEM_DESCRIPTION,Product_ID,Product_DESCRIPTION,Count_Balance,ISNULL(Count_Actual,0) as Count_Actual  
		from View_CheckStockBalance
		where CountStock_ID = ?

            
        ";

        $query = $this->db->query($sql,$param['CountStock_ID']);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

    /**
     * CountStockSnap
     * ---------------------------------
     * @param : null
     */
    public function select_countstocksnap($param)
    {

        $this->set_db('default');

        $sql = "
        select ROW_NUMBER() OVER(Order by Item_ID) as [key],Location_ID,Location,Product_ID,Product_DESCRIPTION,ITEM_ID,ITEM_CODE,ITEM_DESCRIPTION,Count_Balance,0 as Count_Actual
        from View_SnapStockbalance $param

            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

        /**
     * CountStockStatus
     * ---------------------------------
     * @param : null
     */
    public function select_countstockstatus($param)
    {

        $this->set_db('default');

        $sql = "
            select * from Tb_StockCount where CountStock_ID = $param
        
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

}