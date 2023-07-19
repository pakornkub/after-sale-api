<?php defined('BASEPATH') or exit('No direct script access allowed');

class RequestNoIssue_Model extends MY_Model
{

    /**
     * Menu Type
     * ---------------------------------
     * @param : null
     */
    public function select_requestno_issue()
    {

        $this->set_db('default');

        $sql = "
        select Withdraw_No from Tb_Withdraw where Withdraw_type in ('1','2') and status <> -1
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
