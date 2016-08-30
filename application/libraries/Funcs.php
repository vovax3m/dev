<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// мой класс с фунциями необходимыми для работы
class Funcs {

    public function __construct() {
        //parent::__construct();
    }
// формирует timestamp с нашим часовым поясом
    	function getQS(){
		//берем строку параметров и убираем пустые
		 	$qs=$_SERVER['QUERY_STRING'];
			$qsnew='';
			$qsel=explode('&',$qs);
			foreach($qsel as $par){
				$parkv=explode('=',$par);
				if($parkv[1]){
					if($parkv[0])continue;
					$qsnew.=$parkv[0].'='.$parkv[1].'&';
					
				}
				
			}
			return $qsnew; 
		}
		function QS($p=False,$page){
		//$this->load->library('funcs'); 
		
		$qsnew= $this->getQS();
		//формируем ссылку на указанную страницу
		
			if(!strstr($qsnew,'page')){
				$qsnew.='page='.$p.'&';
				
			}else{
				$o='page='.$page;
				$n='page='.$p;
				$qsnew=	str_replace($o, $n, $qsnew);
				
			}
			unset($o); 
			unset($n);
			//return '<a href="'.$_SERVER['SCRIPT_NAME'].'?'.$qsnew.'">'.$p.'</a>'; 
			return '<a href="'.$_SERVER['SCRIPT_NAME'].'?'.$qsnew.'">'.$p.'</a>'; 
		}
		function checkbad($word){
			
			$badchars = array("`",";","'","*","/"," \ ","DROP", "SELECT", "UPDATE", "DELETE", "WHERE", "drop", "select", "update", "delete", "where",  "distinct", "having", "truncate", "replace", "handler", "like", "procedure", "limit", "order by", "group by");
			$word=str_replace( $badchars,'',$word);
			if (in_array($word, $badchars)) { 
				return ""; 
			} else { 
				return  $word; 
			}
		}
					  
		function get_fltr_name($param){
		
			switch($param){
				case 'startdate':
					return "Начальная дата";
				break;
				case 'enddate':
					return "Конечная дата";
				break;
				case 'exten':
					return "Вн. номер";
				break;
				case 'incom':
					return "Входящие вызовы";
				break;
				case 'outcom':
					return "Исходящие вызовы";
				break;
				case 'durtype':
					return "Условие длительности";
				break;
				case 'durtime':
					return "Длительность";
				break;
				case 'anstype':
					return "Статус звонка";
				break;
				case 'recyes':
					return "Наличие записи";
				break;
				case 'recno':
					return "Отсутствие записи";
				break;
			}
		}
		function sec2hms($sec){
			 $h=floor($sec/3600);
			 $m=floor(($sec-3600*$h)/60);
			 $s=floor($sec-(3600*$h+$m*60));
			 if($h<10) $h='0'.$h;
			 if($m<10) $m='0'.$m;
			 if($s<10) $s='0'.$s;
			 return $h.':'.$m.':'.$s;
		}
		
		function min2hms($min){
			 $h=floor($min/60);
			 $m=floor($min-60*$h);
			 $s='00';
			 if($h<10) $h='0'.$h;
			 if($m<10) $m='0'.$m;
			 return $h.':'.$m.':'.$s;
		}
		/*
		получаем остаток кредитного лимита
		*/
	function getsaldo(){
			$CI =& get_instance();
			$m = new Memcached(); 
			$m->addServer('localhost', 11211);
			/*
			Смотрим в кэше сначала, если нет обращаемся к базе (через деплой)
			*/
			$vats = $CI->config->item('vats');
			
			if(!$m->get($vats.'saldo')){
				$f=file_get_contents('https://'.$vats.'/cabinet/reg.php?type=one');
				$url='http://deploy.sip64.ru/service/getsaldo?no='.$f;
				$res=file_get_contents($url);
				// раз уж получили сохраняем в кэщ на 5 минут
				$m->set($vats.'saldo', $res, time() + 300);
			}
			else{
				//берем знаяение из кэша
				$res=$m->get($vats.'saldo');
			}
			return round($res,0);
			
		}
		/*
		 получаем список абонентов их типов и состояния регистрации
		*/
		function GetExt(){
			$CI =& get_instance();
			//инициализируем мэмкэшд
			$m = new Memcached(); 
			$m->addServer('localhost', 11211);
			
			/*
			проверяем, если значение сохранено, то срузу отдае его, иначе получаем от ватс
			*/
			$vats = $CI->config->item('vats');
			if(!$m->get($vats.'ext')){
				$f=file_get_contents('https://'.$vats.'/cabinet/extensions.php?type=get_w_reg');
				$m->set($vats.'ext', $f, time() + 300);
			}else{
				$f=$m->get($vats.'ext');
			}
			return json_decode($f, true);

		}
		/*
		 получаем список текущих разговоров
		*/
		function GetConv(){
			$CI =& get_instance();
			$vats = $CI->config->item('vats');
			$f=file_get_contents('https://'.$vats.'/cabinet/conversations.php?act=get');
			return ( $f==true ) ? $f : false;
		}
		/*
		 получаем список текущих разговоров
		*/
		function DropCall($ch){
			
			$CI =& get_instance();
			$vats = $CI->config->item('vats');
			$f=file_get_contents('https://'.$vats.'/cabinet/conversations.php?act=drop&ch='.$ch);
			//return  ( $f==true ) ? $f : false;
			echo $f;
		}
		/*
		 получаем список текущих разговоров
		*/
		function Spy($a,$b,$type){
			 
			$CI =& get_instance();
			$vats = $CI->config->item('vats');
			$f=file_get_contents('https://'.$vats.'/cabinet/call.php?spy=1&a='.$a.'&b='.$b.'&type='.$type);
			//return  ( $f==true ) ? $f : false;
			echo $f;
		}
		/*
		 приводим время разговорова к поминутному значению, в большую сторону
		 получаем количество секунд округляем до целых минут
		*/	
	function	 dur2min($dur){
		if($dur==0) return '';
		return ceil($dur/60);
	}
	
	function e_mail($log,$sub=false,$subj='Письмо с ЛК',$mess='',$att='',$from='no_reply@sip64.ru',$to='mironov@dialog64.ru'){
		/*
		отправка эл писем
		$log параметры письма для логов,
		$sub=false, поддомен
		$subj='Письмо с ЛК', тема сообщения
		$mess='', содержимое письма 
		$att='',путь к вложению
		$from='no_reply@dialog64.ru'  откакого имени шлем
		$to='mironov@dialog64.ru' на какой адрес шлем
		*/
		$CI =& get_instance();
		$CI->load->library('email');
		// формат письма
		$config['mailtype']='html';
		$CI->email->initialize($config);
		$CI->email->from($from, 'Диалог.Кабинет '.$sub);
		$CI->email->to($to); 
		//$this->email->cc('vovax3m@ya.ru'); 
		$CI->email->subject($subj); 
		$CI->email->message($mess);
		// если есть файл, криерепляем
		if($att){
			$CI->email->attach($att);
		};
		// если отправка не удалась
		if(!$CI->email->send()){
			$res= false;
		}else{
			$res= true;
		};
		/*
		логирование
		*/
		$ch = curl_init();
		if(!$log){
			$error='loggin failed';
		}else{
			if($res) $log.='&status=1';
			$url='http://deploy.sip64.ru/service/maillog';
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('application/x-www-form-urlencoded'));
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $log);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_exec($ch);	
		}
		return $res; // возвращаем результат отправки
			
	}
	function GetFwd($ext=false){
		$CI =& get_instance();
		$vats = $CI->config->item('vats');
		$f=file_get_contents('https://'.$vats.'/cabinet/fwd.php?mode=get&ext='.$ext);
		return $f;
	}
	
	function SetFwd($set=false){
		if($set['exten']){
			$set['mode']='put';
			$CI =& get_instance();
			$vats = $CI->config->item('vats');
			$ch = curl_init();
			$url='https://'.$vats.'/cabinet/fwd.php';
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('application/x-www-form-urlencoded'));
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $set);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			$r= curl_exec($ch);	
			return $r;
		}else{
			return 'error';
		}
	}
	function SetCustom($set){
		$set['mode']='custom';
			$CI =& get_instance();
			$vats = $CI->config->item('vats');
			$ch = curl_init();
			$url='https://'.$vats.'/cabinet/fwd.php';
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('application/x-www-form-urlencoded'));
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $set);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			$r= curl_exec($ch);	
			//clean cache
			$m = new Memcached(); 
			$m->addServer('localhost', 11211);
			$m->delete($vats.'ext');
			//$m->set($vats.'ext', '', time() - 300);
			return $r;
	}
	function directcall($num=false, $ext=false, $oldnum=false){
		$CI =& get_instance();
		$vats = $CI->config->item('vats');
		file_get_contents('https://'.$vats.'/cabinet/directcall.php?num='.$num.'&ext='.$ext.'&old='.$oldnum);
		return true;
	}
//end of class		
}