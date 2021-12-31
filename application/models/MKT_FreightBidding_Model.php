<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MKT_FreightBidding_Model extends MY_Model {

    /**
     * Select Port Country
     * ---------------------------------
     * @param : {array} null
     */
    public function select_port_country($param = []){

        $this->set_db('default');

        $sql = "

            select PortCountry_Index as id, Port_Name as name , Country as country from ms_PortCountry order by Country,Port_Name
           
        ";

        $query = $this->db->query($sql);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }

    /**
     * Select Vessel
     * ---------------------------------
     * @param : {array} null
     */
    public function select_vessel($param = []){

        $this->set_db('default');

        $sql = "

            select Vessel_Index as id , Vessel_Name as name from ms_Vessel order by Vessel_Name
           
        ";

        $query = $this->db->query($sql);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }

    /**
     * Select Freight Bidding
     * ---------------------------------
     * @param : {array} ***form_data
     */
    public function select_quotation_no($param = []){

        $this->set_db('default');

        $sql = "

            select 
                        fb.PortCountry_Index
                        ,fb.Quotation_No
            
            from        tb_FreightBidding fb 

            where       1=1 and fb.PortCountry_Index = ? and fb.Quotation_No = ?
           
        ";
      
        $query = $this->db->query($sql,[$param['PortCountry_Index'],$param['Quotation_No']]);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }

    /**
     * Select Freight Bidding
     * ---------------------------------
     * @param : {array} ***form_data
     */
    public function select_freight_bidding($param = []){

        $this->set_db('default');

        $sql_var = '';
        $sql_where = '';
        $sql_param = [];

        if(isset($param['startDate']) && $param['startDate'] && isset($param['endDate']) && $param['endDate'])
        {
            $sql_var    .= ' declare @startDate varchar(50) set @startDate = ? ';
            $sql_var    .= ' declare @endDate varchar(50) set @endDate = ? ';
            $sql_where  .= ' and convert(date,fb.add_date) between @startDate and @endDate ';
            array_push($sql_param,$param['startDate']);
            array_push($sql_param,$param['endDate']);
        }
        else if(isset($param['startDate']) && $param['startDate'])
        {
            $sql_var    .= ' declare @startDate varchar(50) set @startDate = ? ';
            $sql_where  .= ' and convert(date,fb.add_date) >= @startDate ';
            array_push($sql_param,$param['startDate']);
        }
        else if(isset($param['endDate']) && $param['endDate'])
        {
            $sql_var    .= ' declare @endDate varchar(50) set @endDate = ? ';
            $sql_where  .= ' and convert(date,fb.add_date) <= @endDate ';
            array_push($sql_param,$param['endDate']);
        }

        $sql = "

            select 
                        fb.FreightBidding_Index as id
                        ,port.Port_Name
                        ,port.PortCountry_Index
                        ,port.Country
                        ,fb.Transit
                        ,fb.Time_Day
                        ,fb.Valid_Until
                        ,fb.Quoter
                        ,fb.Quotation
                        ,fb.Quotation_No
                        ,ves.Vessel_Name
                        ,ves.Vessel_Index
                        ,fb.Status as Status_FB
                        ,fb.add_date as add_date_FB
                        ,(

                            select count(*) from tb_FreightBidding_History where Quotation_No = fb.Quotation_No and PortCountry_Index = fb.PortCountry_Index

                        ) as countImprove
                        ,fbi.*
            
            from        tb_FreightBidding fb 
                        left join ms_PortCountry port on  fb.PortCountry_Index = port.PortCountry_Index
                        left join ms_Vessel ves on fb.Vessel_Index = ves.Vessel_Index
                        left join tb_FreightBiddingItem fbi on fb.FreightBidding_Index = fbi.FreightBidding_Index
            
            where       1=1
           
        ";

        $sql_order = "
            order by    fb.add_date DESC
        ";

        $query = $this->db->query($sql_var.$sql.$sql_where.$sql_order,$sql_param);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }

    /**
     * Insert Freight Bidding / Freight Bidding Item
     * ---------------------------------
     * @param : {array} ***form_data
     */
    public function insert_freight_bidding($param = [])
    {
        $this->set_db('default');

        $this->db->trans_begin();

        if($param['FreightBidding_Index'])
        {
            $this->db->delete('tb_FreightBidding', array('FreightBidding_Index' => $param['FreightBidding_Index']));
        }

        $FreightBidding_Index_New =  ($this->db->insert('tb_FreightBidding',$param['data_header'])) ? $this->db->insert_id() : false /*$this->db->error()*/;

        $con_num=1;
        $con_count=0;

        if(isset($FreightBidding_Index_New) && $FreightBidding_Index_New)
        {

            while($con_num <= 2)
            {
                $con = '_con'.$con_num;

                $param['data_detail'.$con]['FreightBidding_Index'] = $FreightBidding_Index_New;

                $this->db->insert('tb_FreightBiddingItem',$param['data_detail'.$con]);

                $con_num++;
            }
        }
        
        return $this->check_begintrans() ? $FreightBidding_Index_New : false;

    }

}