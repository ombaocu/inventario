<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 19/11/2016
 * Time: 18:44
 */

class Groups_model  extends MY_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->set_table_name('groups');
        $this->set_primary_key('id');
    }

    public function get_all($conditions = null){

        $this->db->select('groups.*');
        $this->db->from('groups');
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

    public function get_members($group_id){
        $conditions = array('users_groups.group_id' =>$group_id );
        $this->db->select('users.*');
        $this->db->from('users_groups');
        $this->db->join('users', 'users.id = users_groups.user_id');
        $this->db->order_by('groups.id', 'DESC');

        if ($conditions != null){
            $this->_set_db_fields($conditions);
        }

        $all = array();
        foreach ($this->db->get()->result_array() as $row) {
            $item = $row;
            $all[] = $item;
        }
        return $all;
    }

    public function get_last_ten_entries()
    {
        return $this->get_all();
    }

    public function get_permisos($group_id){
//        $conditions
    }


    function get_by_id($id){
        $conditions = array('groups.id' => $id);
        return $this->get($conditions);
    }

    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }


}