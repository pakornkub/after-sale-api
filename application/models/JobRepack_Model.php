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
     * Insert JobRepack QR
     * ---------------------------------
     * @param : FormData
     */
    public function insert_jobrepack_qr($param = [])
    {
        $this->set_db('default');

        $sql = "

        exec [dbo].[SP_CreateBoxQR]  ?,?
          
        ";

        return $this->db->query($sql,[$param['JOB_ID'],$param['username']]) ? true : false;
    }

    


     /**
     * Update JobRepack
     * ---------------------------------
     * @param : FormData
     */
    public function update_jobrepack($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('Tb_Job', $param['data'], ['JOB_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete JobRepack
     * ---------------------------------
     * @param : JobRepack_Index
     */
    public function delete_jobrepack($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_Job', ['JOB_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }
         /**
     * Delete JobRepack Item
     * ---------------------------------
     * @param : JobRepack_ID
     */
    public function delete_jobrepack_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_JobItem', ['JOB_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

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
                select JobItem_ID as key_index,ms_Item.ITEM_ID as Grade_ID,ms_Item.ITEM_CODE as Grade_Name,Qty as QTY,TotalQty as ToTal_Use 
                from tb_JobItem
                inner join ms_Item on tb_JobItem.Item_ID = ms_Item.ITEM_ID
                where Job_ID = '$param'
                order by JobItem_ID
            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }


     /**
     * QR BOX
     * ---------------------------------
     * @param : null
     */
    public function select_qrbox($param = [])
    {

        $this->set_db('default');

        $sql = "
                select Tb_Job.JOB_ID,Tb_Job.BOM_ID,Tb_Job.FG_ITEM_ID,QR_ID,QR_NO,BOM_ID,BOX_NO,ms_Item.ITEM_CODE,ms_Item.ITEM_DESCRIPTION
                from Tb_Job
                inner join Tb_QRBOX_Generate QR on Tb_Job.JOB_ID = QR.job_ID
                inner join ms_Item on Tb_Job.FG_ITEM_ID = ms_Item.ITEM_ID
                where Tb_Job.JOB_ID = ?
        ";

        $query = $this->db->query($sql,$param['JOB_ID']);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

         /**
     * withdrawitem
     * ---------------------------------
     * @param : null
     */
    public function select_withdrawitem($param = [])
    {

        $this->set_db('default');

        $sql = "
            select WI.*,ms_Item.ITEM_CODE,ms_Item.ITEM_DESCRIPTION,ms_Status.Status_desc
            from Tb_WithdrawItem WI
            INNER JOIN Tb_Withdraw W on WI.Withdraw_ID = W.Withdraw_ID
            INNER JOIN ms_Item on WI.ITEM_ID = ms_Item.ITEM_ID
            INNER JOIN ms_Status on WI.Status = ms_Status.Status_ID
            where W.Withdraw_No = ?
        ";

        $query = $this->db->query($sql,$param['JOB_ID']);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }
}