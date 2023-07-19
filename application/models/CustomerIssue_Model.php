<?php defined('BASEPATH') or exit('No direct script access allowed');

class CustomerIssue_Model extends MY_Model
{

    /**
     * Menu Type
     * ---------------------------------
     * @param : null
     */
    public function select_customer_issue()
    {

        $this->set_db('default');

        $sql = "
        select Customer_Name from Tb_Withdraw where (Customer_Name is not null or Customer_Name <> '') and status <> -1 group by Customer_Name

        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
