<?php defined('BASEPATH') or exit('No direct script access allowed');

class JobPlan_Model extends MY_Model
{

    /**
     * JobPlan
     * ---------------------------------
     * @param : null
     */
    public function select_jobplan()
    {

        $this->set_db('default');

        $sql = "
        select * from ms_Plan order by Plan_id DESC
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Insert JobPlan
     * ---------------------------------
     * @param : FormData
     */
    public function insert_jobplan($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_Receive', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

    /**
     * Insert JobPlan Item
     * ---------------------------------
     * @param : FormData
     */
    public function insert_jobplan_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_ReceiveItem', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

     /**
     * Update JobPlan
     * ---------------------------------
     * @param : FormData
     */
    public function update_jobplan($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('Tb_Receive', $param['data'], ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete JobPlan
     * ---------------------------------
     * @param : JobPlan_Index
     */
    public function delete_jobplan($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_Receive', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }
         /**
     * Delete JobPlan Item
     * ---------------------------------
     * @param : JobPlan_ID
     */
    public function delete_jobplan_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_ReceiveItem', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }



    /**
     * JobPlanItem
     * ---------------------------------
     * @param : null
     */
    public function select_jobplanitem($param)
    {

        $this->set_db('default');

        $sql = "
            select Tb_ReceiveItem.RecItem_ID as key_index,Tb_ReceiveItem.Item_ID as Grade_ID,ms_Item.ITEM_CODE as Grade_Name,Tb_ReceiveItem.Lot_No,Tb_ReceiveItem.Qty as QTY
            from Tb_ReceiveItem
            inner join ms_Item on Tb_ReceiveItem.Item_ID = ms_Item.ITEM_ID
            where Rec_ID = '$param'
            order by RecItem_ID

            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

}