<?php

class Log_model extends MY_Model {

    public function __construct() {
        parent::__construct();

        $this->set_table_name('log');
        $this->set_primary_key('id');
    }

    public function insert($log) {
        
        if (array_key_exists('type', $log)) {
            $this->db->set('type', $log['type']);
        }

        if (array_key_exists('user_id', $log)) {
            $this->db->set('user_id', $log['user_id']);
        }

        if (array_key_exists('ipaddress', $log)) {
            $this->db->set('ipaddress', $log['ipaddress']);
        }

        if (array_key_exists('msg', $log)) {
            $this->db->set('msg', $log['msg']);
        }
        
        $this->db->set('dt', mktime());
        
        $this->db->insert('log');
        
        $id = $this->db->insert_id();
        return $id;
    }

    public static function set_cron_status($connection, $msg, $type=LogType::NOTIFICATION)
    {
        if (!($type OR $msg))
        {
            return FALSE;
        }

        $database = $dbhost = $dbuser = $dbpass = NULL;
        if (array_key_exists('database', $connection))
        {
            $database = $connection['database'];
        }
        if (array_key_exists('hostname', $connection))
        {
            $dbhost = $connection['hostname'];
        }
        if (array_key_exists('username', $connection))
        {
            $dbuser = $connection['username'];
        }
        if (array_key_exists('password', $connection))
        {
            $dbpass = $connection['password'];
        }

        if (!($database OR $dbhost OR $dbuser OR $dbpass))
        {
            return FALSE;
        }

        $conn = mysql_connect($dbhost, $dbuser, $dbpass);
        if (!$conn)
        {
            return FALSE;  // mysql_error()
        }

        $sql = "INSERT INTO log ".
                "(`type`, `msg`, `dt`, `user_id`, `ipaddress`) ".
                    "VALUES ".
                        "('$type','$msg', mktime(), 'user_id', 'ipaddress' )";

        mysql_select_db($database);

        $retval = mysql_query($sql, $conn);
        if (!$retval)
        {
            return FALSE;
        }
        return TRUE;
    }
}
