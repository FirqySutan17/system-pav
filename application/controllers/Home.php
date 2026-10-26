<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Property_model');
    }
    
    public function index()
    {
        $data['title'] = 'PT PAV';

        $this->load->view('frontend/home', $data);
    }
}