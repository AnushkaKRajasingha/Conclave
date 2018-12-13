<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    /**
     *
     * create
     *
     * @param array $data
     * @return bool
     *
     */
    
    public function create($data) {
    	$this->db->insert(DB_PREFIX .'posts', $data);
    	return $this->db->insert_id();;
    }
    
    public function update($data) {
    	$this->db->where('id',$data['id']);
    	unset($data['id']);
    	$this->db->update(DB_PREFIX .'posts', $data);
    	return $this->db->affected_rows();
    }
    
    public function getallbyuser($status = null){
    	$this->db->select(array('p.id', 'p.post_author', 'p.post_date', 'p.post_content', 'p.post_title', 'p.post_status','u.*') );
    	$this->db->from(DB_PREFIX .'posts p');
    	$this->db->join(DB_PREFIX .'user u', 'u.user_id = p.post_author', 'left'); 
    	if($status != null)
    	$this->db->where('p.post_status', $status);
    	else 
    		$this->db->where('p.post_status <>', 'deleted');
    	$this->db->where('p.post_author', $this->session->userdata('user_id'));
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	if($query->num_rows() > 0) {
    		return $query->result();
    	}
    	return false;
    }
    
    public function getpost($id){
    	$this->db->select('*');
    	$this->db->from(DB_PREFIX .'posts p');
    	$this->db->join(DB_PREFIX .'user u', 'u.user_id = p.post_author', 'left');
    	$this->db->where('id',$id);
    	//$this->db->where('post_author',$this->session->userdata('user_id'));
    	$this->db->limit(1);
    	 
    	$query = $this->db->get();
    	 
    	 if($query->num_rows() == 1) {
            return $query->row();
        }
        return false;
    }
    
    public function getsharedpost($id){
    	$query = "SELECT p.id,ps.id psid, ps.postid, ps.sharedby, ps.sharedto, ps.share_date, ps.isread, ps.isreshared, ps.sharedfrom ,
p.post_author,p.post_title,p.post_date,p.post_content,p.post_status,
u.user_id,u.first_name,
uu.first_name shareusernaem,uu.user_id shareuserid
FROM ".DB_PREFIX ."postshare ps
join ".DB_PREFIX ."posts p on p.id = ps.postid
join ".DB_PREFIX ."user u on u.user_id = p.post_author
join ".DB_PREFIX ."user uu on uu.user_id = ps.sharedby
where ps.sharedto = '{$this->session->userdata('user_id')}' and p.id = {$id}";
    	
    	$query =  $this->db->query($query);
    	 
    	 
    	if($query->num_rows() > 0) {
    		return $query->row();
    	}
    	return false;
    }   
    
    public function getpostbyshareid($id){
    	$query = "SELECT p.id,ps.id psid, ps.postid, ps.sharedby, ps.sharedto, ps.share_date, ps.isread, ps.isreshared, ps.sharedfrom ,
    	p.post_author,p.post_title,p.post_date,p.post_content,p.post_status,
    	u.user_id,u.first_name,
    	uu.first_name shareusernaem
    	FROM ".DB_PREFIX ."postshare ps
    	join ".DB_PREFIX ."posts p on p.id = ps.postid
    	join ".DB_PREFIX ."user u on u.user_id = p.post_author
    	join ".DB_PREFIX ."user uu on uu.user_id = ps.sharedby
    	where ps.id  = {$id}";
    	 
    	$query =  $this->db->query($query);
    
    
    	if($query->num_rows() > 0) {
    		return $query->row();
    	}
    	return false;
    }
    
    public function getsharedposts(){
    	$query = "SELECT p.id,ps.id psid, ps.postid, ps.sharedby, ps.sharedto, ps.share_date, ps.isread, ps.isreshared, ps.sharedfrom ,
    	p.post_author,p.post_title,p.post_date,p.post_content,p.post_status,
    	u.user_id,u.first_name,
    	uu.first_name shareusernaem,uu.user_id shareuserid,
    	pst.first_name sharedto_uname
    	FROM ".DB_PREFIX ."postshare ps
    	join ".DB_PREFIX ."posts p on p.id = ps.postid
    	join ".DB_PREFIX ."user u on u.user_id = p.post_author
    	join ".DB_PREFIX ."user uu on uu.user_id = ps.sharedby
    	join ".DB_PREFIX ."user pst on pst.user_id = ps.sharedto
    	where ps.sharedby = '{$this->session->userdata('user_id')}'";
    	 
    	$query =  $this->db->query($query);
    
    
    	if($query->num_rows() > 0) {
    		return $query->result();
    	}
    	return false;
    }
    
    public function postalsosharedwith($userid,$postid){
    	$query = "select user_id,username,first_name from  ".DB_PREFIX ."user
where user_id in (SELECT sharedto FROM  ".DB_PREFIX ."postshare WHERE postid = {$postid} and sharedby= {$userid} )";
    	 
    	
    	$query =  $this->db->query($query);
    
    //	echo $this->db->last_query();
    	if($query->num_rows() > 0) {
    		return $query->result();
    	}
    	return false;
    }
    	
    private function _getpost($id){
    	$this->db->select('*');
    	$this->db->from(DB_PREFIX .'posts p');
    	$this->db->where('id',$id);
    	$this->db->limit(1);
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		return json_decode(json_encode($query->row()), true);
    	}
    	return false;
    }
    public function copypost($id){
    	$post = $this->_getpost($id);
    	if($post){
    		unset($post['id']);
    		$post['post_title'] = 'Copy of '.$post['post_title'];
    		$post['post_date'] = date('Y-m-d H:i:s');
    		return $this->create($post);
    	}   	
		return false;
    }
    
    public function deletepost($id){
    	$post =  $this->_getpost($id); 	
    	if($post){
    	$post['post_status'] = 'deleted';
    	return $this->update($post);
    	}
    	return false;
    }
    
    public function getpostsummusry(){
    	$this->db->select('p.*');
    	$this->db->from(DB_PREFIX .'posts p');
    	$this->db->where('post_status !=','deleted');
    	$this->db->where('post_author',$this->session->userdata('user_id'));
    	
    	$query = $this->db->get();
    	
    	if($query->num_rows() > 0) {
    		return $query->result();
    	}
    	return false;
    }
    
    public function markpostread($shareid) {
    	$this->db->where('id',$shareid);
    	$this->db->update(DB_PREFIX .'postshare', array('isread'=>1));
    	return $this->db->affected_rows();
    }
    
    public function markpostreshare($shareid) {
    	/*$this->db->where('id',$shareid);
    	$this->db->update(DB_PREFIX .'postshare', array('isreshared'=>1));    	
    	return $this->db->affected_rows();*/
    }
    
}