<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Property extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Property_model');
    }

    /**
     * ==========================================================
     * PROPERTY PORTFOLIO
     * ==========================================================
     */
    public function index()
    {
        $data['title'] =
            'PT Sinergi Aset Jagat Integrasi';


        /*
        |--------------------------------------------------------------------------
        | FILTER INPUT
        |--------------------------------------------------------------------------
        */

        $keyword =
            trim(
                $this->input->get(
                    'keyword',
                    TRUE
                )
            );

        $category_id =
            trim(
                $this->input->get(
                    'category',
                    TRUE
                )
            );

        $location_id =
            trim(
                $this->input->get(
                    'location',
                    TRUE
                )
            );

        $sort =
            trim(
                $this->input->get(
                    'sort',
                    TRUE
                )
            );


        /*
        |--------------------------------------------------------------------------
        | DEFAULT SORT
        |--------------------------------------------------------------------------
        */

        if ($sort === '') {

            $sort = 'latest';

        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $per_page = 9;

        $current_page =
            (int) $this->input->get(
                'page'
            );

        if ($current_page < 1) {

            $current_page = 1;

        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL DATA
        |--------------------------------------------------------------------------
        */

        $total_properties =
            $this->Property_model
                ->count_properties(
                    $keyword,
                    $category_id,
                    $location_id
                );


        /*
        |--------------------------------------------------------------------------
        | TOTAL PAGES
        |--------------------------------------------------------------------------
        */

        $total_pages =
            $total_properties > 0
                ? (int) ceil(
                    $total_properties /
                    $per_page
                )
                : 1;


        /*
        |--------------------------------------------------------------------------
        | NORMALIZE CURRENT PAGE
        |--------------------------------------------------------------------------
        */

        if (
            $current_page > $total_pages
        ) {

            $current_page =
                $total_pages;

        }


        /*
        |--------------------------------------------------------------------------
        | OFFSET
        |--------------------------------------------------------------------------
        */

        $offset =
            (
                $current_page - 1
            ) * $per_page;


        /*
        |--------------------------------------------------------------------------
        | GET PROPERTIES
        |--------------------------------------------------------------------------
        */

        $data['properties'] =
            $this->Property_model
                ->get_properties(
                    $keyword,
                    $category_id,
                    $location_id,
                    $sort,
                    $per_page,
                    $offset
                );


        /*
        |--------------------------------------------------------------------------
        | FEATURED PROPERTY
        |--------------------------------------------------------------------------
        |
        | Featured tetap independent dari filter portfolio.
        |
        */

        $data['featured_property'] =
            $this->Property_model
                ->get_featured_property();


        /*
        |--------------------------------------------------------------------------
        | FILTER MASTER DATA
        |--------------------------------------------------------------------------
        */

        $data['filter_categories'] =
            $this->Property_model
                ->get_filter_categories();


        $data['filter_locations'] =
            $this->Property_model
                ->get_filter_locations();


        /*
        |--------------------------------------------------------------------------
        | FILTER STATE
        |--------------------------------------------------------------------------
        */

        $data['filters'] = [

            'keyword' =>
                $keyword,

            'category' =>
                $category_id,

            'location' =>
                $location_id,

            'sort' =>
                $sort

        ];


        /*
        |--------------------------------------------------------------------------
        | PAGINATION DATA
        |--------------------------------------------------------------------------
        */

        $data['pagination'] = [

            'current_page' =>
                $current_page,

            'total_pages' =>
                $total_pages,

            'per_page' =>
                $per_page,

            'total_properties' =>
                $total_properties

        ];


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        $this->load->view(
            'template-frontend/header',
            $data
        );


        $this->load->view(
            'frontend/property',
            $data
        );


        $this->load->view(
            'template-frontend/footer',
            $data
        );
    }

    /**
     * ==========================================================
     * PROPERTY DETAIL
     * ==========================================================
     */
    public function property_detail($slug = NULL)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE SLUG
        |--------------------------------------------------------------------------
        */

        if (empty($slug)) {

            return redirect(
                'property'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | GET PROPERTY
        |--------------------------------------------------------------------------
        */

        $property =
            $this->Property_model
                ->get_property_by_slug($slug);


        /*
        |--------------------------------------------------------------------------
        | PROPERTY NOT FOUND
        |--------------------------------------------------------------------------
        */

        if (!$property) {

            show_404();

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | PAGE DATA
        |--------------------------------------------------------------------------
        */

        $data['title'] =
            $property['title'];

        $data['transparent_header'] =
            false;

        $data['property'] =
            $property;


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        $this->load->view(
            'template-frontend/header',
            $data
        );

        $this->load->view(
            'frontend/property-detail',
            $data
        );

        $this->load->view(
            'template-frontend/footer',
            $data
        );
    }
}