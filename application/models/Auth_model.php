<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
 * |==============================================================|
 * | Please DO NOT modify this information :                      |
 * |--------------------------------------------------------------|
 * | Author          : Susantokun
 * | Email           : admin@susantokun.com
 * | Filename        : Auth_model.php
 * | Instagram       : @susantokun
 * | Blog            : http://www.susantokun.com
 * | Info            : http://info.susantokun.com
 * | Demo            : http://demo.susantokun.com
 * | Youtube         : http://youtube.com/susantokun
 * | File Created    : Thursday, 12th March 2020 8:31:02 am
 * | Last Modified   : Thursday, 12th March 2020 8:39:31 am
 * |==============================================================|
 */

class Auth_model extends CI_Model
{
    public $table       = 'tbl_user';
    public $id          = 'tbl_user.id';

    public function __construct()
    {
        parent::__construct();
    }

    public function reg()
    {
      date_default_timezone_set('ASIA/JAKARTA');
      $data = array(
        'username' => $this->input->post('username'),
        'email' => $this->input->post('email'),
        'id_role' => '2',
        'created_on' => date('Y-m-d H:i:s'),
        'password' => get_hash($this->input->post('password'))
      );
      return $this->db->insert('tbl_user', $data);
    }

    public function login($email, $password)
    {
        $query = $this->db->get_where('tbl_user', array('email'=>$email, 'password'=>$password));
        return $query->row_array();
    }

    public function check_account($email)
    {
        //cari email lalu lakukan validasi
        $this->db->where('email', $email);
        $query = $this->db->get($this->table)->row();

        //jika bernilai 1 maka user tidak ditemukan
        if (!$query) {
            return 1;
        }
        //jika bernilai 2 maka user tidak aktif
        if ($query->active == 0) {
            return 2;
        }
        //jika bernilai 3 maka password salah
        if (!hash_verified($this->input->post('password'), $query->password)) {
            return 3;
        }

        return $query;
    }

    public function logout($date, $id)
    {
        $this->db->where('tbl_user.id', $id);
        $this->db->update('tbl_user', $date);
    }
}
