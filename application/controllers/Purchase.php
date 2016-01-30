<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller {
		public function __construct()	{
			parent::__construct();
			// подключаем помощники, библиотеки, модели
			$this->load->helper('url');
			$this->load->model('auth_model');
			$this->load->helper('cookie');
			$this->load->library('funcs');
			$this->load->library('cab_auth'); 
     	}
		public function index(){ 
			$h['isadmin']=$data['isadmin']=$this->cab_auth->is_admin(); 
			//уровень функционала кабинета
			$h['is_full']=$data['is_full']=$this->auth_model->get_setting('type'); 
			$this->load->view('templates/header',$h);  
			$this->load->view('purchase',$data);
			$this->load->view('templates/footer',$h);			
		
		}
		
		public function mem(){ 
			//$CI =& get_instance();
			$m = new Memcached(); 
			$m->addServer('localhost', 11211);
			$res=$m->getAllKeys() ;
			print_r($res);
		}
//end of class		
}