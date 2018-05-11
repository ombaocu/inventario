<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 16/09/2016
 * Time: 12:39
 */


define('VENDORPATH', realpath(FCPATH).'/vendor/');

define('PLANTILLAREPORTES', realpath(FCPATH) .'/assets/reports');


//echo VENDORPATH . 'PHPExcel/Classes/PHPExcel/IOFactory.php';

/** Include PHPExcel_IOFactory */
require_once  VENDORPATH . 'PHPExcel/Classes/PHPExcel/IOFactory.php';

class Reporte
{
    private $nombre;
    private $titulo;
    private $elements = array();
    private $plantilla;
    private $format;


    private $excel;

    public function __construct() {
        // initialise the reference to the codeigniter instance
//        require_once APPPATH.'third_party/phpexcel/PHPExcel.php';
        $this->excel = new PHPExcel();
        $this->format = array('xls', 'pdf');
    }

    public function load($path) {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $this->excel = $objReader->load($path);
    }

    public function save($path) {
        // Write out as the new file
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save($path);
    }

    public function stream($filename) {
        header('Content-type: application/ms-excel');
        header("Content-Disposition: attachment; filename=\"".$filename."\"");
        header("Cache-control: private");
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function  __call($name, $arguments) {
        // make sure our child object has this method
        if(method_exists($this->excel, $name)) {
            // forward the call to our child object
            return call_user_func_array(array($this->excel, $name), $arguments);
        }
        return null;
    }

    public function setData($data, $objSheet){
        foreach($data as $item){
            if ($item['type'] == 'cell'){
                $valor = $this->evaluar($item);
                $posicion = $item['posicion'];
                $objSheet->setCellValue($posicion, $valor);
            }

            if ($item['type'] == 'row'){
                $start = intval($item['fila']);
                $columna = $item['columna'];
                $header = $item['header'];
                $index = $start;
                if ($header != null){
                    $index++;
                }
                $fuentes = $item['fuente'];

                foreach($fuentes as $fuente){

                    foreach($item['data'] as $fila){

                        $valor =  ($fila['type'] == 'valor') ? $fila['data'] :  $this->evaluar(array(
                            'formato' => $fila['formato'],
                            'data' => $fuente[$fila['data']]
                        ));
                        $posicion = $fila['columna'] . $index;

                        $objSheet->setCellValue($posicion, $valor);
                    }
                    $index++;
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param mixed $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * @return array
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param array $elements
     */
    public function setElements($elements)
    {
        $this->elements = $elements;
    }

    /**
     * @return mixed
     */
    public function getPlantilla()
    {
        return $this->plantilla;
    }

    /**
     * @param mixed $plantilla
     */
    public function setPlantilla($plantilla)
    {
        $this->plantilla = $plantilla;
    }

    /**
     * @return array
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param array $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }


    public function mostrar($fileoutput, $data = null)
    {
        $fullpathplantilla = PLANTILLAREPORTES .'/'.$this->getPlantilla();


        if (!file_exists($fullpathplantilla)) {
            throw new Exception('Plantilla no se encontro para hacer el reporte ' .$fullpathplantilla);
        }

        if ($data == null){
            throw new Exception('No se entraron datos para usar en el reporte');
        }

//        $objPHPExcel = PHPExcel_IOFactory::load($fullpathplantilla);
//        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//        $objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue('E3', 'Nombre del autorizado Cambiado')
//            ->setCellValue('B5', 'COMBUSTIBLE INSUMO Gasolina Motor')
//            ->setCellValue('B6', 'FECHA: 15/09/16')
//            ->setCellValue('C9', '4654654')
//            ->setCellValue('D9', '120')
//            ->setCellValue('F9', '120')
//            ->setCellValue('G9', 'San Jose')
//            ->setCellValue('H9', 'Pepito');
        $objPHPExcel = PHPExcel_IOFactory::load($fullpathplantilla);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objSheet = $objPHPExcel->setActiveSheetIndex(0);

        $this->setData($data, $objSheet);

        $filename = PLANTILLAREPORTES.'/'.$fileoutput;
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$fileoutput.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter->save('php://output');
        return $filename;

    }

    public function imprimir(){}

    public function guardar($fileoutput, $data = null){}

    protected function evaluar($data){
        $salida = '';

        if (is_array($data)){
            $salida = sprintf($data['formato'], $data['data']);
            return $salida;
        }
        else{
            throw new Exception('Error evaluando los datos');
        }
        return $salida;
    }


    protected function evaluar_compleja($data){
        $salida = '';$valor = 0;
        $isarray = explode('.', $data['field']);
        $valor = (count($isarray) <= 1) ? $data['data'][$isarray[0]][$isarray[1]] : $data['data'][$isarray[0]];
        if (is_array($data)){
            $salida = sprintf($data['formato'], $valor);
            return $salida;
        }
        else{
            throw new Exception('Error evaluando los datos');
        }
        return $salida;
    }



}