<?php

/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 11/09/2016
 * Time: 7:20
 */


defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';


class Rest_Producto extends REST_Controller {

    const IMAGES_PATH = './uploads/producto/';
    const CROPPED_IMAGES_PATH = 'uploads/images/cropped/';

    const THUMBS_POSTFIX = 'thumbs/';
//    const THUMBS_IMAGES_PATH = self::IMAGES_PATH . self::THUMBS_POSTFIX;  // Not supported by php 5.5.9 (server current version)
    const THUMBS_IMAGES_PATH = self::IMAGES_PATH.'/thumbs/';
    const DEFAULT_IMAGE = 'assets/nophoto.jpg';

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Ion_auth_model');
        $this->load->model('producto_model');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php

        $this->methods['producto_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['producto_save_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['producto_delete_get']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function producto_get()
    {
        // Users from a data store e.g. database
        $productos = $this->producto_model->get_all();

        $id = $this->get('id');


        if ($id === NULL) {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($productos) {
                // Set the response and exit
                $this->response($productos, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No resource were found'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
        }


        $productos_by_id = $this->producto_model->get_by_id($id);

        if (!empty($productos_by_id)) {
            $this->set_response($productos_by_id, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Marca could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

    }

    public function producto_save_post()
    {

        try{
            $data = array(
                'nombre' => $this->post('nombre'),
                'cod_interno' => $this->post('cod_interno'),
                'cod_ext' => $this->post('cod_ext'),
                'descripcion' => $this->post('descripcion'),
                'modelo_id' => $this->post('modelo_id'),
                'marca_id' => $this->post('marca_id'),
                'ubicacion' => $this->post('ubicacion'),
                'serie' => $this->post('serie'),
                'largo' => $this->post('largo'),
                'ancho' => $this->post('ancho'),
                'alto' => $this->post('alto'),
                'peso' => $this->post('peso'),
                'cantidad' => $this->post('cantidad'),
            );

            if (array_key_exists('id', $this->_post_args)) {
                $data['id'] = $this->post('id');
            }

            $inserted = $this->producto_model->save( $data , true );

            $response = array(
                'success' => true,
                'message' => 'Added a resource',
            );

            $this->set_response($response, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        }
        catch(Exception $e){
            $response = array(
                'success' => false,
                'message' => $e->getMessage(),
            );
        }
        $this->set_response($response, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code

    }


    public function producto_upload_photo_post(){

        $config['upload_path']          = './uploads/producto/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']            = $this->struuid(false);

        $this->load->library('upload', $config);

        try
        {
            if ( ! $this->upload->do_upload('file'))
            {
                $error = array('error' => $this->upload->display_errors());

                $response = array(
                    'success' => false,
                    'message' => $error
                );
                $this->response($response, REST_Controller::HTTP_BAD_REQUEST, FALSE); // CREATED (201) being the HTTP response code
            }
            else
            {
//                $data = array('upload_data' => $this->upload->data());
                $filename = $this->upload->data('file_name');

                $response = array(
                    'success' => true,
                    'message' => 'Photo upload OK',
                    'file_name' => $filename,
                );
                $this->response($response, REST_Controller::HTTP_OK, FALSE); // CREATED (201) being the HTTP response code
            }
        }
        catch(Exception $e){
            $response = array(
                'success' => false,
                'message' => $e->getMessage(),
            );
            $this->response($response, REST_Controller::HTTP_OK, FALSE); // CREATED (201) being the HTTP response code
        }


        $this->response($config, REST_Controller::HTTP_OK, FALSE); // CREATED (201) being the HTTP response code
    }

    public function producto_delete_get()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        $deleted = $this->producto_model->delete($id);


        $message = array(
            'success' => ($deleted > 0)  ? true : false,
            'message' => 'Deleted the resource',
            'id' => $id
        );

        $this->set_response($message, REST_Controller::HTTP_OK); // NO_CONTENT (204) being the HTTP response code
    }


    /**
     * Save files
     * @param $base64_image_string
     * @param $output_file_without_extention
     * @param string $path_with_end_slash
     * @return string
     */
    private function save_base64_image($base64_image_string, $output_file_without_extention, $path_with_end_slash="" ) {
        //usage:  if( substr( $img_src, 0, 5 ) === "data:" ) {  $filename=save_base64_image($base64_image_string, $output_file_without_extentnion, getcwd() . "/application/assets/pins/$user_id/"); }
        //
        //data is like:    data:image/png;base64,asdfasdfasdf
        $splited = explode(',', substr( $base64_image_string , 5 ) , 2);
        $mime=$splited[0];
        $data=$splited[1];

        $mime_split_without_base64=explode(';', $mime,2);
        $mime_split=explode('/', $mime_split_without_base64[0],2);
        if(count($mime_split)==2)
        {
            $extension=$mime_split[1];
            if($extension=='jpeg')$extension='jpg';
            //if($extension=='javascript')$extension='js';
            //if($extension=='text')$extension='txt';
            $output_file_with_extention.=$output_file_without_extention.'.'.$extension;
        }
        file_put_contents( $path_with_end_slash . $output_file_with_extention, base64_decode($data) );
        return $output_file_with_extention;
    }

    /**
     * Creates path recursively.
     * @param $path
     * @throws ApiException
     */
    public function path_create($path)
    {
        if (!file_exists($path))
        {
            // TODO: check if this is the best suitable permission to set
            $old_umask = umask(0);
            if (!mkdir($path, 0777, TRUE))
            {
                throw new \ApiException(500, 500000);
            }
            umask($old_umask);
        }
    }

    public function file_delete($file_relative_path)
    {
        @unlink(FCPATH . $file_relative_path);
    }

    public function get_img_dir_base($img)
    {
        return self::IMAGES_PATH . $this->get_index_name($img) . '/';
    }

    public function get_index_name($img_full_path)
    {
        return $this->get_initial_capitalized(basename($img_full_path));
    }

    public function get_initial_capitalized($img_name)
    {
        if (!is_string($img_name))
        {
            throw new Exception('Name not found');
        }
        return strtoupper($img_name[0]);
    }

    private function struuid($entropy)
    {
        $s=uniqid("",$entropy);
        $num= hexdec(str_replace(".","",(string)$s));
        $index = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base= strlen($index);
        $out = '';
        for($t = floor(log10($num) / log10($base)); $t >= 0; $t--) {
            $a = floor($num / pow($base,$t));
            $out = $out.substr($index,$a,1);
            $num = $num-($a*pow($base,$t));
        }
        return $out;
    }

}