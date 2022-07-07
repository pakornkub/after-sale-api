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
        select * from ms_Item where Product_ID = '1' and Status = '1'
        ";

        $query = $this->db->query($sql);

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

        $sql = "
        select BOM_ID,BOM_Name as BOM_Name from ms_BOM where FG_ITEM_ID = ? and Status = '1' order by Bom_Rev_No DESC
";

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
        select BI.ITEM_Seq as key_index,BI.ITEM_ID as Grade_ID,ms_Item.ITEM_CODE as Grade_Name,BI.ITEM_QTY as QTY,0 as ToTal_Use
        from ms_BOM_Item BI
        inner join ms_Item on BI.ITEM_ID = ms_Item.ITEM_ID
        where BOM_ID = ?
        ";

        $query = $this->db->query($sql,$param['BOM_ID']);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
