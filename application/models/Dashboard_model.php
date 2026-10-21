<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    /**
     * ==========================================================
     * SUMMARY
     * ==========================================================
     */

    /**
     * Total seluruh property
     */
    public function summary_properties()
    {
        return $this->db
            ->count_all('properties');
    }


    /**
     * Total property Available
     */
    public function summary_available()
    {
        return $this->db
            ->where('status', 'Available')
            ->count_all_results('properties');
    }


    /**
     * Total property Featured
     */
    public function summary_featured()
    {
        return $this->db
            ->where('featured', 1)
            ->count_all_results('properties');
    }


    /**
     * Total kategori property
     */
    public function summary_categories()
    {
        return $this->db
            ->count_all('property_categories');
    }


    /**
     * Total lokasi property
     */
    public function summary_locations()
    {
        return $this->db
            ->count_all('property_locations');
    }


    /**
     * Total property images
     */
    public function summary_images()
    {
        return $this->db
            ->count_all('property_images');
    }


    /**
     * ==========================================================
     * RECENT PROPERTIES
     * ==========================================================
     */

    public function recent_properties($limit = 5)
    {
        return $this->db
            ->select('
                p.id,
                p.title,
                p.slug,
                p.asset_class,
                p.asset_type,
                p.thumbnail,
                p.featured,
                p.status,
                p.created_at,

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
            ->order_by('p.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result_array();
    }


    /**
     * ==========================================================
     * PROPERTY BY CATEGORY
     * ==========================================================
     */

    public function property_by_category()
    {
        return $this->db
            ->select('
                pc.name AS category_name,
                COUNT(p.id) AS total
            ')
            ->from('property_categories pc')
            ->join(
                'properties p',
                'p.category_id = pc.id',
                'left'
            )
            ->group_by('pc.id')
            ->order_by('total', 'DESC')
            ->get()
            ->result_array();
    }


    /**
     * ==========================================================
     * PROPERTY BY LOCATION
     * ==========================================================
     */

    public function property_by_location()
    {
        return $this->db
            ->select('
                pl.city,
                pl.province,
                COUNT(p.id) AS total
            ')
            ->from('property_locations pl')
            ->join(
                'properties p',
                'p.location_id = pl.id',
                'left'
            )
            ->group_by([
                'pl.id',
                'pl.city',
                'pl.province'
            ])
            ->order_by('total', 'DESC')
            ->get()
            ->result_array();
    }
}