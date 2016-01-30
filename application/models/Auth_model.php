<?php
class Auth_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function login($user,$pass) 
	{
		$query = $this->db->query("SELECT id,username,exten FROM auth  WHERE ( username='$user' OR exten='$user') AND passwd='$pass';");
		return $query->row_array();
	}
	public function savesessid($id,$sessid,$ip) 
	{
		$data['sessid'] = $sessid;
		$data['ip'] = $ip;
		$this->db->where('id', $id);
		return $this->db->update('auth', $data);
	}
		public function saveip($id,$ip) 
	{
		//echo $id,$ip;
		$data['ip'] = $ip;
		$this->db->where('id', $id);
		return $this->db->update('auth', $data);
	}
	public function checksessid($asi,$ip) 
	{
		$data['sessid'] = $asi;
		$data['ip'] = $ip;
		$this->db->where($data);
		$query = $this->db->get('auth'); 
		if ($query->num_rows() > 0){
			$row = $query->row_array(); 
			return $row['id'];
  		}
	}
	public function checkblock() 
	{
		$data['useragent'] = $_SERVER['HTTP_USER_AGENT']; ;
		$data['ip'] = $this->input->ip_address();;
		$this->db->where($data);
		$query = $this->db->get('auth_failed');
		if ($query->num_rows() > 0){
			$row = $query->row_array(); 
			return $row['try'];
  		}
	}
	public function reset_failed() 
	{
		$data['useragent'] = $_SERVER['HTTP_USER_AGENT']; ;
		$data['ip'] = $this->input->ip_address();;
		$this->db->where($data);
		$query = $this->db->delete('auth_failed');
		
	}
	public function failed($ip,$ua) 
	{
		$query = $this->db->get_where('auth_failed', array('ip'=>$ip,'useragent'=>$ua));
		if ($query->num_rows() > 0){
			$row = $query->row_array(); 
			//print_r($row['try']);
			$id=$row['id'];
			$try=$row['try']+1;
			$this->db->where('id', $id);
			$this->db->update('auth_failed', array('try' => $try));  
			return $try;
		}else{
			$data = array('ip'=>$ip,'useragent'=>$ua,'try'=>1);
			$query=$this->db->insert('auth_failed', $data); 
			return 1; 
		}	
	//	echo $id,$ip,$ua;
		$data['ip'] = $ip;
		$this->db->where('id', $id);
		return $this->db->update('auth', $data);
	}
	public function isadmin($asi) 
	{
		$data['sessid'] = $asi ;
		$this->db->where($data);
		$query = $this->db->get('auth');
		if ($query->num_rows() > 0){
			$row = $query->row_array(); 
			return $row['is_admin'];
  		}
		
	}
	public function get_setting($key)	{
		/*
		получает значение настройки кабинета 
		*/
		$data['s_key'] = $key ;
		$this->db->where($data);
		$query = $this->db->select('s_value');
		$query = $this->db->get('settings');
		if ($query->num_rows() > 0){
			 $row=$query->row(); 
			 return $row->s_value;
		}		
	}
}