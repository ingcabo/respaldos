<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';
class Users extends REST_Controller
{
    function __construct()
    {
        //Construct the parent class
        parent::__construct();
        $this->load->helper('my_api_helper');
    }
    //username password nombres apellidos pregunta1  respuesta1 email cargo
    //registro usuario
    // metodo put
    //http://localhost/apiRestCodeigniter/index.php/Users/usr/ID_usuario
    function usr_put()
    {
        $usr_data = $this->put();
        $this->load->library('form_validation');
        $data = remove_unknown_fields($usr_data, $this->form_validation->get_field_names('usr_put'));
        $this->form_validation->set_data($data);
        $usr   = $data['username'];
        $email = $data['email'];
        if ($this->form_validation->run('usr_put') != false) {
            //cargo el modelo de gestion de usuarios
            $this->load->model('Model_usr');
            //consulto el nombre de usuario    y el email
            $exist       = $this->Model_usr->count_by(array(
                "username" => $usr
            ));
            $exist_email = $this->Model_usr->count_by(array(
                "email" => $email
            ));
            //validar si existe almenos un nombre de usuario a consultado
            if (($exist >= 1) || ($exist_email >= 1)) {
                $this->response(array(
                    "staus" => "Error",
                    "message" => "email duplicado",
                    "user" => $usr,
                    "studenarray" => $data
                ), REST_Controller::HTTP_BAD_REQUEST);
            } else {
                //si no existe lo ingreso
                $usr_id = $this->Model_usr->insert($data);
            }
            if (!$usr_id) {
                $this->response(array(
                    'status' => 'Faliured',
                    'message' => 'un error al crear el usuario',
                    'nombre' => $usr,
                    REST_Controller::HTTP_BAD_REQUEST
                ));
            } else {
                $this->response(array(
                    "staus" => "Susses",
                    "message" => "user created",
                    "studenarray" => $data,
                    "id_usuario" => $usr_id
                ));
            }
        } else {
            $this->response(array(
                'status' => 'Faliurede',
                'message' => $this->form_validation->get_errors_as_array(),
                404
            ));
        }
    } //fin user put
    // modificar usuario
    // metodo post
    //http://localhost/apiRestCodeigniter/index.php/Users/usr/ID_usuario
    function usr_post()
    {
        //capturo el id del usuario
        $usr_id = $this->uri->segment(3);
        //cargo el modelo usuario
        $this->load->model('Model_usr');
        $usr  = $this->Model_usr->get_by(array(
            "id" => $usr_id,
            "status" => "active"
        ));
        //remuevo los campos erroneos que no espera mi tabla
        $data = remove_unknown_fields($this->post(), $this->form_validation->get_field_names('usr_post'));
        //llego la clase validacion
        $this->form_validation->set_data($data);
        if ($this->form_validation->run('usr_post') != false) {
            //podria cambiar el email si y solo si  no esta en usuo    el nuevo EMAIL
            $safe_email = !isset($data['email']) || $data['email'] == $usr['email'] || !$this->Model_usr->get_by(array(
                'email' => $data['email']
            ));
            if (!$safe_email) {
                //respuesta email no seguro
                $this->response(array(
                    "staus" => "Error",
                    "message" => "email ya existe",
                    "email rechazado" => "email rechazado",
                    "studenarray" => $data
                ));
            } else {
                //podemos incluir los datos
                $updated = $this->Model_usr->update($usr_id, $data);
            }
            if (!$updated) {
                $this->response(array(
                    'status' => 'Faliured',
                    'message' => 'un error al actualizar el usuario',
                    REST_Controller::HTTP_BAD_REQUEST
                ));
            } else {
                $this->response(array(
                    "staus" => "Susses",
                    "message" => "usuario actualizado",
                    "data" => $data,
                    200
                ));
            }
        } else {
            $this->response(array(
                "staus" => "Faliured",
                "message" => "el usuario no existe",
                REST_Controller::HTTP_BAD_REQUEST
            ));
        } //fin de validacion de formulario
    }
}
?>
