<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 20/11/2016
 * Time: 17:39
 * users_users_groups
 */
class Usersgroups_model   extends MY_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->set_table_name('users_groups');
        $this->set_primary_key('id');
    }

    public function get_all($conditions = null){

        $this->db->select('users_groups.*');
        $this->db->from('users_groups');
        $this->db->order_by('users_groups.id', 'DESC');

        if ($conditions != null){
            $this->_set_db_fields($conditions);
        }

        return $this->db->get()->result_array();
    }


    public function get_last_ten_entries()
    {
        return $this->get_all();
    }

    public function get_permisos($group_id){
//        $conditions
    }

    function get_by_id($id){
        $conditions = array('users_groups.id' => $id);
        return $this->get($conditions);
    }

    //TODO: Eliminar roles de un usuario por el user id
    public function delete_by_user($user_id){
        $this->db->delete($this->table_name, array('user_id' => $user_id));
        return $this->db->affected_rows();
    }
    

}