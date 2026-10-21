<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('session');
        $this->load->helper(array('url', 'form'));
    }

    public function login()
    {
        // Jika sudah login, langsung redirect ke dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->load->view('auth/login');
    }

    public function process_login()
    {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI INPUT
        |--------------------------------------------------------------------------
        */

        if (empty($username) || empty($password)) {

            $this->session->set_flashdata(
                'error',
                'Username dan password wajib diisi.'
            );

            return redirect('auth/login');
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK LOGIN
        |--------------------------------------------------------------------------
        */

        $user = $this->Auth_model->check_login(
            $username,
            $password
        );


        /*
        |--------------------------------------------------------------------------
        | LOGIN FAILED
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            $this->session->set_flashdata(
                'error',
                'Username atau password salah!'
            );

            return redirect('auth/login');
        }


        /*
        |--------------------------------------------------------------------------
        | SET SESSION
        |--------------------------------------------------------------------------
        */

        $this->session->set_userdata([

            'user_id'  => $user->id,

            'username' => $user->username,

            'name'     => $user->name,

            'logged_in' => TRUE

        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT DASHBOARD
        |--------------------------------------------------------------------------
        */

        return redirect('dashboard');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
