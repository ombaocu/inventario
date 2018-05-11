<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Public_Controller {

    public function __construct()
    {
        parent::__construct();

        /* Load :: Common */
        $this->load->helper('number');
        $this->load->model('admin/dashboard_model');
        $this->load->model('common/notification_model');

        $this->lang->load('admin/example');
    }


    public function index(){
        $this->template->index_render('public/index', $this->data);
    }

    public function section1(){
        $this->template->content_render('ui/section1', $this->data);
    }

    public function TopBar(){
        $this->template->content_render('ui/topbar', $this->data);
    }

    public function SideBar(){
        $this->template->content_render('ui/sidebar', $this->data);
    }

    public function dashboard(){
        $this->template->content_render('ui/dashboard', $this->data);
    }

    public function dropdow(){
        $this->template->content_render('ui/dropdown', $this->data);
    }
}
