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

            select se_User.*,se_Group.Name as Group_Name 
            from 	se_User
            left join se_Group on se_User.Group_Index = se_Group.Group_Index

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

            exec SP_Permission ?,?,?
          
        ";
        
        $query = $this->db->query($sql, [ $param['username'],md5($param['password']),$param['platform'] ]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Permission not token
     * ---------------------------------
     * @param : {array} username
     */
    public function select_permission_new($param = [])
    {
        $this->set_db('default');

        $sql = "

            exec SP_Permission_New ?,?
          
        ";
        
        $query = $this->db->query($sql, [ $param['username'],'WA' ]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}