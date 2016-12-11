<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	/**
	 * @var Rbac
	 */
	public $rbac;

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
//		$this->output->enable_profiler(TRUE);
//		$this->load->view('welcome_message');
		$this->home();
	}


	public function home()
	{
		$this->load->config('extjs');
		$data['extjs'] = $this->config->item('extjs');
		$this->load->view('home', $data);
	}

	public function rbac($type = 'role', $id = null)
	{
		$this->load->library('RbacLib');
		$this->load->helper('plantuml');

		if($type == 'role')
		{
			$uml = $this->rbaclib->getRoleUml($id);
		}
		else
		{
			$uml = $this->rbaclib->getPermissionUml($id);
		}


		echo getSVG($uml);
		die();

		$this->paintUml($uml);
	}

	protected function paintUml($uml)
	{
		$url = 'http://localhost:8080/plantuml/form';
		$fields = array(
			'text'=>urlencode($uml)
		);

		$fields_string = '';
//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string,'&');

//open connection
		$ch = curl_init();

//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

//execute post
		$result = curl_exec($ch);
		print $result;
	}
}
