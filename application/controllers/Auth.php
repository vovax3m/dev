<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
		public function __construct()	{
			parent::__construct();
			// подключаем помощники, библиотеки, модели
			$this->load->helper('url');
			$this->load->model('auth_model');
			$this->load->helper('cookie');
			$this->load->library('funcs'); 
     	}
		/*
		индексная страница
		переводим на главный экран
		*/
		public function index(){ 
			$bu= base_url();
			header('Location: '.$bu);  
		
		}
		/*
		Функция обработки входа пользователя
		*/
		public function enter(){  
			// определение базового пути
			$bu= base_url();
			//проверяем существование обоих параметров ввода логина пароля
			//и при их наличии проводим операции
			if($this->input->post('username') and $this->input->post('passwd')){
					//присваиваем значание переменным, пропустив через фильтр
					$user=$this->funcs->checkbad($this->input->post('username'));
					$pass=$this->funcs->checkbad($this->input->post('passwd'));
					//обращаемся к базе и ищем указанные значения
					$db=$this->auth_model->login($user,sha1($pass));
					//если в базе нашлось совпадение
					if($db['id']){
						// success login pass
						//заносим в куки имя пользователя
						$this->input->set_cookie('auth_username', $db['username'],'86400'); 
						//заносим в куки номер пользователя
						$this->input->set_cookie('auth_exten', $db['exten'], '86400'); 
						//формируем  уникальный идентификатор
						$sessid=sha1(microtime(true).$db['exten']);
						// заносим его в базу
						$this->input->set_cookie('auth_sessid', $sessid, '86400');
						// определяем текущий ip  пользователя
						$ip=$this->input->ip_address();
						//сохраняем в базу ид и ип
					 	$db=$this->auth_model->savesessid($db['id'],$sessid,$ip);
						// формируем приветствие
						$this->input->set_cookie('STAT', 'Добро пожаловать', '86400');
						// сбрасываем неудачные попытки входа
						$db=$this->auth_model->reset_failed($ip,$ua);
						// переадресуем на главную страницу
						header('Location: '.$bu); 
						exit;
					// если совпадений по пользователям нет
					}else{
					// формируем сообщение
					$this->input->set_cookie('STAT', 'Неверный логин\пароль', '86400'); 
					// берем параметры браузера клиента,
					$ua=$_SERVER['HTTP_USER_AGENT']; 
					// определяем текущий ip  пользователя
					$ip=$this->input->ip_address(); 
					//$user=$this->funcs->checkbad($this->input->post('username')); 
					// записываем в базу
					$db=$this->auth_model->failed($ip,$ua);
					}
			}
			// перенаправляем на страницу входа
			header('Location: '.$bu.'auth/login');   
			
			
		}
		/*
		страница входа в систему
		*/
		public function login(){ 
			
			
		
			$trynomer=$this->auth_model->checkblock();
			if($trynomer>9){
				$this->load->view('/auth/block');		 
			}else{
				$data['u']=$_POST['u'];		
				$data['p']=$_POST['p'];		
				$data['trynomer']=$trynomer;
				$this->load->view('/auth/login',$data);		
			}
		
		}
		public function logout(){ 
		
			$this->input->set_cookie('auth_username','','-3600'); 
			$this->input->set_cookie('auth_exten', '', '-3600');
			$this->input->set_cookie('auth_sessid', '', '-3600');
			$this->input->set_cookie('fltr', '', '-3600');
			$this->input->set_cookie('su_deploy', '', '-3600','/',"sip64.ru"); 
			$bu= base_url();
			$this->input->set_cookie('STAT', 'До новых встреч', '86400'); 
			header('Location: '.$bu.'auth/login'); 
		}
		public function forbitten(){ 
			$this->load->view('/auth/forbitten');
		}
}