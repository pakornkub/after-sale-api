<?php defined('BASEPATH') or exit('No direct script access allowed');

class UnlockSP_Model extends MY_Model
{

    /**
     * UnlockSP
     * ---------------------------------
     * @param : null
     */
    public function select_unlock_sp()
    {

        $this->set_db('default');

        $sql = "
           select * from Tb_Receive where Rec_type = 1 and Status in (5) order by Rec_ID DESC
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Update UnlockSP
     * ---------------------------------
     * @param : FormData
     */
    public function update_unlock_sp($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('Tb_Receive', $param['data'], $param['where'])) ? true : false/*$this->db->error()*/;

    }

    /**
     * Update UnlockSP Tag
     * ---------------------------------
     * @param : Rec_ID, QR_NO, Tag_ID, Username
     */
    public function exec_unlock_sp_tag($param = [])
    {
        $this->set_db('default');

        $sql = "

            exec [dbo].[SP_UpdateUnlockTag] ?,?,?,?

        ";

        $query = $this->db->query($sql,[$param['QR_NO'],$param['Rec_ID'],$param['Tag_ID'],$param['Username']]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
