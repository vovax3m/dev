<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ivr extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
			$this->load->library('cab_auth'); 
			//auth check
			if(!$this->cab_auth->check()){
				$bu= base_url();
				setcookie('STAT', 'Пожалуйста, войдите', time()+86400);
				header('Location: '.$bu.'auth/login') ;
			}
			// раздел доступен только администратору, если не админ переадресуем на запрет
			if(!$this->cab_auth->is_admin()) header('Location: '.$bu.'/auth/forbitten') ;
			// подключаем  доп модули
			$this->load->helper('url');
            $this->load->library('funcs');    
            $this->load->helper('cookie');    
		}
	/*
	 Функция 
	*/	
	public function index(){
		
		 $vats = $this->config->item('vats');
		$f=file_get_contents('https://'.$vats.'/cabinet/ivr.php?act=show&');
		$data['row']=$rawdata=json_decode($f,true);
		//echo $data['stat']=$this->input->cookie('STAT', TRUE);
		
		$h['isadmin']=$this->cab_auth->is_admin();
		$h['base_url']=base_url();
		//уровень функционала кабинета
			$h['is_full']=$data['is_full']=$this->auth_model->get_setting('type'); 
		$this->load->view('/templates/header',$h);   
		$this->load->view('ivr',$data);
		$this->load->view('/templates/footer',$h); 
	}
	
	function add(){
	//	print_r($_POST);
	//	print_r($_FILES);
	
		$fnameB=$this->input->post('setas'); 
		if($_FILES['upload']['name']){ // если есть вложения
					//$config['file_name']  = urlencode($_FILES['upload']['name']);
					$tmpfilename=microtime(true); 
					$tmpfilename=str_replace('.', "_", $tmpfilename);
					$config['file_name']  = $tmpfilename.'.wav';
					$config['upload_path'] = 'files/';
					$config['allowed_types'] = 'wav';
					$config['remove_spaces']=true;
					$this->load->library('upload', $config);
					if ( ! $this->upload->do_upload('upload'))	{
						 $error = array('error' => $this->upload->display_errors());
						 $log=fopen('logs/errorlog','a+');
						 fwrite($log,'Upload error Ivr.php '.$error);
						 fclose($log);
						 print_r($error);
						 
					}else{
						echo 'upload success';
						$fnameA= $config['file_name'];
						 $vats = $this->config->item('vats');
						$f=file_get_contents('https://'.$vats.'/cabinet/ivr.php?act=get&fnameA='.$fnameA.'&fnameB='.$fnameB);
						if($f=='OK'){
						//echo $f;
							$this->input->set_cookie('STAT', 'Установлено успешно', '3600', '', '/', '', false);
							//redirect('ivr');
							$bu= base_url();
							header('Location: '.$bu.'ivr'); 
						}
					}
		}
				
	}
}
