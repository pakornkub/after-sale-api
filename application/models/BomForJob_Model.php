<?php defined('BASEPATH') or exit('No direct script access allowed');

class BomForJob_Model extends MY_Model
{

    /**
     * Bom
     * ---------------------------------
     * @param : null
     */
    public function select_bom()
    {

        $this->set_db('default');

        $sql = "
        select FG_ITEM_ID,ITEM_CODE,ITEM_DESCRIPTION 
		from ms_BOM
		inner join ms_Item on ms_BOM.FG_ITEM_ID = ms_Item.ITEM_ID
		where ms_BOM.Status = '1' and ms_Item.Product_ID = '3'
		group by FG_ITEM_ID,ITEM_CODE,ITEM_DESCRIPTION 
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }
    /**
     * Grade plan
     * ---------------------------------
     * @param : null
     */
    public function select_gradeplan($param = [])
    {

        $this->set_db('default');

        $sql = "
        declare @DatePlan date
		set @DatePlan = ?

		select ITEM_ID,ITEM_CODE,ITEM_QTY as QTY from ms_Plan
		inner join ms_Item on ms_Plan.FG_ITEM_ID = ms_Item.ITEM_ID
		where DATE = @DatePlan and 
        ITEM_ID not in (select FG_ITEM_ID from tb_Job where CONVERT(date, JOB_Date) = @DatePlan and JOB_STATUS <> -1)";

        $query = $this->db->query($sql,$param['DATE']);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }
    /**
     * Bom Rev
     * ---------------------------------
     * @param : null
     */
    public function select_bomrev($param = [])
    {

        $this->set_db('default');

        $sql = "select BOM_ID,Remark from ms_BOM where FG_ITEM_ID = ? and Status = '1' order by Bom_Rev_No ASC";

        $query = $this->db->query($sql,$param['GRADE_ID']);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }
    /**
     * Bom Item
     * ---------------------------------
     * @param : null
     */
    public function select_bomitem($param = [])
    {

        $this->set_db('default');

        $sql = "
        select BI.ITEM_ID as [key],BI.ITEM_ID as Grade_ID,ms_Item.ITEM_CODE as Grade_Name,ms_Item.ITEM_DESCRIPTION as Grade_Des,
		ms_ProductType.Product_DESCRIPTION as Type ,BI.ITEM_QTY as QTY,0 as Qty_Action
        from ms_BOM_Item BI
        inner join ms_Item on BI.ITEM_ID = ms_Item.ITEM_ID
		inner join ms_ProductType on ms_Item.Product_ID = ms_ProductType.Product_ID
        where BOM_ID = ?
        ";

        $query = $this->db->query($sql,$param['BOM_ID']);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }
    /**
     * Bom Item V1
     * ---------------------------------
     * @param : null
     */
    public function select_bomitem_v1($param = [])
    {

        $this->set_db('default');

        $sql = "
        select BI.ITEM_ID as [key],BI.ITEM_ID as ITEM_ID,ms_Item.ITEM_CODE as ITEM_CODE,ms_Item.ITEM_DESCRIPTION as ITEM_DESCRIPTION,
		ms_ProductType.Product_DESCRIPTION as Product_DESCRIPTION 
        from ms_BOM_Item BI
        inner join ms_Item on BI.ITEM_ID = ms_Item.ITEM_ID
		inner join ms_ProductType on ms_Item.Product_ID = ms_ProductType.Product_ID
        where BOM_ID = ?
        ";

        $query = $this->db->query($sql,$param['BOM_ID']);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
