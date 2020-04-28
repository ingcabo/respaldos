<?php
//debemos colocar esta línea para extender de REST_Controller
require APPPATH.'/libraries/REST_Controller.php';

class Sum extends REST_Controller{

	function __construct()
	{
		parent:: __construct();
		$this->load->helper('my_api_helper');
		$this->load->config('rest');
	}

	public function index_post()
	{
    $this->load->library('form_validation');
    //$data= $this->input->raw_input_stream;
    $data= $this->input->post();

    //$data = remove_unknown_fields($data, $this->form_validation->get_field_names('suma_post'));
    //$this->form_validation->set_data($data);
    $httpRespond="200";

/*
    if ($this->form_validation->run('suma_post') != false) {
      $data=$data["right"] + $data["left"];
      $httpRespond="200";
    }else{
      $data= $this->form_validation->get_errors_as_array();
      $httpRespond="505";
    }

*/

		$this->response(array(
			"result" =>$data,
			"name"=> "ingcabo@gmail.com"
    ),$httpRespond);

	}

}
