<?php

require APPPATH.'/libraries/REST_Controller.php';

/**
 *
 */
class Exchanges extends REST_Controller
{

  function __construct()
  {
    parent:: __construct();
    $this->load->helper('my_api_helper');
    $this->load->model('Model_Exchanges');
    $this->load->config('rest');
  }

  function index_get(){

	 $data = $this->Model_Exchanges->get_manyby();

    $this->response(array(
			"status" =>"susses",
			 "data"=> $data
		 ),200);

  }

}
