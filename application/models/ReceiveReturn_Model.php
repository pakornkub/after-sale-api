<?php defined('BASEPATH') or exit('No direct script access allowed');

class ReceiveReturn_Model extends MY_Model
{

    /**
     * ReceiveReturn
     * ---------------------------------
     * @param : null
     */
    public function select_receivereturn()
    {

        $this->set_db('default');

        $sql = "
        select * from view_Receive where Rec_type = '2' order by Rec_ID DESC

        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * receive no
     * ---------------------------------
     * @param : null
     */
    public function select_receive_no()
    {

        $this->set_db('default');

        $sql = "
        select dbo.[fnGetRcDocNo] ('2') as ReceiveNo
        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Insert ReceiveReturn
     * ---------------------------------
     * @param : FormData
     */
    public function insert_receivereturn($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_Receive', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

    /**
     * Insert ReceiveReturn Item
     * ---------------------------------
     * @param : FormData
     */
    public function insert_receiveReturn_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('Tb_ReceiveItem', $param['data'])) ? $this->db->insert_id() : false/*$this->db->error()*/;

    }

     /**
     * Update ReceiveReturn
     * ---------------------------------
     * @param : FormData
     */
    public function update_receiveReturn($param = [])
    {
        $this->set_db('default');

        return ($this->db->update('Tb_Receive', $param['data'], ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }

     /**
     * Delete ReceiveReturn
     * ---------------------------------
     * @param : ReceiveReturn_Index
     */
    public function delete_receivereturn($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_Receive', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }
         /**
     * Delete ReceiveReturn Item
     * ---------------------------------
     * @param : ReceiveReturn_ID
     */
    public function delete_receivereturn_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->delete('Tb_ReceiveItem', ['Rec_ID'=> $param['index']])) ? true : false/*$this->db->error()*/;

    }



    /**
     * ReceiveReturnItem
     * ---------------------------------
     * @param : null
     */
    public function select_receivereturnitem($param)
    {

        $this->set_db('default');

        $sql = "
            select Tb_ReceiveItem.RecItem_ID as [key],Tb_ReceiveItem.Item_ID as Grade_ID,ms_Item.ITEM_CODE as Grade_Name,Tb_ReceiveItem.Lot_No,Tb_ReceiveItem.Qty as QTY
            from Tb_ReceiveItem
            inner join ms_Item on Tb_ReceiveItem.Item_ID = ms_Item.ITEM_ID
            where Rec_ID = '$param'
            order by RecItem_ID

            
        ";

        $query = $this->db->query($sql,$param);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;



    }

}