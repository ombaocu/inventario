<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 10/09/2016
 * Time: 16:20
 */


defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';


class Rest_Upload extends REST_Controller
{

    /**
     * Path to images folder, from FCPATH
     * @var string
     */
    const IMAGES_PATH = 'assets/uploads/vehiculos/';
    const CROPPED_IMAGES_PATH = 'uploads/images/cropped/';

    const THUMBS_POSTFIX = 'thumbs/';
//    const THUMBS_IMAGES_PATH = self::IMAGES_PATH . self::THUMBS_POSTFIX;  // Not supported by php 5.5.9 (server current version)
    const THUMBS_IMAGES_PATH = self::IMAGES_PATH.'/thumbs/';



}