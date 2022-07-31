<?php defined('BASEPATH') or exit('No direct script access allowed');

class Permission_Model extends MY_Model
{

    /**
     * User Permission
     * ---------------------------------
     * @param : null
     */
    public function select_user_permission($UserName = null, $Platform = null)
    {

        $this->set_db('default');

        $sql = "

            exec SP_GroupPermission ?,?

        ";

        $query = $this->db->query($sql, [$UserName, $Platform]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

    /**
     * Group Permission
     * ---------------------------------
     * @param : null
     */
    public function select_group_permission($Group_Index = null, $Platform = null)
    {

        $this->set_db('default');

        $sql = "

            exec SP_GroupPermission ?,?

        ";

        $query = $this->db->query($sql, [$Group_Index, $Platform]);

        $result = ($query->num_rows() > 0) ? $query->result_array() : false;

        return $result;

    }

}
