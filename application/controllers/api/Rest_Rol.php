<?php


/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 02/11/2016
 * Time: 2:32
 */

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Rest_Rol extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Ion_auth_model');
        $this->load->model('groups_model');
        $this->load->model('permisos_model');
        $this->load->model('accion_model');
        $this->load->model('entidad_model');
//        $this->load->library('common/Utiles');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php

        $this->methods['rol_get']['limit'] = 500; // 500 requests per hour per rol/key
        $this->methods['rol_post']['limit'] = 100; // 100 requests per hour per rol/key
        $this->methods['rol_delete']['limit'] = 50; // 50 requests per hour per rol/key
    }

    public function rol_list_permisos_get(){
        $id = (int) $this->get('rol');
        $permisos = $this->permisos_model->get_permisos_by_rol($id);
        $this->response($permisos, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }


    public function rol_accion_get()
    {
        $id = $this->get('id');

        if ($id === NULL) {
            $lista = $this->accion_model->get_all();
            $this->response($lista, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $lista = $this->accion_model->get_by_id($id);
            $this->response($lista, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function rol_entidad_get()
    {
        $id = $this->get('id');

        if ($id === NULL) {
            $lista = $this->entidad_model->get_all();
            $this->response($lista, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $lista = $this->entidad_model->get_by_id($id);
            $this->response($lista, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function rol_get()
    {

        $id = $this->get('id');

        if ($id === NULL)
        {
            $roles = $this->groups_model->get_all();
            $this->set_response($roles, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else{
            $r_by_id =  $this->groups_model->get_by_id($id);
            $this->set_response($r_by_id, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }

    }

    public function rol_permisos_get()
    {
        $id = $this->get('id');

        if ($id === NULL) {
            $lista = $this->permisos_model->get_all();
            $this->response($lista, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $lista = $this->permisos_model->get_by_id($id);
            $this->response($lista, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }
    public function rol_permisos_delete()
    {
        $id = (int) $this->get('id');


        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response([
                'success' => false,
                'id' => $id,
                'message' => 'Problem to delete the resource'
            ], REST_Controller::HTTP_OK); // BAD_REQUEST (400) being the HTTP response code
        }

        $this->permisos_model->delete( $id );
        $message = [
            'success' => true,
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_OK); // NO_CONTENT (204) being the HTTP response code
    }
    public function rol_permisos_post()
    {
        try{

            $data = array(
                'slug' => $this->post('slug'),
                'groups_id' => $this->post('groups_id'),
                'entidad_id' => $this->post('entidad_id'),
                'accion_id' => $this->post('accion_id'),
                'parent_slug' => $this->post('parent_slug'),
            );
//
            if (array_key_exists('id', $this->_post_args)) {
                $data['id'] = $this->post('id');
            }

            $inserted = $this->permisos_model->save( $data , true );

            $response = array(
                'success' => true,
                'message' => 'Added a resource',
                'object' =>  $data
            );

            $this->set_response($response, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        }
        catch(Exception $e){
            $response = array(
                'success' => false,
                'message' => $e->getMessage(),
                'object' => null
            );
        }
        $this->set_response($response, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
    }

    public function rol_save_post()
    {

        try{

            $can = true;
            if ($can){

                $data = array(
                    'name' => $this->post('name'),
                    'description' => $this->post('description'),
                    'bgcolor' => $this->post('bgcolor'),
                    'slug' => groups_model::slugify($this->post('name')),
                );

                if (array_key_exists('id', $this->_post_args)){
                    $data['id'] = $this->post('id');
                }

                $inserted = $this->groups_model->save( $data , true );

                $response = array(
                    'success' => true,
                    'message' => 'Added a resource',
                    'object' =>  $data
                );

                $this->set_response($response, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
            }
            else{
                $response = array(
                    'success' => false,
                    'message' => 'Usted no tiene acceso',
                );

                $this->set_response($response, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
                return;
            }

        }
        catch(Exception $e){
            $response = array(
                'success' => false,
                'message' => $e->getMessage(),
                'object' => $this->_post_args
            );
        }
        $this->set_response($response, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code



    }

    public function rol_delete()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }


//


}