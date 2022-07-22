<?php defined('BASEPATH') or exit('No direct script access allowed');

class JobRepack_Model extends MY_Model
{

    /**
     * JobRepack
     * ---------------------------------
     * @param : null
     */
    public function select_job_repack()
    {

        $this->set_db('default');

        $sql = "
            select  *
                    , (
                        select count(*) from Tb_Transaction where ref_num = j.JOB_No and Transaction_Type = 'Receive'
                    ) as BOX_QTY
            from Tb_Job j where j.JobType_ID in (1,3) and JOB_STATUS not in (9,-1)

        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Update JobRepack
     * ---------------------------------
     * @param : FormData
     */
    public function update_job_repack($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('Tb_Job', $param['data'], $param['where'])) ? true : false/*$this->db->error()*/;

    }

    /**
     * JobRepack BOM
     * ---------------------------------
     * @param : JOB_ID
     */
    public function select_job_repack_bom($JOB_ID)
    {

        $this->set_db('default');

        $sql = "

            select
                    ROW_NUMBER() Over (Order by j.JOB_ID) as 'No'
                    ,i.ITEM_DESCRIPTION as 'SP'
                    ,i.ITEM_ID as 'Item_ID'
                    , ji.Qty as 'BOM'
                    ,(

						select COUNT(*) from Tb_Withdraw w inner join Tb_WithdrawItem wi on w.Withdraw_ID = wi.Withdraw_ID where Ref_No = j.JOB_ID and ITEM_ID = i.ITEM_ID and w.status in (1,3) and wi.Status = 3

					) as 'Actual'

            from	Tb_Job j
                    inner join Tb_JobItem ji on j.JOB_ID = ji.JOB_ID
                    inner join ms_Item i on ji.Item_ID = i.ITEM_ID

            where	j.JOB_ID = ?

        ";

        $query = $this->db->query($sql, [$JOB_ID]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Exec JobRepack Item
     * ---------------------------------
     * @param : QR_NO, JOB_ID, Tag_ID, Username
     */
    public function exec_job_repack_item($param = [])
    {

        $this->set_db('default');

        $sql = "

            exec [dbo].[SP_CreateJobWithdraw] ?,?,?,?

        ";

        $query = $this->db->query($sql, [$param['QR_NO'], $param['JOB_ID'], $param['Tag_ID'], $param['Username']]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Exec JobRepack Transaction
     * ---------------------------------
     * @param : JOB_ID, QR_NO_BOX, Username
     */
    public function exec_job_repack_transaction($param = [])
    {

        $this->set_db('default');

        $sql = "

            exec [dbo].[SP_ConfirmBox] ?,?,?

        ";

        $query = $this->db->query($sql, [$param['QR_NO_BOX'], $param['JOB_ID'], $param['Username']]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
