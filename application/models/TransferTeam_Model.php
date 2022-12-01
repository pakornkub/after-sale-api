<?php defined('BASEPATH') or exit('No direct script access allowed');

class TransferTeam_Model extends MY_Model
{

    /**
     * TransferTeam
     * ---------------------------------
     * @param : null
     */
    public function select_transferteam()
    {

        $this->set_db('default');

        $sql = "
        select * from View_Request where Withdraw_type in ('1','2') order by Withdraw_ID DESC

        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }


 




    /**
     * Insert TransferTeam
     * ---------------------------------
     * @param : FormData
     */
    public function insert_transferteam($param = [])
    {
        $this->set_db('default');

        $sql = "

        exec [dbo].[SP_TransferTeam]  ?,?,?,?
          
        ";

        $query = $this->db->query($sql,[$param['Withdraw_No'],$param['Old_Team'],$param['New_Team'],$param['Create_By']]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;
        //return $this->db->query($sql,[$param['Withdraw_No'],$param['Old_Team'],$param['New_Team'],$param['Create_By']]) ? true : false;
        
        
        

        // $query = $this->db->query($sql, [$Group_Index, $Platform]);

        // $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        // return $result;

    }

    

    /**
     * Insert TransferTeam Item
     * ---------------------------------
     * @param : FormData
     */
    public function insert_requestsale_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_WithdrawItem', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

    


    

}