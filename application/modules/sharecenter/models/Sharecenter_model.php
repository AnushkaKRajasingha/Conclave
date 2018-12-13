<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sharecenter_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function loadSharecenter(){
    	$_myposts = $this->posts_model->getallbyuser('publish');
    	$_sharedposts = $this->getshareposts();
    	return array('_myposts' => $_myposts,'_sharedposts'=>$_sharedposts);
    }
    
    
    public function connectstoshare($postid){
    	$query = "SELECT c.requestby,c.acceptby, u.user_id,u.first_name,u.email  ,u.date_registered,u.username,u.profile_img,c.constatus ,count(p.id) noofposts
,u.date_registered,c.id,1 'request'
FROM ".DB_PREFIX."connections c
left join ".DB_PREFIX."user u on  u.user_id = c.acceptby
left join ".DB_PREFIX."posts p on p.post_author = u.user_id
where c.requestby = {$this->session->userdata('user_id')} and c.constatus ='connected'
and u.user_id NOT IN (select ps.sharedto from ".DB_PREFIX."postshare ps where ps.postid = {$postid} and ps.sharedby = c.requestby)
   	group by u.user_id
union
SELECT  c.requestby,c.acceptby, u.user_id,u.first_name,u.email  ,u.date_registered,u.username,u.profile_img,c.constatus ,count(p.id) noofposts
,u.date_registered,c.id,0 'request'
FROM ".DB_PREFIX."connections c
left join ".DB_PREFIX."user u on  u.user_id = c.requestby
left join ".DB_PREFIX."posts p on p.post_author = u.user_id
where c.acceptby = {$this->session->userdata('user_id')} and c.constatus ='connected'
and u.user_id NOT IN (select ps.sharedto from ".DB_PREFIX."postshare ps where ps.postid = {$postid} and ps.sharedby = c.acceptby)
   	group by u.user_id";
    	
    	$query =  $this->db->query($query);
    	
    	
    	if($query->num_rows() > 0) {
    		return $query->result_array();
    	}
    	return false;
    	
    }
    
    public function sharepost($data){
    		$this->db->insert(DB_PREFIX .'postshare', $data);
    		$this->db->affected_rows();
    		
    		if(isset($data['sharedfrom']) && $data['sharedfrom'] != 0){
    			$this->db->where('id',$data['sharedfrom'] );
    			$this->db->update(DB_PREFIX .'postshare', array('isreshared'=>1));
    		}
    		return   $this->db->affected_rows();;
    }
    
    public function getshareposts(){
    	$query = "SELECT p.id,ps.id psid, ps.postid, ps.sharedby, ps.sharedto, ps.share_date, ps.isread, ps.isreshared, ps.sharedfrom ,
p.post_author,p.post_title,p.post_date,p.post_content,p.post_status,u.*
FROM ".DB_PREFIX ."postshare ps
join ".DB_PREFIX ."posts p on p.id = ps.postid
join ".DB_PREFIX ."user u on u.user_id = ps.sharedby
where ps.sharedto = '{$this->session->userdata('user_id')}' and ps.isreshared = 0 and ps.isdelted = 0";
    	
    	$query =  $this->db->query($query);
    	 
    	 
    	if($query->num_rows() > 0) {
    		return $query->result();
    	}
    	return false;
    }
    
    public function getsharepoststoread(){
    	$query = "SELECT p.id,ps.id psid, ps.postid, ps.sharedby, ps.sharedto, ps.share_date, ps.isread, ps.isreshared, ps.sharedfrom ,
    	p.post_author,p.post_title,p.post_date,p.post_content,p.post_status,
    	u.*
    	FROM ".DB_PREFIX ."postshare ps
    	join ".DB_PREFIX ."posts p on p.id = ps.postid
    	join ".DB_PREFIX ."user u on u.user_id = ps.sharedby
    	where ps.sharedto = '{$this->session->userdata('user_id')}' and  ps.isreshared = 0 and isread = 0 and ps.isdelted = 0 ";
    	 
    	$query =  $this->db->query($query);
    
    
    	if($query->num_rows() > 0) {
    		return $query->result();
    	}
    	return false;
    }
    
    
    public function myshares(){
    	$query = "SELECT p.id,ps.id psid, ps.postid, ps.sharedby, ps.sharedto, ps.share_date, ps.isread, ps.isreshared, ps.sharedfrom ,
    	p.post_author,p.post_title,p.post_date,p.post_content,p.post_status
    	FROM ".DB_PREFIX ."postshare ps
    	join ".DB_PREFIX ."posts p on p.id = ps.postid
    	where ps.sharedby = '{$this->session->userdata('user_id')}' and ps.isreshared = 0 and ps.isdelted = 0";
    	 
    	$query =  $this->db->query($query);
    	
    	
    	if($query->num_rows() > 0) {
    		return $query->result();
    	}
    	return false;
    }
    
    public function deletesharepost($id) {
    	$this->db->where('id',$id);
    	$this->db->update(DB_PREFIX .'postshare', array('isdelted'=>1));
    	return $this->db->affected_rows();
    }
    
    
}