<?php defined('BASEPATH') or exit('No direct script access allowed');

class JobRepack_Model extends MY_Model
{

    /**
     * JobRepack
     * ---------------------------------
     * @param : null
     */
    public function select_jobrepack()
    {

        $this->set_db('default');

        $sql = "
        select * from View_JobRepack order by JOB_ID DESC

        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Insert JobRepack
     * ---------------------------------
     * @param : FormData
     */
    public function insert_jobrepack($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_Job', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

    /**
     * Insert JobRepack Item
     * ---------------------------------
     * @param : FormData
     */
    public function insert_jobrepack_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_JobItem', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

     /**
     * Update JobRepack
     * ---------------------------------
     * @param : FormData
     */
    public function update_jobrepack($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('Tb_Receive', $param['data'], ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete JobRepack
     * ---------------------------------
     * @param : JobRepack_Index
     */
    public function delete_jobrepack($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_Receive', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }
         /**
     * Delete JobRepack Item
     * ---------------------------------
     * @param : JobRepack_ID
     */
    public function delete_jobrepack_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_ReceiveItem', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }



    /**
     * JobRepackItem
     * ---------------------------------
     * @param : null
     */
    public function select_jobrepackitem($param)
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