<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        }

    /**
     *
     * create_member
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $first_name
     * @param string $last_name
     * @param bool $active
     * @return mixed
     *
     */

    public function create_member($username, $password, $email, $first_name, $last_name, $active = 0) {

        if ($username == Settings_model::$db_config['root_admin_username']) {
            $this->db->select('user_id')->from(DB_PREFIX .'user')->where('username', $username);
            $q = $this->db->get();
            if ($q->num_rows() == 1) {
                return "installed";
            }
        }

        $cookie_part = md5(uniqid(mt_rand(), true));

        $data = array(
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'active' => $active
        );

        $this->db->trans_start();

        $this->db->set('date_registered', 'NOW()', FALSE);
        $this->db->set('last_login', 'NOW()', FALSE);
        $this->db->insert(DB_PREFIX .'user', $data);

        $last_id = $this->db->insert_id();

        $this->db->insert(DB_PREFIX .'user_cookie_part',
            array('user_id' => $last_id,
                  'cookie_part' => $cookie_part,
                  'ip_address' => $this->input->ip_address()
            )
        );

        $this->db->trans_complete();

        if (! $this->db->trans_status() === false)
        {
            return array('cookie_part' => $cookie_part, 'user_id' => $last_id);
        }

        return false;
    }

}

