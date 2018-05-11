<?php

class Timezones_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($conditions = array(), $orderby = 'order', $direction = 'ASC') {
        $this->db->select()->from('timezones');
        $this->_set_db_fields($conditions);
        $this->db->order_by($orderby, $direction);
        return $this->db->get()->result_array();
    }
    
}
