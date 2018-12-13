<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_dashboard extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        self::$page = "my_dashboard";
        $this->lang->load('membership');
    }

    public function index() {
    	$this->load->model('sharecenter/sharecenter_model');
    	$this->load->model('sharecenter/points_model');
    	$this->load->model('posts/posts_model');
    	$this->load->model('connections/connections_model');
    	$data = array(
    			'_sharedposts'=>  $this->sharecenter_model->getsharepoststoread(),
    			'_postsummary' => $this->posts_model->getpostsummusry(),
    			'_sharings' =>  $this->sharecenter_model->myshares(),
    			'_connections' => $this->connections_model->getmyconnections(),
    			'_poinssummary' => $this->points_model->getpointsummarybyuser()
    	);
    /*	echo '<pre>';
    	var_dump($data['_sharedposts']);
    	echo '</pre>';*/
    	$counter = 0 ;
    	if(is_array($data['_sharedposts']))
    	foreach ($data['_sharedposts'] as $value) {
    		$data['_sharedposts'][$counter]->alsoshare = $this->posts_model->postalsosharedwith($value->user_id,$value->id);
    		$counter++;
    	}
        $this->quick_page_setup(
            Settings_model::$db_config['adminpanel_theme'],
            'adminpanel',
            'My dashboard',
            'my_dashboard',
            'header',
            'footer',
        		"",
        		$data
        );
        $this->load->view('generic/conclave_scripts');
        $this->load->view('generic/mydashboard_scripts');
    }

}