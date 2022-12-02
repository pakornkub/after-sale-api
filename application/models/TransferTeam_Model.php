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
    public function insert_transferteam($a= null,$b= null,$c= null,$d= null)
    {
        $this->set_db('default');

        $sql = "

        exec [dbo].[SP_TransferTeam]  ?,?,?,?
          
        ";

        


        return $this->db->query($sql,[$a,$b,$c,$d]) ? true : false;
        
        
    

    }

    

    /**
     * Insert TransferTeam Item
     * ---------------------------------
     * @param : FormData
     */
    public function insert_transferteam_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Temp_TransferTeam', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

    


    

}