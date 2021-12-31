<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MKT_FreightBooking_Model extends MY_Model {

    /**
     * Select Shipment Status
     * ---------------------------------
     * @param : {array} null
     */
    public function select_shipment_status($param = []){

        $this->set_db('default');

        $sql = "

            select * from ms_ShipmentStatus 
           
        ";

        $query = $this->db->query($sql);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }

    /**
     * Select Freight Bidding
     * ---------------------------------
     * @param : {array} ***form_data
     */
    public function select_freight_bidding($param = []){

        $this->set_db('default');

        $sql = "
           
            select 
                        fb.FreightBidding_Index as id
                        ,port.Port_Name
                        ,port.Country
                        ,fb.Quotation_No
                        ,ves.Vessel_Name
                        ,isnull((

                            select Freight+LSS+WSC  from tb_FreightBiddingItem fbi where fbi.FreightBidding_Index = fb.FreightBidding_Index and Con_Type = 20

                        ),0) as Con_20
                        ,isnull((

                            select Freight+LSS+WSC  from tb_FreightBiddingItem fbi where fbi.FreightBidding_Index = fb.FreightBidding_Index and Con_Type = 40

                        ),0) as Con_40

            from        tb_FreightBidding fb 
                        left join ms_PortCountry port on  fb.PortCountry_Index = port.PortCountry_Index
                        left join ms_Vessel ves on fb.Vessel_Index = ves.Vessel_Index

            where       Port_Name = ? and Country = ? 

            order by    fb.Quotation_No,fb.add_date DESC

        ";

        $query = $this->db->query($sql,[$param['Port'],$param['Country']]);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }

    /**
     * Insert Freight Booking
     * ---------------------------------
     * @param : {array} ***form_data
     */
    public function insert_freight_booking($param = [])
    {
        $this->set_db('default');

        if($param['FreightBooking_Index'])
        {
            $this->db->delete('tb_FreightBooking', array('FreightBooking_Index' => $param['FreightBooking_Index']));
        }

        return ($this->db->insert('tb_FreightBooking',$param['data'])) ? $this->db->insert_id() : false /*$this->db->error()*/;

    }

}