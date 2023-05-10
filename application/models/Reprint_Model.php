<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reprint_Model extends MY_Model
{

    

    /**
     * Stock Detail
     * ---------------------------------
     * @param : null
     */
    public function select_qrcode($param)
    {

        $this->set_db('default');

        $sql = "
        select TOP 1 * from View_TagQR $param order by Create_Date DESC
 
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

  
    
}

