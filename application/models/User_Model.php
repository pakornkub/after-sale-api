<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_Model extends MY_Model {

    /**
     * Login
     * ---------------------------------
     * @param : {array} username, password
     */
    public function select_login($param = []){

        $this->set_db('HR_TPIPL');

        $sql = "

            declare @username varchar(50)
            declare @password varchar(50)

            set @username = ?
            set @password = ?

            select
                    emUser.EmpCode
                    ,emUser.UserName
                    ,View_Employee.Title
                    ,View_Employee.FirstName
                    ,View_Employee.LastName
                    ,case  
                        when View_Employee.OrgUnitTypeId = '835B0440-070B-4E19-A409-D4A67C343E47' then View_Employee.OrgUnitName 
                        when emOrgUnit.OrgUnitTypeId = '835B0440-070B-4E19-A409-D4A67C343E47' then emOrgUnit.OrgUnitName
                        when a.OrgUnitTypeId = '835B0440-070B-4E19-A409-D4A67C343E47' then a.OrgUnitName
                    End
                    as Department
                    ,case  
                        when View_Employee.OrgUnitTypeId = 'CA29B14C-74EE-45A1-9B30-EED7C27AC5A7' then View_Employee.OrgUnitName 
                        when emOrgUnit.OrgUnitTypeId = 'CA29B14C-74EE-45A1-9B30-EED7C27AC5A7' then emOrgUnit.OrgUnitName
                        when a.OrgUnitTypeId = 'CA29B14C-74EE-45A1-9B30-EED7C27AC5A7' then a.OrgUnitName
                    End
                    as Section
                    ,case  
                        when View_Employee.OrgUnitTypeId = '28F2B38C-C89E-4066-980E-3000B8F8F136' then View_Employee.OrgUnitName 
                        when emOrgUnit.OrgUnitTypeId = '28F2B38C-C89E-4066-980E-3000B8F8F136' then emOrgUnit.OrgUnitName
                        when a.OrgUnitTypeId = '28F2B38C-C89E-4066-980E-3000B8F8F136' then a.OrgUnitName
                    End
                    as Unit
                    ,REPLACE(PositionCode,' ','') as PositionCode
                    ,PositionName
                    ,emEmployeePicture.Picture
                    ,View_Employee.OrgUnitCode
                    ,View_Employee.OrgUnitName
                    ,View_Employee.OrgCode
                    ,emUser.Group_Index

            from 	emUser left join View_Employee on emUser.EmpCode = View_Employee.EmpCode 
                    left join emOrgUnit on View_Employee.ParentOrgUnit = emOrgUnit.OrgUnitID
                    left join emOrgUnit as a on emOrgUnit.ParentOrgUnit = a.OrgUnitID
                    left join emEmployeePicture on emUser.EmpCode = emEmployeePicture.EmpCode
            where 	WorkingStatus = 'ทำงาน' and
                    emUser.UserName = @username COLLATE Latin1_General_CS_AS and emUser.CurrentPassword = @password

            union all

            select 

                    emUserSpecial.EmpCode
                    ,emUserSpecial.UserName
                    ,emUserSpecial.Title as Title
                    ,emUserSpecial.FirstName as FirstName
                    ,emUserSpecial.LastName as LastName
                    ,emUserSpecial.Department as Department
                    ,emUserSpecial.Section as Section
                    ,emUserSpecial.Unit as Unit
                    ,'' as PositionCode 
                    ,emUserSpecial.PositionName as PositionName 
                    ,'' as Picture
                    ,'' as OrgUnitCode
                    ,'' as OrgUnitName
                    ,'' as OrgCode
                    ,emUserSpecial.Group_Index

            from 	emUserSpecial 
            where 	emUserSpecial.UserName = @username COLLATE Latin1_General_CS_AS and emUserSpecial.CurrentPassword = @password
        ";

        $query = $this->db->query($sql, [ $param['username'],md5($param['password']) ]);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }

    /**
     * Permission
     * ---------------------------------
     * @param : {array} username, password, menu
     */
    public function select_permission($param = [])
    {
        $this->set_db('HR_TPIPL');

        $sql = "

            declare @username   varchar(50)
            declare @password   varchar(50)
            declare @menu_name  varchar(50)

            set @username = ?
            set @password = ?
            set @menu_name = ?

            select 

                    emUser.UserName
                    ,emUser.EmpCode
                    ,Permission_Group.Group_Index
                    ,Permission_Group.Group_Name
                    ,Permission_Menu_New.Menu_index
                    ,Permission_Menu_New.Menu_name
                    ,Permission_Menu_New.Menu_id
                    ,Permission_Menu_New.route_id
                    ,Permission_Program.Program_index
                    ,Permission_Program.Program_name
                    ,Input
                    ,Viewer
                    ,Edit
                    ,Deleted
                    ,asPrint
                    ,Approve1
                    ,Approve2
                    ,Pilot
                    ,Permission_Menu_New.Part
                    ,Permission_Menu_New.Icon
                    ,Permission_Menu_New.Segment
                    ,Permission_Menu_New.Menu_des
                    ,Permission_Menu_New.MenuType_index
                    ,Permission_Menu_New.Multilevel
                    ,Permission_Menu_New.App_id

            from emUser inner join Permission_Group on emUser.Group_Index = Permission_Group.Group_Index
            inner join Permission_Activity on Permission_Group.Group_Index = Permission_Activity.Group_Index
            inner join Permission_Menu_New on Permission_Activity.Menu_index = Permission_Menu_New.Menu_index and Permission_Menu_New.IsUse <> 0
            inner join Permission_Program on Permission_Menu_New.Program_index = Permission_Program.Program_index

            where emUser.UserName = @username COLLATE Latin1_General_CS_AS and CurrentPassword = @password

                  and Permission_Menu_New.Menu_name = @menu_name

            union all

            select 

                    emUserSpecial.UserName
                    ,emUserSpecial.EmpCode
                    ,Permission_Group.Group_Index
                    ,Permission_Group.Group_Name
                    ,Permission_Menu_New.Menu_index
                    ,Permission_Menu_New.Menu_name
                    ,Permission_Menu_New.Menu_id
                    ,Permission_Menu_New.route_id
                    ,Permission_Program.Program_index
                    ,Permission_Program.Program_name
                    ,Input
                    ,Viewer
                    ,Edit
                    ,Deleted
                    ,asPrint
                    ,Approve1
                    ,Approve2
                    ,Pilot
                    ,Permission_Menu_New.Part
                    ,Permission_Menu_New.Icon
                    ,Permission_Menu_New.Segment
                    ,Permission_Menu_New.Menu_des
                    ,Permission_Menu_New.MenuType_index
                    ,Permission_Menu_New.Multilevel
                    ,Permission_Menu_New.App_id

            from emUserSpecial inner join Permission_Group on emUserSpecial.Group_Index = Permission_Group.Group_Index
            inner join Permission_Activity on Permission_Group.Group_Index = Permission_Activity.Group_Index
            inner join Permission_Menu_New on Permission_Activity.Menu_index = Permission_Menu_New.Menu_index and Permission_Menu_New.IsUse <> 0
            inner join Permission_Program on Permission_Menu_New.Program_index = Permission_Program.Program_index

            where emUserSpecial.UserName = @username COLLATE Latin1_General_CS_AS and CurrentPassword = @password
            
                  and Permission_Menu_New.Menu_name = @menu_name

            union all

            select 

                    '' as UserName
                    ,'' as EmpCode
                    ,'' as Group_Index
                    ,'' as Group_Name
                    ,Permission_Menu_New.Menu_index
                    ,Permission_Menu_New.Menu_name
                    ,Permission_Menu_New.Menu_id
                    ,Permission_Menu_New.route_id
                    ,Permission_Program.Program_index
                    ,Permission_Program.Program_name
                    ,'' as Input
                    ,'' as Viewer
                    ,'' as Edit
                    ,'' as Deleted
                    ,'' as asPrint
                    ,'' as Approve1
                    ,'' as Approve2
                    ,'' as Pilot
                    ,Permission_Menu_New.Part
                    ,Permission_Menu_New.Icon
                    ,Permission_Menu_New.Segment
                    ,Permission_Menu_New.Menu_des
                    ,Permission_Menu_New.MenuType_index
                    ,Permission_Menu_New.Multilevel
                    ,Permission_Menu_New.App_id

            from Permission_Menu_New inner join Permission_Program on Permission_Menu_New.Program_index = Permission_Program.Program_index and Permission_Menu_New.IsUse <> 0 and MenuType_index not in (1,2,3,4,5,11,13,14)
            where 1=1 and Permission_Menu_New.Menu_name = @menu_name
            order by Menu_id
        ";
        
        $query = $this->db->query($sql, [ $param['username'],md5($param['password']),$param['menu_name'] ]);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }

    /**
     * List All User
     */
    public function select_list_all_user(){

        $this->set_db('HR_TPIPL');

        $sql = "
           select top 2 EmpCode,UserName from emUser
        ";

        $query = $this->db->query($sql);

        return ($query->num_rows() > 0) ? $query->result_array() : false;

    }

}