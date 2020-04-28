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
    $this->load->config('rest');
    //cargo la libreria form_validation que esta ubicada en config/form_validation.php
    $this->load->library('form_validation');
  }
  /*
  username:casslos
  password:12345678
  nombres:carlos
  apellidos:ramirez
  pregunta1:cual
  respuesta1:cual
  email:1carksdj@gmail.com
  cargo:analista
  */
  function usr_put()
  {
    //cargar valores del header de la peticion
    $headers  = apache_request_headers();
    $usr_data = array();
    foreach ($headers as $header => $value) {
      $usr_data[$header] = $value;
    }
    //en data se guarda em array de valores aceptados por que utilizo una funcion remove_unknown_fields que recorre dos arreglo el esperado y el enviado y solo deja pasar los nombres de campos que espero esta funcion la ubicas en helpers/my_api_helper.php pero basicamente le entregas dos arreglos y se recorren entre si remove_unknown_fields(array(enviado),array(esperado))
    $data = remove_unknown_fields($usr_data, $this->form_validation->get_field_names('usr_put'));
    //alimentas la funcion form_valition
    $this->form_validation->set_data($data);
    // compraro si los valores que se envian cumplen con la validacion de form_validation
    if ($this->form_validation->run('usr_put') != false) {
      //saco de data los valores que necesito
      $usr   = $data['username'];
      $email = $data['email'];
      //cargo el modelo de gestion de usuarios
      $this->load->model('Model_usr');
      //consulto el nombre de usuario    y el email
      $exist       = $this->Model_usr->count_by(array(
        "username" => $usr
      ));
      //consulto el nombre de usuario    y el email
      $exist_email = $this->Model_usr->count_by(array(
        "email" => $email
      ));
      //validar si existe almenos un nombre de usuario a consultado
      if (($exist >= 1) || ($exist_email >= 1)) {
        // aca respondo que el reistro ya existe
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
          $this->config->item('rest_message_field_name') => "email/nombre de usuario existe",
          $this->config->item('data') => 'false'
        ), REST_Controller::HTTP_BAD_REQUEST);
      } else {
        //si no existe lo ingreso
        $usr_id = $this->Model_usr->insert($data);
      }
      if (!$usr_id) {
        //si no se puede hacer el insert muestro un error
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
          $this->config->item('rest_message_field_name') => 'un error al crear el usuario',
          $this->config->item('data') => 'false'
        ), REST_Controller::HTTP_BAD_REQUEST);
      } else {
        //si si se hace el insert le asigno unos permisos basicos al usuario en este archivo puedes ver la funcion "inicio_permisos"
        $permiso = $this->inicio_permisos($usr_id);
        //aca muestro registro creado
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('bien'),
          $this->config->item('rest_message_field_name') => "Usuario Creado",
          $this->config->item('data') => $this->config->item('dataT')
        ), 200);
      }
    } else {
      //aca muestro que faltan datos en el formulario o no cumplen con lo esperado
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
        $this->config->item('rest_message_field_name') => $this->form_validation->get_errors_as_array(),
        $this->config->item('data') => $this->config->item('dataF')
      ), 404);
    }
  } //find user put
  // modificar usuario
  // metodo post
  // super usuario debera emviar em la url el id a actualizar
  // usuario normal solo con su llave se actuliazara sus datos
  //http://localhost/apiRestCodeigniter/index.php/Users/usr/ID_usuario
  function usr_post()
  {
    //capturo el id del usuario
    //cargo el modelo usuario
    $this->load->model('Model_usr');
    //cargar valores del header de la peticion
    $headers  = apache_request_headers();
    $usr_data = array();
    foreach ($headers as $header => $value) {
      $usr_data[$header] = $value;
    }
    //cargo el modelo para consultas a la tabla usuarios
    $this->load->model('Model_usr');
    //ubico lla llave que viene en la cabecera
    $key       = $usr_data['key'];
    //consulto de quien es la llave
    $usr_datos = $this->Model_usr->consulta_usuario($key);
    //compraro si la llave es level 3 administrador
    if ($usr_datos['level'] == 3) {
      // si si lo es administrador permito leer segmento 3 de la url para sacar el id a gestionar
      $id = $this->uri->segment(3);
    } else {
      // si no es administrador el id a gestionar es el id del dueño de la llave enviada
      $id = $usr_datos['user_id'];
    }
    // consulto el usuario en particular
    $usr  = $this->Model_usr->get_by(array(
      "id" => $id,
      "status" => "active"
    ));
    // si es por formulario en el body (solo para web)
    //$usr_data = $this->post();
    //remuevo los campos erroneos que no espera mi tabla, esta porcion se repite en todas la funciones se explica mejor en usr_put
    $data = remove_unknown_fields($usr_data, $this->form_validation->get_field_names('usr_post'));
    //llego la clase validacion
    $this->form_validation->set_data($data);
    // compraro si los valores que se envian cumplen con la validacion de form_validation
    if ($this->form_validation->run('usr_post') != false) {
      if ($usr >= 1) {
        //podria cambiar el email si y solo si  no esta en usuo    el nuevo EMAIL
        $safe_email = !isset($data['email']) || $data['email'] == $usr['email'] || !$this->Model_usr->get_by(array(
          'email' => $data['email']
        ));
        if (!$safe_email) {
          //respuesta email no seguro
          $this->response(array(
            $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
            $this->config->item('rest_message_field_name') => "email ya existe",
            $this->config->item('data') => $this->config->item('dataF')
          ), 400);
        } else {
          //podemos incluir los datos
          $updated = $this->Model_usr->update($id, $data);
        }
        if (!$updated) {
          // si no hay update muestro un error
          $this->response(array(
            $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
            $this->config->item('rest_message_field_name') => 'un error al actualizar el usuario',
            $this->config->item('data') => $this->config->item('dataF')
          ), REST_Controller::HTTP_BAD_REQUEST);
        } else {
          // si existe update muestro mensaje
          $this->response(array(
            $this->config->item('rest_status_field_name') => $this->config->item('bien'),
            $this->config->item('rest_message_field_name') => 'Registro Actualizado',
            $this->config->item('data') => $this->config->item('dataT')
          ), 200);
        }
      } else {
        // muestro registro existente
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
          $this->config->item('rest_message_field_name') => 'usuario no existe',
          $this->config->item('data') => $this->config->item('dataF')
        ), REST_Controller::HTTP_BAD_REQUEST);
      }
    } else {
      //aca muestro que faltan datos en el formulario o no cumplen con lo esperado
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
        $this->config->item('rest_message_field_name') => $this->form_validation->get_errors_as_array(),
        $this->config->item('data') => $this->config->item('dataF')
      ), REST_Controller::HTTP_BAD_REQUEST);
    } //fin de validacion de formulario
  }
  //registro usuario
  // metodo get
  //todos los usuario
  //http://localhost/apiRestCodeigniter/index.php/Users/usr
  //un resgistro usuario
  //http://localhost/apiRestCodeigniter/index.php/Users/usr/Id
  function usr_get()
  {
    //capturamos los datos que vienen en la cabecera
    $headers     = apache_request_headers();
    $metodo_data = array();
    foreach ($headers as $header => $value) {
      $metodo_data[$header] = $value;
    }
    // cargamos el mudulo de consultas
    $this->load->model('Model_usr');
    //extraemos la llave enviada
    $key       = $metodo_data['key'];
    //consultamos quien es el usuario de la llave
    $usr_datos = $this->Model_usr->consulta_usuario($key);
    //validamos si es diferente a level 3, en este segmento ampliamos el criterio de la
    if ($usr_datos['level'] <> 3) {
      //no es super usuario    solo consulta sus datos
      $criterio = array(
        "id" => $usr_datos['user_id'],
        "status" => "active"
      );
    } else {
      //es super usuario puede consultar el id de otro usuario o una consulta sin id que le regresaria todos los actuivos eso es criteriop
      if ($this->uri->segment(3)) {
        $usr_id   = $this->uri->segment(3);
        $criterio = array(
          "id" => $usr_id,
          "status" => "active"
        );
      } else {
        $criterio = array(
          "status" => "active"
        );
      }
    }
    //aca consultamos segun el criterio
    $usr = $this->Model_usr->get_many_by($criterio);
    // si no  hay resultado
    if (isset($usr['id'])) {
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
        $this->config->item('rest_message_field_name') => 'Consulta no exitosa',
        $this->config->item('data') => $this->config->item('dataF')
      ), 200);
      //$this->response($usr);
    } else {
      //si hay resultado
      if (count($usr) > 0) {
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('bien'),
          $this->config->item('rest_message_field_name') => "Consulta Satisfactoria",
          $this->config->item('data') => $usr
        ), 200);
        //  $this->response($usr);
      } else {
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
          $this->config->item('rest_message_field_name') => "Consulta no Satisfactoria",
          $this->config->item('data') => $this->config->item('dataF')
        ), 400);
        // $this->response(array('status' => 'error', 'msg' => 'error_details'), 404);
      }
    }
  }
  // el id debe venir en la url
  function usr_delete()
  {
    //el id del usuario a eliminar
    $usr_id = $this->uri->segment(3);
    //cargamos el modulo de consultas
    $this->load->model('Model_usr');
    //consultamos el usuario
    $usr = $this->Model_usr->get_by(array(
      "id" => $usr_id,
      "status" => "active"
    ));
    // si el usuario exuiste
    if (isset($usr['id'])) {
      //le cambiamos el estatis de active a delete
      $data['status'] = "delete";
      $deleted        = $this->Model_usr->update($usr_id, $data);
      if (!$deleted) {
        // si no hay delete
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
          $this->config->item('rest_message_field_name') => 'un error al aeliminar el usuario',
          $this->config->item('data') => $this->config->item('dataF')
        ), REST_Controller::HTTP_BAD_REQUEST);
      } else {
        //exito
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('bien'),
          $this->config->item('rest_message_field_name') => "Usuario Eliminado",
          $this->config->item('data') => $this->config->item('dataT')
        ), 200);
      }
    } else {
      //faltan datos en el formulario
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
        $this->config->item('rest_message_field_name') => "el usuario no existe",
        $this->config->item('data') => $this->config->item('dataF')
      ), REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  function access_get()
  {
    //igual que lo anteriores aca obtenemos los datos de la cabecera
    $headers     = apache_request_headers();
    $metodo_data = array();
    foreach ($headers as $header => $value) {
      $metodo_data[$header] = $value;
    }
    //cargamos los modulos consultas de usuario y access
    $this->load->model('Model_usr');
    $this->load->model('model_usracc');
    // de la cabecera sacamos la key
    $key       = $metodo_data['key'];
    // vemos de quien es la key
    $usr_datos = $this->Model_usr->consulta_usuario($key);
    // la logica se repite con la evaluacion si es o no level 3 para formar el parametro criterio
    if ($usr_datos['level'] <> 3) {
      //no es super usuario
      $criterio = array(
        "id" => $usr_datos['user_id'],
        "status" => "active"
      );
    } else {
      //es super usuario
      if ($this->uri->segment(3)) {
        $usr_id   = $this->uri->segment(3);
        $criterio = array(
          "user_id" => $usr_id,
          "status" => "active"
        );
      } else {
        $criterio = array(
          "status" => "active"
        );
      }
    }
    $usr = $this->model_usracc->get_many_by($criterio);
    if (isset($usr['id'])) {
      //si no hay registros
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
        $this->config->item('rest_message_field_name') => 'Consulta no exitosa',
        $this->config->item('data') => $this->config->item('dataF')
      ), 200);
    } else {
      // si si hay registros
      if (count($usr) > 0) {
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('bien'),
          $this->config->item('rest_message_field_name') => "Consulta Satisfactoria",
          $this->config->item('data') => $usr
        ), 200);
        //  $this->response($usr);
      } else {
        //si no hay registros
        $this->response(array(
          $this->config->item('rest_status_field_name') => 'success',
          $this->config->item('rest_message_field_name') => "Consulta no Satisfactoria",
          $this->config->item('data') => $this->config->item('dataF')
        ), 200);
        // $this->response(array('status' => 'error', 'msg' => 'error_details'), 404);
      }
    }
  }
  function access_put()
  {
    //lo parametros de la cabecera
    $headers     = apache_request_headers();
    $metodo_data = array();
    foreach ($headers as $header => $value) {
      $metodo_data[$header] = $value;
    }
    // cargamos la validacion de formulario
    $this->load->library('form_validation');
    //en este model creamos la llave
    $this->load->model("Register_model", "registerModel");
    // ya explicamos esta funcion en usr_put
    $data = remove_unknown_fields($metodo_data, $this->form_validation->get_field_names('access_put'));
    // ya explicamos esta funcion en usr_put
    $this->form_validation->set_data($data);
    // validamos que la validacion si pase
    if ($this->form_validation->run('access_put') != false) {
      //ubico los parametros que necesito para asignar permisos en la tabla access
      $control  = $data['controller'];
      $function = $data['function'];
      $metodo   = $data['metodo'];
      $usr_id   = $data['user_id'];
      //en la tabla acces al ingresar el campo controller debe ingresar de la siguiente manera "/ControlValor" aca propongo quitar el string "/"" y al moneto de ingresar el registro colocarlo asi me aseguro que solo yo manejo la nomeclatura necesaria
      $control  = str_replace("/", "", $control);
      //cargo el modelo de gestion de usuarios
      $this->load->model('model_usracc');
      //consulto
      $exist = $this->model_usracc->count_by(array(
        "controller" => "/" . $control,
        "function" => $function,
        "metodo" => $metodo,
        "status" => "active",
        "user_id" => $usr_id
      ));
      //validar si existe almenos un nombre de usuario a consultado
      if (($exist >= 1)) {
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
          $this->config->item('rest_message_field_name') => "Registro duplicado",
          $this->config->item('data') => $this->config->item('dataF')
        ), REST_Controller::HTTP_BAD_REQUEST);
      } else {
        //si no existe lo ingreso
        //generar primera llave, esta llave se actualizara con la primera session del usuario
        $generate = $this->registerModel->new_api_key($level = false, $ignore_limits = false, $is_private_key = false, $ip_addresses = "", $user_id = $usr_id);
        if ($generate) {
          $data['key'] = $generate;
          //insertar permiso
          $id          = $this->model_usracc->insert($data);
        }
      }
      if (!$id) {
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
          $this->config->item('rest_message_field_name') => 'un error al crear el registro',
          $this->config->item('data') => $this->config->item('dataF')
        ), REST_Controller::HTTP_BAD_REQUEST);
      } else {
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('bien'),
          $this->config->item('rest_message_field_name') => "Registro Creado",
          $this->config->item('data') => $this->config->item('dataT')
        ), 404);
      }
    } else {
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
        $this->config->item('rest_message_field_name') => $this->form_validation->get_errors_as_array(),
        $this->config->item('data') => $this->config->item('dataF')
      ), 404);
    }
  }
  //http://127.0.0.1/apiRestCodeigniter/index.php/Users/access/Id_access
  function access_post()
  {
    //el id de acceso a modificar
    $usracc_id   = $this->uri->segment(3);
    //capturamos la cabecera del mensaje
    $headers     = apache_request_headers();
    $access_data = array();
    foreach ($headers as $header => $value) {
      $access_data[$header] = $value;
    }
    //cargamos el modulo de consulta de la tabla acces los modulos para cada tabla esta en la ruta /models     alli se encuntran todos los modulos d consultas
    $this->load->model('model_usracc');
    // aca consultamos los permisos
    $usracc = $this->model_usracc->get_by(array(
      "id" => $usracc_id,
      "status" => "active"
    ));
    // si el permiso existe en base de su id enviado
    if (isset($usracc['id'])) {
      // cargamos las validaciones
      $this->load->library('form_validation');
      // removemos los parametros que no nos interesan
      $data = remove_unknown_fields($access_data, $this->form_validation->get_field_names('access_post'));
      // se lo asignamos a el formulacion para poder comprar si paso o no la validacion
      $this->form_validation->set_data($data);
      //vemos si paso o no la validacion de parametros de formulario
      if ($this->form_validation->run('access_post') != false) {
        $this->load->model('model_usracc');
        //esta consulta pregunta si lo que quiero modificar genera un regsitro identico en la tabla  para el mismo usuario
        $safe_metodo = $this->model_usracc->verifica_metodo_update_exist($data['controller'], $data['function'], $data['metodo'], $usracc_id, $data['user_id']);
        if ($safe_metodo > 0) {
          // si safe_metodo ya existe
          $this->response(array(
            $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
            $this->config->item('rest_message_field_name') => "Acceso Duplicado para el usuario",
            $this->config->item('data') => $this->config->item('dataF')
          ), 404);
        }
        // si no existe hacemos el update
        $updated = $this->model_usracc->update($usracc_id, $data);
        if (!$updated) {
          $this->response(array(
            $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
            $this->config->item('rest_message_field_name') => 'Un error al actualizar el acceso',
            $this->config->item('data') => $this->config->item('dataF')
          ), REST_Controller::HTTP_BAD_REQUEST);
        } else {
          $this->response(array(
            $this->config->item('rest_status_field_name') => $this->config->item('bien'),
            $this->config->item('rest_message_field_name') => "Acceso Actualizado",
            $this->config->item('data') => $this->config->item('dataT')
          ), 200);
        }
      } else {
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
          $this->config->item('rest_message_field_name') => $this->form_validation->get_errors_as_array(),
          $data => $this->config->item('dataF')
        ), 404);
      }
    } else {
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
        $this->config->item('rest_message_field_name') => "Acceso no existe",
        $this->config->item('data') => $this->config->item('dataF')
      ), REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  //http://127.0.0.1/apiRestCodeigniter/index.php/Users/access/id_access
  function access_delete()
  {
    $id = $this->uri->segment(3);
    $this->load->model('model_usracc');
    $metodo = $this->model_usracc->get_by(array(
      "id" => $id,
      "status" => "active"
    ));
    if (isset($metodo['id'])) {
      $data['status'] = "delete";
      $deleted        = $this->model_usracc->delete($id);
      if (!$deleted) {
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
          $this->config->item('rest_message_field_name') => 'un error al eliminar el access',
          $this->config->item('data') => $this->config->item('dataF')
        ), REST_Controller::HTTP_BAD_REQUEST);
      } else {
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('bien'),
          $this->config->item('rest_message_field_name') => "Ecceso eliminado",
          $this->config->item('data') => $this->config->item('dataT')
        ), 200);
      }
    } else {
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
        $this->config->item('rest_message_field_name') => "el access no existe",
        $this->config->item('data') => $this->config->item('dataF')
      ), REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  function session_put()
  {
    //cargar valores del header de la peticion
    /*    $headers = apache_request_headers();
    $usr_data= array();
    foreach ($headers as $header => $value) {
    $usr_data[$header] =  $value;

    }

    */
    $usr_data = $this->put();
    //        $nombre = $this->put('username');
    //        $nombre = $usr_data['username'];
    //cargar libreria de validacion
    $this->load->library('form_validation');
    //si me envias algo que no es lo elimino y solo dejo pasar los necesario
    $data = remove_unknown_fields($usr_data, $this->form_validation->get_field_names('session_put'));
    $this->form_validation->set_data($data);
    if ($this->form_validation->run('session_put') != false) {
      //cargo los modelos que utilizare
      $this->load->model('model_usracc');
      $this->load->model('model_acc');
      //capturo los valores necesarios
      $username = $data['username'];
      $password = $data['password'];
      //consulto el usuario y contraseña
      $uk       = $this->model_acc->get_by(array(
        "username" => $username,
        "password" => md5($password),
        "status" => "active"
      ));
      //pregunto si la consulta tiene mas de un registro
      if ($uk >= 1) {
        //si el usario es valido solo asi cargo el modelo para crear llave
        $this->load->model("Register_model", "registerModel");
        //creo la llave y de una vez s eguarda la llave asociada al usuario
        $generate = $this->registerModel->new_api_key($level = $uk['level'], $ignore_limits = false, $is_private_key = false, $ip_addresses = "", $user_id = $uk['id']);
        // si se genera la llave y se logra guardar
        if ($generate) {
          //aca cargo el modelo para actualizar las llaves de los permisos y asi dejarle paso a las funciones solo a la llave mas no al usuario
          $this->load->model('model_usracc');
          //actualiso las llaves de access
          $updated = $this->model_usracc->update_key($uk['id'], $generate);
          if ($updated >= 1) {
            $response = array(
              array(
                "key" => $generate,
                "id" => $uk['id'],
                'keys_expire' => $this->config->item('rest_time_keys_expire'),
                "level" => $uk['level']
              )
            );
            $this->response(array(
              $this->config->item('rest_status_field_name') => $this->config->item('bien'),
              $this->config->item('rest_message_field_name') => "Bienvenido",
              "key" => $generate,
              "data" => $response
            ), 200);
          } else {
            $this->response(array(
              $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
              $this->config->item('rest_message_field_name') => "En estos momentos no  podemos asignarle permisos",
              $this->config->item('data') => $this->config->item('dataF')
            ), 400);
          }
          //$this->response(array('status' => 'Faliured' ,"menssage" => "uno", "key"=> Null,400));
        } else {
          $this->response(array(
            $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
            $this->config->item('rest_message_field_name') => "En estos Momentos no podemos atender su inicio de sesion",
            $this->config->item('data') => $this->config->item('dataF')
          ), 400);
        } //fin is generate
      } else {
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
          $this->config->item('rest_message_field_name') => "Usuario o Contraseña erroneos",
          $this->config->item('data') => $this->config->item('dataF')
        ), 400);
      } // fin si existe el usuario valido
    } else {
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
        $this->config->item('rest_message_field_name') => "datos imcompletos",
        $this->config->item('data') => $this->config->item('dataF')
      ), 400);
    } //fin form validation
  } //fin session
  /*esta funcion solo entrega la pregunta secreta a partir de un user_name valido esto es para iniciar el proceso de recuperacion de contraseña*/
  function prerecu_get()
  {
    //obtenemos la cabecera de valores
    $headers  = apache_request_headers();
    $usr_data = array();
    foreach ($headers as $header => $value) {
      $usr_data[$header] = $value;
    }
    $this->load->library('form_validation');
    $this->load->model('Model_usr_p');
    //consultamos si el user_name existe
    $usrdat = $this->Model_usr_p->get_by(array(
      "username" => $usr_data['username']
    ));
    //   $this->response(array("datos"=> $usrdat),200);
    if (count($usrdat) > 0) {
      //queremos mostrar la respuesta entre llaves [] para eso la encerramos en un array esta es la razon de esta linea
      $response = array(
        $usrdat
      );
      // mostramos el resultado
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('bien'),
        $this->config->item('rest_message_field_name') => "Muestra Pregunta Secreta",
        "data" => $response
      ), 200);
    } else {
      //usuario no existe
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
        $this->config->item('rest_message_field_name') => "No existe el usuario",
        "data" => $this->config->item('dataF')
      ), 500);
    }
  }
  /* esta funcion permite cambiar la contraseña a partir de responder correctamente la pregunta secreta ya entregada en la funcion get anterior*/
  function prerecu_post()
  {
    $headers  = apache_request_headers();
    $usr_data = array();
    foreach ($headers as $header => $value) {
      $usr_data[$header] = $value;
    }
    $this->load->library('form_validation');
    //remuevo lo que no me interesa
    $data = remove_unknown_fields($usr_data, $this->form_validation->get_field_names('Modelusrp_post'));
    $this->form_validation->set_data($data);
    $this->load->model('Model_usr_p');
    // si viene lo que esperamos en config/form_validation.php
    if ($this->form_validation->run('Modelusrp_post') != false) {
      //sacamos los valores necesarios que vienen en la cabecra y fomamos una consulta para ver si respondio existosamente la pregunta y es del usuario
      $usrdat = $this->Model_usr_p->get_by(array(
        "pregunta1" => $usr_data['pregunta1'],
        "respuesta1" => $usr_data['respuesta1'],
        "username" => $usr_data['username']
      ));
      //asignamos un array con password para set de la nueva contraseña
      $datos  = array(
        "password" => $usr_data['password']
      );
      if (count($usrdat) >= 1) {
        // si es valida la respuesta hacemos update de contraseña
        $updated = $this->Model_usr_p->update($usrdat['id'], $datos);
      } else {
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
          $this->config->item('rest_message_field_name') => 'Respuesta Erronea',
          $this->config->item('data') => $this->config->item('dataF')
        ), 500);
      }
      if ($updated) {
        // si si se actualizo la contraseña
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('bien'),
          $this->config->item('rest_message_field_name') => "Clave Actualizada",
          $this->config->item('data') => $this->config->item('dataT')
        ), 200);
      } else {
        $this->response(array(
          $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
          $this->config->item('rest_message_field_name') => 'No puede actualizar clave',
          $this->config->item('data') => $this->config->item('dataF')
        ), 500);
      }
    } else {
      //faltan datos en el formulario
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
        $this->config->item('rest_message_field_name') => $this->form_validation->get_errors_as_array(),
        $this->config->item('data') => $this->config->item('dataF')
      ), 404);
    }
  }
  /*esta funcion presumen que estas en session para utilizarla solo recive la nueva contraseña y la llave para saber que usuario quiere cambiar su contraseña es mas una funcion en un modulo de manteniento*/
  function prerecu_put()
  {
    $headers  = apache_request_headers();
    $usr_data = array();
    foreach ($headers as $header => $value) {
      $usr_data[$header] = $value;
    }
    $this->load->model('Model_usr');
    $this->load->model('Model_usr_p');
    $key       = $usr_data['key'];
    $usr_datos = $this->Model_usr->consulta_usuario($key);
    //formamos el clriterio de la consulta
    if ($usr_datos['level'] == 3) {
      $id = $this->uri->segment(3);
    } else {
      $id = $usr_datos['user_id'];
    }
    if (isset($usr_data['password'])) {
      //hacemos el update de contraseña
      $datos   = array(
        "password" => ltrim(rtrim(($usr_data['password'])))
      );
      $updated = $this->Model_usr_p->update($id, $datos);
    } else {
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
        $this->config->item('rest_message_field_name') => 'Escriba su nueva Contraseña',
        $this->config->item('data') => $this->config->item('dataF')
      ), 500);
    }
    if ($updated) {
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('bien'),
        $this->config->item('rest_message_field_name') => "Clave Actualizada",
        $this->config->item('data') => $this->config->item('dataT')
      ), 200);
    } else {
      $this->response(array(
        $this->config->item('rest_status_field_name') => $this->config->item('fallo'),
        $this->config->item('rest_message_field_name') => 'No se puede modificar el registro',
        $this->config->item('data') => $this->config->item('dataF')
      ), 500);
    }
  }
  // la funcion postilion_get permite invocarla y mantener viva la llave generando un nuevo registro exitoso en la tabla logs
  // solo se envia la llave NO caducada
  function postilion_get()
  {
    $this->response(array(
      $this->config->item('rest_status_field_name') => $this->config->item('bien'),
      $this->config->item('rest_message_field_name') => "postilion",
      $this->config->item('data') => $this->config->item('rest_time_keys_expire')
    ), 200);
  }
  // asignamos permisos minimos al registrar usuario
  function inicio_permisos($usr_id)
  {
    $this->load->model("Register_model", "registerModel");
    $this->load->model('model_usracc');
    $generate              = $this->registerModel->new_api_key($level = false, $ignore_limits = false, $is_private_key = false, $ip_addresses = "", $user_id = $usr_id);
    $deta[0]['controller'] = '/Users';
    $deta[0]['function']   = 'session';
    $deta[0]['metodo']     = 'put';
    $deta[0]['user_id']    = $usr_id;
    $deta[0]['key']        = $generate;
    $deta[1]['controller'] = '/Users';
    $deta[1]['function']   = 'usr';
    $deta[1]['metodo']     = 'post';
    $deta[1]['user_id']    = $usr_id;
    $deta[1]['key']        = $generate;
    $deta[2]['controller'] = '/Users';
    $deta[2]['function']   = 'usr';
    $deta[2]['metodo']     = 'get';
    $deta[2]['user_id']    = $usr_id;
    $deta[2]['key']        = $generate;
    $deta[3]['controller'] = '/Users';
    $deta[3]['function']   = 'postilion';
    $deta[3]['metodo']     = 'get';
    $deta[3]['user_id']    = $usr_id;
    $deta[3]['key']        = $generate;
    $permisos              = $this->model_usracc->insert_many_desa($deta);
  }
  /*************************************/
}
?>
