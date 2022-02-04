<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PC_TrialProductForCustomer_Model extends MY_Model {

    /**
     * Select Product Spec
     * ---------------------------------
     * @param : {array} inputBarcode
     */
    public function select_product_spec($param = []){

        $this->set_db('default');

        $sql = "

            declare @inputBarcode varchar(50)

            set @inputBarcode = ?

            select	    top 1 
                        BaggingOrder_No		as 'Bagout_No'
                        ,convert(varchar(50),Item_Seq)			as 'Number'
                        ,Pallet_No			as 'Pallet_No'
                        ,b.PLot				as 'Lot_No'
                        ,sku.Sku_Id			as 'Grade'
                        ,convert(varchar(50),bi.Qty)			as 'Qty'
                        ,convert(varchar(50),bi.Total_Qty)		as 'Package'
                        ,cP4				as 'Viscosity'
                        ,SContent_Oven		as 'Solid_Content'
            from        tb_BaggingOrder b inner join tb_BaggingOrderItem bi on b.BaggingOrder_Index = bi.BaggingOrder_Index 
                        inner join ms_SKU sku on b.Sku_Index = sku.Sku_Index
                        inner join MRP_VAE.dbo.VAE_Lot lot on b.PLot = lot.Lot_Create
                        inner join MRP_VAE.dbo.VAE_QCResultBag qc on lot.Lot_No = qc.Lot_No and 'Bag '+convert(varchar(50),bi.Item_Seq) = qc.Title
            where       BaggingLine_No = 'VAE' and b.Status <> -1 and Pallet_No = @inputBarcode 
            order by    BaggingOrder_Date DESC
           
        ";

        $query = $this->db->query($sql, [ $param['inputBarcode'] ]);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }


    /**
     * Select List Product Spec
     * ---------------------------------
     * @param : {array} inputSearch
     */
    public function select_list_product_spec($param = []){

        $this->set_db('MRP_VAE');

        $sql_var = '';
        $sql_where = '';
        $sql_param = [];

        if(isset($param['data']['Pallet_No']) && $param['data']['Pallet_No'])
        {
            $sql_var    .= ' declare @Pallet_No varchar(50) set @Pallet_No = ? ';
            $sql_where  .= ' and Pallet_No = @Pallet_No ';
            array_push($sql_param,$param['data']['Pallet_No']);
        }

        if(isset($param['data']['Grade']) && $param['data']['Grade'])
        {
            $sql_var    .= ' declare @Grade varchar(50) set @Grade = ? ';
            $sql_where  .= ' and Grade = @Grade ';
            array_push($sql_param,$param['data']['Grade']);
        }

        if(isset($param['data']['Lot_No']) && $param['data']['Lot_No'])
        {
            $sql_var    .= ' declare @Lot_No varchar(50) set @Lot_No = ? ';
            $sql_where  .= ' and Lot_No = @Lot_No ';
            array_push($sql_param,$param['data']['Lot_No']);
        }

        if(isset($param['inputSearch']) && $param['inputSearch'])
        {
            $sql_where .= " and ( Pallet_No like '%".$param['inputSearch']."%' or Grade like '%".$param['inputSearch']."%' or Lot_No like '%".$param['inputSearch']."%' or Bagout_No like '%".$param['inputSearch']."%')";
        }

        $sql = "

            select  top 10 
                    convert(varchar(50),TrialProductForCustomer_Index) as 'TrialProductForCustomer_Index'
                    ,Pallet_No
                    ,Grade
                    ,Lot_No
                    ,convert(varchar(50),Number) as 'Number'
                    ,Bagout_No
                    ,convert(varchar(50),VAE_Qty) as 'VAE_Qty'
                    ,convert(varchar(50),VAE_Package) as 'VAE_Package'
                    ,VAE_Viscosity
                    ,VAE_Solid_Content
                    ,convert(varchar(50),PC_Qty) as 'PC_Qty'
                    ,convert(varchar(50),PC_Package) as 'PC_Package'
                    ,PC_Viscosity
                    ,PC_Solid_Content
                    ,PC_RunningNumber
                    ,convert(varchar(50),PC_DrumNumber) as 'PC_DrumNumber'
                    ,Status
                    ,Ref_Index
                    ,add_by
                    ,add_date
                    ,approve_by
                    ,approve_date
                    ,approve2_by
                    ,approve2_date
            from    PC_TrialProductForCustomer
            where   1=1 and Status <> -1
        ";

        $sql_order = "
        
            order by TrialProductForCustomer_Index DESC
        
        ";

        $query = $this->db->query( $sql_var.$sql.$sql_where.$sql_order, $sql_param);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }

    /**
     * Insert Product Spec
     * ---------------------------------
     * @param : {array} ***form_data
     */
    public function insert_product_spec($param = [])
    {
        $this->set_db('MRP_VAE');

        if($param['TrialProductForCustomer_Index'])
        {
            $this->db->delete('PC_TrialProductForCustomer', array('TrialProductForCustomer_Index' => $param['TrialProductForCustomer_Index']));
        }

        return ($this->db->insert('PC_TrialProductForCustomer',$param['data'])) ? $this->db->insert_id() : false /*$this->db->error()*/;

    }

     /**
     * Update Product Spec
     * ---------------------------------
     * @param : {array} ***form_data
     */
    public function update_product_spec($param = [])
    {
        $this->set_db('MRP_VAE');

        $result = $this->db->update("PC_TrialProductForCustomer",$param['data'],"TrialProductForCustomer_Index = '".$param['TrialProductForCustomer_Index']."'");

        return ($result) ? true : false /*$this->db->error()*/;

    }


}