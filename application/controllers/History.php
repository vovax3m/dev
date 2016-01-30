<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History extends CI_Controller { 
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
		
			/* основная функция  статистики вызовов*/
			//проверяет является ли пользователь админом или нет, boolean
			$isadmin=$h['isadmin']=$data['isadmin']=$this->cab_auth->is_admin(); 
			//уровень функционала кабинета
			$h['is_full']=$data['is_full']=$this->auth_model->get_setting('type'); 
			//возвращает базовый путь сайта  используется для переадресации и подключения скриптов и стилей
			$bu=$h['base_url']=base_url();
			// внутренний номер пользователя
			 $exten=$this->input->cookie('auth_exten', TRUE);
			 // получаем куки с фильтром
			$filter=json_decode($this->input->cookie('fltr', TRUE));
			//print_r($filter);
			 // проверяем наличие  параметров фильтров
			foreach($filter as $k=>$v){
				if($v=='zero'and ($k=='durtime' or $k=='durtime_sec')){
					$data['fltr'][$k]='0'; 
					$fltr[$k]='zero';
				}else{
					$data['fltr'][$k]=$fltr[$k]=$v; 
				}
			}
			if(!$fltr['startdate'] ) $fltr['startdate']=$data['fltr']['startdate']=date('Y-m-d');
			if(!$fltr['enddate'] ) {
				$fltr['enddate']=$data['fltr']['enddate']=date('Y-m-d');
				$this->input->set_cookie('fltr', json_encode($fltr), '86400'); 
			 }
			$data['extlist']=$this->funcs->GetExt();
			if($isadmin){
				$data['fltr']['exten']   =$fltr['exten'] ;
			}else{
			    $data['fltr']['exten']   =$fltr['exten'] =$this->funcs->checkbad($exten); 
			}
			
			//пагинация
			
			 if($this->input->cookie('cdr_page', TRUE)){
				$rowPAGE=$this->input->cookie('cdr_page', TRUE);
				$this->input->set_cookie('cdr_page', $num, '-86400'); 
				
			}else{
				$rowPAGE=$this->input->get('page');
			}
			
			
			// определяем текущую странцицу		
			if(empty($rowPAGE)){
				$page=$iCurr=1;
			}else{
				$page=$iCurr=intval($rowPAGE);
			}
				
		  // limits offset  обрабатываем отступы
		  //RPP = строк на странице, задается в конфиге и на странице, если если задали на странице,перезатираем 
		  $RPP=$this->config->item('cdr_pagin');;
		  if($this->input->cookie('cdr_RPP', TRUE)){
			$RPP=$this->input->cookie('cdr_RPP', TRUE);
		  }
		  
		  
		  	if($iCurr===1){
				$limit='0,'.$RPP;
			}else{
				$limit=($iCurr-1) * $RPP.','.$RPP;
			}
			// перебор параметров фильтра, убираем пустые значения
			// нужно для экспорта
			foreach($fltr as $k => $v){
				if($v){
					$filter.=$k.'='.$v.'&';
				}
			}
		 	$data['filter']=$filter;
		 //$vats  айпишник ватс, задается в настройках
		$vats = $this->config->item('vats'); 
		// обращаемся к ватс, принимаем список звонков в JSON формате
		//echo $filter;
		//echo 'https://'.$vats.'/cabinet/cdr.php?limit='.$limit.'&'.$filter;
		$f=file_get_contents('https://'.$vats.'/cabinet/cdr.php?limit='.$limit.'&'.$filter);
		$data['row']=$rawdata=json_decode($f,true); 
		
		
		
		//разбираем результат
		if($rawdata['total']){
			foreach($rawdata as $k=>$s){ 
				if($s['total']){
					$data['total']=$numrows=$s;
					continue;
				};
				/*
				$num  = номер А  берем из поля channel, обычно в таком виде SIP/peer-000008c6 ( SIP/111-000008bf ), разбиваем по тире,
				 потом по слэшу и берем 2 часть
				 с $dnum  - номер Б по аналогии   берем из поля dstchannel, делаем тоже самое
				*/
				//NOMER A
				$num=explode('-',$s['channel']); 
				$num=explode('/',$num[0]);
				$num=$num[1];
				//NOMER B
				$dnum=explode('-',$s['dstchannel']);
				$dnum=explode('/',$dnum[0]);
				//echo $s['uniqueid'];
				/*
				считаем что если номерА состоит только из цифр, то есть без названия транка типа peer и подобного, то звонок исходящий
				иначе входящий
				*/
				//if($dnum=='01')print_r($s) ;
				if($fltr['exten']){
					if($num==$fltr['exten']){
						$data['cdr'][$k]['direct']=$direction='out';
						
					
					}elseif($dnum==$fltr['exten']){
							// входящий
						$data['cdr'][$k]['direct']=$direction='incom';
					}else{
						$data['cdr'][$k]['direct']=$direction='incom';
					}
				}
				else{
					//echo $num.';';
					if(is_numeric($num)){
						
						// исходящий
						$data['cdr'][$k]['direct']=$direction='out';	
					}
					else{
						// входящий
						$data['cdr'][$k]['direct']=$direction='incom';
					}
				}
				/*	
				if($num==$fltr['exten']){
					$data['cdr'][$k]['direct']=$direction='out';
				//}elseif(is_numeric($num)){
					// исходящий
				//	$data['cdr'][$k]['direct']=$direction='out';
					
				}elseif($dnum==$fltr['exten']){
					// входящий
					$data['cdr'][$k]['direct']=$direction='incom';
				}else{
					$data['cdr'][$k]['direct']=$direction='incom';
				}
				*/
				//дата звонка
				$data['cdr'][$k]['calldate']=$s['calldate'];
				// имя номераА
				$name=$this->book_model->get($s['src']);
				$data['cdr'][$k]['src']=$s['src'];
				$data['cdr'][$k]['src_name']=$name;
				$data['cdr'][$k]['channel']=$s['channel'];
				/*
				 Если звонок входящий и номерБ внутреннй, то в поле Назначение подставляем dnum, иначе подставляем имя dst
				 Это необходимо для обработки звонков на сотовые, когда у внешнего номера есть внутренний.
				 Если звонок исходящий, то в назаначении оставляем как есть dst
				*/
				if($direction=='incom'){
					if(is_numeric($dnum)){
						$name=$this->book_model->get($dnum);
						$data['cdr'][$k]['dst']=$dnum;
						$data['cdr'][$k]['dstchannel']=$s['dstchannel'];
						$data['cdr'][$k]['dst_name']=$name;
					} else{
						$name=$this->book_model->get($s['dst']);
						$data['cdr'][$k]['dst']=$s['dst'];
						$data['cdr'][$k]['dstchannel']=$s['dstchannel'];
						$data['cdr'][$k]['dst_name']=$name;
					}
				}else{
					$name=$this->book_model->get($s['dst']);
					$data['cdr'][$k]['dst']=$s['dst'];
					$data['cdr'][$k]['dstdstchannel']=$s['dstchannel'];
					$data['cdr'][$k]['dst_name']=$name;
				}
				// описываем варианты  состояний разговора
				if($s['disposition']=='NO ANSWER'){
						$data['cdr'][$k]['status']='не отвечен';
				}elseif($s['disposition']=='ANSWERED'){ 
						$data['cdr'][$k]['status']='отвечен';
				}elseif($s['disposition']=='FAILED'){
						$data['cdr'][$k]['status']='ошибка';
				}elseif($s['disposition']=='BUSY'){
						$data['cdr'][$k]['status']='занят';
				}elseif($s['disposition']=='UNKNOWN'){
						$data['cdr'][$k]['status']='неизвестно';
				}
				// DID
				$data['cdr'][$k]['account']=$s['account'];
				// продолжительность вызова
				$data['cdr'][$k]['dur']=$this->funcs->dur2min($s['billsec']);
				// название файла записи разговора. может быть, может не быть
				$data['cdr'][$k]['recordingfile']=$s['recordingfile'];;
			}
		}
		/*
		pagination  
		Если текущая страница  в началае, отображаем следующие + полседнюю
		Если в середине, то несколько перед текущей, несколько после, первую и последнюю
		Если в конце, то несколько предыдущих, первую
		*/ 
		// получаем количество страниц	берем количество строк, делим на количество на странице, округляем в большую сторону 
		$numpages=ceil($numrows /$RPP); //количество страниц
		//страница в середине 
		if($iCurr > 4 && $iCurr < ($numpages-3)){	
			$pagin.= '<span class="pagin">'.$this->funcs->QS(1,$page).'</span>';
			for($i=$iCurr-3; $i<=$iCurr+3; $i++){
				if($i==$iCurr){
					$pagin.=  '<span class="pagin curr">'.$this->funcs->QS($i,$page).'</span>';
				}else{
					$pagin.= '<span class="pagin">'.$this->funcs->QS($i,$page).'</span>';
				}
			} 
			$pagin.='<span class="pagin">'.$this->funcs->QS($numpages,$page).'</span>';
			//страница в начале	
		}elseif($iCurr<=4){
			if($numpages==1){
				//pass
			}elseif($numpages==$iCurr and $iCurr==1 ){
				$pagin.=  '<span class="pagin curr">'.$this->funcs->QS($iCurr,$page).'</span>'; 
			}elseif($numpages <= 8){
				for($i=1; $i<=$numpages; $i++){
					if($i==$iCurr){
						$pagin.=  '<span class="pagin curr">'.$this->funcs->QS($i,$page).'</span>';
					}else{
						$pagin.= '<span class="pagin">'.$this->funcs->QS($i,$page).'</span>';
					}
				}	
			}else{
				//страница в начале';
				$iSlice = 1+3-$iCurr;
				for($i=1; $i<=$iCurr+(3+$iSlice); $i++){
					if($i==$iCurr){
						$pagin.=  '<span class="pagin curr">'.$this->funcs->QS($i,$page).'</span>';
					}else{
						$pagin.= '<span class="pagin">'.$this->funcs->QS($i,$page).'</span>';
					}
				}
				$pagin.='<span class="pagin">'.$this->funcs->QS($numpages,$page).'</span>';
			}	
			//страница в конце
		}else {
			//echo 'страница в конце';
			if($numpages <= 8){
				for($i=1; $i<=$numpages; $i++) {
					if($i==$iCurr){
						$pagin.=  '<span class="pagin curr">'.$this->funcs->QS($i,$page).'</span>';
					}else{
						$pagin.= '<span class="pagin">'.$this->funcs->QS($i,$page).'</span>';
					}
				}
			}else{	
				$pagin.= '<span class="pagin">'.$this->funcs->QS(1,$page).'</span>';
				$iSlice = 3-($numpages - $iCurr);
				for($i=$iCurr-(3+$iSlice); $i<=$numpages; $i++){
					if($i==$iCurr){
						$pagin.=  '<span class="pagin curr">'.$this->funcs->QS($i,$page).'</span>';
					}else{
							$pagin.= '<span class="pagin">'.$this->funcs->QS($i,$page).'</span>';
					}
				}
			}	
		}
	
		/*  
		конец пагинации
		*/
		$data['RPP']=$RPP;
		$data['pagin']=$pagin; 
		//отображаем html
		$this->load->view('/templates/header',$h);
		$this->load->view('cdr',$data);
		$this->load->view('/templates/footer',$h);
	}
	function test(){
		echo phpinfo();
	
	}
	
	function changeRPP($num){
			if (is_numeric($num)){
				$this->input->set_cookie('cdr_RPP', $num, '86400'); 
				return 'true';
			}else{
				return 'false';
			}
		
		}
	function changepage($num){
			if (is_numeric($num)){
				$this->input->set_cookie('cdr_page', $num, '86400'); 
				return 'true';
			}else{
				return 'false';
			}
		
		}
		/*
		сохраняем в куки установки фильтра
		*/
		function setfilter($page){
		
			$this->input->set_cookie('cdr_page', $num, '86400'); 
			if($_GET['startdate']){
				$fltr['startdate'] =$this->funcs->checkbad($_GET['startdate']); 
			}else{
				$fltr['startdate']=date('Y-m-d');
			}
			 // если приняли параметр коненой даты, то и используем его, иначе подставляем текущую, тоже самое что и с начальной
			if($_GET['enddate']){
				$fltr['enddate']  =$this->funcs->checkbad($_GET['enddate']);
			}else{
				$fltr['enddate']  =date('Y-m-d');
			}
			//тип длительности
			
			if($_GET['durtype'] and ($_GET['durtime'] or $_GET['durtime']=='0')){
				$fltr['durtype']  = $_GET['durtype'] ;
				//  значение длительности
				if($_GET['durtime'] =='0'){
					$fltr['durtime']='zero';
					$fltr['durtime_sec'] ='zero';
				}else{
					$fltr['durtime'] =($this->funcs->checkbad($_GET['durtime'])*60);
					$fltr['durtime_sec']  =$this->funcs->checkbad($_GET['durtime']);
				}
			};
				// статус звонка
			if($_GET['anstype']) $fltr['anstype']  =$this->funcs->checkbad($_GET['anstype']);
			
			if($_GET['exten']) $fltr['exten'] =$_GET['exten']; 
			// чекбокс входящие вызовы
			if($_GET['incom']) $fltr['incom'] =$_GET['incom'];
			// чекбокс исходящие вызовы
			if($_GET['outcom']) $fltr['outcom']   =$_GET['outcom'];
			// чекбокс наличие записи Есть
			if($_GET['recyes']) $fltr['recyes']    =$_GET['recyes'];
			// чекбокс наличие записи нет
			if($_GET['recno']) $fltr['recno']     =$_GET['recno'];
			// поле поиска в результатах 
			// только при наличии все 3 полей
			if($_GET['find']){ 
				if(is_numeric($_GET['find'])){
					$fltr['find']       =$this->funcs->checkbad($_GET['find']);
				}else{
				  //$fltr['find']=$this->funcs->checkbad($_GET['find']);
				  $arr=$this->book_model->search($_GET['find']);
				  $fltr['find']=$this->funcs->checkbad($_GET['find']);
				  foreach($arr as $n){
					$fltr['FindByName'].=$n['nomer'].',';
				  }
				  
				}
				// место в тексте
				$fltr['findplace']       =$_GET['findplace'];
				// столбец в котором ищем
				$fltr['findfield']       =$_GET['findfield'];
				
				
			};
			
			
						
			$this->input->set_cookie('fltr', json_encode($fltr), '86400'); 
			//echo json_encode($fltr);
			$bu=$h['base_url']=base_url();
		header('Location: '.$bu.$page);  
		
		}
		
}
