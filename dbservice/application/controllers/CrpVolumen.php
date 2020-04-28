<?php
//debemos colocar esta línea para extender de REST_Controller
require APPPATH.'/libraries/REST_Controller.php';

class CrpVolumen extends REST_Controller{

	function __construct()
	{
		parent:: __construct();
		$this->load->helper('my_api_helper');
		$this->load->model('Model_crpvolumen');
		$this->load->config('rest');
	}

	public function index_get()
	{

		$data = $this->Model_crpvolumen->get_manyby();

		$this->response(array(
			"status" =>"susses",
			 "data"=> $data
		 ),200);

	}

  public function index_put(){


  }
}
