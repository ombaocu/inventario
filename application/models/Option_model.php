<?php

class Option_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_option($conditions) {
        $this->_set_db_fields($conditions);
        return $this->db->get('options')->result_array();
    }

    public function save_option($option) {
        $this->db->query('INSERT INTO options(`name`, `value`) VALUES (' . $this->db->escape($option['name']) . ', ' . $this->db->escape($option['value']) . ') ON DUPLICATE KEY UPDATE `value` = VALUES (`value`)');
        //affected_rows() return 1 on insert and 0 (no change) or 2 (changed) on update
        return $this->db->affected_rows();
    }

}
