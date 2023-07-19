<?php defined('BASEPATH') or exit('No direct script access allowed');

class QuotationNoIssue_Model extends MY_Model
{

    /**
     * Menu Type
     * ---------------------------------
     * @param : null
     */
    public function select_quotationno_issue()
    {

        $this->set_db('default');

        $sql = "
        select Quotation_No from Tb_Withdraw where (Quotation_No is not null or Quotation_No <> '') and status <> -1 group by Quotation_No
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
