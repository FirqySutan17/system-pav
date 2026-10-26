
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    /**
     * ==========================================================
     * STATIC DASHBOARD
     * Tidak menggunakan model atau database
     * ==========================================================
     */
    public function index()
    {
        // Dummy summary data
        $data['summary'] = [
            'properties' => 120,
            'available'  => 85,
            'featured'   => 12,
            'categories' => 8
        ];

        // Dummy recent properties
        $data['recent_properties'] = [
            [
                'title'         => 'Commercial Office Building',
                'category_name' => 'Commercial',
                'city'          => 'Jakarta',
                'price'         => 15000000000,
                'status'        => 'Available',
                'slug'          => '#'
            ],
            [
                'title'         => 'Premium Warehouse',
                'category_name' => 'Industrial',
                'city'          => 'Bekasi',
                'price'         => 8500000000,
                'status'        => 'Available',
                'slug'          => '#'
            ],
            [
                'title'         => 'Strategic Land Property',
                'category_name' => 'Land',
                'city'          => 'Tangerang',
                'price'         => 12000000000,
                'status'        => 'Occupied',
                'slug'          => '#'
            ],
            [
                'title'         => 'Modern Business Center',
                'category_name' => 'Commercial',
                'city'          => 'Surabaya',
                'price'         => 20000000000,
                'status'        => 'Available',
                'slug'          => '#'
            ],
            [
                'title'         => 'Industrial Land',
                'category_name' => 'Land',
                'city'          => 'Karawang',
                'price'         => 6000000000,
                'status'        => 'Available',
                'slug'          => '#'
            ]
        ];

        // Render dashboard menggunakan layout existing
        $this->load->view(
            'templates/header',
            [
                'title' => 'Dashboard'
            ]
        );

        $this->load->view('templates/sidebar');

        $this->load->view('admin/dashboard', $data);

        $this->load->view('templates/footer');
    }
}
