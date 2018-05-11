<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 11/09/2016
 * Time: 7:23
 */
class Trabajador_model extends MY_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->set_table_name('trabajador');
        $this->set_primary_key('id');
    }


    public function get_last_ten_entries()
    {
        return $this->get();
//        return $query;
    }


    function get_by_id($id){
        $conditions = array('trabajador.id' => $id);
        $lista = $this->get_last_ten_entries($conditions);
        if (count($lista) > 0){
            return $lista[0];
        }
        else{
            return null;
        }
    }

}