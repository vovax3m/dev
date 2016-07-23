<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor extends CI_Controller { 
		public function __construct(){
			parent::__construct();
			$this->load->library('cab_auth');  // подключаем библиотеку авторизации
			$this->load->library('funcs');        // подключаем библиотеку доп функций
			$this->load->model('book_model');        // подключаем библиотеку доп функций
			$this->load->model('auth_model');        // подключаем библиотеку доступности функций
			//проверка авторизации
			if(!$this->cab_auth->check()){
				$bu= base_url();  // определяем базовый путь
				setcookie('STAT', 'Пожалуйста, войдите', time()+86400); // записываем в куку сообщение
				header('Location: '.$bu.'auth/login') ; // переадресовываем на страницу с формой входа
			}
		}
		public function index(){
			
			//проверяет является ли пользователь админом или нет, boolean
			$isadmin=$h['isadmin']=$data['isadmin']=$this->cab_auth->is_admin(); 
			//уровень функционала кабинета
			$h['is_full']=$data['is_full']=$this->auth_model->get_setting('type'); 
			//возвращает базовый путь сайта  используется для переадресации и подключения скриптов и стилей
			$bu=$h['base_url']=base_url();
			// внутренний номер пользователя
			$exten=$this->input->cookie('auth_exten', TRUE);
			
			//получаем список абонентов
			$ext=$this->funcs->GetExt();
			
			
			asort($ext);
			
			//print_r($ext);
			foreach ($ext as $k => $v){
				$name=$this->book_model->get($k,1);
				$ext[$k]['n']=$name['name'];
				$ext[$k]['t']=$name['type'];
				//$ext[$k]['n']=$name;
			}
			$data['ext']=$ext;
			
			$data['kolvo']=count($ext);
			 //отображаем html
			$this->load->view('/templates/header',$h);
			$this->load->view('/monitor/monitor.php',$data);
			$this->load->view('/templates/footer',$h);
		}
		/*
		ajax 
		*/
		public function getconv(){
			
			$is_full=$h['is_full']=$data['is_full']=$this->auth_model->get_setting('type'); 
			
			$isadmin=$h['isadmin']=$data['isadmin']=$this->cab_auth->is_admin(); 
			$exten=$this->input->cookie('auth_exten', TRUE);
			$conv=$this->funcs->GetConv();
			$conv=json_decode($conv,true);
			
			# get rotator numbers
			$m = new Memcached(); 
			$m->addServer('localhost', 11211);
			if(!$m->get('rotator')){
				$rotator=file_get_contents('http://deploy.sip64.ru/service/rotator');
				$m->set('rotator', $rotator, time() + 3600);
			} else {
				$rotator=$m->get('rotator');
			}
			$rotator=json_decode($rotator);
			
			foreach($conv as $c){
				if(!$c['state']) continue;
				if($c['state']=='Down (0)') continue;
				if($c['state']=='Ringing (5)') continue;
				if(in_array($c['cid'],$rotator)) continue;
				if($c['clid']=='(N/A)') $c['clid']='s';
				$ch=explode("-",$c['channel'])[0];
				$ch=str_replace("SIP/",'',$ch);
				$ch=str_replace("IAX/",'',$ch);
				if($c['state']=='Up (6)'){
					$color='back-green';
				}else{
					$color='back-grey';
				}
				
				//$title="{$c['channel']} ; {$c['cid']} ; {$c['clid']} ; {$c['state']} ; {$c['time']}/ ; {$c['app']}";
				
				if (is_numeric($ch)){
					//outgoing call
					$name1=$this->book_model->get($ch,1);
					$name2=$this->book_model->get($c['clid'],1);
					echo "<span class=' twentypx convitem arrow_left {$color}' title='{$title}' >
							<span class='fpart'>
								<i class='fa fa-arrow-left '></i>
							</span>
							<span class='spart'>
								{$ch} 
								<span class='green'>
									{$name1['name']} {$name1['type']}
								</span> <i class='fa fa-long-arrow-right '></i> {$c['clid']} 
								<span class='green'>
									{$name2['name']} {$name2['type']}
								</span>
							</span>";
							if($is_full=='FULL'){
								if($isadmin){
									echo "<span class='tpart' onclick=\"drop('{$c['channel']}')\">
										<i class='fa fa-close hand '></i>
									</span>
									<span class='tpart'onclick=\"sufl('{$c['channel']}','{$exten}')\">
										<i class='fa fa-bullhorn hand '></i>
									</span>";
								}
							}else{
								if($isadmin){
									echo "<span class='tpart inactive' onclick=\"inactive()\">
										<i class='fa fa-close hand '></i>
									</span>
									<span class='tpart inactive'onclick=\"inactive()\">
										<i class='fa fa-bullhorn hand '></i>
									</span>";
								}
							}
							echo "<span class='tpart'>
								{$c['time']}
							</span>
						</span>";
				}else{
					//incoming call
					$name1=$this->book_model->get($c['cid'],1);
					$name2=$this->book_model->get($c['clid'],1);
					echo "<span class=' twentypx convitem arrow_right {$color}'  title='{$title}'>
							<span class='fpart'>
								<i class='fa fa-arrow-right '></i>
							</span>
							<span class='spart'>
								{$c['cid']} 
								<span class='green'>
									{$name1['name']} {$name1['type']}
								</span> <i class='fa fa-long-arrow-right '></i> {$c['clid']}
								<span class='green'>
									{$name2['name']} {$name2['type']}
								</span>
							</span>";
							
							if($is_full=='FULL'){
								if($isadmin){
									echo "<span class='tpart' onclick=\"drop('{$c['channel']}')\">
										<i class='fa fa-close hand '></i>
									</span>
									<span class='tpart'onclick=\"sufl('{$c['channel']}','{$exten}')\">
										<i class='fa fa-bullhorn hand '></i>
									</span>";
								}
							}else{
								if($isadmin){
									echo "<span class='tpart inactive' onclick=\"inactive()\">
										<i class='fa fa-close hand '></i>
									</span>
									<span class='tpart inactive' onclick=\"inactive()\">
										<i class='fa fa-bullhorn hand '></i>
									</span>";
								}
							}
							echo "<span class='tpart'>{$c['time']}
							</span>
						</span>";
				}
				//echo "<span class='convitem {$color}' >{$ch} {$c['cid']} {$c['clid']}   {$c['time']}</span>" ;
			}
			
		}
		public function dropcall(){
			
			$drop=$this->funcs->DropCall($_GET['ch']);
			echo $drop;
		}
		public function spy(){
			
			$spy=$this->funcs->spy($_GET['a'],$_GET['b'],$_GET['type']);
			echo $spy;
		}
//class end			
}