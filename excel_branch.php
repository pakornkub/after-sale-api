<?php
$serverName = "119.59.105.14";  
$connectionInfo = array( "Database"=>"TOTO_AfterSale230818", "UID"=>"sa", "PWD"=>"Dev@2022");  
$conn = sqlsrv_connect( $serverName, $connectionInfo);  
  
if( $conn )  
{  
     echo "Connection established.\n";
	if(($result = sqlsrv_query($conn,"select * from Tb_RequestBranch where RequestBranch_ID = '".$_GET['ID']."' ")) !== false){
        	while( $obj = sqlsrv_fetch_object( $result )) {
              		echo $obj->RequestBranch_No.'<br />';
        	}
    	}
	if(($result = sqlsrv_query($conn,"select Tb_RequestBranchItem.ITEM_ID as [key],ms_Item.ITEM_ID as ITEM_ID,ms_Item.ITEM_CODE as ITEM_CODE,ms_Item.ITEM_DESCRIPTION as ITEM_DESCRIPTION,
		ms_ProductType.Product_DESCRIPTION as Product_DESCRIPTION,Tb_RequestBranchItem.QTY as QTY  
		from Tb_RequestBranchItem
		inner join ms_Item on ms_Item.ITEM_ID = Tb_RequestBranchItem.ITEM_ID
		inner join ms_ProductType on ms_Item.Product_ID = ms_ProductType.Product_ID
		where RequestBranch_ID = '".$_GET['ID']."' ")) !== false){
        	while( $obj = sqlsrv_fetch_object( $result )) {
              		echo $obj->ITEM_ID.'<br />';
        	}
    	}  
}  
else  
{  
     echo "Connection could not be established.\n";  
     die( print_r( sqlsrv_errors(), true));  
}  
  

sqlsrv_close( $conn);  
	

?>

	<html>
		<head>
			<meta charset="utf-8">
			<title>Cost Report</title>
           	<style>
				body
				{
					font-family:Tahoma, Geneva, sans-serif;
					font-size:12px;
				}
				.hide
				{
					display:none;
				}
		   	</style>
	</head>

	<body>
    <form id="form_cost" name="form_cost">
    	<div style="font-size:14px;">
        	<center>
            	<br><b>PD Order : <font color="#0066FF"></font></b><br>
            	<br><b>Grade : <font color="#0066FF"></font></b><br>
            </center>
        </div>
        <br>
        <div>
        <table>
        	<tr>
        		<td colspan="5" valign="top">
        			<table width="100%" >
                <tr>
                    <td colspan="4" align="center"><u>ข้อมูล Production</u></td>
                  </tr>
                  <tr height="40">
                    <td colspan="1"></td>
                      <td align="center">Planing</td>
                      <td align="center">Actual</td>
                  </tr>
                <tr>
                    <td width="150">Start Date &nbsp;&nbsp;&nbsp;&nbsp; :</td>
                      <td align="center"></td>
                    <td align="center"></td>

                 </tr>   
              </table>

        		</td>
        		<td colspan="7" valign="top">
        			<table x:str BORDER="1"  align="center" style="font-size:12px;">
			            <tr class="s_height_tr" style="vertical-align:middle;text-align:center; font-weight:bold;">
			                <td colspan="1" rowspan="2" bgcolor="#48A4FF">Cost</td>
			                <td colspan="2" bgcolor="#66FFFF">Planing</td>
			                <td colspan="2" bgcolor="#00AA55">Actual</td>
			                <td colspan="2" bgcolor="#FFFF80">Diff</td>
			            </tr>
			            <tr class="s_height_tr" style="vertical-align:middle;text-align:center;font-weight:bold;">
			                <td height="20" width="100" bgcolor="#CCCCCC">Price/Kg</td>
			                <td width="100" bgcolor="#CCCCCC">Price/Sqm</td>
			                <td class="<?=$hide_v?>" width="100" bgcolor="#CCCCCC">Price/Kg</td>
			                <td class="<?=$hide_v?>" width="100" bgcolor="#CCCCCC">Price/Sqm</td>
			                <td class="<?=$hide_v?>" width="100" bgcolor="#CCCCCC">Price/Kg</td>
			                <td class="<?=$hide_v?>" width="100" bgcolor="#CCCCCC">Price/Sqm</td>
			            </tr>
			            
			        </table>


        		</td>
        	</tr>
        </table>
            </tbody>
        </table>
    </form>
	</body>
</html>
