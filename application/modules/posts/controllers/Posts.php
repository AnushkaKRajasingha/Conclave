<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts extends Site_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('posts/posts_model');
        
        $this->lang->load('posts');
    }

    public function index() {
    	$this->load->model('sharecenter/points_model');
    	$data = array(
    			'total_posts' => 0 ,
    			'avail_points' => $this->points_model->getpointsummarybyuser(),
    			'post_count' =>  $this->posts_model->getpostsummusry()
    	);
    	/*echo '<pre>';
    	var_dump($this->session->userdata['user_id']);
    	echo '</pre>';*/
    	
    	
    	
        $this->quick_page_setup(Settings_model::$db_config['adminpanel_theme'], 'adminpanel', 'My posts', 'myposts', 'header', 'footer',"",$data);
        $this->load->view('generic/conclave_scripts');
        $this->load->view('generic/post_scripts'); 
    } 
    
    
    public function readsharedpost($id){
    	$data = $this->posts_model->getsharedpost($id);
    	
    	$pointdata = array(
    			'user_id' => $data->user_id,
    			'amount' => 1,
    			'reason' => 'Read post with id :'.$data->id,
    			'earn_date' => date('Y-m-d H:i:s') ,
    			'user_response' => $this->session->userdata('user_id')
    	);
    	
    	$postalsosharedwith = $this->postalsosharedwith($data->shareuserid,$data->id);
    	$data->alsoshare = $postalsosharedwith;
    	
    	$this->load->model('sharecenter/points_model');
    	$this->points_model->addpoint($pointdata);
    	$this->posts_model->markpostread($data->psid);
    	$this->quick_page_setup(Settings_model::$db_config['adminpanel_theme'], 'adminpanel', 'Shared post', 'readsharedposts', 'header', 'footer',"",$data);
    }
    
    
    public function update($id){
    	$this->form_validation->set_error_delimiters('', '');
    	$this->form_validation->set_rules('post_title', $this->lang->line('post_title'), 'trim|required|max_length[50]');
    	$this->form_validation->set_rules('post_content', $this->lang->line('post_content_p'), 'trim|max_length[1024]');
    	 
    	if (!$this->form_validation->run()) {
    		$this->session->set_flashdata('error', validation_errors());
    		redirect('/posts/posts');
    	}
    	$result = $this->posts_model->update(array('id'=>$id,'post_title' => $this->input->post('post_title'),
    			'post_content' => $this->input->post('post_content'),'post_author'=> $this->session->userdata['user_id'],'post_date' => date('Y-m-d H:i:s')
    			,'post_status' => $this->input->post('post_status')
    	));
    	if(!$result) {
    		$this->session->set_flashdata('error', 'Unable to add post.');
    		redirect('posts/posts');
    	}
    	 
    	$this->session->set_flashdata('success', '<p>'. $this->lang->line('post_update') .'</p>');
    	redirect('posts/posts');
    }
    
    public function edit($id){
    	
    	$data = $this->posts_model->getpost($id);   // var_dump($data);	exit;
    	if($data){
    	$this->quick_page_setup(Settings_model::$db_config['adminpanel_theme'], 'adminpanel', 'My posts', 'myposts', 'header', 'footer',"",$data);
    	$this->load->view('generic/conclave_scripts');
    	$this->load->view('generic/post_scripts');
    	}
    	else{
    		$this->index()
;    	}
    }
    
    public function add_post() { 
    	
    //	var_dump($this->input->post('shareusers')); exit;
    	
    	$this->form_validation->set_error_delimiters('', '');
    	$this->form_validation->set_rules('post_title', $this->lang->line('post_title'), 'trim|required|max_length[50]');
    	$this->form_validation->set_rules('post_content', $this->lang->line('post_content_p'), 'trim|max_length[1024]');
    	
    	if (!$this->form_validation->run()) {
    		$this->session->set_flashdata('error', validation_errors());
    		redirect('/posts/posts');
    	}
    	$result = $this->posts_model->create(array('post_title' => $this->input->post('post_title'),
    			'post_content' => $this->input->post('post_content'),'post_author'=> $this->session->userdata['user_id'],'post_date' => date('Y-m-d H:i:s')
    			,'post_status' => $this->input->post('post_status')
    	));
    	if(!$result) {
    		$this->session->set_flashdata('error', 'Unable to add post.');
    		redirect('posts/posts');
    	}
    	else{
    		$postid = $result;
    		$this->load->model('sharecenter/sharecenter_model');
    		$shareusers = $this->input->post('shareusers');
    		foreach ($shareusers as $shareuser) {
    			$data = array(
    				'postid' => $postid,
    				'sharedby' => $this->session->userdata('user_id') , //$sb,
    				'share_date' => date('Y-m-d H:i:s'),
    					'sharedto' => $shareuser,
    		);
    		$result = $this->sharecenter_model->sharepost($data);
    		}
    		
    	}
    
    	$this->session->set_flashdata('success', '<p>'. $this->lang->line('post_added') .'</p>');
    	redirect('posts/posts');
    }
    
    public function getallpostsbyuser(){
    	$result = $this->posts_model->getallbyuser();
    	echo json_encode($result);
    }
    
    public function getsinglepost($id){
    	$result = $this->posts_model->getpost($id);
    	echo json_encode($result);
    }
    
    public function copypost($id = null){
    	$result = $this->posts_model->copypost($id);
    	echo json_encode($result);
    }
    
    public function deletepost($id = null){
    	$result = $this->posts_model->deletepost($id);
    	echo json_encode($result);
    }
    
    public function postalsosharedwith($user_id,$post_id){
    	$result = $this->posts_model->postalsosharedwith($user_id,$post_id);
    	return $result;
    }
    

}