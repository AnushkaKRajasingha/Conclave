<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Points_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    
    public function addpoint($data){
    	
    	$this->db->from(DB_PREFIX .'points p');
    	foreach ($data as $key => $value) {
    		if($key != 'earn_date')
    		$this->db->where($key,$value);
    	}
    	
    	$query = $this->db->get();
    	//$this->db->last_query();
    	if($query->num_rows() <= 0) {
    		$this->db->insert(DB_PREFIX .'points', $data);
    		return $this->db->affected_rows();
    	}
    	return false;
    }
    
    public function getpointsbyuser(){
    	$this->db->select('*');
    	$this->db->from(DB_PREFIX .'points p');
    	$this->db->join(DB_PREFIX .'user u', 'u.user_id = p.user_response', 'left');
    	$this->db->where('p.user_id',$this->session->userdata('user_id'));    	
    	
    	$query = $this->db->get();
    	
    	if($query->num_rows() > 0) {
    		return $query->result();
    	}
    	return false;
    }
    
    public function getpointsummarybyuser(){
    	//$this->db->select_sum('p.amount');
    	$this->db->select('sum(p.amount) total');
    	$this->db->from(DB_PREFIX .'points p');
    	$this->db->where('p.user_id',$this->session->userdata('user_id'));
    	$this->db->group_by('p.user_id');
    	$this->db->limit(1);
    	$query = $this->db->get();
    	 
    	if($query->num_rows() == 1) {
    		return $query->row();
    	}
    	return false;
    }
}