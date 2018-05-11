<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 28/10/2016
 * Time: 19:56
 */
class Producto extends Public_Controller{

    public function __construct()
    {
        parent::__construct();


        $this->load->database();
        $this->load->helper('url');

        $this->load->model('producto_model');

        /* Title Page :: Common */
        $this->page_title->push('producto');
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, 'producto', 'producto');
    }

    public function index()
    {
        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            $this->template->content_render('ui/producto/index', $this->data);
        }

    }

    public function create()
    {
        $this->template->content_render('ui/producto/create', $this->data);

    }

}