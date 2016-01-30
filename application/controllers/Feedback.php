<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends CI_Controller {
		public function __construct()	{ 
			parent::__construct();
			// подключаем помощники, библиотеки, модели
			$this->load->helper('url');
			$this->load->model('auth_model');
			$this->load->helper('cookie');
			$this->load->library('funcs'); 
			$this->load->library('cab_auth');  // подключаем библиотеку авторизации
			//проверка авторизации
			if(!$this->cab_auth->check()){
				$bu= base_url();  // определяем базовый путь
				setcookie('STAT', 'Пожалуйста, войдите', time()+86400); // записываем в куку сообщение
				header('Location: '.$bu.'auth/login') ; // переадресовываем на страницу с формой входа
			}
     	}
		/*
		индексная страница
		переводим на главный экран
		*/
		public function index(){ 
		
			if($_GET['backto']){
				setcookie('backto', $_GET['backto'], time()+86400);
			}
			$klient_name = $this->config->item('klient_name'); 
			$login=$this->input->cookie('auth_username', TRUE);
			$exten=$this->input->cookie('auth_exten', TRUE);
			$date=date('Y-m-d H:i:s');
			if($_GET['subj']=='LITE'){
				$data['subj']='Заявка на переключение кабинета на ЛЕГКУЮ версию';
				$data['mess']="Добрый день! Прошу Вас рассмотреть возможность переключения кабинета компании {$klient_name} на ЛЕГКУЮ версию. {$login} / {$exten} / {$date}";
			}
			elseif($_GET['subj']=='FULL'){
				$data['subj']='Заявка на переключение кабинета на ПОЛНУЮ версию';
				$data['mess']="Добрый день! Прошу Вас рассмотреть возможность переключения кабинета компании {$klient_name} на ПОЛНУЮ версию. {$login} / {$exten} / {$date}";
			}
			elseif($_GET['subj']=='INDI'){
				$data['subj']='Заявка на переключение кабинета на ИНДИВИДУАЛЬНУЮ версию';
				$data['mess']="Добрый день! Прошу Вас рассмотреть возможность переключения кабинета компании {$klient_name} на ИНДИВИДУАЛЬНУЮ версию. {$login} / {$exten} / {$date}";
			}
			$h['isadmin']=$data['isadmin']=$this->cab_auth->is_admin(); 
			//уровень функционала кабинета
			$h['is_full']=$data['is_full']=$this->auth_model->get_setting('type'); 
			//возвращает базовый путь сайта  используется для переадресации и подключения скриптов и стилей
			$bu=$h['base_url']=base_url();
			// внутренний номер пользователя
			//$exten=$this->input->cookie('auth_exten', TRUE);
			//print_r($_SERVER);
			$this->load->view('/templates/header',$h);
			$this->load->view('feedback',$data);
			$this->load->view('/templates/footer',$h);
		
		}
		/*
			обработка формы обратной связи
		*/
		public function handler(){
			// поля формы
			$theme=$_POST['fb_theme'];
			$name=$_POST['fb_name'];
			$phone=$_POST['fb_phone'];
			$mess=$_POST['fb_mess'];
			
			// разрешенные типы файлов вложений и место загрузки
			$config['upload_path'] = './files/';
			$config['allowed_types'] = 'gif|jpg|png|doc|docx|xls|xlsx|pdf|txt|csv';
			//загрузка вложений			
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('fb_file'))
			{
				// неудачная загрузка
				$up_error = array('error' => $this->upload->display_errors()); 
			}
			else{
				//удачная, массив с информацией о загруженном файле
				$up_data = array('upload_data' => $this->upload->data());
			
			}
			// получение пути к файлу при аттаче			
			if($up_data['upload_data']['full_path']){
				$att=$up_data['upload_data']['full_path'];
			}
			
			// формируем сообщение (переделать на шаблон)
			$mess_full=$mess.' <br><br>NAME:'.$name.'<br><br>PHONE:'.$phone;
			
			// разбиваем доменное имя для получения поддомена
			$sub=explode('.',$_SERVER['SERVER_NAME']);
			//достоем логин из печений
			$login=$this->input->cookie('auth_username', TRUE);
			// формируем строку для логирования
			$log="sub={$sub[0]}&login={$login}&subj={$theme}&name={$name}&name={$name}&nomer={$phone}&mess={$mess}&attach={$up_data['upload_data']['orig_)name']}&type=feedback";
			// шлем письмо
			$status=$this->funcs->e_mail($log,$_SERVER['SERVER_NAME'],$theme,$mess_full,$att);
			// формируем уведомлеие в зависимости от результата
			if($status){
				$this->input->set_cookie('STAT', 'Письмо отправлено', '86400');
			}
			else{
				$this->input->set_cookie('STAT', 'Ошибка отправки письма', '86400');	
			}
			// если приерепляли файл - удаляем его
			if($att){
				 unlink($att);
			};
			// перенаправляем на страницу с формой
			$bu=base_url();
			if($this->input->cookie('backto', TRUE)){
				header('Location: '.$bu.$this->input->cookie('backto', TRUE)) ; 
			}else{
				header('Location: '.$bu.'feedback') ; 
			}
		}
//end of class	
}