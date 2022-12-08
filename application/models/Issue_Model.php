<?php defined('BASEPATH') or exit('No direct script access allowed');

class Issue_Model extends MY_Model
{

    /**
     * Issue
     * ---------------------------------
     * @param : null
     */
    public function select_issue()
    {

        $this->set_db('default');

        $sql = "
        select * from View_Request where Withdraw_type in ('1','2') order by Withdraw_ID DESC

        ";

        $query = $this->db->query($sql);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }




    /**
     * Insert Issue
     * ---------------------------------
     * @param : FormData
     */
    public function insert_issue($a= null,$b= null,$c= null,$d= null)
    {
        $this->set_db('default');

        $sql = "

        exec [dbo].[SP_TransferTeam]  ?,?,?,?
          
        ";

        


        return $this->db->query($sql,[$a,$b,$c,$d]) ? true : false;
        
        
    

    }

    

    /**
     * Insert Issue Item
     * ---------------------------------
     * @param : FormData
     */
    public function insert_issue_item($param = [])
    {
        $this->set_db('default');

        $this->db->trans_begin();

        $UniqueKey = date('YmdHis');

        foreach ($param['items'] as $value) {

            $data = [
                'UniqueKey' => $UniqueKey,
                'Withdraw_ID' => null,
                'QR_NO' => $value['QR_NO'],
                'ITEM_ID' => $value['ITEM_ID'],
                'Qty' => $value['Qty'],
                'Create_Date' => $param['user']['Create_Date'],
                'Create_By' => $param['user']['Create_By'],
     
            ];

            $this->db->insert('Temp_WithdrawItem', $data);

        }

        $sql = "

            exec [dbo].[SP_WithdrawTrans] ?,?,?

        ";

        $this->db->query($sql, [$UniqueKey,$param['user']['Create_By'],'Issue']);

        return $this->check_begintrans();/*$this->db->error();*/
    }


    /**
     * Tag
     * ---------------------------------
     * @param : null
     */
    public function select_stockbal($param = [])
    {

        $this->set_db('default');

        $sql = "
        select TOP 1 * from View_ItemForQuotation where QR_NO = ? and Location_ID = ? and Bal_QTY <> 0
        ";

        $query = $this->db->query($sql,[$param['QR_NO'],$param['Location_ID']]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }
    


    

}