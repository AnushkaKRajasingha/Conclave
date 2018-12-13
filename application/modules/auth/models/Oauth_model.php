<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OAuth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_all_providers
     *
     * @return mixed
     *
     */

    public function get_all_providers() {
        $this->db->select('id, name, oauth_type, client_id, client_secret')
            ->from(DB_PREFIX .'oauth_providers')
            ->where('enabled', true)
            ->order_by('order', 'asc');
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            return $q->result();
        }

        return false;
    }

    /**
     *
     * get_provider_data
     *
     * @param string $provider
     * @return mixed
     *
     */

    public function get_provider_data($provider) {
        $this->db->select('client_id, client_secret, enabled')->from(DB_PREFIX .'oauth_providers')->where('name', $provider);
        $q = $this->db->get();

        if ($q->num_rows() == 1) {
            return $q->row();
        }

        return false;
    }

    /**
     *
     * create_member_oauth
     *
     * @param string $username
     * @param string $email
     * @return mixed
     *
     */

    public function create_member_oauth($username, $email) {

        $cookie_part = md5(uniqid(mt_rand(), true));

        $data = array(
            'username' => $username,
            'email' => $email,
            'active' => 1
        );

        $this->db->trans_start();

        $this->db->set('date_registered', 'NOW()', FALSE);
        $this->db->set('last_login', 'NOW()', FALSE);
        $this->db->insert(DB_PREFIX .'user', $data);

        $last_id = $this->db->insert_id();

        $returnArray = array();

        // optional extra session data query: ready for usage
        // $this->db->select('session data')->from(DB_PREFIX .'user')->where('user_id', $last_id);
        // $q = $this->db->get();

        //if ($q->num_rows() == 1) {
        //   $returnArray['session data'] = $q->row('session data');
        //}

        $this->db->insert(DB_PREFIX .'user_cookie_part', array('user_id' => $last_id, 'cookie_part' => $cookie_part, 'ip_address' => $this->input->ip_address()));

        $this->db->trans_complete();

        if (!$this->db->trans_status() === false)
        {
            $returnArray['last_id'] = $last_id;
            return array('last_id' => $last_id);
        }

        return false;
    }

}