<?php defined('BASEPATH') or exit('No direct script access allowed');

class SplitPart_Model extends MY_Model
{

    /**
     * SplitPart
     * ---------------------------------
     * @param : null
     */
    public function select_split_part()
    {

        $this->set_db('default');

        $sql = "
           select * from Tb_Receive where Rec_type = 4 and Status in (2,3) order by Rec_ID DESC
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Update SplitPart
     * ---------------------------------
     * @param : FormData
     */
    public function update_split_part($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('Tb_Receive', $param['data'], $param['where'])) ? true : false/*$this->db->error()*/;

    }

    /**
     * SplitPart Item
     * ---------------------------------
     * @param : Rec_ID
     */
    public function select_split_part_Item($Rec_ID)
    {

        $this->set_db('default');

        $sql = "

            select
                        ROW_NUMBER() Over (Order by r.Rec_ID) as 'No'
                        ,i.ITEM_CODE as 'Part'
                        ,i.ITEM_ID as 'Item_ID'
                        ,(
                            select count(*) from Tb_TagQR where Rec_ID = r.Rec_ID and Item_ID = ri.Item_ID and RecItem_ID = ri.RecItem_ID and ItemStatus_ID = 5 and Tag_Status = 9
                        ) as 'Good'
                        ,ri.Qty as 'Total'

            from		Tb_Receive r
                        inner join Tb_ReceiveItem ri on r.Rec_ID = ri.Rec_ID
                        inner join ms_Item i on ri.Item_ID = i.ITEM_ID

            where		r.Rec_ID = ?

        ";

        $query = $this->db->query($sql, [$Rec_ID]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Exec SplitPart Transaction
     * ---------------------------------
     * @param : Rec_ID, QR_NO, Tag_ID, Username
     */
    public function exec_split_part_transaction($param = [])
    {

        $this->set_db('default');

        $sql = "

            exec [dbo].[SP_CreateSplitTransaction] ?,?,?,?

        ";

        $query = $this->db->query($sql,[$param['QR_NO'],$param['Rec_ID'],$param['Tag_ID'],$param['Username']]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
