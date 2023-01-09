<?php defined('BASEPATH') or exit('No direct script access allowed');

class GradeAll_Model extends MY_Model
{

    /**
     * Menu Type
     * ---------------------------------
     * @param : null
     */
    public function select_grade_all()
    {

        $this->set_db('default');

        $sql = "
        select ms_Item.*,ms_ProductType.Product_DESCRIPTION 
        from ms_Item
        inner join ms_ProductType on ms_Item.Product_ID = ms_ProductType.Product_ID
        where Status = '1'
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
