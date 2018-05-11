<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 19/11/2016
 * Time: 18:44
 */

class users_model  extends MY_Model {

    var $permisos_model = null;
    var $groups_model = null;
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->set_table_name('users');
        $this->set_primary_key('id');

        $this->permisos_model = $this->model_load_model('permisos_model');
        $this->groups_model = $this->model_load_model('groups_model');
    }

    public function get_all($conditions = null){

        $this->db->select('users.*');
        $this->db->from('users');
        $this->db->order_by('users.id', 'DESC');

        if ($conditions != null){
            $this->_set_db_fields($conditions);
        }

        $all = array();
        foreach ($this->db->get()->result_array() as $row) {
            $item = $row;

            $item['groups'] = $this->get_groups($row['id']);
            if (count($item['groups'] ) > 0){
                $item['role'] = $item['groups'][0]['slug'];
            }

            $permisos = array();
            foreach($item['groups'] as $group){
                $permisos += $this->permisos_model->get_permisos_by_rol($group['id']);
//                $permisos = array_unique($permisos);
            }
            $item['permisos'] = array();
            foreach($permisos as $perm){
                $item['permisos'][$perm['parent_slug']] = $perm;
            }
            $all[] = $item;
        }
        return $all;
    }

    public function get_last_ten_entries()
    {
        return $this->get_all();
    }

    public function get_groups($user_id){
        $conditions = array('users_groups.user_id' => $user_id);
        $this->db->select('groups.*');
        $this->db->from('users_groups');
        $this->db->join('groups', 'groups.id = users_groups.group_id');
        $this->db->order_by('groups.id', 'DESC');

        if ($conditions != null){
            $this->_set_db_fields($conditions);
        }

        $all = array();
        foreach ($this->db->get()->result_array() as $row) {
            $item = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'bgcolor' => $row['bgcolor'],
                'slug' => $row['slug'],
            );
            $all[] = $item;
        }
        return $all;
    }

    function get_by_id($id){
        $conditions = array('users.id' => $id);
        return $this->get($conditions);
    }

    function get_single_by_id($id){
        $conditions = array('users.id' => $id);
        $list = $this->get_all($conditions);
        if (count($list) > 0)
            return $list[0];
        else
            return null;
    }


}