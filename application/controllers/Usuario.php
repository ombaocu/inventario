<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 02/11/2016
 * Time: 0:25
 */
class Usuario  extends Public_Controller{

    public function __construct()
    {
        parent::__construct();


        $this->load->database();
        $this->load->helper('url');

        $this->load->model('Ion_auth_model');

        /* Title Page :: Common */
        $this->page_title->push('Usuarios');
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
//        $this->breadcrumbs->unshift(1, 'usuario', 'vehiculos/usuario');
    }

    public function index()
    {
        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();
            
            $this->template->content_render('ui/usuario/index', $this->data);
        }

    }

    public function create()
    {
        $this->template->content_render('ui/usuario/create', $this->data);

    }

}