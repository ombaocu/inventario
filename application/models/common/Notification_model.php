<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 28/06/2016
 * Time: 23:17
 */

class Notification_model extends MY_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->table_name = 'notification';
        $this->primary_id = 'id';
    }

    function get_last_ten_entries()
    {
        $query = $this->db->get('entries', 10);
        return $query->result();
    }

    function insert_entry($data)
    {
        return $this->save($data);
    }

    function update_entry($data, $id)
    {
        $data[$this->primary_id] = $id;
        return $this->save($data);
    }

    /**
     * Count notification per user
     * @param $user_id
     * @return mixed
     */
    public function count($user_id){
        return count($this->get(array('usersid' => $user_id, 'status' => false)));
    }

    /**
     * Get list of notifications per user
     * @param $user_id
     * @return array
     */
    public function get_notification_per_user($user_id){
        return $this->get(array('usersid' => $user_id, 'status' => false));
    }

}