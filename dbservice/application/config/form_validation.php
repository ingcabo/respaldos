<?php
if (!defined('BASEPATH'))
     exit('No direct script access allowed');
$config = array(
     'usr_put' => array(
             array(
                     'field' => 'username',
                     'lebel' => 'nombre de usuario',
                     'rules' => 'trim|required|max_length[50]'
             ),
             array(
                     'field' => 'password',
                     'lebel' => 'contraseña',
                     'rules' => 'trim|required|max_length[50]'
             ),
             array(
                     'field' => 'nombres',
                     'lebel' => 'Nombres',
                     'rules' => 'trim|required|max_length[50]'
             ),
             array(
                     'field' => 'apellidos',
                     'lebel' => 'apellidos',
                     'rules' => 'trim|required|max_length[50]'
             ),
             array(
                     'field' => 'pregunta1',
                     'lebel' => 'pregunta',
                     'rules' => 'trim|required|max_length[120]'
             ),
             array(
                     'field' => 'respuesta1',
                     'lebel' => 'respuesta',
                     'rules' => 'trim|required|max_length[120]'
             ),
             array(
                     'field' => 'email',
                     'lebel' => 'Email',
                     'rules' => 'trim|valid_email'
             ),
             array(
                     'field' => 'cargo',
                     'lebel' => 'cargo ocupado',
                     'rules' => 'trim|required|max_length[50]'
             )
     ),
     'usr_post' => array(
             array(
                     'field' => 'password',
                     'lebel' => 'contraseña',
                     'rules' => 'trim|max_length[50]'
             ),
             array(
                     'field' => 'nombres',
                     'lebel' => 'Nombres',
                     'rules' => 'trim|max_length[50]'
             ),
             array(
                     'field' => 'apellidos',
                     'lebel' => 'apellidos',
                     'rules' => 'trim|max_length[50]'
             ),
             array(
                     'field' => 'pregunta1',
                     'lebel' => 'pregunta',
                     'rules' => 'trim|max_length[120]'
             ),
             array(
                     'field' => 'respuesta1',
                     'lebel' => 'respuesta',
                     'rules' => 'trim|max_length[120]'
             ),
             array(
                     'field' => 'email',
                     'lebel' => 'Email',
                     'rules' => 'trim|valid_email'
             ),
             array(
                     'field' => 'cargo',
                     'lebel' => 'cargo ocupado',
                     'rules' => 'trim|max_length[50]'
             )
     ),
     'metodo_put' => array(
             array(
                     'field' => 'controller',
                     'lebel' => 'controlador',
                     'rules' => 'trim|required|max_length[60]'
             ),
             array(
                     'field' => 'funcion',
                     'lebel' => 'funcion',
                     'rules' => 'trim|required|max_length[60]'
             ),
             array(
                     'field' => 'metodo',
                     'lebel' => 'metoto',
                     'rules' => 'trim|required|max_length[60]'
             ),
             array(
                     'field' => 'description',
                     'lebel' => 'description',
                     'rules' => 'trim|required|max_length[60]'
             )
     ),
     'metodo_post' => array(
             array(
                     'field' => 'controller',
                     'lebel' => 'controlador',
                     'rules' => 'trim|max_length[60]'
             ),
             array(
                     'field' => 'funcion',
                     'lebel' => 'funcion',
                     'rules' => 'trim|max_length[60]'
             ),
             array(
                     'field' => 'metodo',
                     'lebel' => 'metoto',
                     'rules' => 'trim|max_length[60]'
             ),
             array(
                     'field' => 'description',
                     'lebel' => 'description',
                     'rules' => 'trim|max_length[60]'
             )
     ),
     'access_put' => array(
             array(
                     'field' => 'user_id',
                     'lebel' => 'usuario id',
                     'rules' => 'trim|required|max_length[12]'
             ),
             array(
                     'field' => 'controller',
                     'lebel' => 'controlador',
                     'rules' => 'trim|required|max_length[60]'
             ),
             array(
                     'field' => 'function',
                     'lebel' => 'funcion',
                     'rules' => 'trim|required|max_length[60]'
             ),
             array(
                     'field' => 'metodo',
                     'lebel' => 'metoto',
                     'rules' => 'trim|required|max_length[60]'
             ),
             array(
                     'field' => 'description',
                     'lebel' => 'description',
                     'rules' => 'trim|max_length[60]'
             )
     ),
     'access_post' => array(
             array(
                     'field' => 'user_id',
                     'lebel' => 'usuario id',
                     'rules' => 'trim|max_length[12]'
             ),
             array(
                     'field' => 'controller',
                     'lebel' => 'controlador',
                     'rules' => 'trim|max_length[60]'
             ),
             array(
                     'field' => 'function',
                     'lebel' => 'funcion',
                     'rules' => 'trim|max_length[60]'
             ),
             array(
                     'field' => 'metodo',
                     'lebel' => 'metoto',
                     'rules' => 'trim|max_length[60]'
             ),
             array(
                     'field' => 'description',
                     'lebel' => 'description',
                     'rules' => 'trim|max_length[60]'
             )
     ),
     'session_put' => array(
             array(
                     'field' => 'username',
                     'lebel' => 'usuario',
                     'rules' => 'trim|required|max_length[50]'
             ),
             array(
                     'field' => 'password',
                     'lebel' => 'password',
                     'rules' => 'trim|required|max_length[50]'
             )
     ),
     'traking_put' => array(
             array(
                     'field' => 'id_lote',
                     'lebel' => 'factura',
                     'rules' => 'trim|required|max_length[11]'
             ),
             array(
                     'field' => 'empresa_env',
                     'lebel' => 'empresa_env',
                     'rules' => 'trim|required|max_length[250]'
             ),
             array(
                     'field' => 'descripcion',
                     'lebel' => 'descripcion',
                     'rules' => 'trim|required|max_length[250]'
             )
     ),
     'traking_post' => array(
             array(
                     'field' => 'id_lote',
                     'lebel' => 'factura',
                     'rules' => 'trim|required|max_length[11]'
             ),
             array(
                     'field' => 'empresa_env',
                     'lebel' => 'empresa_env',
                     'rules' => 'trim|max_length[250]'
             ),
             array(
                     'field' => 'descripcion',
                     'lebel' => 'descripcion',
                     'rules' => 'trim|max_length[250]'
             )
     ),
     'status_put' => array(
             array(
                     'field' => 'control',
                     'lebel' => 'control',
                     'rules' => 'trim|max_length[150]'
             ),
             array(
                     'field' => 'cod_afiliacion',
                     'lebel' => 'cod_afiliacion',
                     'trim|max_length[150]'
             ),
             array(
                     'field' => 'factura',
                     'lebel' => 'factura',
                     'rules' => 'trim|max_length[150]'
             ),
             array(
                     'field' => 'monto',
                     'lebel' => 'monto',
                     'rules' => 'trim|max_length[150]'
             ),
             array(
                     'field' => 'estado',
                     'lebel' => 'estado',
                     'rules' => 'trim|max_length[15]'
             ),
             array(
                     'field' => 'codigo',
                     'lebel' => 'codigo',
                     'trim|max_length[15]'
             ),
             array(
                     'field' => 'descripcion',
                     'lebel' => 'descripcion',
                     'rules' => 'trim|max_length[150]'
             ),
             array(
                     'field' => 'vtid',
                     'lebel' => 'vtid',
                     'rules' => 'trim|max_length[150]'
             ),
             array(
                     'field' => 'seqnum',
                     'lebel' => 'seqnum',
                     'rules' => 'trim|max_length[150]'
             ),
             array(
                     'field' => 'authid',
                     'lebel' => 'authid',
                     'rules' => 'trim|max_length[150]'
             ),
             array(
                     'field' => 'authname',
                     'lebel' => 'authname',
                     'rules' => 'trim|max_length[150]'
             ),
             array(
                     'field' => 'tarjeta',
                     'lebel' => 'tarjeta',
                     'trim|max_length[150]'
             ),
             array(
                     'field' => 'referencia',
                     'lebel' => 'referencia',
                     'rules' => 'trim|max_length[150]'
             ),
             array(
                     'field' => 'terminal',
                     'lebel' => 'terminal',
                     'rules' => 'trim|max_length[150]'
             ),
             array(
                     'field' => 'lote',
                     'lebel' => 'lote',
                     'rules' => 'trim|max_length[150]'
             ),
             array(
                     'field' => 'rifbanco',
                     'lebel' => 'rifbanco',
                     'trim|max_length[150]'
             ),
             array(
                     'field' => 'afiliacion',
                     'lebel' => 'afiliacion',
                     'rules' => 'trim|max_length[150]'
             ),
             array(
                     'field' => 'voucher',
                     'lebel' => 'voucher',
                     'rules' => 'trim'
             )
     ),
     'suma_post' => array(
             array(
                     'field' => 'left',
                     'lebel' => 'left',
                     'rules' => 'trim|required|max_length[250]'
             ),
             array(
                     'field' => 'right',
                     'lebel' => 'right',
                     'rules' => 'trim|required|max_length[250]'
             )
     ),
);
?>
