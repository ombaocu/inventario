<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 16/09/2016
 * Time: 12:56
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once "Reporte.php";



class ReporteAutorizoCarga extends Reporte
{

    private $list = array();

    /**
     * ReporteAutorizoCarga constructor.
     */
    public function __construct()
    {

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

    public function imprimir()
    {
        // TODO: Implement imprimir() method.
    }


}