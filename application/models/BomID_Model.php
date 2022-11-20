<?php defined('BASEPATH') or exit('No direct script access allowed');

class BomID_Model extends MY_Model
{

    /**
     * BomID
     * ---------------------------------
     * @param : null
     */
    public function select_BomID($param)
    {

        $this->set_db('default');

        $sql = "
            select ms_Item.ITEM_CODE,
                CASE WHEN Count(Bom_Rev_No)+1 <   10 THEN ms_Item.ITEM_CODE+'-'+'00'+ CONVERT(varchar(2),Count(Bom_Rev_No)+1)
                    WHEN Count(Bom_Rev_No)+1 >=  10 THEN ms_Item.ITEM_CODE+'-'+'0'+ CONVERT(varchar(2),Count(Bom_Rev_No)+1)
                    WHEN Count(Bom_Rev_No)+1 >= 100 THEN ms_Item.ITEM_CODE+'-'+CONVERT(varchar(2),Count(Bom_Rev_No)+1)	
                ELSE 'No' END as Bom_ID,
                CASE WHEN Count(Bom_Rev_No)+1 <   10 THEN '00'+ CONVERT(varchar(2),Count(Bom_Rev_No)+1)
                    WHEN Count(Bom_Rev_No)+1 >=  10 THEN '0'+ CONVERT(varchar(2),Count(Bom_Rev_No)+1)
                    WHEN Count(Bom_Rev_No)+1 >= 100 THEN CONVERT(varchar(2),Count(Bom_Rev_No)+1)	
                ELSE 'No' END as Rev_No

            from ms_BOM
            right join ms_Item on ms_Item.ITEM_ID = ms_BOM.FG_ITEM_ID
            where ms_Item.ITEM_ID = '$param'
            group by ms_Item.ITEM_CODE 
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    


}
