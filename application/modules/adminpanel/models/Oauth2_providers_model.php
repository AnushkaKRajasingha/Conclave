<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Oauth2_providers_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_providers
     *
     * @return mixed
     *
     */

    public function get_providers() {
        $this->db->select('id, name, client_id, client_secret, enabled, order')->from(DB_PREFIX .'oauth_providers')->order_by('order', 'asc');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }

        return false;
    }

    /**
     *
     * save_provider
     *
     * @param array $data
     * @return bool
     *
     */

    public function save_provider($data) {
        $this->db->set('date_modified', 'NOW()', FALSE);
        $this->db->where('id', $data['id']);
        $this->db->update(DB_PREFIX .'oauth_providers', $data);
        return $this->db->affected_rows();
    }

    /**
     *
     * delete_provider
     *
     * @param int $id
     * @return bool
     *
     */

    public function delete_provider($id) {
        $this->db->where('id', $id)->delete(DB_PREFIX .'oauth_providers');
        return $this->db->affected_rows();
    }

}