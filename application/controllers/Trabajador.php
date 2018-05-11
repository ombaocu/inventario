<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 11/09/2016
 * Time: 7:19
 */
class Trabajador extends Public_Controller{

    public function __construct()
    {
        parent::__construct();


        $this->load->database();
        $this->load->helper('url');

        $this->load->model('trabajador_model');

        /* Title Page :: Common */
        $this->page_title->push('Trabajador');
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, 'trabajador', 'vehiculos/trabajador');
    }

    public function index()
    {
        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            $this->template->content_render('ui/trabajador/index', $this->data);
        }

    }

    public function create()
    {
        $this->template->content_render('ui/trabajador/create', $this->data);

    }

}