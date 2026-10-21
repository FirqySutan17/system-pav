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
        $data['title'] = 'PT Sinergi Aset Jagat Integrasi';

        $data['featured_properties'] =
        $this->Property_model
            ->get_featured_properties_home();

        $this->load->view('template-frontend/header', $data);
        $this->load->view('frontend/home', $data);
        $this->load->view('template-frontend/footer', $data);
    }
}