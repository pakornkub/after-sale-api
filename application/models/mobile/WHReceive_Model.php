<?php defined('BASEPATH') or exit('No direct script access allowed');

class WHReceive_Model extends MY_Model
{
    /**
     * Update WHReceive
     * ---------------------------------
     * @param : FormData
     */
    public function update_wh_receive($param = [])
    {
        $this->set_db('default');

        $this->db->trans_begin();

        $UniqueKey = date('YmdHis');

        foreach ($param['items'] as $value) {

            $data = [
                'UniqueKey' => $UniqueKey,
                'QR_NO' => $value['QR_NO'],
                'ITEM_ID' => $value['ITEM_ID'],
                'Qty' => $value['QTY'],
                'Create_Date' => $param['user']['Create_Date'],
                'Create_By' => $param['user']['Create_By'],
     
            ];

            $this->db->insert('Temp_WithdrawItem', $data);

        }

        $sql = "

            exec [dbo].[SP_WithdrawTrans] ?,?,?

        ";

        $this->db->query($sql, [$UniqueKey,$param['user']['Create_By'],'WHReceive']);

        return $this->check_begintrans();/*$this->db->error()*/;

    }

}
