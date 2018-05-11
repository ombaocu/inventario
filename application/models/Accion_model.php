<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 05/11/2016
 * Time: 20:55
 */
class Accion_model  extends MY_Model {

//    var $producto_model = null;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->set_table_name('accion');
        $this->set_primary_key('id');

//        $this->producto_model = $this->model_load_model('producto_model');
    }

    public function get_all($conditions = null){

        $this->db->select('accion.*');
        $this->db->from('accion');
        $this->db->order_by('accion.id', 'DESC');

        if ($conditions != null){
            $this->_set_db_fields($conditions);
        }

        $all = array();
        foreach ($this->db->get()->result_array() as $row) {

            $accion = array(
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'slug' => $row['slug'],
            );

            $all[] = $accion;
        }
        return $all;
    }

    function get_by_id($id){
        $conditions = array('accion.id' => $id);
        return $this->get($conditions);
    }

}