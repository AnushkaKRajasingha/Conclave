<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

	
	public function __construct() {
        parent::__construct();
    }

    /**
     *
     * count_users
     *
     * @return mixed
     *
     */
	
	public function count_users() {
		return $this->db->count_all_results(DB_PREFIX .'user');
	}

    /**
     *
     * count_users_this_week
     *
     * @return mixed
     *
     */

    public function count_users_this_week() {
        return $this->db->query("SELECT count(1) as count FROM `". DB_PREFIX ."user` WHERE date_registered > DATE_SUB(NOW(), INTERVAL 1 WEEK)")->row();
    }

    /**
     *
     * count_users_this_month
     *
     * @return mixed
     *
     */

    public function count_users_this_month() {
        return $this->db->query("SELECT count(1) as count FROM `". DB_PREFIX ."user` WHERE date_registered > DATE_SUB(NOW(), INTERVAL 1 MONTH)")->row();
    }

    /**
     *
     * count_users_this_year
     *
     * @return mixed
     *
     */

    public function count_users_this_year() {
        return $this->db->query("SELECT count(1) as count FROM `". DB_PREFIX ."user` WHERE date_registered > DATE_SUB(NOW(), INTERVAL 1 YEAR)")->row();
    }

    /**
     *
     * get_latest_members
     *
     * @return mixed
     *
     */

    public function get_latest_members($limit) {
        $this->db->select('user_id, username, first_name, last_name, email, last_login, profile_img')->from(DB_PREFIX .'user');
        $this->db->order_by("user_id", "asc");
        $this->db->limit($limit);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            return $q->result();
        }

        return false;
    }

}