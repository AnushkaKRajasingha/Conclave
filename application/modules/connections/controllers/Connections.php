<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Connections extends Site_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('connections/connections_model');
        
        $this->lang->load('connections');
    }

    public function index() {
    	$data = array(
    			'total_posts' => 0   			
    	);
    	/*echo '<pre>';
    	var_dump($this->session->userdata['user_id']);
    	echo '</pre>';*/
        $this->quick_page_setup(Settings_model::$db_config['adminpanel_theme'], 'adminpanel', 'My connections', 'my_connections', 'header', 'footer',"",$data);
        $this->load->view('generic/conclave_scripts');
        $this->load->view('generic/connections_scripts');
    }
    
    public function getrequest($status = 'pending'){
    	$result = $this->connections_model->getrequest($status);
    	echo json_encode($result);
    }
    
    public function getavailableconnections(){
    	$result = $this->connections_model->getavailableconnections();
    	echo json_encode($result);
    }
    public function getallconnections($query){
    	$result = $this->connections_model->getallconnections($query);
    	echo json_encode($result);
    }
    
    public function getmyconnections(){
    	$result = $this->connections_model->getmyconnections();
    	echo json_encode($result);
    }
    
    public function disconnect($id=null){
    	$this->connections_model->disconnect($id);
    	echo json_encode(array('id'=>$id));
    }
    
    public function connect($id=null){
    	$data = array(
    			'requestby' => $this->session->userdata('user_id'),
    			'acceptby' => $id,
    			'req_date' => date('Y-m-d H:i:s')
    			
    	);
    	$result = $this->connections_model->connect($data);
    	
    	/* redeem points */
    	$pointdata = array(
    			'user_id' => $this->session->userdata('user_id'),
    			'amount' =>  isset(Settings_model::$db_config['min_noof_pointtocon']) ? Settings_model::$db_config['min_noof_pointtocon']*-1 : 0,
    			'reason' => 'Redeem point for new connection :'.$id,
    			'earn_date' => date('Y-m-d H:i:s') ,
    			'user_response' => $id
    	);
    	$this->load->model('sharecenter/points_model');
    	$this->points_model->addpoint($pointdata);
    	
    	echo json_encode($result);
    }
    
    public function acceptfriend($id = null){
    	$data = array(
    			'id' => $id,
    			'constatus' => 'connected',
    			'con_date' => date('Y-m-d H:i:s')
    	);
    	$result = $this->connections_model->update($data);
    	echo json_encode($result);
    }

}