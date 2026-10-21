<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Dashboard_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }


    /**
     * ==========================================================
     * DASHBOARD
     * ==========================================================
     */
    public function index()
    {
        $data['summary'] = [

            'properties' =>
                $this->Dashboard_model
                    ->summary_properties(),

            'available' =>
                $this->Dashboard_model
                    ->summary_available(),

            'featured' =>
                $this->Dashboard_model
                    ->summary_featured(),

            'categories' =>
                $this->Dashboard_model
                    ->summary_categories()

        ];


        $data['recent_properties'] =
            $this->Dashboard_model
                ->recent_properties();


        $this->load->view(
            'templates/header',
            [
                'title' => 'Dashboard'
            ]
        );


        $this->load->view(
            'templates/sidebar'
        );


        $this->load->view(
            'admin/dashboard',
            $data
        );


        $this->load->view(
            'templates/footer'
        );
    }
}