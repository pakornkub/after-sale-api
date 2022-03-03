<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_Model extends MY_Model {

    /**
     * Login
     * ---------------------------------
     * @param : {array} username, password
     */
    public function select_login($param = []){

        $this->set_db('default');

        $sql = "

            declare @username varchar(50)
            declare @password varchar(50)

            set @username = ?
            set @password = ?

            select *

            from 	se_User

            where 	UserName = @username COLLATE Latin1_General_CS_AS and CurrentPassword = @password

        ";

        $query = $this->db->query($sql, [ $param['username'],md5($param['password']) ]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Permission
     * ---------------------------------
     * @param : {array} username, password
     */
    public function select_permission($param = [])
    {
        $this->set_db('default');

        $sql = "

            declare @username   varchar(50)
            declare @password   varchar(50)

            set @username = ?
            set @password = ?

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
          
        ";
        
        $query = $this->db->query($sql, [ $param['username'],md5($param['password']) ]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}