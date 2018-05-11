<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

	function __construct()
	{
		parent::__construct();

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
        $this->load->model('Ion_auth_model');
        $this->load->model('users_model');
        $this->load->model('groups_model');
        $this->load->model('permisos_model');
        $this->load->library('session');
	}


	function index()
	{
        if ( ! $this->ion_auth->logged_in())
        {
            redirect('#/auth/login', 'refresh');
        }
        else
        {
            redirect('/', 'refresh');
        }
	}


    function login()
	{
        if ( ! $this->ion_auth->logged_in())
        {
            /* Load */
            $this->load->config('admin/dp_config');
            $this->load->config('common/dp_config');

            /* Valid form */
            $this->form_validation->set_rules('identity', 'Identity', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');

            /* Data */
            $this->data['title']               = $this->config->item('title');
            $this->data['title_lg']            = $this->config->item('title_lg');
            $this->data['auth_social_network'] = $this->config->item('auth_social_network');
            $this->data['forgot_password']     = $this->config->item('forgot_password');
            $this->data['new_membership']      = $this->config->item('new_membership');

            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['identity'] = array(
                'name'        => 'identity',
                'id'          => 'identity',
                'type'        => 'email',
                'value'       => $this->form_validation->set_value('identity'),
                'class'       => 'form-control',
                'placeholder' => lang('auth_your_email'),
                'ng-model' => 'item.identity'
            );
            $this->data['password'] = array(
                'name'        => 'password',
                'id'          => 'password',
                'type'        => 'password',
                'class'       => 'form-control',
                'placeholder' => lang('auth_your_password'),
                'ng-model' => 'item.password'
            );

            /* Load Template */
            $this->template->auth_render('auth/login', $this->data);
        }
        else
        {
            redirect('/', 'refresh');
        }
   }
    function logout($src = NULL)
    {
        $logout = $this->ion_auth->logout();

        $this->session->set_flashdata('message', $this->ion_auth->messages());

        if ($src == 'admin')
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            redirect('/', 'refresh');
        }
    }
    public function logoutAction()
    {
        $logout = $this->ion_auth->logout();

        $result = array(
            'success' => true,
            'message' => $this->ion_auth->messages()
        );
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($result));
    }

    public function loginAction(){


        $remember = true;//(bool) $this->input->post('remember');

        if ($this->ion_auth->login($this->input->post('username'), $this->input->post('password'), $remember)) {

            $user_id = $this->session->userdata('user_id');

            $user = $this->users_model->get_single_by_id($user_id);

            if ($user != null){
                $result = array(
                    'success' => true,
                    'user' => $user,
                    'code' => 200,
                    'data' => $user_id
                );
            }
            else{
                $result = array(
                    'success' => false,
                    'message' => 'User not found',
                    'code' => 401,
                    'data' => $user_id
                );
            }

        }
        else
        {
            $result = array(
                'success' => false,
                'message' => $this->ion_auth->errors(),
                'code' => 401
            );
        }

//        $result = array(
//            'success' => false,
//            'message' => "Error"
//        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header($result['code'])
            ->set_output(json_encode($result));
    }

    public function is_authenticated()
    {
        $this->is_authenticated = (bool)$this->session->userdata('user_id');

        $result = array(
            'success' => true,
            'is_authenticated' => $this->is_authenticated
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($result));
    }

    public function current()
    {
        $user_id = $this->session->userdata('user_id');
        if ($user_id){
            $user = $this->users_model->get_single_by_id($user_id);
            $result = array(
                'success' => true,
                'user' => $user
            );
        }else{
            $result = array(
                'success' => false,
                'message' => 'Usuario no autenticado'
            );
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($result));
    }

}
