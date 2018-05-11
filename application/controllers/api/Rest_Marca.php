<?php
/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 17/08/2016
 * Time: 6:01
 */



defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';


class Rest_Marca extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Ion_auth_model');
        $this->load->model('marca_model');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php

        $this->methods['marca_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['marca_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['marca_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function marca_get()
    {

        // Users from a data store e.g. database
        $marcas = $this->marca_model->get_all();


        $id = $this->get('id');

//        $this->set_response($this->_get_args, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($marcas)
            {
                // Set the response and exit
                $this->response($marcas, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No resource were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        $marcas_by_id =  $this->marca_model->get_by_id($id);

        if (!empty($marcas_by_id))
        {
            $this->set_response($marcas_by_id, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Marca could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

    }

    public function marca_save_post()
    {

        try{
//            if ( ! $this->ion_auth->logged_in())
//            {
//                $response = array(
//                    'success' => false,
//                    'message' => 'Usted no ha iniciado sesión',
//                );
//
//                $this->set_response($response, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
//                return;
//            }
            $can = true;
            if ($can){

                $data = array(
                    'nombre' => $this->post('nombre'),
                );

                if (array_key_exists('id', $this->_post_args)){
                    $data['id'] = $this->post('id');
                }

                $inserted = $this->marca_model->save( $data , true );

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

    public function marca_update_post()
    {

        try{

            $data = array(
                'nombre' => $this->post('nombre'),
                'id' => $this->post('id')
            );
            $inserted = $this->marca_model->save( $data , true );

            $response = array(
                'success' => true,
                'message' => 'Update a resource',
                'object' =>  $data
            );

            $this->set_response($response, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
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

    public function marca_delete_get()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

         $deleted = $this->marca_model->delete($id);


        $message = array(
            'success' => ($deleted > 0)  ? true : false,
            'message' => 'Deleted the resource',
            'id' => $id
        );

        $this->set_response($message, REST_Controller::HTTP_OK); // NO_CONTENT (204) being the HTTP response code
    }




}