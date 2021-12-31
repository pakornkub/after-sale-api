<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MKT_Bidding_Model extends MY_Model {

    /**
     * Select Port
     * ---------------------------------
     * @param : {array} null
     */
    public function select_port($param = []){

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
     * Select Sea Freight
     * ---------------------------------
     * @param : {array} ***form_data
     */
    public function select_sea_freight($param = []){

        $this->set_db('default');

        $sql = "

            select 
                        sf.SeaFreight_Index as id
                        ,sf.add_date
                        ,port.Port_Name
                        ,sf.Transit
                        ,sf.Time_Day
                        ,sf.Valid_Until
                        ,sf.Quoter
                        ,sf.Quotation
                        ,sf.Quotation_No
                        ,ves.Vessel_Name
            
            from        tb_SeaFreight sf 
                        left join ms_PortCountry port on  sf.PortCountry_Index = port.PortCountry_Index
                        left join ms_Vessel ves on sf.Vessel_Index = ves.Vessel_Index
            
            order by    add_date DESC
           
        ";

        $query = $this->db->query($sql);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }

    

    /**
     * Insert Sea Freight
     * ---------------------------------
     * @param : {array} ***form_data
     */
    public function insert_sea_freight($param = [])
    {
        $this->set_db('default');

        if($param['SeaFreight_Index'])
        {
            $this->db->delete('tb_SeaFreight', array('SeaFreight_Index' => $param['SeaFreight_Index']));
        }

        return ($this->db->insert('tb_SeaFreight',$param['data_header'])) ? $this->db->insert_id() : false /*$this->db->error()*/;

    }

    /**
     * Insert Sea Freight Item
     * ---------------------------------
     * @param : {array} ***form_data
     */
    public function insert_sea_freight_item($param = [])
    {
        $this->set_db('default');

        return ($this->db->insert('tb_SeaFreightItem',$param['data_detail'])) ? $this->db->insert_id() : false /*$this->db->error()*/;

    }

}