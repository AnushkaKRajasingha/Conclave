<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Connections_model extends CI_Model {

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
    	return $this->db->affected_rows();
    }
    
    public function getavailableconnections(){
    	$this->db->select(array( 'u.username','u.user_id','u.first_name','u.email' , 'count(p.id) noofposts' ,'u.date_registered','u.profile_img' ));
    	$this->db->from(DB_PREFIX .'user u');
    	$this->db->join(DB_PREFIX.'posts p', 'p.post_author = u.user_id','left');
    	$this->db->where('u.active',1);
    	$this->db->where('u.user_id !=', $this->session->userdata('user_id'));
    	$this->db->where('u.user_id NOT IN (SELECT acceptby FROM '.DB_PREFIX .'connections WHERE requestby = '.$this->session->userdata('user_id').' UNION SELECT requestby FROM '.DB_PREFIX .'connections WHERE acceptby = '.$this->session->userdata('user_id').' )');
    	$this->db->group_by('u.first_name');
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	
    	if($query->num_rows() > 0) {
    		return $query->result();
    	}
    	return false;
    }
    
  /*  public function getallconnections($query){
    	$this->db->select(array( 'u.username','u.user_id','u.first_name','u.email' , 'count(p.id) noofposts' ,'u.date_registered','u.profile_img' ));
    	$this->db->from(DB_PREFIX .'user u');
    	$this->db->join(DB_PREFIX.'posts p', 'p.post_author = u.user_id','left');
    	$this->db->where('u.active',1);
    	$this->db->where('u.user_id !=', $this->session->userdata('user_id'));
    	$this->db->like('u.first_name',$query);
    //	$this->db->where('u.user_id NOT IN (SELECT acceptby FROM '.DB_PREFIX .'connections WHERE requestby = '.$this->session->userdata('user_id').' UNION SELECT requestby FROM '.DB_PREFIX .'connections WHERE acceptby = '.$this->session->userdata('user_id').' )');
    	$this->db->group_by('u.first_name');
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	 
    	if($query->num_rows() > 0) {
    		return $query->result();
    	}
    	return false;
    }*/
     
     public function getallconnections($selquery){
     	
     /*	$_query = "SELECT distinct u.user_id, u.username,u.first_name ,IF( {$this->session->userdata('user_id')} = c.requestby or  {$this->session->userdata('user_id')} = c.acceptby,1,0) connected
FROM ".DB_PREFIX."user u
left join ".DB_PREFIX."connections c on c.requestby = u.user_id 
where u.user_id != {$this->session->userdata('user_id')} and ( u.username like '%{$selquery}%' or u.first_name like '%{$selquery}%') "; 
     	*/ 		
     	$_query = "select distinct t.user_id, t.username,t.first_name ,IF( {$this->session->userdata('user_id')}= t.requestby or  {$this->session->userdata('user_id')}= t.acceptby,1,0) connected
from (
SELECT distinct u.user_id, u.username,u.first_name ,c.requestby,c.acceptby
FROM ".DB_PREFIX."user u
left join ".DB_PREFIX."connections c on c.acceptby = u.user_id 
where u.user_id != {$this->session->userdata('user_id')}
union
SELECT distinct u.user_id, u.username,u.first_name ,c.requestby,c.acceptby
FROM ".DB_PREFIX."user u
left join ".DB_PREFIX."connections c on c.requestby = u.user_id 
where u.user_id != {$this->session->userdata('user_id')}
) t
where t.first_name like '%{$selquery}%' 
group by t.user_id";
     	
     	
    		$query =  $this->db->query($_query);
   	
	
	   	if($query->num_rows() > 0) {
	   		return $query->result_array();
	   	}
	   	return false; 
	   	
    }
   public function getmyconnections(){
   	
   	$query = "SELECT c.requestby,c.acceptby, u.user_id,u.first_name,u.email  ,u.date_registered,u.username,u.profile_img,c.constatus ,count(p.id) noofposts
,u.date_registered,c.constatus,c.id,1 'request'
FROM ".DB_PREFIX."connections c
left join ".DB_PREFIX."user u on  u.user_id = c.acceptby
left join ".DB_PREFIX."posts p on p.post_author = u.user_id
where c.requestby = {$this->session->userdata('user_id')}
   	group by u.user_id
union
SELECT  c.requestby,c.acceptby, u.user_id,u.first_name,u.email  ,u.date_registered,u.username,u.profile_img,c.constatus ,count(p.id) noofposts
,u.date_registered,c.constatus,c.id,0 'request'
FROM ".DB_PREFIX."connections c
left join ".DB_PREFIX."user u on  u.user_id = c.requestby
left join ".DB_PREFIX."posts p on p.post_author = u.user_id
where c.acceptby = {$this->session->userdata('user_id')}
   	group by u.user_id";

   	$query =  $this->db->query($query);
   	
	
	   	if($query->num_rows() > 0) {
	   		return $query->result_array();
	   	}
	   	return false;
   }
   
   public function connect($data){
   	$this->db->insert(DB_PREFIX .'connections', $data);
   	return $this->db->affected_rows();
   }
   
   public function disconnect($id){
   		$this->db->where('id',$id)->delete(DB_PREFIX .'connections');
   		return $this->db->affected_rows();
   }
   
   public function getrequest($status = 'pending'){
	   	$this->db->select(array( 'u.user_id','u.first_name','u.email' , 'count(p.id) noofposts' ,'u.date_registered','c.constatus' ));
	   	$this->db->from(DB_PREFIX .'user u');
	   	$this->db->join(DB_PREFIX.'posts p', 'u.user_id = p.post_author','inner');
	   	$this->db->join(DB_PREFIX.'connections c', 'u.user_id = c.requestby','inner');
	   	$this->db->where('u.active',1);
	   	$this->db->where('c.acceptby', $this->session->userdata('user_id'));
	   //	$this->db->where('c.constatus', $status);
	   //	$this->db->where('u.user_id NOT IN (SELECT acceptby FROM '.DB_PREFIX .'connections WHERE requestby = '.$this->session->userdata('user_id').')');
	   	$this->db->group_by('u.first_name');
	   	
	   	$query = $this->db->get();
	//   	echo $this->db->last_query();
	   	if($query->num_rows() > 0) {
	   		return $query->result();
	   	}
	   	return false;
   }
   
   public function update($data){
   		$id = $data['id']; unset($data['id']);
   		$this->db->where('id',$id);
   		$this->db->update(DB_PREFIX.'connections',$data);
   		return $this->db->affected_rows();
   }
}