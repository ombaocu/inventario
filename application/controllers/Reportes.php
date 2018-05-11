<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 15/10/2016
 * Time: 12:59
 */
class Reportes  extends Public_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');

//        $this->load->model('operacionestarjeta_model');
//        $this->load->model('chofer_model');
//        $this->load->model('tarjetacombustible_model');
//        $this->load->model('autorizocarga_model');
//        $this->load->model('conciliacion_model');
//        $this->load->model('lubricante_model');
//        $this->load->model('distribucionlubricanteinsumo_model');
//        $this->load->model('bc3vehiculos_model');


        /* Title Page :: Common */
        $this->page_title->push('Reportes');
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
//        $this->breadcrumbs->unshift(1, 'vehiculo', 'marca/vehiculo');
    }

    public function index()
    {
        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            $this->template->content_render('ui/reportes/index', $this->data);
        }
    }

    public function show($menu, $formato = 'html')
    {

        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            $cmenu = str_replace('-', '_', $menu);
            $this->data['saldo'] = 0;
            $this->data['menu'] = $cmenu;
            $this->data['data'] = $this->$cmenu();
            $this->data['formato'] = ($formato != '') ? $formato : 'html';
            if ($this->data['formato'] == 'html' ){
                $this->template->content_render('ui/reportes/'.$cmenu, $this->data);
            }
            else{
                $n = $cmenu.'_xls';
                $this->$n();
            }

        }
    }

    private function movimiento_tarjeta_combustible(){
        $data = $this->operacionestarjeta_model->get_all();
        return $data;
    }

    private function movimiento_tarjeta_combustible_xls(){
        $this->load->library('Reporte');


        $today =  new DateTime();
        $this->data['today'] = $today;
        $this->data['lista'] = array();
        $operaciones = $this->movimiento_tarjeta_combustible();

        $salida = array();
        foreach($operaciones as $fuente){

            $item = $fuente;
            $item['tarjeta'] = $fuente['tarjetaCombustible']['number'];
            $item['tipo_operacion'] = $fuente['tipoOperacion']['nombre'];
            $salida[] = $item;
        }


        $data['repeat'] = array('type' => 'row', 'fila' => '5', 'columna' => 'B', 'header' => null, 'data' => array(),
            'fuente' => $salida
        );


        $data['repeat']['data'][] = array('type' => 'evaluar', 'formato' => '%s', 'columna' => 'B', 'data' => 'lugar');
        $data['repeat']['data'][] = array('type' => 'evaluar', 'formato' => '%s', 'columna' => 'C', 'data' => 'tarjeta');
        $data['repeat']['data'][] = array('type' => 'evaluar', 'formato' => '%s', 'columna' => 'D', 'data' => 'tipo_operacion');
        $data['repeat']['data'][] = array('type' => 'evaluar', 'formato' => '%s', 'columna' => 'E', 'data' => 'litros');
        $data['repeat']['data'][] = array('type' => 'evaluar', 'formato' => '%s', 'columna' => 'F', 'data' => 'importe');
        $data['repeat']['data'][] = array('type' => 'evaluar', 'formato' => '%s', 'columna' => 'G', 'data' => 'elitros');
        $data['repeat']['data'][] = array('type' => 'evaluar', 'formato' => '%s', 'columna' => 'H', 'data' => 'eimporte');
        $data['repeat']['data'][] = array('type' => 'evaluar', 'formato' => '%s', 'columna' => 'I', 'data' => 'fechastr');

        $this->reporte->setTitulo('Operaciones');
        $this->reporte->setPlantilla('operacionestarjeta.xlsx');

        $this->reporte->mostrar('operacionestarjeta.xlsx', $data);
    }

}