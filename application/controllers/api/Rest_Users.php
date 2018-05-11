<?php



/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 03/07/2016
 * Time: 11:59
 */

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Rest_Users extends REST_Controller {

//    var $usersgroups_model = null;
    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Ion_auth_model');
        $this->load->model('Permisos_model');
        $this->load->model('users_model');
        $this->load->model('Usersgroups_model');

        $this->load->library('Ion_auth');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php

        $this->methods['user_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['user_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; // 50 requests per hour per user/keys
    }

    public function users_get()
    {
        // Users from a data store e.g. database
        $users = $this->users_model->get_all();


        $id = $this->get('id');

//        $this->set_response($this->_get_args, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($users)
            {
                // Set the response and exit
                $this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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

        $users_by_id =  $this->users_model->get_single_by_id($id);

        if (!empty($users_by_id))
        {
            $this->set_response($users_by_id, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'trabajador could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function users_save_post()
    {

        $id =  $this->post('id');
//        $this->set_response(array('result' => $id), REST_Controller::HTTP_CREATED);

        if ($id == NULL){ // se va a crear
            $cmd = "insert";
            $username = strtolower(trim($this->input->post('first_name'))) . trim(strtolower($this->input->post('last_name')));
            $email    = strtolower($this->input->post('email'));
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone'),
            );
            $output =  $this->Ion_auth_model->register($username, $password, $email, $additional_data);

            if ($output){
                $groups = $this->post('groups');
                $groups_ids = explode(',', $groups);

                foreach($groups_ids as $id){
                    $this->usersgroups_model->save(array(
                        'user_id' => $output,
                        'group_id' => $id,
                    ), false);
                }
            }
            $response = array(
                'success' => true,
                'message' => 'Added a resource',
                'object' =>  $output
            );
            $this->set_response($response, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        }
        else{ // se va a actualizar

            $data = [
                'phone' => $this->post('phone'),
                'company' => $this->post('company'),
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
            ];

            $output =  $this->Ion_auth_model->update($id, $data);
            if ($output){
                $groups = $this->post('groups');
                $groups_ids = explode(',', $groups);

                $this->usersgroups_model->delete_by_user($id);

                foreach($groups_ids as $id){
                    $this->usersgroups_model->save(array(
                        'user_id' => $output,
                        'group_id' => $id,
                    ), false);
                }
            }

            $response = array(
                'success' => true,
                'message' => 'Added a resource',
                'object' =>  $output
            );
            $this->set_response($response, REST_Controller::HTTP_CREATED);
        }


    }

    public function users_update_post()
    {

        $id =  $this->post('id');
//        $this->set_response(array('result' => $id), REST_Controller::HTTP_CREATED);

        if ($id == NULL){ // se va a crear

            $username = strtolower(trim($this->input->post('first_name'))) . trim(strtolower($this->input->post('last_name')));
            $email    = strtolower($this->input->post('email'));
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone'),
            );
            $output =  $this->Ion_auth_model->register($username, $password, $email, $additional_data);

            if ($output){

            }
            $response = array(
                'success' => true,
                'message' => 'Added a resource',
                'object' =>  $output
            );
            $this->set_response($response, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        }
        else{ // se va a actualizar
            $data = [
                'phone' => $this->post('phone'),
                'company' => $this->post('company'),
            ];

            $output =  $this->Ion_auth_model->update($id, $data);

            $this->set_response(array('result' => print_r($output, true)), REST_Controller::HTTP_CREATED);
        }


    }

    public function users_delete()
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

//    public function users_current_get()
//    {
//        $this->is_authenticated = (bool)$this->session->userdata('user_id');
//        var $user_id = $this->session->userdata('user_id');
//        $this->response(array('is_authenticated' => $this->is_authenticated), REST_Controller::HTTP_OK);
//        return;
////
////        if ($this->is_authenticated){
//////
//////            $user = $this->Ion_auth_model->user($this->is_authenticated);
//////            $user->groups = $this->Ion_auth_model->get_users_groups($user_id)->result();
////            $this->response(array('success' => $user_id), REST_Controller::HTTP_OK);
////        }
////        else{
////            $this->response(array('success' => false), REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION);
////        }
//    }

    public function users_is_authenticated_get()
    {
        $this->is_authenticated = (bool)$this->session->userdata('user_id');
        $this->set_response([
            'status' => $this->is_authenticated,
            'message' => ($this->is_authenticated) ? 'Autenticado' : 'No Autenticado'
        ], REST_Controller::HTTP_OK);
    }


    public function users_changepassword_post(){

        $identity = $this->post('identity');
        $clave = $this->post('password');
        $old = $this->post('oldPassowrd');

        $result = $this->Ion_auth_model->change_password($identity, $old, $clave);
        $message = 'Clave cambiada correctamente';
        if (!$result){
            $message = 'No se pudo cambiar la clave';
        }

        $this->set_response(array('success' => $result, 'result' => $result, 'message' => $message ), REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
    }
}