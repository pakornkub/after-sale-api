<?php defined('BASEPATH') or exit('No direct script access allowed');

class ShipToWH_Model extends MY_Model
{
    /**
     * Update ShipToWH
     * ---------------------------------
     * @param : FormData
     */
    public function update_ship_to_wh($param = [])
    {
        $this->set_db('default');

        $this->db->trans_begin();

        foreach ($param['items'] as $value) {

            $tag_data = [
                'ItemStatus_ID' => 4,
                'Update_By' => $param['user']['Update_By'],
                'Update_Date' => $param['user']['Update_Date'],
            ];

            $stock_data = [
                'ItemStatus_ID' => 4,
                'Location_ID' => 2,
                'Update_By' => $param['user']['Update_By'],
                'Update_Date' => $param['user']['Update_Date'],
            ];

            $where = [
                'QR_NO' => $value['QR_NO'],
            ];

            $this->db->update('Tb_TagQR', $tag_data, $where);

            $this->db->update('Tb_StockBalance', $stock_data, $where);

        }

        return $this->check_begintrans();/*$this->db->error()*/;

    }

}
