<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fwd extends CI_Controller { 
		public function __construct(){
			parent::__construct();
			$this->load->library('cab_auth');  // подключаем библиотеку авторизации
			$this->load->library('funcs');        // подключаем библиотеку доп функций
			$this->load->model('book_model');        // подключаем библиотеку доп функций
			$this->load->model('auth_model');        // подключаем библиотеку доступности функций
			//проверка авторизации
			if(!$this->cab_auth->check()){
				$bu= base_url();  // определяем базовый путь
				setcookie('STAT', 'Пожалуйста, войдите', time()+300); // записываем в куку сообщение
				header('Location: '.$bu.'auth/login') ; // переадресовываем на страницу с формой входа
			}
		}
		public function index(){
			//проверяет является ли пользователь админом или нет, boolean
			$isadmin=$h['isadmin']=$data['isadmin']=$this->cab_auth->is_admin(); 
			//echo 'admin=<  '.$isadmin.'  >';
			//уровень функционала кабинета
			$h['is_full']=$data['is_full']=$this->auth_model->get_setting('type'); 
			//возвращает базовый путь сайта  используется для переадресации и подключения скриптов и стилей
			$bu=$h['base_url']=base_url();
			// внутренний номер пользователя
			$exten=$this->input->cookie('auth_exten', TRUE);
			
			//получаем список абонентов
			$ext=$this->funcs->GetExt();
			asort($ext);
			
			foreach ($ext as $k => $v){
				$name=$this->book_model->get($k,1);
				$ext[$k]['n']=$name['name'];
				$ext[$k]['t']=$name['type'];
			}
			$data['ext']=$ext;
			$data['kolvo']=count($ext);
			
			//получаем списко переадресаций
			$fwd=$this->funcs->GetFwd();
			
			foreach(json_decode($fwd,true) as $v){
				$keyval=explode(':',$v); //разделяем ключи от значений
				$keynum=explode('/',$keyval[0]); // разделяем тип от номеров
				$r[trim($keynum[2])][trim($keynum[1])]=trim($keyval[1]); // группируем ключи и значения по вн номерам
			}
			
			if($isadmin){
				$data['fwd']=$r;
			}else{
				$data['fwd'][$exten]=$r[$exten];
				$data['gotoext']=$exten;
				
			}
					
			
			 //отображаем html
			$this->load->view('/templates/header',$h);
			$this->load->view('/fwd/fwd.php',$data);
			$this->load->view('/templates/footer',$h);
		}
 		function set(){
			//print_r($_POST);
			if($_POST['exten']){
				$set['exten']=$_POST['exten'];
				if ($_POST['cf']  ){
					if (is_numeric($_POST['cf']))	$set['cf']=$_POST['cf'];
				} 
				if ($_POST['cfu'] ){
					if (is_numeric($_POST['cfu']))	$set['cfu']=$_POST['cfu'];
				}
				if ($_POST['cfb'] ){
					if (is_numeric($_POST['cfb']))	$set['cfb']=$_POST['cfb'];
				}
				if (isset($_POST['cw'])) {  
					echo 'CW';
					$set['cw']='on';
				}
				if (isset($_POST['dnd'])) { 
					echo 'DND';
					$set['dnd']='on';
				}
			}
			  $fwd=$this->funcs->SetFwd($set);
			 $bu= base_url();  // определяем базовый путь
			 $this->input->set_cookie('gotoext', $_POST['exten'], '+3600');
			 $this->input->set_cookie('STAT', 'Данные обновлены', '+3600');
			 header('Location: '.$bu.'fwd') ; 
	 }
	 function setcustom(){
		$set=$_POST	;
		echo $this->funcs->SetCustom($set);
		$bu= base_url();  // определяем базовый путь
		$this->input->set_cookie('gotoext', $_POST['exten'], '+3600');
		//$this->input->set_cookie('STAT', 'Данные обновлены', '+3600');
		header('Location: '.$bu.'fwd') ; 
	 }
			
		
		
}		