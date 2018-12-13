<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_settings_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * save_settings: save the new settings to the DB
     *
     * @param array $data
     * @return bool
     *
     */

    public function save_settings($data) {

    	
    	
        $this->db->trans_start();

        foreach ($data as $name => $value) {

            $this->db->where('name', $name);
            $this->db->update(DB_PREFIX .'setting', array('value' => $value));
        }

        $this->db->trans_complete();
        
       // echo $this->db->last_query(); exit;

        if ($this->db->trans_status() !== FALSE)
        {
            return true;
        }

        return false;
    }

    /**
     *
     * clear_sessions: remove all session data
     *
     * @return bool
     *
     */

    public function clear_sessions() {
        $this->db->where('id != ', $this->session->userdata('session_id'));
        $this->db->delete(DB_PREFIX .'ci_sessions');
        if ($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }
}