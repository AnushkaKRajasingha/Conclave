<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sharecenter extends Site_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('sharecenter/sharecenter_model');
        $this->load->model('posts/posts_model');
        $this->lang->load('sharecenter');
    }

    public function index() {
    	$data = $this->sharecenter_model->loadSharecenter();
        $this->quick_page_setup(Settings_model::$db_config['adminpanel_theme'], 'adminpanel', 'Sharecenter', 'sharecenter', 'header', 'footer',"",$data);
        $this->load->view('generic/conclave_scripts');
        $this->load->view('generic/sharecenter_scripts');
    }
    
    public function conectstopostshare($postid){
    	$result = $this->sharecenter_model->connectstoshare($postid);
    	echo json_encode($result);
    }
    
    public function sharepost($pid,$sb,$st,$sf){
    	$data = array(
    			'postid' => $pid,
    			'sharedby' => $this->session->userdata('user_id') , //$sb,
    			'sharedto' => $st,
    			'sharedfrom' => $sf,
    			'share_date' => date('Y-m-d H:i:s')
    	);
    	$result = $this->sharecenter_model->sharepost($data);
    	if($sf != 0){
    		$this->load->model('posts/posts_model');    		
    		$sharepost = $this->posts_model->getpostbyshareid($sf);    		
    		$pointdata = array(
    				'user_id' => $sharepost->user_id,
    				'amount' => 1,
    				'reason' => 'Reshare post with id :'.$sharepost->id,
    				'earn_date' => date('Y-m-d H:i:s') ,
    				'user_response' => $this->session->userdata('user_id')
    		);
    		$this->load->model('sharecenter/points_model');
    		$this->points_model->addpoint($pointdata);
    	}
    	echo json_encode($result);
    }
    
    public function history(){
    	$this->quick_page_setup(Settings_model::$db_config['adminpanel_theme'], 'adminpanel', 'My Share History', 'sharehistory', 'header', 'footer');
    	$this->load->view('generic/conclave_scripts');
    	$this->load->view('generic/sharecenterhistory_scripts');
    }
    
    public function points(){
    	$this->quick_page_setup(Settings_model::$db_config['adminpanel_theme'], 'adminpanel', 'My Point History', 'pointhistory', 'header', 'footer');
    	$this->load->view('generic/conclave_scripts');
    	$this->load->view('generic/sharecenterpoints_scripts');
    }
    
    public function getsharehistory(){
    	$this->load->model('posts/posts_model');
    	$result = $this->posts_model->getsharedposts();
    	echo json_encode($result);
    }
    
    public function getpinthistory(){
    	$this->load->model('sharecenter/points_model');
    	$result = $this->points_model->getpointsbyuser();
    	echo json_encode($result);
    }
    
    public function deletesharepost($id){
    	
    	$this->load->model('posts/posts_model');
    	$sharepost = $this->posts_model->getpostbyshareid($id);
    	$pointdata = array(
    			'user_id' => $sharepost->user_id,
    			'amount' => -1,
    			'reason' => 'Delete post with id :'.$sharepost->id,
    			'earn_date' => date('Y-m-d H:i:s') ,
    			'user_response' => $this->session->userdata('user_id')
    	);
    	$this->load->model('sharecenter/points_model');
    	$this->points_model->addpoint($pointdata);
    	
    	$this->load->model('sharecenter/sharecenter_model');
    	$result = $this->sharecenter_model->deletesharepost($id);
    	echo json_encode($result);
    }

}