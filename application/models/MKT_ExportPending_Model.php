<?php 

ini_set('memory_limit','256M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
ini_set('sqlsrv.ClientBufferMaxKBSize','524288'); // Setting to 512M
ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288'); // Setting to 512M - for pdo_sqlsrv

defined('BASEPATH') OR exit('No direct script access allowed');

class MKT_ExportPending_Model extends MY_Model {

    /**
     * Select Export Pending
     * ---------------------------------
     * @param : {array} ***form_data
     */
    public function select_export_pending($param = []){

        $this->set_db('default');

        $sql_var = '';
        $sql_where = '';
        $sql_param = [];

        if(isset($param['startDate']) && $param['startDate'] && isset($param['endDate']) && $param['endDate'])
        {
            $sql_var    .= ' declare @startDate varchar(50) set @startDate = ? ';
            $sql_var    .= ' declare @endDate varchar(50) set @endDate = ? ';
            $sql_where  .= ' and Confirm_Date between @startDate and @endDate ';
            array_push($sql_param,$param['startDate']);
            array_push($sql_param,$param['endDate']);
        }
        else if(isset($param['startDate']) && $param['startDate'])
        {
            $sql_var    .= ' declare @startDate varchar(50) set @startDate = ? ';
            $sql_where  .= ' and Confirm_Date >= @startDate ';
            array_push($sql_param,$param['startDate']);
        }
        else if(isset($param['endDate']) && $param['endDate'])
        {
            $sql_var    .= ' declare @endDate varchar(50) set @endDate = ? ';
            $sql_where  .= ' and Confirm_Date <= @endDate ';
            array_push($sql_param,$param['endDate']);
        }

        if(isset($param['priority']) && $param['priority'])
        {
            if($param['priority'] == 1)
            {
                $sql_where  .= ' and (TT_Date is not null or LC_Date is not null) and Loading_Date is not null ';
            }
            else if($param['priority'] == 2)
            {
                $sql_where  .= ' and (TT_Date is not null or LC_Date is not null) and Loading_Date is null ';
            }
            else if($param['priority'] == 3)
            {   
                $sql_where  .= ' and (TT_Date is null and LC_Date is null) and Loading_Date is null ';
            }
        }

        $sql = "
            select 
                        ex.ExportPending_Index as id
                        ,convert(date,ex.Confirm_Date) as Confirm_Date_EX
                        ,ex.Vessel_Name as Vessel_Name_EX
                        ,fb.Vessel_Name as Vessel_Name_FB
                        ,ex.Booking_No as Booking_No_EX
                        ,fb.Booking_No as Booking_No_FB
                        ,convert(date,ex.Latest_Date) as Latest_Date_EX
                        ,convert(date,ex.Expiry_Date_) as Expiry_Date_EX
                        ,fb.Status as Status_FB
                        ,*
                        ,(

                            select count(*) from tb_FreightBooking_History where ExportPending_Index = ex.ExportPending_Index

                        ) as countImprove
            from        VIEW_TPIPL_MKT_ExportPending ex left join tb_FreightBooking fb on ex.ExportPending_Index = fb.ExportPending_Index
            where       1=1
        ";

        $sql_order = "
            order by    ex.ExportPending_Index 
        ";

        $query = $this->db->query($sql_var.$sql.$sql_where.$sql_order,$sql_param);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }

}