<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 28/06/2016
 * Time: 23:34
 */
class Tags_model extends MY_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->table_name = 'tags';
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


}