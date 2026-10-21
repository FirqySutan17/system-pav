<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Property_model extends CI_Model
{
    protected $table = 'properties';

    /**
     * ==========================================================
     * GET FRONTEND PROPERTIES
     * ==========================================================
     */
    public function get_properties(
        $keyword = '',
        $category_id = '',
        $location_id = '',
        $sort = 'latest',
        $limit = 9,
        $offset = 0
    ) {
        $this->db
            ->select('
                p.id,
                p.title,
                p.slug,
                p.asset_class,
                p.asset_type,
                p.price,
                p.thumbnail,
                p.featured,
                p.status,
                p.short_description,

                pc.name AS category_name,

                pl.city,
                pl.province
            ')
            ->from('properties p')

            ->join(
                'property_categories pc',
                'pc.id = p.category_id',
                'left'
            )

            ->join(
                'property_locations pl',
                'pl.id = p.location_id',
                'left'
            )

            ->where(
                'p.status',
                'available'
            );


        /*
        |--------------------------------------------------------------------------
        | KEYWORD
        |--------------------------------------------------------------------------
        */

        if ($keyword !== '') {

            $this->db->group_start();

            $this->db
                ->like(
                    'p.title',
                    $keyword
                );

            $this->db
                ->or_like(
                    'p.short_description',
                    $keyword
                );

            $this->db
                ->or_like(
                    'p.asset_class',
                    $keyword
                );

            $this->db
                ->or_like(
                    'p.asset_type',
                    $keyword
                );

            $this->db->group_end();
        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORY
        |--------------------------------------------------------------------------
        */

        if ($category_id !== '') {

            $this->db->where(
                'p.category_id',
                (int) $category_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOCATION
        |--------------------------------------------------------------------------
        */

        if ($location_id !== '') {

            $this->db->where(
                'p.location_id',
                (int) $location_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SORT
        |--------------------------------------------------------------------------
        */

        switch ($sort) {

            case 'name_asc':

                $this->db->order_by(
                    'p.title',
                    'ASC'
                );

                break;


            case 'name_desc':

                $this->db->order_by(
                    'p.title',
                    'DESC'
                );

                break;


            case 'latest':

            default:

                $this->db->order_by(
                    'p.created_at',
                    'DESC'
                );

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $this->db->limit(
            (int) $limit,
            (int) $offset
        );


        return $this->db
            ->get()
            ->result_array();
    }

    /**
     * ==========================================================
     * COUNT FRONTEND PROPERTIES
     * ==========================================================
     */
    public function count_properties(
        $keyword = '',
        $category_id = '',
        $location_id = ''
    ) {
        $this->db
            ->from('properties p')

            ->where(
                'p.status',
                'available'
            );


        /*
        |--------------------------------------------------------------------------
        | KEYWORD
        |--------------------------------------------------------------------------
        */

        if ($keyword !== '') {

            $this->db->group_start();

            $this->db
                ->like(
                    'p.title',
                    $keyword
                );

            $this->db
                ->or_like(
                    'p.short_description',
                    $keyword
                );

            $this->db
                ->or_like(
                    'p.asset_class',
                    $keyword
                );

            $this->db
                ->or_like(
                    'p.asset_type',
                    $keyword
                );

            $this->db->group_end();
        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORY
        |--------------------------------------------------------------------------
        */

        if ($category_id !== '') {

            $this->db->where(
                'p.category_id',
                (int) $category_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOCATION
        |--------------------------------------------------------------------------
        */

        if ($location_id !== '') {

            $this->db->where(
                'p.location_id',
                (int) $location_id
            );
        }


        return $this->db
            ->count_all_results();
    }

    /**
     * ==========================================================
     * GET FRONTEND CATEGORIES
     * ==========================================================
     */
    public function get_filter_categories()
    {
        return $this->db
            ->select('id, name')
            ->from('property_categories')
            ->where(
                'status',
                'Active'
            )
            ->order_by(
                'name',
                'ASC'
            )
            ->get()
            ->result_array();
    }


    /**
     * ==========================================================
     * GET FRONTEND LOCATIONS
     * ==========================================================
     */
    public function get_filter_locations()
    {
        return $this->db
            ->select('id, city, province')
            ->from('property_locations')
            ->order_by(
                'city',
                'ASC'
            )
            ->get()
            ->result_array();
    }

    /**
     * ==========================================================
     * GET FEATURED PROPERTY
     * ==========================================================
     */
    public function get_featured_property()
    {
        return $this->db
            ->select('
                p.id,
                p.title,
                p.slug,
                p.asset_class,
                p.asset_type,
                p.price,
                p.thumbnail,
                p.featured,
                p.status,

                pc.name AS category_name,

                pl.city,
                pl.province
            ')
            ->from('properties p')

            ->join(
                'property_categories pc',
                'pc.id = p.category_id',
                'left'
            )

            ->join(
                'property_locations pl',
                'pl.id = p.location_id',
                'left'
            )

            ->where(
                'p.featured',
                1
            )

            ->where(
                'p.status',
                'available'
            )

            ->order_by(
                'p.created_at',
                'DESC'
            )

            ->limit(1)

            ->get()
            ->row_array();
    }

    /**
     * ==========================================================
     * GET FEATURED PROPERTIES FOR HOME
     * ==========================================================
     */
    public function get_featured_properties_home()
    {
        return $this->db
            ->select('
                p.id,
                p.title,
                p.slug,
                p.asset_class,
                p.asset_type,
                p.thumbnail,
                p.price,
                p.featured,
                p.status,

                pc.name AS category_name,

                pl.city,
                pl.province
            ')
            ->from('properties p')

            ->join(
                'property_categories pc',
                'pc.id = p.category_id',
                'left'
            )

            ->join(
                'property_locations pl',
                'pl.id = p.location_id',
                'left'
            )

            ->where(
                'p.featured',
                1
            )

            ->where(
                'p.status',
                'available'
            )

            ->order_by(
                'p.created_at',
                'DESC'
            )

            ->limit(3)

            ->get()
            ->result_array();
    }

    /**
     * ==========================================================
     * GET PROPERTY DETAIL BY SLUG
     * ==========================================================
     */
    public function get_property_by_slug($slug)
    {
        $property = $this->db
            ->select('
                p.*,

                pc.name AS category_name,

                pl.city,
                pl.province
            ')
            ->from('properties p')

            ->join(
                'property_categories pc',
                'pc.id = p.category_id',
                'left'
            )

            ->join(
                'property_locations pl',
                'pl.id = p.location_id',
                'left'
            )

            ->where(
                'p.slug',
                $slug
            )

            ->where(
                'p.status',
                'available'
            )

            ->get()
            ->row_array();


        if (!$property) {
            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | PROPERTY IMAGES
        |--------------------------------------------------------------------------
        */

        $property['images'] = $this->db
            ->select('
                id,
                property_id,
                image,
                caption,
                sort_order,
                is_cover
            ')
            ->from('property_images')
            ->where(
                'property_id',
                $property['id']
            )
            ->order_by(
                'is_cover',
                'DESC'
            )
            ->order_by(
                'sort_order',
                'ASC'
            )
            ->order_by(
                'id',
                'ASC'
            )
            ->get()
            ->result_array();


        /*
        |--------------------------------------------------------------------------
        | PROPERTY DOCUMENTS
        |--------------------------------------------------------------------------
        */

        $property['documents'] = $this->db
            ->select('
                id,
                property_id,
                title,
                file,
                sort_order
            ')
            ->from('property_documents')
            ->where(
                'property_id',
                $property['id']
            )
            ->order_by(
                'sort_order',
                'ASC'
            )
            ->order_by(
                'id',
                'ASC'
            )
            ->get()
            ->result_array();


        return $property;
    }

    /**
     * ==========================================================
     * GET CATEGORIES
     * ==========================================================
     */
    public function get_categories()
    {
        return $this->db
            ->select('
                id,
                name
            ')
            ->from('property_categories')

            ->where(
                'status',
                'Active'
            )

            ->order_by(
                'name',
                'ASC'
            )

            ->get()
            ->result_array();
    }

    /**
     * ==========================================================
     * GET LOCATIONS
     * ==========================================================
     */
    public function get_locations()
    {
        return $this->db
            ->select('
                id,
                city,
                province
            ')
            ->from('property_locations')

            ->order_by(
                'city',
                'ASC'
            )

            ->get()
            ->result_array();
    }

    /**
     * ==========================================================
     * GET PROPERTY DETAIL
     * ==========================================================
     */
    public function get_property_detail($slug)
    {
        /*
        |--------------------------------------------------------------------------
        | PROPERTY
        |--------------------------------------------------------------------------
        */

        $property = $this->db
            ->select('
                p.id,
                p.category_id,
                p.location_id,
                p.title,
                p.slug,
                p.asset_class,
                p.asset_type,
                p.price,
                p.land_area,
                p.building_area,
                p.year_built,
                p.thumbnail,
                p.short_description,
                p.description,
                p.featured,
                p.status,
                p.address,

                pc.name AS category_name,

                pl.city,
                pl.province
            ')
            ->from('properties p')

            ->join(
                'property_categories pc',
                'pc.id = p.category_id',
                'left'
            )

            ->join(
                'property_locations pl',
                'pl.id = p.location_id',
                'left'
            )

            ->where(
                'p.slug',
                $slug
            )

            ->where(
                'p.status',
                'available'
            )

            ->limit(1)

            ->get()
            ->row_array();


        if (!$property) {

            return null;

        }


        /*
        |--------------------------------------------------------------------------
        | PROPERTY IMAGES
        |--------------------------------------------------------------------------
        */

        $property['images'] = $this->db
            ->select('
                id,
                property_id,
                image,
                caption,
                sort_order,
                is_cover
            ')
            ->from('property_images')

            ->where(
                'property_id',
                $property['id']
            )

            ->order_by(
                'is_cover',
                'DESC'
            )

            ->order_by(
                'sort_order',
                'ASC'
            )

            ->order_by(
                'id',
                'ASC'
            )

            ->get()
            ->result_array();


        /*
        |--------------------------------------------------------------------------
        | PROPERTY DOCUMENTS
        |--------------------------------------------------------------------------
        */

        $property['documents'] = $this->db
            ->select('
                id,
                property_id,
                title,
                file,
                sort_order
            ')
            ->from('property_documents')

            ->where(
                'property_id',
                $property['id']
            )

            ->order_by(
                'sort_order',
                'ASC'
            )

            ->order_by(
                'id',
                'ASC'
            )

            ->get()
            ->result_array();


        return $property;
    }
}