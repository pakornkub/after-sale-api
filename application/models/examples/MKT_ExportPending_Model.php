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
                        ep.ExportPending_Index as id
                        ,convert(date,ep.Confirm_Date) as Confirm_Date_EP
                        ,ep.Vessel_Name as Vessel_Name_EP
                        ,fb.Vessel_Name as Vessel_Name_FB
                        ,ep.Booking_No as Booking_No_EP
                        ,fb.Booking_No as Booking_No_FB
                        ,ep.Agent_Line as Agent_Line_EP
                        ,convert(date,ep.Latest_Date) as Latest_Date_EP
                        ,convert(date,ep.Expiry_Date_) as Expiry_Date_EP
                        ,convert(date,ep.ETA_Date) as ETA_Date_EP
                        ,convert(date,ep.ETD_Date) as ETD_Date_EP
                        ,convert(varchar(10),convert(date,ep.Closing_Date))+'T'+ep.Closing_Time as Closing_Date_EP
                        ,fb.Closing_Date as Closing_Date_FB
                        ,ep.LatestPresent_Date as LatestPresent_Date_EP 
                        ,convert(date,ep.Loading_Date) as Loading_Date_EP
                        ,fb.Status as Status_FB
                        ,*
                        ,(

                            select count(*) from tb_FreightBooking_History where ExportPending_Index = ep.ExportPending_Index

                        ) as countImprove
            from        VIEW_TPIPL_MKT_ExportPending ep left join tb_FreightBooking fb on ep.ExportPending_Index = fb.ExportPending_Index
                        inner join [192.168.21.59\hrserver_tpipl].HR_TPIPL.dbo.emUserSpecial hr on ep.add_by = hr.UserName
            where       1=1 and ( DATEDIFF(day, ETD_Date, GETDATE()) <= 60 or ETD_Date is null ) and hr.Group_Index = '88'
        ";

        $sql_order = "
            order by    ep.ExportPending_Index 
        ";

        $query = $this->db->query($sql_var.$sql.$sql_where.$sql_order,$sql_param);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }

}