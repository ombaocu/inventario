<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 05/11/2016
 * Time: 20:36
 */
class Permisos_model  extends MY_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->set_table_name('permisos');
        $this->set_primary_key('id');
    }

    public function get_all($conditions = null){

        $this->db->select('permisos.*, groups.name as gname, entidad.nombre as enombre,
        entidad.slug as eslug, accion.nombre as anombre, accion.slug as aslug');
        $this->db->from('permisos');
        $this->db->join('groups', 'groups.id = permisos.groups_id');
        $this->db->join('entidad', 'entidad.id = permisos.entidad_id');
        $this->db->join('accion', 'accion.id = permisos.accion_id');
        $this->db->order_by('permisos.id', 'DESC');

        if ($conditions != null){
            $this->_set_db_fields($conditions);
        }

        $all = array();
        foreach ($this->db->get()->result_array() as $row) {
            $item = array(
                'id' => $row['id'],
                'parent_slug' => $row['parent_slug'],
                'slug' => $row['slug'],
                'groups_id' => $row['groups_id'],
                'entidad_id' => $row['entidad_id'],
                'entidad_id' => $row['entidad_id'],
                'accion_id' => $row['accion_id'],
                'groups' => array(
                    'id' => $row['groups_id'],
                    'name' => $row['gname'],
                ),
                'entidad' => array(
                    'id' => $row['entidad_id'],
                    'nombre' => $row['enombre'],
                    'slug' => $row['eslug'],
                ),
                'accion' => array(
                    'id' => $row['entidad_id'],
                    'nombre' => $row['anombre'],
                    'slug' => $row['aslug'],
                ),
            );
            $all[] = $item;
        }
        return $all;
    }

    public function get_last_ten_entries()
    {
        return $this->get_all();
    }

    public function get_permisos_by_rol($groups_id){
        $conditions = array('permisos.groups_id' => $groups_id);
        return $this->get_all($conditions);
    }

    /**
     * @param array $groups_id
     * @return array
     */
    public function get_permisos_by_roles(array $groups_id){
        $permisos = array();
        foreach($groups_id as $group_id){
            $conditions = array('permisos.groups_id' => $groups_id);
            $lists =  $this->get_all($conditions);
            array_push($permisos, $lists);
        }

        return $permisos;
    }

    function get_by_id($id){
        $conditions = array('permisos.id' => $id);
        return $this->get($conditions);
    }



}