<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 11/09/2016
 * Time: 7:20
 */


defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';


class Rest_Trabajador extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Ion_auth_model');
        $this->load->model('trabajador_model');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php

        $this->methods['trabajador_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['trabajador_save_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['trabajador_delete_get']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function trabajador_get()
    {
        // Users from a data store e.g. database
        $trabajadors = $this->trabajador_model->get_last_ten_entries();


        $id = $this->get('id');

//        $this->set_response($this->_get_args, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($trabajadors)
            {
                // Set the response and exit
                $this->response($trabajadors, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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


        $trabajadors_by_id =  $this->trabajador_model->get_by_id($id);

        if (!empty($trabajadors_by_id))
        {
            $this->set_response($trabajadors_by_id, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'trabajador could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

    }

    public function trabajador_save_post()
    {

        try{
            $data = array(
                'nombre' => $this->post('nombre'),
                'apellidos' => $this->post('apellidos'),
            );

            if (array_key_exists('id', $this->_post_args)) {
                $data['id'] = $this->post('id');
            }

            $inserted = $this->trabajador_model->save( $data , true );

            $response = array(
                'success' => true,
                'message' => 'Added a resource',
            );

            $this->set_response($response, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        }
        catch(Exception $e){
            $response = array(
                'success' => false,
                'message' => $e->getMessage(),
            );
        }
        $this->set_response($response, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code

    }

    public function trabajador_delete_get()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        $deleted = $this->trabajador_model->delete($id);


        $message = array(
            'success' => ($deleted > 0)  ? true : false,
            'message' => 'Deleted the resource',
            'id' => $id
        );

        $this->set_response($message, REST_Controller::HTTP_OK); // NO_CONTENT (204) being the HTTP response code
    }




}