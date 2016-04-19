<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Normal_Controller {

	public function __construct()
	{		
		parent::__construct();
	}

	public function index()
	{
		echo "welcome";
		// $this->load->view($this->getTemplateFile());
	}
}
