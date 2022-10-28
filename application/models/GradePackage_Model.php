<?php defined('BASEPATH') or exit('No direct script access allowed');

class GradePackage_Model extends MY_Model
{

    /**
     * Menu Type
     * ---------------------------------
     * @param : null
     */
    public function select_grade_package()
    {

        $this->set_db('default');

        $sql = "
        select ms_Item.*,ms_ProductType.Product_DESCRIPTION 
        from ms_Item
        inner join ms_ProductType on ms_Item.Product_ID = ms_ProductType.Product_ID
        where ms_Item.Product_ID = '1' and Status = '1' 
        and ms_Item.ITEM_ID not in (select FG_ITEM_ID from ms_SKUMapping)
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
